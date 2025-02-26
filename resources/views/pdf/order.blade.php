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
    @if($order->user)
        <p>User: {{ $order->user->name }}</p>
    @endif
    @if($order->status)
        <p>Status: {{ $order->status }}</p>
    @endif
    @if($order->created_at)
        <p>Order Generated At: {{ $order->created_at->format('M j, Y g:i A') }}</p>
    @endif
</div>
<h2>Billing</h2>
@if($order->billing_name)
    <p>{{ $order->billing_name }}</p>
@endif
@if($order->billing_address)
    <p>{{ $order->billing_address }}</p>
@endif
@if($order->billing_city || $order->billing_state || $order->billing_zip)
    <p>
        @if($order->billing_city){{ $order->billing_city }}@endif
        @if($order->billing_city && $order->billing_state), @endif
        @if($order->billing_state){{ $order->billing_state }}@endif
        @if(($order->billing_city || $order->billing_state) && $order->billing_zip) @endif
        @if($order->billing_zip){{ $order->billing_zip }}@endif
    </p>
@endif
@if($order->billing_phone)
    <p>Phone: {{ $order->billing_phone }}</p>
@endif
@if($order->billing_email)
    <p>Email: {{ $order->billing_email }}</p>
@endif
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
        @if($item && $item->product)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₱{{ number_format($item->price, 2) }}</td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
<div class="grand-total">
    <p>Grand Total: ₱{{ number_format($order->orderItems->sum(fn($item) => $item && $item->price ? $item->price * ($item->quantity ?? 1) : 0), 2) }}</p>
</div>
</body>
</html>
