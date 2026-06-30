{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Chart.js for Analytics -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    :root {
        --theme-bg: #FFFFFF;
        --theme-accent-light: #F8F5F2;
        --theme-primary: #6C4E31;
        --theme-dark: #603F26;
    }

    body, .admin-wrapper {
        background-color: var(--theme-bg);
        color: var(--theme-dark);
        font-family: 'Figtree', sans-serif;
    }

    /* Card Styling */
    .analytic-card {
        background-color: var(--theme-accent-light);
        border: 1px solid rgba(108, 78, 49, 0.1);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.01);
    }
    
    .card-title-custom {
        color: var(--theme-primary);
        font-weight: 600;
        font-size: 0.90rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .card-value {
        color: var(--theme-dark);
        font-size: 1.8rem;
        font-weight: 700;
    }

    /* Scalable Button Group */
    .btn-theme-primary {
        background-color: var(--theme-primary);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-theme-primary:hover, .btn-theme-primary.active {
        background-color: var(--theme-dark);
        color: #fff;
    }
    
    .btn-theme-outline {
        background-color: transparent;
        color: var(--theme-primary);
        border: 2px solid var(--theme-primary);
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-theme-outline:hover {
        background-color: var(--theme-primary);
        color: #fff;
    }

    /* Timeframe Filter Buttons */
    .time-filter-group .btn {
        border-color: rgba(108, 78, 49, 0.2);
        color: var(--theme-primary);
        font-weight: 500;
    }
    .time-filter-group .btn.active {
        background-color: var(--theme-primary);
        color: #fff;
        border-color: var(--theme-primary);
    }

    /* Table Styling */
    .table-custom {
        --bs-table-bg: transparent;
        --bs-table-color: var(--theme-dark);
    }
    .table-custom th {
        color: var(--theme-primary);
        border-bottom: 2px solid var(--theme-primary);
        font-weight: 600;
    }
    .table-custom td {
        border-bottom: 1px solid rgba(108, 78, 49, 0.1);
        vertical-align: middle;
    }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">
        
        <!-- Header & Action Buttons -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="fw-bold m-0" style="color: var(--theme-dark);">Advanced Sales Analytics</h1>
                <p class="text-muted mb-0" style="color: var(--theme-primary) !important;">Deep dive into item execution and dynamic macro revenue trends.</p>
            </div>
            
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ url('/') }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-house me-2"></i> Homepage
                </a>
                <a href="{{ route('orders.edit_mode') ?? '#' }}" class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Order Mode
                </a>
            </div>
        </div>

        <!-- Timeframe Controls Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 p-3 border rounded bg-light">
            <span class="fw-semibold text-muted"><i class="fa-solid fa-filter me-2"></i> Filter View:</span>
            <div class="btn-group time-filter-group shadow-sm" role="group">
                <!-- Tie these to query strings in Laravel if server-side filtering, e.g., ?period=daily -->
                <a href="?period=daily" class="btn btn-outline-secondary btn-sm active">Daily</a>
                <a href="?period=monthly" class="btn btn-outline-secondary btn-sm">Monthly</a>
                <a href="?period=yearly" class="btn btn-outline-secondary btn-sm">Yearly</a>
            </div>
        </div>

        <!-- Core Financial Metrics -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Gross Revenue</h6>
                        <span class="card-value">₱142,500.00</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Subtotal Collected</h6>
                        <span class="card-value">₱127,230.00</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Taxes Accrued</h6>
                        <span class="card-value">₱15,270.00</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Total Items Sold</h6>
                        <span class="card-value">1,842 <small class="fs-6 fw-normal text-muted">units</small></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 1: Line Trends & Categories -->
        <div class="row g-4 mb-4">
            <!-- Dynamic Revenue Trends Chart -->
            <div class="col-12 col-lg-8">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--theme-dark);">Periodic Performance Trend</h5>
                    <div style="position: relative; height:320px; width:100%">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Top Selling Categories (Pie/Doughnut) -->
            <div class="col-12 col-lg-4">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--theme-dark);">Category Distribution</h5>
                    <div style="position: relative; height:240px; width:100%">
                        <canvas id="categoryChart"></canvas>
                    </div>
                    <div class="text-center mt-3 small text-muted">
                        Based on grouped <code>purchase_items.category</code> counts
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Most Popular Items & Payment Method Breakdown -->
        <div class="row g-4">
            <!-- Top Selling Items (from purchase_items aggregated sum of 'count') -->
            <div class="col-12 col-lg-7">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--theme-dark);"><i class="fa-solid fa-fire text-danger me-2"></i>Most Popular Items</h5>
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
                                {{-- <!-- Template Blade Loop: @foreach($topItems as $item) --> --}}
                                <tr>
                                    <td class="fw-semibold text-capitalize">Caramel Macchiato</td>
                                    <td><span class="badge bg-light text-dark border">Beverages</span></td>
                                    <td class="text-center fw-bold">412</td>
                                    <td class="text-end fw-bold">₱61,800.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Chocolate Croissant</td>
                                    <td><span class="badge bg-light text-dark border">Pastries</span></td>
                                    <td class="text-center fw-bold">289</td>
                                    <td class="text-end fw-bold">₱24,565.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Spanish Latte</td>
                                    <td><span class="badge bg-light text-dark border">Beverages</span></td>
                                    <td class="text-center fw-bold">204</td>
                                    <td class="text-end fw-bold">₱32,640.00</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold text-capitalize">Matcha Cookie</td>
                                    <td><span class="badge bg-light text-dark border">Snacks</span></td>
                                    <td class="text-center fw-bold">145</td>
                                    <td class="text-end fw-bold">₱10,875.00</td>
                                </tr>
                                {{-- <!-- @endforeach --> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Methods Split -->
            <div class="col-12 col-lg-5">
                <div class="card analytic-card h-100 p-4">
                    <h5 class="fw-bold mb-4" style="color: var(--theme-dark);">Payment Method Usage</h5>
                    <div class="d-flex flex-column gap-3 justify-content-center h-100 pb-3">
                        <!-- Cash -->
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-solid fa-money-bill text-success me-2"></i> Cash Payments</span>
                                <span>55%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 55%; background-color: var(--theme-primary);" aria-valuenow="55" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Digital Wallet -->
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-solid fa-wallet text-primary me-2"></i> Digital Wallet</span>
                                <span>30%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 30%; background-color: #A084DC;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Card -->
                        <div>
                            <div class="d-flex justify-content-between mb-1 small fw-bold">
                                <span><i class="fa-regular fa-credit-card text-warning me-2"></i> Credit / Debit Card</span>
                                <span>15%</span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar" role="progressbar" style="width: 15%; background-color: var(--theme-dark);" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- CHART 1: Timeline Performance Trends ---
        const trendCtx = document.getElementById('trendChart').getContext('2d');
        const themePrimary = '#6C4E31';
        
        new Chart(trendCtx, {
            type: 'line',
            data: {
                // These labels can represent hours (Daily), days (Monthly), or months (Yearly)
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Gross Revenue (₱)',
                    data: [14000, 19500, 26000, 22000, 31000, 45000, 38000, 42000, 51000, 58000, 62000, 75000],
                    borderColor: themePrimary,
                    backgroundColor: 'rgba(108, 78, 49, 0.04)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.2,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.04)' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // --- CHART 2: Category Mix Breakdown ---
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Beverages', 'Pastries', 'Snacks', 'Merchandise'],
                datasets: [{
                    data: [55, 25, 15, 5],
                    backgroundColor: [
                        '#6C4E31', // primary
                        '#603F26', // dark
                        '#DDB892', // secondary accent
                        '#EDE0D4'  // soft tint
                    ],
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