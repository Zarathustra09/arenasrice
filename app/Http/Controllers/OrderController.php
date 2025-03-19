<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Mail\OrderPlaced;
use Illuminate\Support\Facades\Mail;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('guest.order.index', compact('orders'));
    }

public function checkout(Request $request)
{
    $user = Auth::user();
    $cartItems = Cart::where('user_id', $user->id)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Check if any cart item exceeds the available stock
    foreach ($cartItems as $item) {
        if ($item->quantity > $item->product->stock) {
            return redirect()->back()->with('error', 'The quantity of ' . $item->product->name . ' exceeds the available stock.');
        }
    }

    $order = Order::create([
        'user_id' => $user->id,
        'total_amount' => $cartItems->sum(fn($item) => $item->product->price * $item->quantity),
        'status' => 'pending',
//        'billing_name' => $request->billing_name,
//        'billing_address' => $request->billing_address,
//        'billing_city' => $request->billing_city,
//        'billing_state' => $request->billing_state,
//        'billing_zip' => $request->billing_zip,
//        'billing_phone' => $request->billing_phone,
//        'billing_email' => $request->billing_email,
    ]);

    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item->product_id,
            'quantity' => $item->quantity,
            'price' => $item->product->price,
        ]);

        // Decrease the product stock
        $item->product->decrement('stock', $item->quantity);
    }

    // Clear the cart
    Cart::where('user_id', $user->id)->delete();

    // Send email notification
    Mail::to(env('KIM_MAIL'))->send(new OrderPlaced($order));

    return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
}

    public function downloadOrder($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        // Filter out order items with a total cost of 0
        $order->orderItems = $order->orderItems->filter(function($item) {
            return $item->price * $item->quantity > 0;
        });

        $customPaper = array(0, 0, 360, 720); // Adjusted height to fit the content
        $pdf = Pdf::loadView('pdf.order', [
            'order' => $order,
            'total_paid' => $order->total_paid,
            'change' => $order->total_paid ? $order->total_paid - $order->total_amount : null
        ])
            ->setPaper($customPaper)
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->download('order_' . $order->id . '.pdf');
    }


    public function renderOrder($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);

        // Filter out order items with a total cost of 0
        $order->orderItems = $order->orderItems->filter(function($item) {
            return $item->price * $item->quantity > 0;
        });

        $customPaper = array(0, 0, 360, 720); // Adjusted height to fit the content
        $pdf = Pdf::loadView('pdf.order', [
            'order' => $order,
            'total_paid' => $order->total_paid,
            'change' => $order->total_paid ? $order->total_paid - $order->total_amount : null
        ])
            ->setPaper($customPaper)
            ->setOptions(['defaultFont' => 'DejaVu Sans']);
        return $pdf->stream('order_' . $order->id . '.pdf');
    }


    public function orederListIndex()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc') // Order by created_at in descending order
            ->get();

        // Update the order items to set the price to 0 if the quantity is zero and recalculate the total amount
        $orders->each(function($order) {
            $totalAmount = 0;
            $order->orderItems->each(function($item) use (&$totalAmount) {
                if ($item->quantity == 0) {
                    $item->price = 0;
                }
                $totalAmount += $item->price * $item->quantity;
            });
            $order->total_amount = $totalAmount;
            $order->save();
        });

        return view('guest.order.list', compact('orders'));
    }
}
