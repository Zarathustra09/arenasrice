@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_name" class="col-md-4 col-form-label text-md-end">{{ __('Billing Name') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_name" type="text" class="form-control @error('billing_name') is-invalid @enderror" name="billing_name" value="{{ old('billing_name') }}" autocomplete="billing_name">

                                    @error('billing_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_address" class="col-md-4 col-form-label text-md-end">{{ __('Billing Address') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_address" type="text" class="form-control @error('billing_address') is-invalid @enderror" name="billing_address" value="{{ old('billing_address') }}" autocomplete="billing_address">

                                    @error('billing_address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_city" class="col-md-4 col-form-label text-md-end">{{ __('Billing City') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_city" type="text" class="form-control @error('billing_city') is-invalid @enderror" name="billing_city" value="{{ old('billing_city') }}" autocomplete="billing_city">

                                    @error('billing_city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_state" class="col-md-4 col-form-label text-md-end">{{ __('Billing State') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_state" type="text" class="form-control @error('billing_state') is-invalid @enderror" name="billing_state" value="{{ old('billing_state') }}" autocomplete="billing_state">

                                    @error('billing_state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_zip" class="col-md-4 col-form-label text-md-end">{{ __('Billing Zip') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_zip" type="text" class="form-control @error('billing_zip') is-invalid @enderror" name="billing_zip" value="{{ old('billing_zip') }}" autocomplete="billing_zip">

                                    @error('billing_zip')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_phone" class="col-md-4 col-form-label text-md-end">{{ __('Billing Phone') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_phone" type="text" class="form-control @error('billing_phone') is-invalid @enderror" name="billing_phone" value="{{ old('billing_phone') }}" autocomplete="billing_phone">

                                    @error('billing_phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="billing_email" class="col-md-4 col-form-label text-md-end">{{ __('Billing Email') }}</label>

                                <div class="col-md-6">
                                    <input id="billing_email" type="email" class="form-control @error('billing_email') is-invalid @enderror" name="billing_email" value="{{ old('billing_email') }}" autocomplete="billing_email">

                                    @error('billing_email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
