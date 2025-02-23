<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Order #{{ $order->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    </style>
</head>
<body>
<h1>Order #{{ $order->id }}</h1>
<p><strong>User:</strong> {{ $order->user->name }}</p>
<p><strong>Status:</strong> {{ $order->status }}</p>
<p><strong>Total Amount:</strong> {{ $order->total_amount }}</p>
<h2>Billing Address</h2>
<p>{{ $order->billing_name }}</p>
<p>{{ $order->billing_address }}</p>
<p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
<p>{{ $order->billing_phone }}</p>
<p>{{ $order->billing_email }}</p>
<h2>Order Items</h2>
<table>
    <thead>
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->orderItems as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->price }}</td>
            <td>{{ $item->quantity }}</td>
            <td>{{ $item->price * $item->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
