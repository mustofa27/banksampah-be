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
        $savings = Saving::latest()->where('user_id',Auth::id)->with(['garbage'])->paginate(10);
        return new APIResource(true, 'Saving list retrieved successfully',$savings);
    }
    public function store(Request $request)
    {
        $request->validate([
            'weight'   => 'required|integer',
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
            $user_id = Auth::id;
            $status = 0;
        }
        $saving = Saving::create([
            'weight'   => $request->weight,
            'garbage_id' => $request->garbage_id,
            'user_id' => $user_id,
            'total_price' => $garbage->price_per_kg*$request->weight,
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
            'weight'   => 'required|integer',
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
                    'balance' => $garbage->price_per_kg*$request->weight,
                ]);
            } else{
                $balance->update([
                    'balance' => $balance->balance + $garbage->price_per_kg*$request->weight,
                ]);
            }
        }
        $saving->update([
            'weight'   => $request->weight,
            'garbage_id' => $request->garbage_id,
            'total_price' => $garbage->price_per_kg*$request->weight,
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
