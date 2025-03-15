@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Accounts</h6>
                <button class="btn btn-primary btn-icon-split" id="addAccountBtn">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Account</span>
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="accountsTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>{{ $account->id }}</td>
                                <td>{{ $account->name }}</td>
                                <td>{{ $account->email }}</td>
                                <td>
                                    @if($account->role == 0)
                                        Retailer
                                    @elseif($account->role == 1)
                                        Admin
                                    @elseif($account->role == 2)
                                        Staff
                                    @endif
                                </td> <!-- Added Role data -->
                                <td>
                                    <button class="btn btn-primary btn-sm editAccountBtn" data-id="{{ $account->id }}">Edit</button>
                                    <button class="btn btn-danger btn-sm deleteAccountBtn" data-id="{{ $account->id }}">Delete</button>
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
            $('#accountsTable').DataTable();

            // Add Account
            $('#addAccountBtn').on('click', function() {
                Swal.fire({
                    title: 'Add Admin',
                    html: `
            <input type="text" id="name" class="swal2-input" placeholder="Name">
            <input type="email" id="email" class="swal2-input" placeholder="Email">
            <input type="password" id="password" class="swal2-input" placeholder="Password">
            <input type="password" id="password_confirmation" class="swal2-input" placeholder="Confirm Password">
            <select id="role" class="swal2-input">
                <option value="0">Retailer</option>
                <option value="1">Admin</option>
                <option value="2">Staff</option>
            </select>
        `,
                    confirmButtonText: 'Add',
                    focusConfirm: false,
                    preConfirm: () => {
                        const name = Swal.getPopup().querySelector('#name').value;
                        const email = Swal.getPopup().querySelector('#email').value;
                        const password = Swal.getPopup().querySelector('#password').value;
                        const password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                        const role = Swal.getPopup().querySelector('#role').value;
                        if (!name || !email || !password || !password_confirmation || !role) {
                            Swal.showValidationMessage(`Please fill all fields`);
                        }
                        return { name, email, password, password_confirmation, role };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('admin.accounts.store') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                name: result.value.name,
                                email: result.value.email,
                                password: result.value.password,
                                password_confirmation: result.value.password_confirmation,
                                role: result.value.role
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
            });

// Edit Account
            $(document).on('click', '.editAccountBtn', function() {
                const accountId = $(this).data('id');
                $.get(`{{ url('admin/accounts') }}/${accountId}`, function(response) {
                    if (response.data) {
                        const account = response.data;
                        Swal.fire({
                            title: 'Edit Admin',
                            html: `
                    <input type="text" id="name" class="swal2-input" value="${account.name}" placeholder="Name">
                    <input type="email" id="email" class="swal2-input" value="${account.email}" placeholder="Email">
                    <input type="password" id="password" class="swal2-input" placeholder="Password">
                    <input type="password" id="password_confirmation" class="swal2-input" placeholder="Confirm Password">
                    <select id="role" class="swal2-input">
                        <option value="0" ${account.role == 0 ? 'selected' : ''}>Retailer</option>
                        <option value="1" ${account.role == 1 ? 'selected' : ''}>Admin</option>
                        <option value="2" ${account.role == 2 ? 'selected' : ''}>Staff</option>
                    </select>
                `,
                            confirmButtonText: 'Update',
                            focusConfirm: false,
                            preConfirm: () => {
                                const name = Swal.getPopup().querySelector('#name').value;
                                const email = Swal.getPopup().querySelector('#email').value;
                                const password = Swal.getPopup().querySelector('#password').value;
                                const password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                                const role = Swal.getPopup().querySelector('#role').value;
                                if (!name || !email || !role) {
                                    Swal.showValidationMessage(`Please fill all required fields`);
                                }
                                return { name, email, password, password_confirmation, role };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `{{ url('admin/accounts') }}/${accountId}`,
                                    type: 'PUT',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        name: result.value.name,
                                        email: result.value.email,
                                        password: result.value.password,
                                        password_confirmation: result.value.password_confirmation,
                                        role: result.value.role
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
                        Swal.fire('Error', 'Failed to load account details.', 'error');
                    }
                }).fail(function() {
                    Swal.fire('Error', 'Failed to load account details.', 'error');
                });
            });

            // Delete Account
            $(document).on('click', '.deleteAccountBtn', function() {
                const accountId = $(this).data('id');
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
                            url: `{{ url('admin/accounts') }}/${accountId}`,
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
