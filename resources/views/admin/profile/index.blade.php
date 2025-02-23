@extends('layouts.app')

@section('content')
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-muted mb-2">Account</h6>
                                        @if($user->role == 1)
                                            <span class="badge bg-primary rounded-pill">Admin</span>
                                        @else
                                            <span class="badge bg-success rounded-pill">User</span>

                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light border-0 rounded-3">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold text-muted mb-2">Last Login</h6>
                                        <p class="mb-0">{{ now()->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display: none;">
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
                    const profilepicture = Swal.getPopup().querySelector('#profilepicture').files[0];
                    if (!name || !email) {
                        Swal.showValidationMessage(`Please enter name and email`);
                    }
                    return { name: name, email: email, profilepicture: profilepicture };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('name', result.value.name);
                    formData.append('email', result.value.email);
                    if (result.value.profilepicture) {
                        formData.append('profilepicture', result.value.profilepicture);
                    }

                    fetch('{{ route('profile.update') }}', {
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
