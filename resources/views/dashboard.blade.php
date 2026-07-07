<x-app-layout>
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

        .cf-shift-row:last-child { border-bottom: none !important; }
    </style>

    @php
        $shifts = $shifts ?? [
            ['day' => 'Today',    'date' => now()->format('M j'),           'time' => '7:00 AM – 3:00 PM',  'role' => 'Barista'],
            ['day' => 'Tomorrow', 'date' => now()->addDay()->format('M j'), 'time' => '7:00 AM – 3:00 PM',  'role' => 'Barista'],
            ['day' => now()->addDays(3)->format('l'), 'date' => now()->addDays(3)->format('M j'), 'time' => '10:00 AM – 6:00 PM', 'role' => 'Cashier'],
        ];
        $salesToday = $salesToday ?? null;
        $itemsSoldToday = $itemsSoldToday ?? null;
        $weeklyLabels = $weeklyLabels ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        $weeklySales = $weeklySales ?? [1200, 1450, 980, 1600, 2100, 2600, 1750];
        $weeklyItemCounts = $weeklyItemCounts ?? [12, 15, 9, 18, 24, 30, 19]; // Fallback data added
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                        <p class="text-sm mt-1" style="color:#EAD9B7;">{{ __("Here's your schedule and sales at a glance.") }}</p>
                    </div>
                </div>
            </div>

            <div class="gap-6">

                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="cf-display text-lg font-semibold text-gray-800 dark:text-gray-200">My schedule</h3>
                        <span class="cf-mono text-xs uppercase tracking-wide px-2 py-1 rounded-full" style="background-color:#F6E7C9; color:#A4692A;">
                            Upcoming
                        </span>
                    </div>

                    @forelse ($shifts as $shift)
                        <div class="cf-shift-row flex items-center justify-between py-3 border-b" style="border-color:#EAD9B7;">
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $shift['day'] }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $shift['date'] }}</p>
                            </div>
                            <div class="text-right">
                                <p class="cf-mono text-sm font-semibold" style="color:#A4692A;">{{ $shift['time'] }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $shift['role'] }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400 py-6 text-center">No upcoming shifts scheduled.</p>
                    @endforelse
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mt-5">
                    <h3 class="cf-display text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">My sales</h3>

                    <div class="grid grid-cols-2 gap-3 mb-5">
                        <div class="rounded-lg p-3" style="background-color:#F7EFE0;">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Today</p>
                            <p class="cf-mono text-xl font-bold" style="color:#A4692A;">
                                {{ isset($salesToday) ? '₱'.number_format($salesToday, 2) : '—' }}
                            </p>
                        </div>
                        <div class="rounded-lg p-3" style="background-color:#F7EFE0;">
                            <p class="text-xs uppercase tracking-wide text-gray-500 mb-1">Items sold</p>
                            <p class="cf-mono text-xl font-bold" style="color:#A4692A;">{{ $itemsSoldToday ?? '—' }}</p>
                        </div>
                    </div>

                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Last 7 days</p>
                    <div style="position: relative; height:190px; width:100%;">
                        <canvas id="mySalesChart"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Store the item counts array globally inside JS context
            const itemCountsData = @json($weeklyItemCounts);

            const ctx = document.getElementById('mySalesChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($weeklyLabels),
                    datasets: [{
                        label: 'Your Weekly Sales',
                        data: @json($weeklySales),
                        backgroundColor: '#C6863B',
                        borderRadius: 6,
                        maxBarThickness: 60,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        // Activating and styled layout legend
                        legend: { 
                            display: true, 
                            position: 'top',
                            align: 'end',
                            labels: {
                                boxWidth: 12,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: {
                                    family: "'Space Grotesk', sans-serif",
                                    size: 11
                                }
                            }
                        },
                        // Modifying tooltip configuration behavior
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const salesAmount = context.raw;
                                    const itemCount = itemCountsData[index] || 0;

                                    // Returns custom multiline data arrays when hover status triggers
                                    return [
                                        `Sales: ₱${salesAmount.toLocaleString(undefined, {minimumFractionDigits: 2})}`,
                                        `Items Sold: ${itemCount}`
                                    ];
                                }
                            }
                        }
                    },
                    scales: {
                        y: { display: false, beginAtZero: true },
                        x: { grid: { display: false } }
                    }
                }
            });
        });
    </script>
</x-app-layout>