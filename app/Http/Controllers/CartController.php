<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
        return view('guest.cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You need to login to add products to cart!');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $request->input('product_id'))
            ->first();

        if ($cartItem) {
            // If the item already exists in the cart, update the quantity
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            // If the item does not exist in the cart, create a new record
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity', 1), // Default quantity
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->delete();

        return redirect()->back()->with('success', 'Product removed from cart successfully!');
    }

    public static function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->count();
        }
        return 0;
    }

    public function increaseQuantity(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $cartItem->quantity += 1;
        $cartItem->save();

        return response()->json(['success' => true, 'quantity' => $cartItem->quantity]);
    }

    public function decreaseQuantity(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        if ($cartItem->quantity > 1) {
            $cartItem->quantity -= 1;
            $cartItem->save();
            return response()->json(['success' => true, 'quantity' => $cartItem->quantity]);
        } else {
            $cartItem->delete();
            return response()->json(['success' => true, 'quantity' => 0]);
        }
    }


}
