@extends('layouts.app')

@section('content')
    <!-- Ingredients Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Ingredient List</h6>
            <a href="#" class="btn btn-primary btn-icon-split" id="addIngredientBtn">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Ingredient</span>
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>SKU</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Stock</th>
                        <th>Image</th>
                        <th>Status</th> <!-- New Status Column -->
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
        .ingredient-image {
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
                ajax: '{{ route('ingredients.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'sku', name: 'sku' },
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    { data: 'stock', name: 'stock' },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `<img src="/storage/${data}" class="ingredient-image" style="width:50px" alt="Ingredient Image">`;
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
                        <a href="#" class="btn btn-primary btn-sm editIngredientBtn" data-id="${data}">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm deleteIngredientBtn" data-id="${data}">Delete</a>
                    `;
                        }
                    }
                ]
            });

            // Add Ingredient
            $('#addIngredientBtn').on('click', function() {
                Swal.fire({
                    title: 'Add Ingredient',
                    html: `
                        <input type="text" id="sku" class="swal2-input" placeholder="SKU">
                        <input type="text" id="name" class="swal2-input" placeholder="Name">
                        <input type="text" id="description" class="swal2-input" placeholder="Description">
                        <input type="number" id="stock" class="swal2-input" placeholder="Stock">
                        <input type="file" id="image" class="swal2-file" placeholder="Image">
                    `,
                    confirmButtonText: 'Add',
                    focusConfirm: false,
                    preConfirm: () => {
                        const sku = Swal.getPopup().querySelector('#sku').value;
                        const name = Swal.getPopup().querySelector('#name').value;
                        const description = Swal.getPopup().querySelector('#description').value;
                        const stock = Swal.getPopup().querySelector('#stock').value;
                        const image = Swal.getPopup().querySelector('#image').files[0];
                        if (!sku || !name || !stock || !image) {
                            Swal.showValidationMessage(`Please fill all required fields`);
                        }
                        return { sku, name, description, stock, image };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('sku', result.value.sku);
                        formData.append('name', result.value.name);
                        formData.append('description', result.value.description);
                        formData.append('stock', result.value.stock);
                        formData.append('image', result.value.image);

                        $.ajax({
                            url: '{{ route('ingredients.store') }}',
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
            });

            // Edit Ingredient
            $(document).on('click', '.editIngredientBtn', function() {
                const ingredientId = $(this).data('id');
                $.get(`{{ url('admin/ingredients') }}/${ingredientId}`, function(response) {
                    if (response.data) {
                        const ingredient = response.data;

                        Swal.fire({
                            title: 'Edit Ingredient',
                            html: `
                                <input type="text" id="sku" class="swal2-input" value="${ingredient.sku}" placeholder="SKU">
                                <input type="text" id="name" class="swal2-input" value="${ingredient.name}" placeholder="Name">
                                <input type="text" id="description" class="swal2-input" value="${ingredient.description}" placeholder="Description">
                                <input type="number" id="stock" class="swal2-input" value="${ingredient.stock}" placeholder="Stock">
                                <input type="file" id="image" class="swal2-file" placeholder="Image">
                            `,
                            confirmButtonText: 'Update',
                            focusConfirm: false,
                            preConfirm: () => {
                                const sku = Swal.getPopup().querySelector('#sku').value;
                                const name = Swal.getPopup().querySelector('#name').value;
                                const description = Swal.getPopup().querySelector('#description').value;
                                const stock = Swal.getPopup().querySelector('#stock').value;
                                const image = Swal.getPopup().querySelector('#image').files[0];
                                if (!sku || !name || !stock) {
                                    Swal.showValidationMessage(`Please fill all required fields`);
                                }
                                return { sku, name, description, stock, image };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('_method', 'PUT');
                                formData.append('sku', result.value.sku);
                                formData.append('name', result.value.name);
                                formData.append('description', result.value.description);
                                formData.append('stock', result.value.stock);
                                if (result.value.image) {
                                    formData.append('image', result.value.image);
                                }

                                $.ajax({
                                    url: `{{ url('admin/ingredients') }}/${ingredientId}`,
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
                        Swal.fire('Error', 'Failed to load ingredient details.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Failed to load ingredient details.', 'error');
                });
            });

            // Delete Ingredient
            $(document).on('click', '.deleteIngredientBtn', function() {
                const ingredientId = $(this).data('id');
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
                            url: `{{ url('admin/ingredients') }}/${ingredientId}`,
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
        });
    </script>
@endpush
