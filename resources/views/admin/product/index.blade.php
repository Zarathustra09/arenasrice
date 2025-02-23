@extends('layouts.app')

@section('content')
    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
            <a href="#" class="btn btn-primary btn-icon-split" id="addProductBtn">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Product</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'price', name: 'price' },
                    { data: 'stock', name: 'stock' },
                    { data: 'category.name', name: 'category.name' },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<img src="/storage/${data}" class="product-image" style="width:50px" alt="Product Image">`;
                        }
                    },
                    {
                        data: 'stock',
                        name: 'status',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data == 0) {
                                return '<span class="badge bg-danger">No Stock</span>';
                            } else if (data < 20) {
                                return '<span class="badge bg-warning text-dark">Low Stock</span>';
                            } else {
                                return '<span class="badge bg-success">In Stock</span>';
                            }
                        }
                    },
                    {
                        data: 'id',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <a href="#" class="btn btn-primary btn-sm editProductBtn" data-id="${data}">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm deleteProductBtn" data-id="${data}">Delete</a>
                    `;
                        }
                    }
                ]
            });
        });


            // Add Product
            $('#addProductBtn').on('click', function() {
                $.get('{{ route('categories.get') }}', function(response) {
                    if (response.data) {
                        let categoryOptions = '';
                        response.data.forEach(category => {
                            categoryOptions += `<option value="${category.id}">${category.name}</option>`;
                        });

                        Swal.fire({
                            title: 'Add Product',
                            html: `
                                <input type="text" id="name" class="swal2-input" placeholder="Name">
                                <input type="text" id="description" class="swal2-input" placeholder="Description">
                                <input type="number" id="price" class="swal2-input" placeholder="Price">
                                <input type="number" id="stock" class="swal2-input" placeholder="Stock">
                                <select id="category_id" class="swal2-input">
                                    <option value="">Select Category</option>
                                    ${categoryOptions}
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
                                const category_id = Swal.getPopup().querySelector('#category_id').value;
                                const image = Swal.getPopup().querySelector('#image').files[0];
                                if (!name || !price || !stock || !category_id || !image) {
                                    Swal.showValidationMessage(`Please fill all required fields`);
                                }
                                return { name, description, price, stock, category_id, image };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('name', result.value.name);
                                formData.append('description', result.value.description);
                                formData.append('price', result.value.price);
                                formData.append('stock', result.value.stock);
                                formData.append('category_id', result.value.category_id);
                                formData.append('image', result.value.image);

                                $.ajax({
                                    url: '{{ route('products.store') }}',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: function(response) {
                                        Swal.fire('Success', response.message, 'success');
                                        $('#dataTable').DataTable().ajax.reload();
                                    },
                                    error: function(response) {
                                        Swal.fire('Error', response.responseJSON.message, 'error');
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire('Error', 'Failed to load categories.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Failed to load categories.', 'error');
                });
            });

            // Edit Product
            $(document).on('click', '.editProductBtn', function() {
                const productId = $(this).data('id');
                $.get(`{{ url('admin/products') }}/${productId}`, function(response) {
                    if (response.data) {
                        const product = response.data;
                        $.get('{{ route('categories.get') }}', function(response) {
                            if (response.data) {
                                let categoryOptions = '';
                                response.data.forEach(category => {
                                    categoryOptions += `<option value="${category.id}" ${category.id == product.category_id ? 'selected' : ''}>${category.name}</option>`;
                                });

                                Swal.fire({
                                    title: 'Edit Product',
                                    html: `
                                        <input type="text" id="name" class="swal2-input" value="${product.name}" placeholder="Name">
                                        <input type="text" id="description" class="swal2-input" value="${product.description}" placeholder="Description">
                                        <input type="number" id="price" class="swal2-input" value="${product.price}" placeholder="Price">
                                        <input type="number" id="stock" class="swal2-input" value="${product.stock}" placeholder="Stock">
                                        <select id="category_id" class="swal2-input">
                                            <option value="">Select Category</option>
                                            ${categoryOptions}
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
                                        const category_id = Swal.getPopup().querySelector('#category_id').value;
                                        const image = Swal.getPopup().querySelector('#image').files[0];
                                        if (!name || !price || !stock || !category_id) {
                                            Swal.showValidationMessage(`Please fill all required fields`);
                                        }
                                        return { name, description, price, stock, category_id, image };
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
                                        formData.append('category_id', result.value.category_id);
                                        if (result.value.image) {
                                            formData.append('image', result.value.image);
                                        }

                                        $.ajax({
                                            url: `{{ url('admin/products') }}/${productId}`,
                                            type: 'POST',
                                            data: formData,
                                            processData: false,
                                            contentType: false,
                                            success: function(response) {
                                                Swal.fire('Success', response.message, 'success');
                                                $('#dataTable').DataTable().ajax.reload();
                                            },
                                            error: function(response) {
                                                Swal.fire('Error', response.responseJSON.message, 'error');
                                            }
                                        });
                                    }
                                });
                            } else {
                                Swal.fire('Error', 'Failed to load categories.', 'error');
                            }
                        }).fail(function() {
                            Swal.fire('Error', 'Failed to load categories.', 'error');
                        });
                    } else {
                        Swal.fire('Error', 'Failed to load product details.', 'error');
                    }
                }).fail(function() {
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
                                $('#dataTable').DataTable().ajax.reload();
                            },
                            error: function(response) {
                                Swal.fire('Error', response.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });
    </script>
@endpush
