<?php

namespace App\Http\Controllers;
use App\Models\Withdraw;
use App\Models\Balance;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdraws = Withdraw::latest()->with(['selectedOption', 'user'])->paginate(10);
        return new APIResource(true, 'Withdraw list retrieved successfully',$withdraws);
    }

    public function store(Request $request)
    {
        $request->validate([
            'count' => 'required|integer',
            'balance_used' => 'required|integer',
            'withdraw_option_id' => 'required|integer',
        ]);
        $balance = Balance::where('user_id', Auth::id())->first();
        if(!$balance || $balance->balance < $request->balance_used){
            return new APIResource(false, 'Saldo anda tidak cukup',null);
        }
        $withdraw = Withdraw::create([
            'count'   => $request->count,
            'balance_used' => $request->balance_used,
            'status' => 0,
            'withdraw_option_id' => $request->withdraw_option_id,
            'user_id' => Auth::id(),
        ]);
        return new APIResource(true, 'Withdraw created successfully',$withdraw);
    }

    public function show($id)
    {
        $withdraw = Withdraw::where('id',$id)->with(['selectedOption', 'user'])->first();

        if (! $withdraw) {
            return new APIResource(false, 'Withdraw not found',null);
        }

        return new APIResource(true, 'Withdraw retrieved successfully',$withdraw);
    }

    public function update(Request $request)
    {
        $withdraw = Withdraw::find($request->id);

        if (! $withdraw) {
            return new APIResource(false, 'Withdraw not found',null);
        }
        
        $request->validate([
            'count' => 'required|integer',
            'balance_used' => 'required|integer',
            'status' => 'required|integer',
            'withdraw_option_id' => 'required|integer',
            'user_id' => 'required|integer',
        ]);
        $old_status = $withdraw->status;
        $balance = Balance::where('user_id', $request->user_id)->first();
        if($old_status != 1 && $request->status == 1){
            if(!$balance || $balance->balance < $request->balance_used){
                return new APIResource(false, 'Saldo tidak cukup',null);
            }
            $balance->balance -= $request->balance_used;
        } else if($old_status == 1 && $request->status != 1){
            if(!$balance){
                $balance = Balance::create([
                    'user_id' => $request->user_id,
                    'balance' => $request->balance_used
                ]);
            } else{
                $balance->balance += $request->balance_used;
            }
        }
        $balance->save();
        $withdraw->update([
            'count'   => $request->count,
            'balance_used' => $request->balance_used,
            'status' => $request->status,
            'withdraw_option_id' => $request->withdraw_option_id,
            'user_id' => $request->user_id,
        ]);
        return new APIResource(true, 'Withdraw updated successfully',$withdraw);
    }

    public function destroy($id)
    {
        $withdraw = Withdraw::find($id);

        if (! $withdraw) {
            return new APIResource(false, 'Withdraw not found',null);
        }

        $withdraw->delete();

        return new APIResource(true, 'Withdraw deleted successfully',null);
    }
}
