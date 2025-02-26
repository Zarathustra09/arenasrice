@extends('layouts.staff.app')

@section('content')
    <!-- POS System using custom color scheme -->
    <div class="container-fluid">
        <header class="mb-4">
            <h1 class="h3 mb-2" style="color: #9B734F;">Point of Sale System</h1>
            <div class="d-flex justify-content-between align-items-center">
                <div>Cashier: <span class="fw-bold">{{auth()->user()->name}}</span></div>
                <div>Date: <span id="current-date" class="fw-bold"></span></div>
            </div>
        </header>

        <div class="row">
            <!-- Product Selection (Left Side) -->
            <div class="col-lg-8 mb-4">
                <div class="card" style="border-color: #D3A780;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #B68D67; color: white;">
                        <h5 class="mb-0">Products</h5>
                    </div>

                    <!-- Product Grid -->
                    <div class="card-body">
                        <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-2">
                            <!-- Product Items -->
                            @foreach($products as $product)
                                <div class="col">
                                    <div class="card h-100 product-card" style="border-color: #F0C29A; cursor: pointer;">
                                        <div class="card-body text-center p-2">
                                            <div class="mb-2 d-flex align-items-center justify-content-center" style="height: 70px; background-color: #FFDEB5; border-radius: 5px;">
                                                <span style="color: #9B734F; font-weight: bold;">{{ $product->id }}</span>
                                            </div>
                                            <h6 class="card-title mb-0" style="color: #9B734F;">{{ $product->name }}</h6>
                                            <p class="card-text" style="color: #B68D67;">&#8369;{{ number_format($product->price, 2) }}</p>
                                            <p class="card-text" style="color: #B68D67;">Quantity: {{ $product->stock }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart (Right Side) -->
            <div class="col-lg-4">
                <div class="card" style="border-color: #D3A780;">
                    <div class="card-header" style="background-color: #B68D67; color: white;">
                        <h5 class="mb-0">Current Order</h5>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead style="background-color: #F0C29A;">
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="cart-items" style="background-color: #FFFBD1;">
                            <!-- Cart items will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer" style="background-color: #FFDEB5;">
                        <div class="d-flex justify-content-between mb-3 fw-bold" style="color: #9B734F;">
                            <span>Total:</span>
                            <span id="cart-total">&#8369;0.00</span>
                        </div>
                        <div class="d-grid gap-2">
                            <form id="order-form" action="{{ route('pos.saveOrder') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cartItems" id="cart-items-input">
                                <button type="submit" class="btn btn-lg" style="background-color: #9B734F; color: white;">Pay Now</button>
                            </form>
                            <button class="btn btn-clear-order" style="background-color: #D3A780; color: white;">Clear Order</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card" style="border-color: #D3A780;">
                    <div class="card-header" style="background-color: #B68D67; color: white;">
                        <h5 class="mb-0">Orders</h5>
                    </div>
                    <div class="card-body">
                        <table id="orders-table" class="table table-striped">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->total_amount }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td><a href="{{ url('admin/orders') }}/{{ $order->id }}/download" class="btn btn-success btn-sm">Print</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const cart = [];
            const cartItemsContainer = document.getElementById('cart-items');
            const cartTotalElement = document.getElementById('cart-total');
            const cartItemsInput = document.getElementById('cart-items-input');

            function updateCart() {
                cartItemsContainer.innerHTML = '';
                let total = 0;

                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    total += itemTotal;

                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${item.name}</td>
                    <td>
                        <div class="input-group input-group-sm">
                            <button class="btn btn-sm" style="background-color: #D3A780; color: white;" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <input type="text" class="form-control text-center" value="${item.quantity}" style="max-width: 40px;" readonly>
                            <button class="btn btn-sm" style="background-color: #D3A780; color: white;" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </td>
                    <td>₱ ${item.price.toFixed(2)}</td>
                    <td>₱ ${itemTotal.toFixed(2)}</td>
                    <td><button class="btn btn-sm" style="background-color: #9B734F; color: white;" onclick="removeFromCart(${item.id})"><i class="bi bi-trash"></i></button></td>
                `;
                    cartItemsContainer.appendChild(row);
                });

                cartTotalElement.textContent = `₱ ${total.toFixed(2)}`;
                cartItemsInput.value = JSON.stringify(cart);
            }

            window.updateQuantity = function(productId, change) {
                const item = cart.find(item => item.id === productId);
                if (item) {
                    item.quantity += change;
                    if (item.quantity <= 0) {
                        removeFromCart(productId);
                    } else {
                        updateCart();
                    }
                }
            }

            window.removeFromCart = function(productId) {
                const index = cart.findIndex(item => item.id === productId);
                if (index !== -1) {
                    cart.splice(index, 1);
                    updateCart();
                }
            }

            function addToCart(product) {
                const existingItem = cart.find(item => item.id === product.id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ ...product, quantity: 1 });
                }
                updateCart();
            }

            function clearOrder() {
                cart.length = 0; // Clear the cart array
                updateCart(); // Update the cart display
            }

            const productCards = document.querySelectorAll('.product-card');
            productCards.forEach(card => {
                card.addEventListener('click', function() {
                    const product = {
                        id: parseInt(card.querySelector('span').textContent),
                        name: card.querySelector('.card-title').textContent,
                        price: parseFloat(card.querySelector('.card-text').textContent.replace('₱', ''))
                    };
                    addToCart(product);
                });
            });

            document.querySelector('.btn-clear-order').addEventListener('click', clearOrder);
        });
    </script>
@endpush
