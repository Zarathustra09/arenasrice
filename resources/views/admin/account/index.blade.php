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
                            <th>Billing Name</th>
                            <th>Billing Address</th>
                            <th>Billing City</th>
                            <th>Billing State</th>
                            <th>Billing Zip</th>
                            <th>Billing Phone</th>
                            <th>Billing Email</th>
                            <th>Profile Picture</th>
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
                                </td>
                                <td>{{ $account->billing_name }}</td>
                                <td>{{ $account->billing_address }}</td>
                                <td>{{ $account->billing_city }}</td>
                                <td>{{ $account->billing_state }}</td>
                                <td>{{ $account->billing_zip }}</td>
                                <td>{{ $account->billing_phone }}</td>
                                <td>{{ $account->billing_email }}</td>
                                <td>
                                    @if($account->profilepicture)
                                        <img src="{{ asset('storage/' . $account->profilepicture) }}" alt="Profile Picture" style="width: 50px; height: 50px;">
                                    @else
                                        N/A
                                    @endif
                                </td>
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

    <script>
        $(document).ready(function() {
            $('#accountsTable').DataTable();

            // Add Account
            $('#addAccountBtn').on('click', function() {
                Swal.fire({
                    title: '<h4 class="text-primary mb-3">Add Admin Account</h4>',
                    width: '800px',
                    html: `
            <div class="container-fluid p-0">
                <div class="row g-3">
                    <!-- Personal Information -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="name" class="form-control" placeholder="Full Name">
                                            <label for="name">Full Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" id="email" class="form-control" placeholder="Email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" id="password" class="form-control" placeholder="Password">
                                            <label for="password">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password">
                                            <label for="password_confirmation">Confirm Password</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Billing Information -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Billing Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="billing_name" class="form-control" placeholder="Billing Name">
                                            <label for="billing_name">Billing Name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="email" id="billing_email" class="form-control" placeholder="Billing Email">
                                            <label for="billing_email">Billing Email</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating">
                                            <input type="text" id="billing_address" class="form-control" placeholder="Billing Address">
                                            <label for="billing_address">Billing Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="billing_city" class="form-control" placeholder="City">
                                            <label for="billing_city">City</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="billing_state" class="form-control" placeholder="State">
                                            <label for="billing_state">State</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-floating">
                                            <input type="text" id="billing_zip" class="form-control" placeholder="Zip Code">
                                            <label for="billing_zip">Zip Code</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input type="text" id="billing_phone" class="form-control" placeholder="Phone">
                                            <label for="billing_phone">Phone</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="profilepicture" class="form-label">Profile Picture</label>
                                        <input type="file" id="profilepicture" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="role" class="form-label">Role</label>
                                        <select id="role" class="form-select">
                                            <option value="0">Retailer</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Staff</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `,
                    confirmButtonText: '<i class="fas fa-plus-circle me-2"></i>Add Account',
                    confirmButtonColor: '#3085d6',
                    cancelButtonText: 'Cancel',
                    cancelButtonColor: '#6c757d',
                    showCancelButton: true,
                    focusConfirm: false,
                    customClass: {
                        container: 'swal-wide',
                        title: 'text-center'
                    },
                    preConfirm: () => {
                        const formFields = [
                            'name', 'email', 'password', 'password_confirmation',
                            'billing_name', 'billing_address', 'billing_city', 'billing_state',
                            'billing_zip', 'billing_phone', 'billing_email', 'role'
                        ];
                        const values = {};
                        let isValid = true;

                        formFields.forEach(field => {
                            values[field] = Swal.getPopup().querySelector(`#${field}`).value;
                            if (!values[field]) isValid = false;
                        });

                        values.profilepicture = Swal.getPopup().querySelector('#profilepicture').files[0];

                        if (!isValid) {
                            Swal.showValidationMessage(`Please fill in all required fields.`);
                            return false;
                        }

                        return values;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        Object.keys(result.value).forEach(key => {
                            formData.append(key, result.value[key]);
                        });

                        $.ajax({
                            url: '{{ route('admin.accounts.store') }}',
                            type: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                                setTimeout(() => location.reload(), 2000);
                            },
                            error: function(response) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: response.responseJSON.message
                                });
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
                            title: '<h4 class="text-primary mb-3">Edit Admin Account</h4>',
                            width: '800px',
                            html: `
                    <div class="container-fluid p-0">
                        <div class="row g-3">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Personal Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" id="name" class="form-control" placeholder="Full Name" value="${account.name}">
                                                    <label for="name">Full Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="email" id="email" class="form-control" placeholder="Email" value="${account.email}">
                                                    <label for="email">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="password" id="password" class="form-control" placeholder="Password">
                                                    <label for="password">Password (leave blank to keep current)</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Billing Information -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Billing Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_name" class="form-control" placeholder="Billing Name" value="${account.billing_name}">
                                                    <label for="billing_name">Billing Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="email" id="billing_email" class="form-control" placeholder="Billing Email" value="${account.billing_email}">
                                                    <label for="billing_email">Billing Email</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_address" class="form-control" placeholder="Billing Address" value="${account.billing_address}">
                                                    <label for="billing_address">Billing Address</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_city" class="form-control" placeholder="City" value="${account.billing_city}">
                                                    <label for="billing_city">City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_state" class="form-control" placeholder="State" value="${account.billing_state}">
                                                    <label for="billing_state">State</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_zip" class="form-control" placeholder="Zip Code" value="${account.billing_zip}">
                                                    <label for="billing_zip">Zip Code</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="text" id="billing_phone" class="form-control" placeholder="Phone" value="${account.billing_phone}">
                                                    <label for="billing_phone">Phone</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-cog me-2"></i>Additional Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="profilepicture" class="form-label">Profile Picture</label>
                                                <input type="file" id="profilepicture" class="form-control">
                                                ${account.profile_picture ? `<div class="mt-2"><img src="${account.profile_picture}" class="img-thumbnail" width="100"></div>` : ''}
                                            </div>
                                            <div class="col-md-6">
                                                <label for="role" class="form-label">Role</label>
                                                <select id="role" class="form-select">
                                                    <option value="0" ${account.role == 0 ? 'selected' : ''}>Retailer</option>
                                                    <option value="1" ${account.role == 1 ? 'selected' : ''}>Admin</option>
                                                    <option value="2" ${account.role == 2 ? 'selected' : ''}>Staff</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `,
                            confirmButtonText: '<i class="fas fa-save me-2"></i>Update Account',
                            confirmButtonColor: '#3085d6',
                            cancelButtonText: 'Cancel',
                            cancelButtonColor: '#6c757d',
                            showCancelButton: true,
                            focusConfirm: false,
                            customClass: {
                                container: 'swal-wide',
                                title: 'text-center'
                            },
                            preConfirm: () => {
                                const formFields = [
                                    'name', 'email', 'billing_name', 'billing_address',
                                    'billing_city', 'billing_state', 'billing_zip',
                                    'billing_phone', 'billing_email', 'role'
                                ];
                                const values = {};
                                let isValid = true;

                                formFields.forEach(field => {
                                    values[field] = Swal.getPopup().querySelector(`#${field}`).value;
                                    if (!values[field]) isValid = false;
                                });

                                values.password = Swal.getPopup().querySelector('#password').value;
                                values.password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                                values.profilepicture = Swal.getPopup().querySelector('#profilepicture').files[0];

                                if (!isValid) {
                                    Swal.showValidationMessage(`Please fill all required fields`);
                                    return false;
                                }

                                return values;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const formData = new FormData();
                                formData.append('_token', '{{ csrf_token() }}');
                                formData.append('_method', 'PUT');

                                Object.keys(result.value).forEach(key => {
                                    if (key === 'password' && !result.value[key]) {
                                        return; // Skip empty password
                                    }
                                    formData.append(key, result.value[key]);
                                });

                                $.ajax({
                                    url: `{{ url('admin/accounts') }}/${accountId}`,
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    beforeSend: function() {
                                        Swal.fire({
                                            title: 'Updating...',
                                            text: 'Please wait while we update the account.',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false,
                                            showConfirmButton: false,
                                            willOpen: () => {
                                                Swal.showLoading();
                                            }
                                        });
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Success!',
                                            text: response.message,
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                        setTimeout(() => location.reload(), 2000);
                                    },
                                    error: function(response) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Error',
                                            text: response.responseJSON.message || 'An error occurred while updating the account.'
                                        });
                                    }
                                });
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load account details.'
                        });
                    }
                }).fail(function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load account details.'
                    });
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
