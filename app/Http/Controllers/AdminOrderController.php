<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminOrderController extends Controller
{
    public function index()
    {
        return view('admin.order.index');
    }

   public function dataTable()
{
    $search = request()->input('search.value');
    $orders = Order::with(['orderItems.product', 'user'])
        ->whereNotNull('user_id')
        ->when($search, function ($query, $search) {
            return $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })->orWhereHas('orderItems.product', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->get();

    $data = $orders->map(function($order) {
        return [
            'id' => $order->id,
            'user' => $order->user->name,
            'orderItems' => $order->orderItems->map(function($item) {
                return [
                    'product' => [
                        'name' => $item->product->name,
                        'image' => Storage::url($item->product->image)
                    ],
                    'price' => $item->price,
                    'quantity' => $item->quantity
                ];
            }),
            'status' => $order->status
        ];
    });

    return response()->json([
        'data' => $data,
        'recordsTotal' => $orders->count(),
        'recordsFiltered' => $orders->count()
    ]);
}

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $newStatus = $request->input('status');

        // Validate the new status
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'canceled'];
        if (!in_array($newStatus, $validStatuses)) {
            return response()->json(['message' => 'Invalid status value!'], 400);
        }

        // If the order is being cancelled, return the stock to the inventory
        if ($newStatus === 'canceled' && $order->status !== 'canceled') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                $product->increment('stock', $item->quantity);
            }
        }

        $order->status = $newStatus;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully!']);
    }
}
