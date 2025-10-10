<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Balance;
use App\Http\Resources\APIResource;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    public function my()
    {
        $balance = Balance::where('user_id',Auth::id())->first();
        if(! $balance){
            $balance = Balance::create([
                    'user_id'   => Auth::id(),
                    'balance' => 0,
                ]);
        }
        return new APIResource(true, 'Balance retrieved successfully',$balance);
    }
}
