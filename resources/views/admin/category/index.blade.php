@extends('layouts.app')

@section('content')
    <!-- Categories Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Category List</h6>
            <a href="#" class="btn btn-primary btn-icon-split" id="addCategoryBtn">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Add Category</span>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('categories.data') }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                {
                    data: 'id',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                    <a href="#" class="btn btn-primary btn-sm editCategoryBtn" data-id="${data}">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm deleteCategoryBtn" data-id="${data}">Delete</a>
                `;
                    }
                }
            ]
        });

            // Add Category
            $('#addCategoryBtn').on('click', function() {
                Swal.fire({
                    title: 'Add Category',
                    html: `
                        <input type="text" id="name" class="swal2-input" placeholder="Name">
                        <input type="text" id="description" class="swal2-input" placeholder="Description">
                    `,
                    confirmButtonText: 'Add',
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = Swal.getPopup().querySelector('#name').value
                        const description = Swal.getPopup().querySelector('#description').value
                        if (!name) {
                            Swal.showValidationMessage(`Please enter the name`)
                        }
                        return { name: name, description: description }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('categories.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: result.value.name,
                                description: result.value.description
                            },
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

            // Edit Category
            $(document).on('click', '.editCategoryBtn', function() {
                const id = $(this).data('id');
                $.get(`{{ url('admin/categories') }}/${id}/edit`, function(data) {
                    Swal.fire({
                        title: 'Edit Category',
                        html: `
                            <input type="text" id="name" class="swal2-input" value="${data.data.name}" placeholder="Name">
                            <input type="text" id="description" class="swal2-input" value="${data.data.description}" placeholder="Description">
                        `,
                        confirmButtonText: 'Update',
                        focusConfirm: false,
                        preConfirm: () => {
                            const name = Swal.getPopup().querySelector('#name').value
                            const description = Swal.getPopup().querySelector('#description').value
                            if (!name) {
                                Swal.showValidationMessage(`Please enter the name`)
                            }
                            return { name: name, description: description }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `{{ url('admin/categories') }}/${id}`,
                                type: 'PUT',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    name: result.value.name,
                                    description: result.value.description
                                },
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
            });

            // Delete Category
            $(document).on('click', '.deleteCategoryBtn', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('admin/categories') }}/${id}`,
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
