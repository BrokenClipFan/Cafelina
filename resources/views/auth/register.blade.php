<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Cafelina POS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Your Custom Theme Variables */
        :root {
            --theme-bg: #FFEAC5;
            --theme-accent-light: #FFDBB5;
            --theme-primary: #6C4E31;
            --theme-dark: #603F26;
        }

        body {
            background-color: var(--theme-bg);
            color: var(--theme-dark);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
        }

        /* Split Screen Container Box */
        .login-container {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(96, 63, 38, 0.1);
            max-width: 960px;
            width: 100%;
        }

        /* Left Side Coffee Image Sidebar */
        .coffee-image-side {
            background-image: linear-gradient(rgba(96, 63, 38, 0.2), rgba(96, 63, 38, 0.75)), url('{{ asset('Login Photo') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem 2.5rem;
        }

        .sidebar-title {
            color: #FFEAC5;
            font-weight: 800;
            font-size: 2.25rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .sidebar-tagline {
            color: #FFDBB5;
            font-size: 1.05rem;
            font-weight: 400;
            margin-bottom: 0;
        }

        /* Right Side Form Panel */
        .form-side {
            padding: 3rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            color: var(--theme-dark);
            font-weight: 700;
            font-size: 1.75rem;
        }

        /* Form Inputs matching Cafelina POS panels */
        .form-control {
            border-color: #e9ecef;
            border-radius: 8px;
            padding: 0.70rem 1rem;
        }

        .form-control:focus {
            border-color: var(--theme-accent-light);
            box-shadow: 0 0 0 0.25rem rgba(108, 78, 49, 0.15);
        }

        /* Main Submit Button (Dark Brown Theme) */
        .btn-primary {
            background-color: var(--theme-dark);
            border-color: var(--theme-dark);
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--theme-primary) !important;
            border-color: var(--theme-primary) !important;
            box-shadow: 0 4px 8px rgba(108, 78, 49, 0.2);
        }

        /* Login Secondary Button (Outline Style) */
        .btn-outline-theme {
            color: var(--theme-dark);
            border: 2px solid var(--theme-accent-light);
            background-color: transparent;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-theme:hover {
            background-color: var(--theme-bg);
            border-color: var(--theme-accent-light);
            color: var(--theme-dark);
        }

        /* Mobile Adjustments */
        @media (max-width: 767.98px) {
            .form-side {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="row g-0">
            
            <div class="col-md-6 d-none d-md-flex coffee-image-side">
                <div>
                    <h1 class="sidebar-title">Cafelina POS</h1>
                    <p class="sidebar-tagline">Get started today and streamline your coffee operations.</p>
                </div>
            </div>

            <div class="col-12 col-md-6 form-side bg-white">
                
                <div class="mb-4">
                    <h2 class="login-title">Create Account</h2>
                    <p class="text-muted small">Sign up to get started with your workspace.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3">
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold text-muted small text-uppercase">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="John Doe">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-muted small text-uppercase">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="name@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-muted small text-uppercase">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="new-password" placeholder="••••••••">
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label fw-semibold text-muted small text-uppercase">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            Register
                        </button>
                        
                        <div class="position-relative text-center my-2">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                        </div>

                        <a href="{{ route('login') }}" class="btn btn-outline-theme text-center text-decoration-none">
                            Already registered? Log In
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>