<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&display=swap');

        .cf-display { font-family: 'Fraunces', serif; }
        .cf-mono { font-family: 'Space Grotesk', sans-serif; }

        .cf-nav-card {
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .cf-nav-card:hover {
            transform: translateY(-3px);
            border-color: #C6863B;
            box-shadow: 0 10px 24px rgba(198, 134, 59, 0.18);
        }
        .cf-nav-card .cf-arrow {
            transition: transform 0.15s ease;
        }
        .cf-nav-card:hover .cf-arrow {
            transform: translateX(3px);
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome banner -->
            <div class="rounded-xl overflow-hidden shadow-sm" style="background-color:#40291B;">
                <div class="p-8 flex items-center gap-5">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center flex-shrink-0" style="background-color:#C6863B;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#40291B" class="w-7 h-7">
                            <path d="M2 3a1 1 0 0 0-1 1v2.5a4.5 4.5 0 0 0 4.5 4.5h.05a2.5 2.5 0 0 0 4.9 0H10.5A4.5 4.5 0 0 0 15 6.5V6a2 2 0 0 0-2-2h-.5V3a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1zm10.5 2H13a1 1 0 0 1 1 1v.5a3.5 3.5 0 0 1-1.5 2.87V5zM3 3h8v5.5A3.5 3.5 0 0 1 7.5 12h-2A3.5 3.5 0 0 1 2 8.5V3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="cf-mono text-xs tracking-widest uppercase" style="color:#F6E7C9;">Cafelina staff dashboard</p>
                        <h1 class="cf-display text-2xl font-semibold text-white">
                            {{ __('Welcome back') }}{{ Auth::user() ? ', ' . Auth::user()->name : '' }}
                        </h1>
                        <p class="text-sm mt-1" style="color:#EAD9B7;">{{ __("Here's where you can jump in.") }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick navigation -->
            <div>
                <h3 class="cf-display text-lg font-semibold mb-3 text-gray-800 dark:text-gray-200">Jump to</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    <a href="{{ Route::has('home') ? route('home') : '#' }}"
                       class="cf-nav-card block bg-white dark:bg-gray-800 rounded-xl p-6 border-2 dark:border-gray-700"
                       style="border-color:#EAD9B7;">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4" style="background-color:#F6E7C9;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#A4692A" class="w-5 h-5">
                                <path d="M0 3a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V3zm2 1v8h12V4H2z"/>
                                <path d="M4 6h8v1H4V6zm0 2h5v1H4V8z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Point of sale</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Take orders and send them to the kitchen.</p>
                        <span class="cf-mono text-sm font-semibold inline-flex items-center gap-1" style="color:#A4692A;">
                            Open <span class="cf-arrow">→</span>
                        </span>
                    </a>

                    <a href="{{ Route::has('queue.display') ? route('queue.display') : '/queue' }}"
                       class="cf-nav-card block bg-white dark:bg-gray-800 rounded-xl p-6 border-2 dark:border-gray-700"
                       style="border-color:#EAD9B7;">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4" style="background-color:#E1EBE0;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#2E5038" class="w-5 h-5">
                                <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/>
                                <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Order board</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">The customer-facing preparing / ready display.</p>
                        <span class="cf-mono text-sm font-semibold inline-flex items-center gap-1" style="color:#2E5038;">
                            Open <span class="cf-arrow">→</span>
                        </span>
                    </a>

                    <a href="{{ Route::has('menu.edit') ? route('menu.edit') : '/menu/edit' }}"
                       class="cf-nav-card block bg-white dark:bg-gray-800 rounded-xl p-6 border-2 dark:border-gray-700"
                       style="border-color:#EAD9B7;">
                        <div class="w-11 h-11 rounded-full flex items-center justify-center mb-4" style="background-color:#F6E7C9;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#A4692A" class="w-5 h-5">
                                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5z"/>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Menu editor</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Add, reorder, or retire categories and items.</p>
                        <span class="cf-mono text-sm font-semibold inline-flex items-center gap-1" style="color:#A4692A;">
                            Open <span class="cf-arrow">→</span>
                        </span>
                    </a>

                </div>
            </div>

            <!-- Today at a glance -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl">
                <div class="p-6">
                    <h3 class="cf-display text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Today at a glance</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-lg p-4" style="background-color:#F7EFE0;">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Orders today</p>
                            <p class="cf-mono text-2xl font-bold" style="color:#A4692A;">{{ $ordersToday ?? '—' }}</p>
                        </div>
                        <div class="rounded-lg p-4" style="background-color:#F7EFE0;">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Active in kitchen</p>
                            <p class="cf-mono text-2xl font-bold" style="color:#A4692A;">{{ $activeOrders ?? '—' }}</p>
                        </div>
                        <div class="rounded-lg p-4" style="background-color:#F7EFE0;">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Revenue today</p>
                            <p class="cf-mono text-2xl font-bold" style="color:#A4692A;">{{ isset($revenueToday) ? '₱'.number_format($revenueToday, 2) : '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>