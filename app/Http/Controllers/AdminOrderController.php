<?php

namespace App\Http\Controllers;

use App\Mail\LowStockNotification;
use App\Mail\OrderDeliveredNotification;
use App\Mail\OrderShippedNotification;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
                return $query->whereNotNull('user_id')
                    ->where(function ($query) use ($search) {
                        $query->whereHas('user', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        })->orWhereHas('orderItems.product', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                    });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'reference_id' => $order->reference_id,
                'user' => $order->user ? $order->user->name : 'N/A',
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

        // If the order is being delivered, deduct the stock from the product containers
        if ($newStatus === 'delivered' && $order->status !== 'delivered') {
            $lowStockContainers = [];
            foreach ($order->orderItems as $item) {
                $container = $item->product->container;
                if ($container) {
                    if ($container->quantity >= $item->quantity) {
                        $container->decrement('quantity', $item->quantity);
                        if ($container->quantity <= $container->low_stock_threshold) {
                            $lowStockContainers[] = $container;
                        }
                    } else {
                        $lowStockContainers[] = $container;
                    }
                }
            }

            Log::info('Low stock containers: ' . json_encode($lowStockContainers));

            // Send low stock notification email
            if (!empty($lowStockContainers)) {
                Mail::to(env('KIM_MAIL'))->send(new LowStockNotification($lowStockContainers));
            }

            // Send order delivered notification email to the user
            Mail::to($order->user->email)->send(new OrderDeliveredNotification($order));
        }

        if ($newStatus === 'shipped' && $order->status !== 'shipped') {
            Mail::to($order->user->email)->send(new OrderShippedNotification($order));
        }
        $order->status = $newStatus;
        $order->save();

        return response()->json(['message' => 'Order status updated successfully!']);
    }

    public function updateOrderItems(Request $request, $id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        $itemsToUpdate = $request->input('items');

        foreach ($itemsToUpdate as $itemData) {
            $item = $order->orderItems()->find($itemData['id']);
            if ($item) {
                $product = $item->product;
                if ($product->stock < $itemData['quantity']) {
                    return response()->json(['message' => "Not enough stock for product: {$product->name}"], 400);
                }
                $item->update(['quantity' => $itemData['quantity']]);
            }
        }

        return response()->json(['message' => 'Order items updated successfully!']);
    }

    public function show($id)
    {
        $order = Order::with('orderItems.product')->findOrFail($id);
        Log::info($order);
        return response()->json(['orderItems' => $order->orderItems]);
    }
}
