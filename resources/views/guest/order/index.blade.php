@extends('layouts.guest-app')

@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Orders</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Orders</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Orders Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ Storage::url($item->product->image) }}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="{{ $item->product->name }}">
                                    </div>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ $item->product->name }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">₱{{ $item->price }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ $item->quantity }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">₱{{ $item->price * $item->quantity }}</p>
                                </td>
                                <td>
                                    <p class="mb-0 mt-4">{{ $order->status }}</p>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Orders Page End -->
@endsection
