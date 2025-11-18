<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\Cart;
use App\Models\TransactionItem;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->with(['items','user', 'items.product'])->paginate(10);
        return new APIResource(true, 'Transaction list retrieved successfully',$transactions);
    }
    public function my()
    {
        $transactions = Transaction::latest()->where('user_id',Auth::id())->with(['items','user'])->get();
        return new APIResource(true, 'Transaction list retrieved successfully',$transactions);
    }
    public function store(Request $request)
    {
        $request->validate([
            'total_price' => 'required|integer',
            'total_point' => 'required|integer',
            'total_discount' => 'required|integer',
            'balance_used' => 'required|integer',
            'orders' => 'required|array|min:1',
            'orders.*.product_id' => 'required|integer|distinct',
            'orders.*.quantity' => 'required|integer',
            'orders.*.subtotal_price' => 'required|integer',
            'orders.*.subtotal_discount' => 'required|integer',
            'orders.*.subtotal_point' => 'required|integer',
        ]);
        $unique_code = Transaction::latest()->whereDate('created_at','=',Carbon::today())->count() + 1;
        $transaction = Transaction::create([
            'total_price'   => $request->total_price,
            'user_id' => Auth::id(),
            'status'   => 0,
            'unique_code'   => $unique_code,
            'balance_used'   => $request->balance_used,
            'total_point'   => $request->total_point,
            'total_discount'   => $request->total_discount,
            'image_path'   => '',
        ]);
        foreach($request->orders as $order){
            TransactionItem::create([
                'transaction_id'   => $transaction->id,
                'product_id' => $order["product_id"],
                'quantity'   => $order["quantity"],
                'subtotal_price'   => $order["subtotal_price"],
                'subtotal_discount'   => $order["subtotal_discount"],
                'subtotal_point'   => $order["subtotal_point"],
            ]);
        }
        return new APIResource(true, 'Transaction created successfully',$transaction);
    }
    public function update_status(Request $request)
    {
        $transaction = Transaction::find($request->id);

        if (! $transaction) {
            return new APIResource(false, 'Transaction not found',null);
        }
        
        $request->validate([
            'status'   => 'required|integer',
        ]);
        $transaction->update([
            'status' => $request->status,
        ]);
        
        return new APIResource(true, 'Transaction status updated successfully',$saving);
    }
}
