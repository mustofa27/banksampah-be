<?php

namespace App\Http\Controllers;
use App\Models\Discount;
use App\Models\Product;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::latest()->with(['product'])->paginate(10);
        return new APIResource(true, 'Discount list retrieved successfully',$discounts);
    }
    public function product_discount(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|integer',
        ]);
        $product = Product::find($request->product_id);
        if(!$product){
            return new APIResource(false, 'Product not found',null);
        }
        $discounts = Discount::latest()->where('product_id',$request->product_id)
                        ->whereDate('start_at', '<=', Carbon::today())
                        ->whereDate('end_at', '>=', Carbon::today())->get();
        return new APIResource(true, 'Discount list retrieved successfully',$discounts);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|integer',
            'percentage' => 'required|integer',
            'start_at' => 'required|date_format:Y-m-d',
            'end_at' => 'required|date_format:Y-m-d|after:start_at',
        ]);
        $product = Product::find($request->product_id);
        if(!$product){
            return new APIResource(false, 'Product not found',null);
        }
        $discount = Discount::create([
            'product_id'   => $request->product_id,
            'percentage' => $request->percentage,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
        ]);
        return new APIResource(true, 'Discount created successfully',$discount);
    }

    public function show($id)
    {
        $discount = Discount::where('id',$id)->first();

        if (! $discount) {
            return new APIResource(false, 'Discount not found',null);
        }

        return new APIResource(true, 'Discount retrieved successfully',$discount);
    }

    public function update(Request $request)
    {
        $discount = Discount::find($request->id);

        if (! $discount) {
            return new APIResource(false, 'Discount not found',null);
        }
        
        $request->validate([
            'product_id'   => 'required|integer',
            'percentage' => 'required|integer',
            'start_at' => 'required|date_format:Y-m-d',
            'end_at' => 'required|date_format:Y-m-d|after:start_at',
        ]);
        $product = Product::find($request->product_id);
        if(!$product){
            return new APIResource(false, 'Product not found',null);
        }
        $discount->update([
            'product_id'   => $request->product_id,
            'percentage' => $request->percentage,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
        ]);

        return new APIResource(true, 'Discount updated successfully',$discount);
    }

    public function destroy($id)
    {
        $discount = Discount::find($id);

        if (! $discount) {
            return new APIResource(false, 'Discount not found',null);
        }

        $discount->delete();

        return new APIResource(true, 'Discount deleted successfully',null);
    }
}
