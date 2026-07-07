<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Cafelina') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Cafelina token system, shared with every other screen in the app. */
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
        }

        .cf-display { font-family: 'Fraunces', serif; }
        .cf-mono { font-family: 'Space Grotesk', sans-serif; }

        .cf-navbar { background-color: var(--espresso); }

        .cf-brand-mark {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: var(--caramel);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .cf-nav-link {
            color: var(--paper-warm);
            font-weight: 500;
            font-size: 0.92rem;
            padding: 0.4rem 0.1rem;
            border-bottom: 2px solid transparent;
            transition: color 0.15s ease, border-color 0.15s ease;
        }
        .cf-nav-link:hover { color: #ffffff; }
        .cf-nav-link.cf-nav-link--active {
            color: #ffffff;
            border-bottom-color: var(--caramel);
        }

        .cf-user-btn {
            color: var(--paper-warm);
            background: transparent;
            border: 1.5px solid rgba(234, 217, 183, 0.35);
            border-radius: 999px;
            padding: 0.35rem 0.9rem;
            font-size: 0.88rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .cf-user-btn:hover { border-color: var(--caramel); color: #fff; }

        .cf-dropdown {
            display: none;
            position: absolute;
            right: 0;
            margin-top: 0.5rem;
            min-width: 12rem;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 24px rgba(64, 41, 27, 0.18);
            overflow: hidden;
            z-index: 40;
        }
        .cf-dropdown.cf-dropdown--open { display: block; }
        .cf-dropdown a, .cf-dropdown button {
            display: block;
            width: 100%;
            text-align: left;
            padding: 0.65rem 1rem;
            font-size: 0.88rem;
            color: var(--ink);
            background: none;
            border: none;
        }
        .cf-dropdown a:hover, .cf-dropdown button:hover { background-color: var(--caramel-tint); color: var(--caramel-deep); }

        .cf-mobile-panel { display: none; }
        .cf-mobile-panel.cf-mobile-panel--open { display: block; }
        .cf-mobile-link {
            display: block;
            padding: 0.6rem 1rem;
            color: var(--paper-warm);
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        .cf-mobile-link:hover { background-color: rgba(255,255,255,0.06); color: #fff; }
        .cf-mobile-link.cf-nav-link--active {
            border-left-color: var(--caramel);
            color: #fff;
            background-color: rgba(255,255,255,0.06);
        }

        .cf-burger {
            color: var(--paper-warm);
            background: none;
            border: none;
            padding: 0.4rem;
        }
        .cf-burger:hover { color: #fff; }
    </style>
</head>
<body class="font-sans antialiased" style="background-color: var(--paper);">
    <div class="min-h-screen">

        <nav class="cf-navbar shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">

                    <div class="flex">
                        <!-- Brand -->
                        <div class="shrink-0 flex items-center gap-3">
                            <a href="{{ Route::has('dashboard') ? route('dashboard') : url('/') }}" class="flex items-center gap-2">
                                <span class="cf-brand-mark">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#40291B" width="18" height="18">
                                        <path d="M2 3a1 1 0 0 0-1 1v2.5a4.5 4.5 0 0 0 4.5 4.5h.05a2.5 2.5 0 0 0 4.9 0H10.5A4.5 4.5 0 0 0 15 6.5V6a2 2 0 0 0-2-2h-.5V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1zm10.5 2H13a1 1 0 0 1 1 1v.5a3.5 3.5 0 0 1-1.5 2.87V5zM3 3h8v5.5A3.5 3.5 0 0 1 7.5 12h-2A3.5 3.5 0 0 1 2 8.5V3z"/>
                                    </svg>
                                </span>
                                <span class="cf-display text-white font-semibold text-lg">Cafelina</span>
                            </a>
                        </div>

                        <!-- Primary nav -->
                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                            <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}"
                               class="cf-nav-link {{ request()->routeIs('dashboard') ? 'cf-nav-link--active' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ Route::has('home') ? route('home') : '#' }}"
                               class="cf-nav-link {{ request()->routeIs('home') ? 'cf-nav-link--active' : '' }}">
                                Point of Sale
                            </a>
                            <a href="{{ Route::has('queue.display') ? route('queue.display') : '/queue' }}"
                               class="cf-nav-link {{ request()->routeIs('queue.display') ? 'cf-nav-link--active' : '' }}">
                                Order Board
                            </a>
                            <a href="{{ Route::has('menu.edit') ? route('menu.edit') : '/menu/edit' }}"
                               class="cf-nav-link {{ request()->routeIs('menu.edit') ? 'cf-nav-link--active' : '' }}">
                                Menu Editor
                            </a>
                            @if (Auth::check() && (Auth::user()->is_admin ?? false))
                                {{-- Adjust this check to whatever your actual admin/role gate is --}}
                                <a href="{{ Route::has('admin.analytics') ? route('admin.analytics') : '#' }}"
                                   class="cf-nav-link {{ request()->routeIs('admin.analytics') ? 'cf-nav-link--active' : '' }}">
                                    Analytics
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- User dropdown (desktop) -->
                    <div class="hidden sm:flex sm:items-center relative">
                        <button type="button" class="cf-user-btn" onclick="document.getElementById('cf-user-dropdown').classList.toggle('cf-dropdown--open')">
                            {{ Auth::user()->name ?? 'Account' }}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="12" height="12">
                                <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                        <div id="cf-user-dropdown" class="cf-dropdown">
                            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Log out</button>
                            </form>
                        </div>
                    </div>

                    <!-- Mobile burger -->
                    <div class="flex items-center sm:hidden">
                        <button class="cf-burger" onclick="document.getElementById('cf-mobile-nav').classList.toggle('cf-mobile-panel--open')" aria-label="Toggle navigation">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" width="22" height="22">
                                <path fill-rule="evenodd" d="M2.5 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile nav panel -->
            <div id="cf-mobile-nav" class="cf-mobile-panel sm:hidden pb-3">
                <a href="{{ Route::has('dashboard') ? route('dashboard') : '#' }}" class="cf-mobile-link {{ request()->routeIs('dashboard') ? 'cf-nav-link--active' : '' }}">Dashboard</a>
                <a href="{{ Route::has('home') ? route('home') : '#' }}" class="cf-mobile-link {{ request()->routeIs('home') ? 'cf-nav-link--active' : '' }}">Point of Sale</a>
                <a href="{{ Route::has('queue.display') ? route('queue.display') : '/queue' }}" class="cf-mobile-link {{ request()->routeIs('queue.display') ? 'cf-nav-link--active' : '' }}">Order Board</a>
                <a href="{{ Route::has('menu.edit') ? route('menu.edit') : '/menu/edit' }}" class="cf-mobile-link {{ request()->routeIs('menu.edit') ? 'cf-nav-link--active' : '' }}">Menu Editor</a>
                @if (Auth::check() && (Auth::user()->is_admin ?? false))
                    <a href="{{ Route::has('admin.analytics') ? route('admin.analytics') : '#' }}" class="cf-mobile-link {{ request()->routeIs('admin.analytics') ? 'cf-nav-link--active' : '' }}">Analytics</a>
                @endif

                <div class="border-t mt-2 pt-2" style="border-color: rgba(234,217,183,0.2);">
                    <div class="px-4 py-1 text-xs cf-mono uppercase tracking-wide" style="color: var(--paper-warm); opacity:0.7;">
                        {{ Auth::user()->name ?? 'Account' }}
                    </div>
                    <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="cf-mobile-link">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="cf-mobile-link w-100 text-start">Log out</button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page heading -->
        @isset($header)
            <header class="bg-white shadow-sm" style="border-bottom: 1px solid var(--paper-warm);">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        // Close the user dropdown when clicking outside of it.
        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('cf-user-dropdown');
            const trigger = event.target.closest('.cf-user-btn');
            if (!dropdown) return;
            if (!trigger && !dropdown.contains(event.target)) {
                dropdown.classList.remove('cf-dropdown--open');
            }
        });
    </script>
</body>
</html>