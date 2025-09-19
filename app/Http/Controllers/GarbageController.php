<?php

namespace App\Http\Controllers;

use App\Models\Garbage;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GarbageController extends Controller
{
    public function index()
    {
        $garbages = Garbage::latest()->paginate(10);
        return new APIResource(true, 'Garbage list retrieved successfully',$garbages);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|integer',
            'price_per_kg' => 'required|integer',
        ]);
        $garbage = Garbage::create([
            'name'   => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price_per_kg' => $request->price_per_kg,
        ]);
        return new APIResource(true, 'Garbage created successfully',$garbage);
    }

    public function show($id)
    {
        $garbage = Garbage::where('id',$id)->first();

        if (! $garbage) {
            return new APIResource(false, 'Garbage not found',null);
        }

        return new APIResource(true, 'Garbage retrieved successfully',$garbage);
    }

    public function update(Request $request)
    {
        $garbage = Garbage::find($request->id);

        if (! $garbage) {
            return new APIResource(false, 'Garbage not found',null);
        }
        
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|integer',
            'price_per_kg' => 'required|integer',
        ]);
        $garbage->update([
            'name'   => $request->name,
            'description' => $request->description,
            'category' => $request->category,
            'price_per_kg' => $request->price_per_kg,
        ]);

        return new APIResource(true, 'Garbage updated successfully',$garbage);
    }

    public function destroy($id)
    {
        $garbage = Garbage::find($id);

        if (! $garbage) {
            return new APIResource(false, 'Garbage not found',null);
        }

        $garbage->delete();

        return new APIResource(true, 'Garbage deleted successfully',null);
    }
}
