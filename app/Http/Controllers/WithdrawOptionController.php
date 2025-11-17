<?php

namespace App\Http\Controllers;
use App\Models\WithdrawOption;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class WithdrawOptionController extends Controller
{
    public function index()
    {
        $withdrawOptions = WithdrawOption::latest()->get();
        return new APIResource(true, 'WithdrawOption list retrieved successfully',$withdrawOptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('withdrawOptions', 'public');
        }
        $withdrawOption = WithdrawOption::create([
            'name'   => $request->name,
            'description' => $request->description,
            'image_path' => $path,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        return new APIResource(true, 'WithdrawOption created successfully',$withdrawOption);
    }

    public function show($id)
    {
        $withdrawOption = WithdrawOption::where('id',$id)->first();

        if (! $withdrawOption) {
            return new APIResource(false, 'WithdrawOption not found',null);
        }

        return new APIResource(true, 'WithdrawOption retrieved successfully',$withdrawOption);
    }

    public function update(Request $request)
    {
        $withdrawOption = WithdrawOption::find($request->id);

        if (! $withdrawOption) {
            return new APIResource(false, 'WithdrawOption not found',null);
        }
        
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('withdrawOptions', 'public');
        }
        $withdrawOption->update([
            'name'   => $request->name,
            'description' => $request->description,
            'image_path' => $path,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        return new APIResource(true, 'WithdrawOption updated successfully',$withdrawOption);
    }

    public function destroy($id)
    {
        $withdrawOption = WithdrawOption::find($id);

        if (! $withdrawOption) {
            return new APIResource(false, 'WithdrawOption not found',null);
        }

        $withdrawOption->delete();

        return new APIResource(true, 'WithdrawOption deleted successfully',null);
    }
}
