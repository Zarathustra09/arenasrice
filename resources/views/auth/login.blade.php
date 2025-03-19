<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        :root {
            --primary: #9B734F;
            --primary-hover: #7d5c3f;
            --secondary: #e6d5c3;
            --light: #f8f3ed;
            --dark: #3a2c20;
            --background: #f5f0ea;
            --card-bg: #ffffff;
            --text: #3a2c20;
            --text-muted: #7d6a5a;
            --border: #e1d5c8;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .container {
            padding: 2rem 1rem;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(155, 115, 79, 0.15);
            overflow: hidden;
        }

        .card-header {
            background: var(--primary);
            color: white;
            border-bottom: none;
            padding: 1.5rem;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 2.5rem;
            background-color: var(--card-bg);
        }

        .form-control {
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            height: auto;
            background-color: var(--light);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(155, 115, 79, 0.25);
        }

        .col-form-label {
            font-weight: 500;
            color: var(--text);
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
        }

        .btn-link {
            color: var(--primary);
            text-decoration: none;
            padding: 0.75rem 1rem;
        }

        .btn-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 0.875rem;
        }

        .input-group-text {
            background-color: var(--light);
            border: 1px solid var(--border);
            border-right: none;
            cursor: pointer;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <div class="d-flex justify-content-center align-items-center mb-2">
                            <i class="fas fa-user-circle fa-3x text-white"></i>
                        </div>
                        {{ __('Login') }}
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
