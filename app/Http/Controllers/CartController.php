<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Http\Resources\APIResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::latest()->with(['product','user'])->paginate(10);
        return new APIResource(true, 'Cart list retrieved successfully',$carts);
    }
    public function my()
    {
        $carts = Cart::latest()->where('user_id',Auth::id)->with(['product'])->get();
        return new APIResource(true, 'Saving list retrieved successfully',$carts);
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id'   => 'required|integer',
        ]);
        $product = Product::find($request->product_id);
        if(!$product){
            return new APIResource(false, 'Product not found',null);
        }
        $cart = Cart::create([
            'product_id'   => $request->product_id,
            'user_id' => Auth::id(),
        ]);
        return new APIResource(true, 'Cart created successfully',$cart);
    }

    public function destroy($id)
    {
        $cart = Cart::find($id);

        if (! $cart) {
            return new APIResource(false, 'Cart not found',null);
        }

        $cart->delete();

        return new APIResource(true, 'Cart deleted successfully',null);
    }
}
