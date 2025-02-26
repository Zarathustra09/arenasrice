<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
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
    if (!Auth::check()) {
        return response()->json(['success' => false, 'message' => 'You need to login to add products to cart!'], 401);
    }

    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::findOrFail($request->input('product_id'));
    $requestedQuantity = $request->input('quantity', 1);

    $cartItem = Cart::where('user_id', Auth::id())
        ->where('product_id', $request->input('product_id'))
        ->first();

    $currentQuantity = $cartItem ? $cartItem->quantity : 0;
    $totalQuantity = $currentQuantity + $requestedQuantity;

    if ($totalQuantity > $product->stock) {
        return response()->json(['success' => false, 'message' => 'The requested quantity exceeds the available stock!'], 400);
    }

    if ($cartItem) {
        $cartItem->quantity = $totalQuantity;
        $cartItem->save();
    } else {
        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $request->input('product_id'),
            'quantity' => $requestedQuantity,
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Product added to cart successfully!']);
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
        $product = $cartItem->product;

        if ($cartItem->quantity + 1 > $product->stock) {
            return response()->json(['success' => false, 'message' => 'The requested quantity exceeds the available stock!'], 400);
        }

        $cartItem->quantity += 1;
        $cartItem->save();

        return response()->json(['success' => true, 'quantity' => $cartItem->quantity]);
    }

    public function decreaseQuantity(Request $request, $id)
    {
        $cartItem = Cart::findOrFail($id);
        $product = $cartItem->product;

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
