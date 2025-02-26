<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $order->id }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;;
            font-size: 12px;
            max-width: 500px;
            margin: auto;
            padding: 10px;
            border: 1px solid #000;
        }
        h1, h2 {
            text-align: center;
            font-size: 14px;
            margin: 5px 0;
        }
        p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            padding: 4px;
            text-align: left;
            border-bottom: 1px dashed #000;
        }
        th {
            font-weight: bold;
        }
        .summary {
            text-align: center;
            margin-bottom: 5px;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .grand-total {
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<h1>Order #{{ $order->id }}</h1>
<div class="summary">
    <p>User: {{ $order->user->name }}</p>
    <p>Status: {{ $order->status }}</p>
    <p>Order Generated At: {{ $order->created_at->format('M j, Y g:i A') }}</p>
</div>
<h2>Billing</h2>
<p>{{ $order->billing_name }}</p>
<p>{{ $order->billing_address }}</p>
<p>{{ $order->billing_city }}, {{ $order->billing_state }} {{ $order->billing_zip }}</p>
<p>Phone: {{ $order->billing_phone }}</p>
<p>Email: {{ $order->billing_email }}</p>
<h2>Items</h2>
<table>
    <thead>
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($order->orderItems as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₱{{ number_format($item->price, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
<div class="grand-total">
    <p>Grand Total: ₱{{ number_format($order->orderItems->sum(fn($item) => $item->price * $item->quantity), 2) }}</p>
</div>
</body>
</html>
