<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Cafelina POS</title>

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* Same token system as the POS, order board, editor and
           dashboards, so the login page reads as the front door of
           the same product rather than a generic auth template. */
        :root {
            --paper: #F7EFE0;
            --paper-warm: #EAD9B7;
            --ink: #2E1D14;
            --ink-soft: #6B5647;
            --espresso: #40291B;
            --caramel: #C6863B;
            --caramel-deep: #A4692A;
            --caramel-tint: #F6E7C9;
            --moss: #3F6B4C;
            --moss-deep: #2E5038;
            --moss-tint: #E1EBE0;
            --stamp: #A8432E;
            --stamp-tint: #F5DCD5;
        }

        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
        }

        h1, h2, h3 { font-family: 'Fraunces', serif; }

        /* Split screen container */
        .login-container {
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(64, 41, 27, 0.12);
            max-width: 960px;
            width: 100%;
        }

        /* Left side: branding/photo */
        .coffee-image-side {
            background: linear-gradient(135deg, rgba(64, 41, 27, 0.8), rgba(64, 41, 27, 0.95)), 
                        url('{{ asset('Login Photo') }}');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem;
        }

        .brand-mark {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            background: var(--caramel);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-eyebrow {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--caramel-tint);
            opacity: 0.85;
            margin-bottom: 4px;
        }

        .sidebar-title {
            color: #ffffff;
            font-weight: 600;
            font-size: 2.1rem;
            line-height: 1.15;
        }

        .sidebar-tagline {
            color: var(--paper-warm);
            font-size: 1rem;
            font-weight: 400;
            margin-bottom: 0;
            max-width: 30ch;
        }

        /* Right side: form */
        .form-side {
            padding: 3.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-title {
            color: var(--ink);
            font-weight: 600;
            font-size: 1.75rem;
        }

        .form-label {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.6px;
            color: var(--ink-soft);
        }

        .form-control {
            border: 1.5px solid var(--paper-warm);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            background-color: #fff;
        }
        .form-control:focus {
            border-color: var(--caramel);
            box-shadow: 0 0 0 0.2rem rgba(198, 134, 59, 0.18);
        }

        .btn-primary {
            background-color: var(--espresso);
            border-color: var(--espresso);
            border-radius: 8px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--caramel-deep) !important;
            border-color: var(--caramel-deep) !important;
            box-shadow: 0 4px 10px rgba(164, 105, 42, 0.25);
        }

        .btn-outline-theme {
            color: var(--ink);
            border: 2px solid var(--paper-warm);
            background-color: transparent;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
        }
        .btn-outline-theme:hover {
            background-color: var(--caramel-tint);
            border-color: var(--caramel);
            color: var(--caramel-deep);
        }

        .text-primary-theme {
            color: var(--caramel-deep);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .text-primary-theme:hover {
            color: var(--espresso);
            text-decoration: underline;
        }

        .form-check-input:checked {
            background-color: var(--caramel);
            border-color: var(--caramel);
        }
        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(198, 134, 59, 0.18);
            border-color: var(--caramel);
        }

        .alert-success {
            background-color: var(--moss-tint);
            border: none;
            color: var(--moss-deep);
        }
        .alert-danger {
            background-color: var(--stamp-tint);
            border: none;
            color: var(--stamp);
        }

        @media (max-width: 767.98px) {
            .form-side { padding: 2.5rem 1.5rem; }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="row g-0">

            <!-- Left Side: Brand panel -->
            <div class="col-md-6 d-none d-md-flex coffee-image-side">
                <div class="brand-mark">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#40291B" class="w-5 h-5" width="22" height="22">
                        <path d="M2 3a1 1 0 0 0-1 1v2.5a4.5 4.5 0 0 0 4.5 4.5h.05a2.5 2.5 0 0 0 4.9 0H10.5A4.5 4.5 0 0 0 15 6.5V6a2 2 0 0 0-2-2h-.5V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1zm10.5 2H13a1 1 0 0 1 1 1v.5a3.5 3.5 0 0 1-1.5 2.87V5zM3 3h8v5.5A3.5 3.5 0 0 1 7.5 12h-2A3.5 3.5 0 0 1 2 8.5V3z"/>
                    </svg>
                </div>
                <div>
                    <p class="sidebar-eyebrow">Cafelina</p>
                    <h1 class="sidebar-title">Run the counter,<br>not the chaos.</h1>
                    <p class="sidebar-tagline mt-2">Orders, the kitchen board, and your menu — all in one workspace.</p>
                </div>
            </div>

            <!-- Right Side: Login form -->
            <div class="col-12 col-md-6 form-side bg-white">

                <div class="mb-4">
                    <h2 class="login-title">Welcome back</h2>
                    <p class="text-muted small">Sign in to get to your workspace.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success mb-4 rounded-3">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3">
                        <ul class="mb-0 ps-3 small">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label text-uppercase">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="name@example.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-uppercase">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>

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

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary text-white">
                            Log in
                        </button>

                        <div class="position-relative text-center my-2">
                            <hr class="text-muted">
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">or</span>
                        </div>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline-theme text-center text-decoration-none">
                                Register new user
                            </a>
                        @endif
                    </div>
                </form>

            </div>
        </div>
    </div>

</body>
</html>