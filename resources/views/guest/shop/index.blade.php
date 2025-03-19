@extends('layouts.guest-app')

@section('content')

    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Shop</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Shop</li>
        </ol>
    </div>
    <div class="container-fluid fruite py-5">
        <div class="container py-5">
            <h1 class="mb-4">Fresh Bakery Shop</h1>
            <div class="row g-4">
                <div class="col-12">
                    <div class="row g-4">
                        <div class="col-xl-3">
                            <div class="input-group w-100 mx-auto d-flex">
                                <form action="{{ route('shop.index') }}" method="GET" class="d-flex w-100">
                                    <input type="search" name="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1" value="{{ request('search') }}">
                                    <button type="submit" class="input-group-text p-3" id="search-icon-1"><i class="fa fa-search"></i></button>
                                </form>
                            </div>
                        </div>
                        <div class="col-6"></div>
                    </div>
                    <div class="row g-4">
                        <div class="col-12">
                            <div class="row g-4 justify-content-center">
                                @foreach($products as $product)
                                    <div class="col-md-3">
                                        <div class="card mb-4">
                                            <img src="{{ Storage::url($product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $product->name }}</h5>
                                                <p class="card-text">{{ $product->description }}</p>


                                                <p class="card-text">₱{{ $product->price }}</p>
                                                @if(auth()->check())
                                                <form class="add-to-cart-form" action="{{ route('cart.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    <input type="number" name="quantity" value="1" min="1" max="{{$product->stock}}" class="form-control mb-2" style="width: 60px;">
                                                    <p class="mb-2">Stock: {{ $product->stock }}</p>
                                                    <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                        <i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart
                                                    </button>
                                                </form>

                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $products->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Toast success notification
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            });

                            Toast.fire({
                                icon: 'success',
                                title: 'Product added to cart successfully!'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            // Toast error notification
                            const Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true
                            });

                            Toast.fire({
                                icon: 'error',
                                title: data.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // Toast error notification
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });

                        Toast.fire({
                            icon: 'error',
                            title: 'An error occurred while adding the product to the cart.'
                        });
                    });
            });
        });
    </script>
@endpush
