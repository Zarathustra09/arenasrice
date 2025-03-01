@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Product Containers</h6>
                <button class="btn btn-primary btn-icon-split" id="addContainerBtn">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Container</span>
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="containerTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Low Stock Threshold</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($containers as $container)
                            <tr>
                                <td>{{ $container->id }}</td>
                                <td>{{ $container->name }}</td>
                                <td>{{ $container->quantity }}</td>
                                <td>{{ $container->low_stock_threshold }}</td>
                                <td>
                                    @if ($container->quantity == 0)
                                        <span class="badge bg-danger">No Stock</span>
                                    @elseif ($container->quantity < $container->low_stock_threshold)
                                        <span class="badge bg-warning text-dark">Low Stock</span>
                                    @else
                                        <span class="badge bg-success">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm editContainerBtn" data-id="{{ $container->id }}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteContainerBtn" data-id="{{ $container->id }}">Delete</button>
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
            $('#containerTable').DataTable();

            // Add Container
            $('#addContainerBtn').on('click', function() {
                Swal.fire({
                    title: 'Add Container',
                    html: `
                        <input type="text" id="name" class="swal2-input" placeholder="Name">
                        <input type="number" id="quantity" class="swal2-input" placeholder="Quantity">
                        <input type="number" id="low_stock_threshold" class="swal2-input" placeholder="Low Stock Threshold">
                    `,
                    confirmButtonText: 'Add',
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = Swal.getPopup().querySelector('#name').value;
                        const quantity = Swal.getPopup().querySelector('#quantity').value;
                        const low_stock_threshold = Swal.getPopup().querySelector('#low_stock_threshold').value;
                        if (!name || !quantity || !low_stock_threshold) {
                            Swal.showValidationMessage(`Please fill all fields`);
                        }
                        return { name, quantity, low_stock_threshold };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('product-containers.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: result.value.name,
                                quantity: result.value.quantity,
                                low_stock_threshold: result.value.low_stock_threshold
                            },
                            success: function(response) {
                                Swal.fire('Success', response.message, 'success').then(() => {
                                    setTimeout(() => {
                                        location.reload();
                                    }, 2000); // 2000 milliseconds = 2 seconds
                                });
                            },
                            error: function(response) {
                                Swal.fire('Error', response.responseJSON.message, 'error');
                            }
                        });
                    }
                });
            });

            // Edit Container
            $(document).on('click', '.editContainerBtn', function() {
                const containerId = $(this).data('id');
                $.get(`{{ url('product-containers') }}/${containerId}`, function(response) {
                    if (response) {
                        const container = response;
                        Swal.fire({
                            title: 'Edit Container',
                            html: `
                                <input type="text" id="name" class="swal2-input" value="${container.name}" placeholder="Name">
                                <input type="number" id="quantity" class="swal2-input" value="${container.quantity}" placeholder="Quantity">
                                <input type="number" id="low_stock_threshold" class="swal2-input" value="${container.low_stock_threshold}" placeholder="Low Stock Threshold">
                            `,
                            confirmButtonText: 'Update',
                            focusConfirm: false,
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#name').value;
                                const quantity = Swal.getPopup().querySelector('#quantity').value;
                                const low_stock_threshold = Swal.getPopup().querySelector('#low_stock_threshold').value;
                                if (!name || !quantity || !low_stock_threshold) {
                                    Swal.showValidationMessage(`Please fill all fields`);
                                }
                                return { name, quantity, low_stock_threshold };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `{{ url('product-containers') }}/${containerId}`,
                                    type: 'PUT',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        name: result.value.name,
                                        quantity: result.value.quantity,
                                        low_stock_threshold: result.value.low_stock_threshold
                                    },
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
                    } else {
                        Swal.fire('Error', 'Failed to load container details.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Failed to load container details.', 'error');
                });
            });

            // Delete Container
            $(document).on('click', '.deleteContainerBtn', function() {
                const containerId = $(this).data('id');
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
                            url: `{{ url('product-containers') }}/${containerId}`,
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
