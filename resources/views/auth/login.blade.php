<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Cafelina POS</title>

    <!-- Bootstrap 5 CSS CDN -->
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
            /* High-quality, warm cafe/coffee image via Unsplash */
            background-image: linear-gradient(rgba(96, 63, 38, 0.2), rgba(96, 63, 38, 0.75)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&q=80&w=800');
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
            padding: 3.5rem 3rem;
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
            padding: 0.75rem 1rem;
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

        /* Register Secondary Button (Outline Style) */
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

        /* Link adjustments */
        .text-primary-theme {
            color: var(--theme-primary);
            text-decoration: none;
            font-size: 0.875rem;
        }

        .text-primary-theme:hover {
            color: var(--theme-dark);
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: var(--theme-primary);
            border-color: var(--theme-primary);
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
            
            <!-- Left Side: Coffee Branding Image (Hidden on small mobile screens) -->
            <div class="col-md-6 d-none d-md-flex coffee-image-side">
                <div>
                    <h1 class="sidebar-title">Cafelina POS</h1>
                    <p class="sidebar-tagline">Manage your coffee operations smoothly and efficiently.</p>
                </div>
            </div>

            <!-- Right Side: Login Form Layout -->
            <div class="col-12 col-md-6 form-side bg-white">
                
                <div class="mb-4">
                    <h2 class="login-title">Welcome Back</h2>
                    <p class="text-muted small">Please enter your details to sign into your workspace.</p>
                </div>

                <!-- Session Status Alerts -->
                @if (session('status'))
                    <div class="alert alert-success mb-4 rounded-3" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors Alerts -->
                @if ($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3">
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Authentication Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold text-muted small text-uppercase">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold text-muted small text-uppercase">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

                    <!-- Remember Me checkbox & Forgot Password block -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                            <label class="form-check-label text-muted small" for="remember_me">
                                Remember me
                            </label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a class="text-primary-theme" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Actions Panel buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            Log In
                        </button>
                        
                        <!-- Visual separation line -->
                        <div class="position-relative text-center my-2">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                        </div>

                        <!-- Register alternative button action requested -->
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-theme text-center text-decoration-none">
                                Register New User
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>