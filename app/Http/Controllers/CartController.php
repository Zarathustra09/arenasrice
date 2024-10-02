<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Auth::user()->cartItems()->with('product')->get();
        return view('guest.checkout', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'You need to login to add products to cart!');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cart = Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->input('product_id'),
            'quantity' => 1, // Default quantity
        ]);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }


}
