@extends('layouts.guest-app')

@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Cart</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Cart</li>
        </ol>
    </div>
    <!-- Single Page Header End -->

    <!-- Cart Page Start -->
    <div class="container-fluid py-5">
        <div class="container py-5">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Products</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total</th>
                        <th scope="col">Handle</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($cartItems as $item)
                        <tr id="cart-item-{{ $item->id }}">
                            <th scope="row">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($item->product->image) }}" class="img-fluid me-5 rounded-circle" style="width: 80px; height: 80px;" alt="{{ $item->product->name }}">
                                </div>
                            </th>
                            <td><p class="mb-0 mt-4">{{ $item->product->name }}</p></td>
                            <td><p class="mb-0 mt-4">₱{{ $item->product->price }}</p></td>
                            <td>
                                <div class="input-group quantity mt-4" style="width: 100px;">
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-minus rounded-circle bg-light border" onclick="updateQuantity({{ $item->id }}, 'decrease')">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </div>
                                    <input type="text" class="form-control form-control-sm text-center border-0" id="quantity-{{ $item->id }}" value="{{ $item->quantity }}" readonly>
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-plus rounded-circle bg-light border" onclick="updateQuantity({{ $item->id }}, 'increase')">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                            <td><p class="mb-0 mt-4" id="total-{{ $item->id }}" data-price="{{ $item->product->price }}">₱{{ $item->product->price * $item->quantity }}</p></td>
                            <td>
                                <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="mt-4">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-md rounded-circle bg-light border">
                                        <i class="fa fa-times text-danger"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                <input type="text" class="border-0 border-bottom rounded me-5 py-3 mb-4" placeholder="Coupon Code">
                <button class="btn border-secondary rounded-pill px-4 py-3 text-primary" type="button">Apply Coupon</button>
            </div>
            <div class="row g-4 justify-content-end">
                <div class="col-8"></div>
                <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                    <div class="bg-light rounded">
                        <div class="p-4">
                            <h1 class="display-6 mb-4">Cart <span class="fw-normal">Total</span></h1>
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0 me-4">Subtotal:</h5>
                                <p class="mb-0" id="subtotal" data-price="{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}">₱{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}</p>
                            </div>
                        </div>
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Total</h5>
                            <p class="mb-0 pe-4" id="total" data-price="{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}">₱{{ $cartItems->sum(fn($item) => $item->product->price * $item->quantity) }}</p>
                        </div>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <div class="px-4 mb-4">
                                <h5>Billing Address</h5>
                                <div class="mb-3">
                                    <input type="text" name="billing_name" class="form-control" placeholder="Full Name" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="billing_address" class="form-control" placeholder="Address" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="billing_city" class="form-control" placeholder="City" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="billing_state" class="form-control" placeholder="State/Province" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="billing_zip" class="form-control" placeholder="Postal Code" required>
                                </div>
                                <div class="mb-3">
                                    <input type="text" name="billing_country" class="form-control" placeholder="Country" required>
                                </div>
                                <div class="mb-3">
                                    <input type="tel" name="billing_phone" class="form-control" placeholder="Phone Number" required>
                                </div>
                                <div class="mb-3">
                                    <input type="email" name="billing_email" class="form-control" placeholder="Email Address" required>
                                </div>
                            </div>
                            <button class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4" type="submit">Proceed Checkout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Cart Page End -->
@endsection
@push('scripts')
    <script>
        function updateQuantity(id, action) {
            $.ajax({
                url: `/guest/cart/${id}/${action}`,
                type: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        let quantityInput = $(`#quantity-${id}`);
                        let totalElement = $(`#total-${id}`);
                        let price = parseFloat(totalElement.data('price'));

                        if (response.quantity > 0) {
                            quantityInput.val(response.quantity);
                            totalElement.text(`₱${(price * response.quantity).toFixed(2)}`);
                        } else {
                            $(`#cart-item-${id}`).remove();
                        }

                        // Update subtotal and total
                        let subtotal = 0;
                        $('p[id^="total-"]').each(function() {
                            subtotal += parseFloat($(this).text().replace('₱', ''));
                        });

                        $('#subtotal').text(`₱${subtotal.toFixed(2)}`);
                        $('#total').text(`₱${subtotal.toFixed(2)}`);
                    }
                }
            });
        }
    </script>
@endpush
