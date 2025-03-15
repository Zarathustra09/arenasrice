@extends('layouts.guest-app')

@section('content')
    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6">Profile</h1>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active text-white">Profile</li>
        </ol>
    </div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center p-4">
                        @if($user->profilepicture)
                            <img src="{{ asset('storage/' . $user->profilepicture) }}"
                                 alt="Profile"
                                 class="rounded-circle img-fluid mb-4 shadow-sm border border-3 border-light"
                                 style="width: 180px; height: 180px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-gradient d-flex align-items-center justify-content-center mx-auto mb-4 shadow-sm"
                                 style="width: 180px; height: 180px; background: linear-gradient(45deg, #3498db, #2ecc71);">
                                <span class="text-white fw-bold" style="font-size: 64px;">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                        <button class="btn btn-primary px-4 rounded-pill" id="editProfileBtn">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-primary bg-gradient text-white py-3">
                        <h4 class="mb-0 fw-bold">Profile Information</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Full Name</h6>
                                <p class="fs-5">{{ $user->name }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Email Address</h6>
                                <p class="fs-5">{{ $user->email }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Member Since</h6>
                                <p class="fs-5">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing Name</h6>
                                <p class="fs-5">{{ $user->billing_name }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing Address</h6>
                                <p class="fs-5">{{ $user->billing_address }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing City</h6>
                                <p class="fs-5">{{ $user->billing_city }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing State</h6>
                                <p class="fs-5">{{ $user->billing_state }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing Zip</h6>
                                <p class="fs-5">{{ $user->billing_zip }}</p>
                            </div>
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing Phone</h6>
                                <p class="fs-5">{{ $user->billing_phone }}</p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <h6 class="fw-bold text-muted mb-2">Billing Email</h6>
                                <p class="fs-5">{{ $user->billing_email }}</p>
                            </div>
                        </div>

                        <form id="profileUpdateForm" method="POST" action="{{ route('guest.profile.update') }}" enctype="multipart/form-data" style="display: none;">
                            @csrf
                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label">Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label">Email</label>
                                <div class="col-md-8">
                                    <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label">Password</label>
                                <div class="col-md-8">
                                    <input type="password" id="password" name="password" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-md-4 col-form-label">Confirm Password</label>
                                <div class="col-md-8">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="profilepicture" class="col-md-4 col-form-label">Profile Picture</label>
                                <div class="col-md-8">
                                    <input type="file" id="profilepicture" name="profilepicture" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_name" class="col-md-4 col-form-label">Billing Name</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_name" name="billing_name" class="form-control" value="{{ $user->billing_name }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_address" class="col-md-4 col-form-label">Billing Address</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_address" name="billing_address" class="form-control" value="{{ $user->billing_address }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_city" class="col-md-4 col-form-label">Billing City</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_city" name="billing_city" class="form-control" value="{{ $user->billing_city }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_state" class="col-md-4 col-form-label">Billing State</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_state" name="billing_state" class="form-control" value="{{ $user->billing_state }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_zip" class="col-md-4 col-form-label">Billing Zip</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_zip" name="billing_zip" class="form-control" value="{{ $user->billing_zip }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_phone" class="col-md-4 col-form-label">Billing Phone</label>
                                <div class="col-md-8">
                                    <input type="text" id="billing_phone" name="billing_phone" class="form-control" value="{{ $user->billing_phone }}">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_email" class="col-md-4 col-form-label">Billing Email</label>
                                <div class="col-md-8">
                                    <input type="email" id="billing_email" name="billing_email" class="form-control" value="{{ $user->billing_email }}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editProfileBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Edit Profile',
                html: document.getElementById('profileUpdateForm').innerHTML,
                showCancelButton: true,
                confirmButtonText: 'Update',
                preConfirm: () => {
                    const name = Swal.getPopup().querySelector('#name').value;
                    const email = Swal.getPopup().querySelector('#email').value;
                    const password = Swal.getPopup().querySelector('#password').value;
                    const password_confirmation = Swal.getPopup().querySelector('#password_confirmation').value;
                    const profilepicture = Swal.getPopup().querySelector('#profilepicture').files[0];
                    const billing_name = Swal.getPopup().querySelector('#billing_name').value;
                    const billing_address = Swal.getPopup().querySelector('#billing_address').value;
                    const billing_city = Swal.getPopup().querySelector('#billing_city').value;
                    const billing_state = Swal.getPopup().querySelector('#billing_state').value;
                    const billing_zip = Swal.getPopup().querySelector('#billing_zip').value;
                    const billing_phone = Swal.getPopup().querySelector('#billing_phone').value;
                    const billing_email = Swal.getPopup().querySelector('#billing_email').value;

                    if (!name || !email) {
                        Swal.showValidationMessage(`Please enter name and email`);
                    }

                    if (password && password !== password_confirmation) {
                        Swal.showValidationMessage(`Passwords do not match`);
                    }

                    return {
                        name: name,
                        email: email,
                        password: password,
                        profilepicture: profilepicture,
                        billing_name: billing_name,
                        billing_address: billing_address,
                        billing_city: billing_city,
                        billing_state: billing_state,
                        billing_zip: billing_zip,
                        billing_phone: billing_phone,
                        billing_email: billing_email
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('name', result.value.name);
                    formData.append('email', result.value.email);
                    if (result.value.password) {
                        formData.append('password', result.value.password);
                        formData.append('password_confirmation', result.value.password);
                    }
                    if (result.value.profilepicture) {
                        formData.append('profilepicture', result.value.profilepicture);
                    }
                    formData.append('billing_name', result.value.billing_name);
                    formData.append('billing_address', result.value.billing_address);
                    formData.append('billing_city', result.value.billing_city);
                    formData.append('billing_state', result.value.billing_state);
                    formData.append('billing_zip', result.value.billing_zip);
                    formData.append('billing_phone', result.value.billing_phone);
                    formData.append('billing_email', result.value.billing_email);

                    fetch('{{ route('guest.profile.update') }}', {
                        method: 'POST',
                        body: formData
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire('Success', 'Profile updated successfully', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'There was an error updating your profile', 'error');
                        }
                    }).catch(error => {
                        Swal.fire('Error', 'There was an error updating your profile', 'error');
                    });
                }
            });
        });
    </script>
@endsection
