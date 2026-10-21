<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="30">
    <title>Cafelinea — Account Pending</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --paper: #F7EFE0;
            --paper-warm: #EAD9B7;
            --ink: #2E1D14;
            --ink-soft: #4A3B32;
            --espresso: #1A0F0A;
            --caramel: #C87941;
            --caramel-tint: #F3E5D8;
            --danger: #8C2A2A;
        }
        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        h1 { font-family: 'Space Grotesk', sans-serif; }
        .pending-card {
            background: #fff;
            border: 1px solid var(--paper-warm);
            border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,.08);
            max-width: 480px;
            width: 100%;
        }
        .icon-circle {
            width: 80px; height: 80px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto;
        }
        .btn-theme-outline {
            color: var(--espresso);
            border: 1px solid var(--espresso);
            border-radius: 8px;
        }
        .btn-theme-outline:hover { background: var(--espresso); color: #fff; }
    </style>
</head>
<body>
    <div class="pending-card p-5 text-center mx-3">
        @if($user->status === \App\Models\User::STATUS_REJECTED)
            <div class="icon-circle mb-4" style="background:#f8d7da; color: var(--danger);">
                <i class="fa-solid fa-user-xmark fs-2"></i>
            </div>
            <h1 class="fs-3 fw-bold mb-2">Account Not Approved</h1>
            <p class="mb-4" style="color: var(--ink-soft);">
                Sorry <strong>{{ $user->name }}</strong>, your registration was declined by the admin.
                Please contact the cafe manager if you think this is a mistake.
            </p>
        @else
            <div class="icon-circle mb-4" style="background: var(--caramel-tint); color: var(--caramel);">
                <i class="fa-solid fa-hourglass-half fs-2"></i>
            </div>
            <h1 class="fs-3 fw-bold mb-2">Waiting for Approval</h1>
            <p class="mb-1" style="color: var(--ink-soft);">
                Thanks for registering, <strong>{{ $user->name }}</strong>!
            </p>
            <p class="mb-4" style="color: var(--ink-soft);">
                An admin needs to verify your account and assign your role
                (Cashier, Stock Clerk, or Admin) before you can continue.
            </p>
            <a href="{{ route('account.pending') }}" class="btn btn-theme-outline w-100 mb-2">
                <i class="fa-solid fa-rotate me-2"></i> Check Again
            </a>
            <p class="small text-muted mb-3">This page refreshes automatically every 30 seconds.</p>
        @endif

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link text-decoration-none" style="color: var(--ink-soft);">
                <i class="fa-solid fa-right-from-bracket me-1"></i> Log out
            </button>
        </form>
    </div>
</body>
</html>
