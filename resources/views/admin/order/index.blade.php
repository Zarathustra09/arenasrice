@extends('layouts.app')

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Orders List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="ordersTable" class="table table-striped table-bordered" width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User</th>
                        <th>Item Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.order.data') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user', name: 'user' },
                    { data: 'orderItems', name: 'orderItems', render: function(data) {
                            return data.map(item => item.product.name).join('<br>');
                        }},
                    { data: 'orderItems', name: 'orderItems', render: function(data) {
                            return data.map(item => `₱${item.price}`).join('<br>');
                        }},
                    { data: 'orderItems', name: 'orderItems', render: function(data) {
                            return data.map(item => item.quantity).join('<br>');
                        }},
                    { data: 'orderItems', name: 'orderItems', render: function(data) {
                            let total = data.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                            return `₱${total}`;
                        }},
                    { data: 'status', name: 'status' },
                    { data: 'id', name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                            let buttons = '';
                            if (row.status === 'canceled') {
                                buttons += `<button class="btn btn-secondary btn-sm" disabled>Cancelled</button>`;
                            } else {
                                buttons += `<a href="#" class="btn btn-primary btn-sm editOrderBtn" data-id="${data}">Edit</a>`;
                            }
                            buttons += `<a href="{{ url('admin/orders') }}/${data}/download" class="btn btn-success btn-sm ms-2">Print</a>`;
                            return buttons;
                        }},
                ],
                order: [[0, 'desc']],
                search: {
                    regex: true
                }
            });

            // Edit Order Status
            $(document).on('click', '.editOrderBtn', function() {
                const orderId = $(this).data('id');
                Swal.fire({
                    title: 'Edit Order Status',
                    input: 'select',
                    inputOptions: {
                        'pending': 'Pending',
                        'processing': 'Processing',
                        'shipped': 'Shipped',
                        'delivered': 'Delivered',
                        'canceled': 'Canceled'
                    },
                    inputPlaceholder: 'Select a status',
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to select a status!';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('admin/orders') }}/${orderId}`,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: result.value
                            },
                            success: function(response) {
                                Swal.fire('Success', response.message, 'success');
                                $('#ordersTable').DataTable().ajax.reload();
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
