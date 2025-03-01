<!DOCTYPE html>
<html>
<head>
    <title>Order Delivered</title>
</head>
<body>
<h1>Order Delivered</h1>
<p>Dear {{ $order->user->name }},</p>
<p>We are pleased to inform you that your order with ID {{ $order->id }} has been delivered successfully.</p>
<p>Thank you for shopping with us!</p>
<p>Best regards,</p>
<p>{{env('APP_NAME')}}</p>
</body>
</html>
