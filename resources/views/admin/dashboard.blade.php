{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Chart.js for Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    /* Shares the Cafelina token system used across the POS, order board
       and menu editor: paper/espresso palette, Fraunces + Space Grotesk. */
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

    body, .admin-wrapper {
        background-color: var(--paper);
        color: var(--ink);
        font-family: 'Inter', 'Figtree', sans-serif;
    }

    .admin-wrapper h1, .admin-wrapper h5, .admin-wrapper h6 {
        font-family: 'Fraunces', serif;
    }

    /* Card styling */
    .analytic-card {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(64, 41, 27, 0.06);
    }

    .card-title-custom {
        color: var(--ink-soft);
        font-weight: 700;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }
    .card-value {
        font-family: 'Space Grotesk', sans-serif;
        color: var(--caramel-deep);
        font-size: 1.8rem;
        font-weight: 700;
    }

    /* Buttons */
    .btn-theme-primary {
        background-color: var(--espresso);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .btn-theme-primary:hover, .btn-theme-primary.active {
        background-color: var(--caramel-deep);
        color: #fff;
    }

    .btn-theme-outline {
        background-color: transparent;
        color: var(--espresso);
        border: 2px solid var(--paper-warm);
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-theme-outline:hover {
        background-color: var(--caramel-tint);
        border-color: var(--caramel);
        color: var(--caramel-deep);
    }

    /* Timeframe filter buttons */
    .time-filter-group .btn {
        border-color: var(--paper-warm);
        color: var(--ink-soft);
        font-weight: 600;
    }
    .time-filter-group .btn.active {
        background-color: var(--espresso);
        color: #fff;
        border-color: var(--espresso);
    }

    .toolbar {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm) !important;
    }

    /* Table styling */
    .table-custom {
        --bs-table-bg: transparent;
        --bs-table-color: var(--ink);
    }
    .table-custom th {
        color: var(--ink-soft);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 2px solid var(--caramel);
        font-weight: 700;
    }
    .table-custom td {
        border-bottom: 1px solid var(--paper-warm);
        vertical-align: middle;
    }
    .table-custom .badge {
        background-color: var(--caramel-tint) !important;
        color: var(--caramel-deep) !important;
        border: none !important;
        font-weight: 600;
    }

    .progress { background-color: var(--paper); }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Header & action buttons -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="fw-bold m-0">Advanced Sales Analytics</h1>
                <p class="mb-0" style="color: var(--ink-soft);">Deep dive into item performance and revenue trends.</p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-house me-2"></i> Homepage
                </a>
                <a href="{{ Route::has('orders.edit_mode') ? route('orders.edit_mode') : '#' }}" class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Order Mode
                </a>
            </div>
        </div>

        <!-- Timeframe controls toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded toolbar">
            <span class="fw-semibold" style="color: var(--ink-soft);"><i class="fa-solid fa-filter me-2"></i> Filter view:</span>
            <div class="btn-group time-filter-group shadow-sm" role="group">
                {{-- Tie these to query strings in Laravel if server-side filtering, e.g., ?period=daily --}}
                <a href="?period=daily" class="btn btn-outline-secondary btn-sm {{ request('period', 'daily') === 'daily' ? 'active' : '' }}">Daily</a>
                <a href="?period=monthly" class="btn btn-outline-secondary btn-sm {{ request('period') === 'monthly' ? 'active' : '' }}">Monthly</a>
                <a href="?period=yearly" class="btn btn-outline-secondary btn-sm {{ request('period') === 'yearly' ? 'active' : '' }}">Yearly</a>
            </div>
        </div>

        <!-- Core financial metrics -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Gross Revenue</h6>
                        <span class="card-value">₱@json($grossRevenue)</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Subtotal Collected</h6>
                        <span class="card-value">₱@json($subTotalCollected)</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Taxes Accrued</h6>
                        <span class="card-value">₱@json($taxAccrued)</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Total Items Sold</h6>
                        <span class="card-value">@json($totalItemsSold)<small class="fs-6 fw-normal" style="color: var(--ink-soft);">units</small></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 1: line trend & category distribution -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Periodic Performance Trend</h5>
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Category Distribution</h5>
                    <div style="position: relative; height:240px; width:100%">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="text-center mt-3 small" style="color: var(--ink-soft);">
                        Based on grouped <code>purchase_items.category</code> counts
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: most popular items & payment method breakdown -->
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4"><i class="fa-solid fa-fire me-2" style="color: var(--stamp);"></i>Most Popular Items</h5>
                    <div class="table-responsive">
                        <table class="table table-custom m-0">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th class="text-center">Units Sold</th>
                                    <th class="text-end">Total Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Template Blade Loop: @foreach($topItems as $item) --}}
                                <tr>
                                    <td class="fw-semibold text-capitalize">Caramel Macchiato</td>
                                    <td><span class="badge">Beverages</span></td>
                                    <td class="text-center fw-bold">412</td>
                                    <td class="text-end fw-bold">₱61,800.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Chocolate Croissant</td>
                                    <td><span class="badge">Pastries</span></td>
                                    <td class="text-center fw-bold">289</td>
                                    <td class="text-end fw-bold">₱24,565.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Spanish Latte</td>
                                    <td><span class="badge">Beverages</span></td>
                                    <td class="text-center fw-bold">204</td>
                                    <td class="text-end fw-bold">₱32,640.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Matcha Cookie</td>
                                    <td><span class="badge">Snacks</span></td>
                                    <td class="text-center fw-bold">145</td>
                                    <td class="text-end fw-bold">₱10,875.00</td>
                                </tr>
                                {{-- @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment methods split -->
            <div class="col-12 col-lg-5">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4">Payment Method Usage</h5>
                    <div class="d-flex flex-column gap-3 justify-content-center h-100 pb-3">
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-solid fa-money-bill me-2" style="color: var(--caramel-deep);"></i> Cash Payments</span>
                                <span>55%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 55%; background-color: var(--caramel);" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-solid fa-wallet me-2" style="color: var(--moss-deep);"></i> Digital Wallet</span>
                                <span>30%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: var(--moss);" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-regular fa-credit-card me-2" style="color: var(--stamp);"></i> Credit / Debit Card</span>
                                <span>15%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 15%; background-color: var(--stamp);" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const themeCaramel = '#C6863B';
        const themeCaramelDeep = '#A4692A';
        const themeEspresso = '#40291B';
        const themeMoss = '#3F6B4C';
        const themePaperWarm = '#EAD9B7';

        // --- Chart 1: timeline performance trend ---
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        new Chart(trendCtx, {
            type: 'line',
            data: {
                // Labels can represent hours (Daily), days (Monthly), or months (Yearly)
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Gross Revenue (₱)',
                    data: [14000, 19500, 26000, 22000, 31000, 45000, 38000, 42000, 51000, 58000, 62000, 75000],
                    borderColor: themeCaramelDeep,
                    backgroundColor: 'rgba(198, 134, 59, 0.08)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.25,
                    pointRadius: 3,
                    pointBackgroundColor: themeCaramelDeep,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(64, 41, 27, 0.05)' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // --- Chart 2: category mix breakdown ---
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Beverages', 'Pastries', 'Snacks', 'Merchandise'],
                datasets: [{
                    data: [55, 25, 15, 5],
                    backgroundColor: [themeCaramel, themeEspresso, themeMoss, themePaperWarm],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    }
                },
                cutout: '70%'
            }
        });
    });
</script>

{{-- @endsection --}}