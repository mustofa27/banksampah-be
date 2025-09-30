<?php

namespace App\Http\Controllers;

use App\Models\Saving;
use App\Models\Garbage;
use App\Models\Balance;
use App\Models\User;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SavingController extends Controller
{
    public function index()
    {
        $savings = Saving::latest()->with(['garbage', 'user'])->paginate(10);
        return new APIResource(true, 'Saving list retrieved successfully',$savings);
    }
    public function my()
    {
        $savings = Saving::latest()->where('user_id',Auth::id())->with(['garbage'])->paginate(10);
        return new APIResource(true, 'Saving list retrieved successfully',$savings);
    }
    public function store(Request $request)
    {
        $request->validate([
            'weight'   => 'required|numeric',
            'garbage_id' => 'required|integer',
        ]);
        $garbage = Garbage::where('id',$request->garbage_id)->first();
        if(!$garbage){
            return new APIResource(false, 'Garbage not found',null);
        }
        if($request->user_id){
            $user = User::find($request->user_id);
            if(!$user){
                return new APIResource(false, 'User not found',null);
            }
            $user_id = $request->user_id;
            $status = 1;
        } else{
            $user_id = Auth::id();
            $status = 0;
        }
        $saving = Saving::create([
            'weight'   => $request->weight,
            'garbage_id' => $request->garbage_id,
            'user_id' => $user_id,
            'total_price' => floor($garbage->price_per_kg*$request->weight/100) * 100,
            'status' => $status,
        ]);
        return new APIResource(true, 'Saving created successfully',$saving);
    }

    public function show($id)
    {
        $saving = Saving::where('id',$id)->with(['garbage', 'user'])->first();

        if (! $saving) {
            return new APIResource(false, 'Saving not found',null);
        }

        return new APIResource(true, 'Saving retrieved successfully',$saving);
    }

    public function update(Request $request)
    {
        $saving = Saving::find($request->id);

        if (! $saving) {
            return new APIResource(false, 'Saving not found',null);
        }
        
        $request->validate([
            'weight'   => 'required|numeric',
            'garbage_id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        $garbage = Garbage::where('id',$request->garbage_id)->first();
        if(!$garbage){
            return new APIResource(false, 'Garbage not found',null);
        }
        $old_status = $saving->status;
        if($old_status == 0 && $request->status == 1){
            $balance = Balance::where('user_id', $saving->user_id)->first();
            if(!$balance){
                $balance = Balance::create([
                    'user_id'   => $saving->user_id,
                    'balance' => floor($garbage->price_per_kg*$request->weight/100) * 100,
                ]);
            } else{
                $balance->update([
                    'balance' => $balance->balance + floor($garbage->price_per_kg*$request->weight/100) * 100,
                ]);
            }
        }
        $saving->update([
            'weight'   => $request->weight,
            'garbage_id' => $request->garbage_id,
            'total_price' => floor($garbage->price_per_kg*$request->weight/100) * 100,
            'status' => $request->status,
        ]);
        
        return new APIResource(true, 'Saving updated successfully',$saving);
    }

    public function destroy($id)
    {
        $saving = Saving::find($id);

        if (! $saving) {
            return new APIResource(false, 'Saving not found',null);
        }

        $saving->delete();

        return new APIResource(true, 'Saving deleted successfully',null);
    }
}
