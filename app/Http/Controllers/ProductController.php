<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->with(['discounts', 'comments','wishlists'])->paginate(10);
        return new APIResource(true, 'Product list retrieved successfully',$products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'point' => 'required|integer',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }
        $product = Product::create([
            'name'   => $request->name,
            'description' => $request->description,
            'image_path' => $path,
            'price' => $request->price,
            'stock' => $request->stock,
            'point' => $request->point,
        ]);
        return new APIResource(true, 'Product created successfully',$product);
    }

    public function show($id)
    {
        $product = Product::where('id',$id)->with(['discounts', 'comments','wishlists'])->first();

        if (! $product) {
            return new APIResource(false, 'Product not found',null);
        }

        return new APIResource(true, 'Product retrieved successfully',$product);
    }

    public function update(Request $request)
    {
        $product = Product::find($request->id);

        if (! $product) {
            return new APIResource(false, 'Product not found',null);
        }
        
        $request->validate([
            'name'   => 'required|string|max:255',
            'description' => 'required|string',
            'image'   => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'point' => 'required|integer',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
        }
        $product->update([
            'name'   => $request->name,
            'description' => $request->description,
            'image_path' => $path,
            'price' => $request->price,
            'stock' => $request->stock,
            'point' => $request->point,
        ]);

        return new APIResource(true, 'Product updated successfully',$product);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (! $product) {
            return new APIResource(false, 'Product not found',null);
        }

        $product->delete();

        return new APIResource(true, 'Product deleted successfully',null);
    }
}
