@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Products</h6>
                <button class="btn btn-primary btn-icon-split" id="addProductBtn">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Product</span>
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="productTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Low Stock Threshold</th>
                            <th>Category</th>
                            <th>Container</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->description }}</td>
                                <td>₱{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->low_stock_threshold }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->container ? $product->container->name : 'N/A' }}</td>
                                <td><img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="50"></td>
                                <td>
                                    @if ($product->stock == 0)
                                        <span class="badge bg-danger">No Stock</span>
                                    @elseif ($product->stock < $product->low_stock_threshold)
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm editProductBtn" data-id="{{ $product->id }}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteProductBtn" data-id="{{ $product->id }}">Delete</button>
                                </td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable();

            // Add Product
            $('#addProductBtn').on('click', function() {
                let categoryOptions = '<option value="">Select Category</option>';
                @foreach($categories as $category)
                    categoryOptions += `<option value="{{ $category->id }}">{{ $category->name }}</option>`;
                @endforeach

                let containerOptions = '<option value="">Select Container</option>';
                @foreach($containers as $container)
                    containerOptions += `<option value="{{ $container->id }}">{{ $container->name }}</option>`;
                @endforeach

                Swal.fire({
                    title: 'Add Product',
                    html: `
                        <input type="text" id="name" class="swal2-input" placeholder="Name">
                        <input type="text" id="description" class="swal2-input" placeholder="Description">
                        <input type="number" id="price" class="swal2-input" placeholder="Price">
                        <input type="number" id="stock" class="swal2-input" placeholder="Stock">
                        <input type="number" id="low_stock_threshold" class="swal2-input" placeholder="Low Stock Threshold">
                        <select id="category_id" class="swal2-input">
                            ${categoryOptions}
                        </select>
                        <select id="container_id" class="swal2-input">
                            ${containerOptions}
                        </select>
                        <input type="file" id="image" class="swal2-file" placeholder="Image">
                    `,
                    confirmButtonText: 'Add',
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = Swal.getPopup().querySelector('#name').value;
                        const description = Swal.getPopup().querySelector('#description').value;
                        const price = Swal.getPopup().querySelector('#price').value;
                        const stock = Swal.getPopup().querySelector('#stock').value;
                        const low_stock_threshold = Swal.getPopup().querySelector('#low_stock_threshold').value;
                        const category_id = Swal.getPopup().querySelector('#category_id').value;
                        const container_id = Swal.getPopup().querySelector('#container_id').value;
                        const image = Swal.getPopup().querySelector('#image').files[0];
                        if (!name || !price || !stock || !low_stock_threshold || !category_id || !container_id || !image) {
                            Swal.showValidationMessage(`Please fill all required fields`);
                        }
                        return { name, description, price, stock, low_stock_threshold, category_id, container_id, image };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('name', result.value.name);
                        formData.append('description', result.value.description);
                        formData.append('price', result.value.price);
                        formData.append('stock', result.value.stock);
                        formData.append('low_stock_threshold', result.value.low_stock_threshold);
                        formData.append('category_id', result.value.category_id);
                        formData.append('container_id', result.value.container_id);
                        formData.append('image', result.value.image);

                        $.ajax({
                            url: '{{ route('products.store') }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire('Success', response.message, 'success');
                                location.reload();
                            },
                            error: function(response) {
                                Swal.fire('Error', response.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });

            $(document).on('click', '.editProductBtn', function() {
                const productId = $(this).data('id');
                console.log('Edit button clicked for product ID:', productId);

                $.get(`{{ url('admin/products') }}/${productId}`, function(response) {
                    console.log('Product data retrieved:', response);

                    if (response.data) {
                        const product = response.data;
                        console.log('Product details:', product);

                        // Fetch categories and containers
                        const categories = @json($categories);
                        const containers = @json($containers);

                        let categoryOptions = '<option value="">Select Category</option>';
                        categories.forEach(category => {
                            categoryOptions += `<option value="${category.id}" ${category.id == product.category_id ? 'selected' : ''}>${category.name}</option>`;
                        });

                        let containerOptions = '<option value="">Select Container</option>';
                        containers.forEach(container => {
                            containerOptions += `<option value="${container.id}" ${container.id == product.container_id ? 'selected' : ''}>${container.name}</option>`;
                        });

                        Swal.fire({
                            title: 'Edit Product',
                            html: `
                    <input type="text" id="name" class="swal2-input" value="${product.name}" placeholder="Name">
                    <input type="text" id="description" class="swal2-input" value="${product.description}" placeholder="Description">
                    <input type="number" id="price" class="swal2-input" value="${product.price}" placeholder="Price">
                    <input type="number" id="stock" class="swal2-input" value="${product.stock}" placeholder="Stock">
                    <input type="number" id="low_stock_threshold" class="swal2-input" value="${product.low_stock_threshold}" placeholder="Low Stock Threshold">
                    <select id="category_id" class="swal2-input">
                        ${categoryOptions}
                    </select>
                    <select id="container_id" class="swal2-input">
                        ${containerOptions}
                    </select>
                    <input type="file" id="image" class="swal2-file" placeholder="Image">
                `,
                            confirmButtonText: 'Update',
                            focusConfirm: false,
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#name').value;
                                const description = Swal.getPopup().querySelector('#description').value;
                                const price = Swal.getPopup().querySelector('#price').value;
                                const stock = Swal.getPopup().querySelector('#stock').value;
                                const low_stock_threshold = Swal.getPopup().querySelector('#low_stock_threshold').value;
                                const category_id = Swal.getPopup().querySelector('#category_id').value;
                                const container_id = Swal.getPopup().querySelector('#container_id').value;
                                const image = Swal.getPopup().querySelector('#image').files[0];
                                console.log('Form values:', { name, description, price, stock, low_stock_threshold, category_id, container_id, image });

                                if (!name || !price || !stock || !low_stock_threshold || !category_id || !container_id) {
                                    Swal.showValidationMessage(`Please fill all required fields`);
                                }
                                return { name, description, price, stock, low_stock_threshold, category_id, container_id, image };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('_method', 'PUT');
                                formData.append('name', result.value.name);
                                formData.append('description', result.value.description);
                                formData.append('price', result.value.price);
                                formData.append('stock', result.value.stock);
                                formData.append('low_stock_threshold', result.value.low_stock_threshold);
                                formData.append('category_id', result.value.category_id);
                                formData.append('container_id', result.value.container_id);
                                if (result.value.image) {
                                    formData.append('image', result.value.image);
                                }
                                console.log('Form data to be sent:', formData);

                                $.ajax({
                                    url: `{{ url('admin/products') }}/${productId}`,
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        console.log('Product updated successfully:', response);
                                        Swal.fire('Success', response.message, 'success');
                                        location.reload();
                                    },
                                    error: function(response) {
                                        console.log('Error updating product:', response);
                                        Swal.fire('Error', response.responseJSON.message, 'error');
                                    }
                                });
                            }
                        });
                    } else {
                        console.log('Failed to load product details.');
                        Swal.fire('Error', 'Failed to load product details.', 'error');
                    }
                }).fail(function() {
                    console.log('Failed to load product details.');
                    Swal.fire('Error', 'Failed to load product details.', 'error');
                });
            });

            // Delete Product
            $(document).on('click', '.deleteProductBtn', function() {
                const productId = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('admin/products') }}/${productId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', response.message, 'success');
                                location.reload();
                            },
                            error: function(response) {
                                Swal.fire('Error', response.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
