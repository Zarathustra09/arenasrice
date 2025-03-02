<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $orders = Order::whereNull('user_id')->get();

        $monthlyEarnings = $orders->whereBetween('created_at', [now()->startOfMonth(), now()])->sum('total_amount');
        $annualEarnings = $orders->whereBetween('created_at', [now()->startOfYear(), now()])->sum('total_amount');
        $deliveredOrders = $orders->where('status', 'delivered')->count();

        return view('staff.pos.index', compact('products', 'monthlyEarnings', 'annualEarnings', 'deliveredOrders'));
    }

    public function getOrders()
    {
        $orders = Order::whereNull('user_id')->orderBy('id', 'desc')->get();
        return response()->json(['orders' => $orders]);
    }

    public function saveOrder(Request $request)
    {
        $user = Auth::user();
        $cartItems = json_decode($request->input('cartItems'), true);

        if (empty($cartItems)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Check if any cart item exceeds the available stock
        foreach ($cartItems as $item) {
            $product = Product::find($item['id']);
            if ($item['quantity'] > $product->stock) {
                return redirect()->back()->with('error', 'The quantity of ' . $product->name . ' exceeds the available stock.');
            }
        }

        $order = Order::create([
            'user_id' => null,
            'total_amount' => array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cartItems)),
            'status' => 'delivered',
            'billing_name' => $request->billing_name,
            'billing_address' => $request->billing_address,
            'billing_city' => $request->billing_city,
            'billing_state' => $request->billing_state,
            'billing_zip' => $request->billing_zip,
            'billing_phone' => $request->billing_phone,
            'billing_email' => $request->billing_email,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Decrease the product stock
            Product::find($item['id'])->decrement('stock', $item['quantity']);
        }

        return redirect()->route('pos.index')->with('success', 'Order placed successfully!');
    }
}
