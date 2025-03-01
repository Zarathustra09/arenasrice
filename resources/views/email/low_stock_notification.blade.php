<!DOCTYPE html>
<html>
<head>
    <title>Low Stock Notification</title>
</head>
<body>
<h1>Low Stock Notification</h1>
<p>The following product containers are in low stock:</p>
<ul>
    @foreach($containers as $container)
        <li>{{ $container->name }} (Current Quantity: {{ $container->quantity }})</li>
    @endforeach
</ul>
</body>
</html>
