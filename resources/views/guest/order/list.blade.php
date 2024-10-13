@extends('layouts.guest-app')

@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Orders</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Orders List</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <div class="container py-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Your Orders</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ordersTable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Products</th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        <img src="{{ Storage::url($item->product->image) }}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="{{ $item->product->name }}">
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        {{ $item->product->name }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        ₱{{ $item->price }}<br>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($order->orderItems as $item)
                                        {{ $item->quantity }}<br>
                                    @endforeach
                                </td>
                                <td>₱{{ $order->total_amount }}</td>
                                <td>{{ $order->status }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                order: [[0, 'desc']],
                search: {
                    regex: true
                }
            });
        });
    </script>
@endpush
