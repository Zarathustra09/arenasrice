<!DOCTYPE html>
<html>
<head>
    <title>New Order Placed</title>
</head>
<body>
<h1>New Order Placed</h1>
<p>Order ID: {{ $order->id }}</p>
<p>Total Amount: ₱{{ number_format($order->total_amount, 2) }}</p>
<!-- Add more order details as needed -->
</body>
</html>
