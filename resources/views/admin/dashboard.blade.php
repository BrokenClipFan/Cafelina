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

    /* Fixed Navigation Header Bar */
    .admin-navbar {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(64, 41, 27, 0.06);
        padding: 1rem 1.5rem;
    }

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

    .btn-theme-danger {
        background-color: #dc3545 !important;
        border: none;
    }
    .btn-theme-danger:hover {
        background-color: #ff0019 !important;
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

    .time-filter-group .btn {
        border-color: var(--paper-warm);
        color: var(--ink-soft);
        font-weight: 600;
        cursor: pointer;
    }

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

    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.5; }
        50% { transform: scale(1.1); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.5; }
    }
    .animate-pulse { animation: pulse 2s infinite ease-in-out; }

    /* Printable Report Styling */
    @media print {
        body * { visibility: hidden; }
        #printableArea, #printableArea * { visibility: visible; }
        #printableArea { position: absolute; left: 0; top: 0; width: 100%; }
        .no-print { display: none !important; }
    }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Fixed Header / Navbar -->
        <div class="admin-navbar mb-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                     style="width: 52px; height: 52px; background-color: var(--espresso); color: var(--caramel-tint);">
                    <i class="fa-solid fa-mug-hot fs-4"></i>
                </div>
                <div>
                    <h1 class="fw-bold fs-3 m-0" style="color: var(--espresso);">Cafelinea Analytics</h1>
                    <p class="mb-0 small" style="color: var(--ink-soft);">Deep dive into item performance, transactions, and revenue trends.</p>
                </div>
            </div>

            <!-- Navbar Control Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Print Report Modal Trigger -->
                <button type="button" class="btn btn-theme-outline shadow-sm" data-bs-toggle="modal" data-bs-target="#printReportModal">
                    <i class="fa-solid fa-print me-2" style="color: var(--caramel-deep);"></i> Print Report
                </button>

                <a href="{{ url('/') }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-house me-2"></i> POS
                </a>
                <a href="{{ Route::has('orders.edit_mode') ? route('orders.edit_mode') : '#' }}" class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Mode
                </a>
                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}" class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-theme-danger text-white shadow-sm fw-semibold">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- KPI Cards Summary Widget Row -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Gross Revenue</h6>
                        <span class="card-value">₱{{ number_format($grossRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Subtotal Collected</h6>
                        <span class="card-value">₱{{ number_format($subTotalCollected, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Taxes Accrued</h6>
                        <span class="card-value">₱{{ number_format($taxAccrued, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Total Items Sold</h6>
                        <span class="card-value">{{ number_format($totalItemsSold) }} <small class="fs-6 fw-normal" style="color: var(--ink-soft);">units</small></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 1: Charts Section -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-8">
                <div class="card analytic-card h-100 p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                        <h5 class="fw-bold m-0" id="trendChartTitle">Periodic Performance Trend (Yearly)</h5>
                        <div class="btn-group time-filter-group shadow-sm" role="group" id="timeframeButtonGroup">
                            <button type="button" onclick="changeChartPeriod('daily', this)" class="btn btn-outline-secondary btn-sm">Daily</button>
                            <button type="button" onclick="changeChartPeriod('monthly', this)" class="btn btn-outline-secondary btn-sm">Monthly</button>
                            <button type="button" onclick="changeChartPeriod('yearly', this)" class="btn btn-outline-secondary btn-sm active" style="background-color: var(--espresso); color:#fff; border-color:var(--espresso);">Yearly</button>
                        </div>
                    </div>
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
                        Based on dynamic Category metrics
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Popular Items & Online Staff Grid -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-lg-7">
                <div class="card analytic-card h-100 p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                        <h5 class="fw-bold m-0"><i class="fa-solid fa-fire me-2" style="color: var(--stamp);"></i>Most Popular Items</h5>
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="tableFilterInput" onkeyup="handleSearchInput()" class="form-control border-start-0" placeholder="Search database...">
                        </div>
                    </div>
                    
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-custom m-0" id="popularItemsTable">
                            <thead style="position: sticky; top: 0; z-index: 1; background: white;">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th class="text-center" onclick="handleBackendSort('units_sold')" style="cursor: pointer; user-select: none;">
                                        Units Sold <i class="fa-solid fa-sort-down ms-1" id="sortIcon_units_sold"></i>
                                    </th>
                                    <th class="text-end" onclick="handleBackendSort('total_income')" style="cursor: pointer; user-select: none;">
                                        Total Income <i class="fa-solid fa-sort ms-1 text-muted" id="sortIcon_total_income"></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="popularItemsTableBody">
                                <!-- Loaded dynamically via AJAX Fetch -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Employees Roster Widget (Online First) -->
            <div class="col-12 col-lg-5">
                <div class="card analytic-card h-100 p-4 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold m-0">
                                <i class="fa-solid fa-users me-2" style="color: var(--caramel-deep);"></i>Staff Directory
                            </h5>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded fw-semibold" style="font-size: 0.8rem;">
                                {{ $employees->where('online_status', 1)->count() }} Online Now
                            </span>
                        </div>

                        <div class="d-flex flex-column gap-3 overflow-y-auto mb-3" style="max-height: 280px;">
                            @forelse($employees as $employee)
                                @php
                                    // Check if online (supports boolean, integer 1/0, or string values)
                                    $isOnline = (bool) $employee->online_status;
                                @endphp
                                <div class="d-flex align-items-center justify-content-between p-2 rounded" 
                                    style="background-color: rgba(234, 217, 183, 0.2); border: 1px solid rgba(234, 217, 183, 0.4);">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="position-relative">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" 
                                                style="width: 40px; height: 40px; background-color: var(--espresso); font-size: 0.9rem;">
                                                {{ strtoupper(substr($employee->name, 0, 2)) }}
                                            </div>
                                            @if($isOnline)
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle animate-pulse" 
                                                    title="Online Now"></span>
                                            @else
                                                <span class="position-absolute bottom-0 end-0 p-1 bg-secondary border border-white rounded-circle opacity-75" 
                                                    title="Offline"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="m-0 fw-bold text-capitalize" style="font-size: 0.95rem;">{{ $employee->name }}</h6>
                                            <small class="text-muted" style="font-size: 0.78rem;">
                                                @if($isOnline)
                                                    <i class="fa-solid fa-circle text-success me-1" style="font-size: 0.5rem;"></i>Active Shift
                                                @else
                                                    <i class="fa-regular fa-clock me-1"></i>Offline
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge text-uppercase" style="background-color: var(--caramel-tint); color: var(--caramel-deep); font-size: 0.7rem;">
                                        {{ $employee->role ?? 'Employee' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-user-slash d-block mb-2 fs-4" style="color: var(--ink-soft);"></i>
                                    No employees registered in database.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-2 border-top" style="border-color: var(--paper-warm) !important;">
                        <a href="{{ Route::has('admin.employees') ? route('admin.employees') : '#' }}" class="btn btn-theme-outline w-100 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="fa-solid fa-users-viewfinder"></i> Manage Employees
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Perfectly Aligned Receipt Lookup Card (Full Width Span) -->
        <div class="row g-4">
            <div class="col-12">
                <div class="card analytic-card p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                style="width: 48px; height: 48px; background-color: var(--caramel-tint); color: var(--caramel-deep);">
                                <i class="fa-solid fa-receipt fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold m-0">Receipt Order Lookup</h5>
                                <small style="color: var(--ink-soft);">Inspect specific customer transactions or process full refunds.</small>
                            </div>
                        </div>

                        <!-- GET / POST Form Navigating to Details View -->
                        <form action="{{ Route::has('receipt.search') }}" method="POST" class="d-flex gap-2 flex-grow-1" style="max-width: 500px;">
                            @csrf
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0" placeholder="Enter Order # or Receipt ID..." required>
                            </div>
                            <button type="submit" class="btn btn-theme-primary px-4 d-flex align-items-center gap-2 flex-shrink-0">
                                <span>Search</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- PRINT REPORT SELECTION MODAL -->
<div class="modal fade" id="printReportModal" tabindex="-1" aria-labelledby="printReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background-color: var(--paper); border-radius: 14px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="printReportModalLabel" style="color: var(--espresso); font-family: 'Fraunces', serif;">
                    <i class="fa-solid fa-file-invoice-dollar me-2" style="color: var(--caramel-deep);"></i>Print Sales Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-3">Select the specific timeframe period you would like to render and print for financial records:</p>
                
                <div class="d-grid gap-2">
                    <button type="button" onclick="triggerReportPrint('daily')" class="btn btn-theme-outline text-start d-flex justify-content-between align-items-center p-3">
                        <div>
                            <strong class="d-block" style="color: var(--espresso);">Daily Sales Report</strong>
                            <small class="text-muted">Breakdown of hourly revenue & transaction velocity today.</small>
                        </div>
                        <i class="fa-solid fa-print fs-5" style="color: var(--caramel-deep);"></i>
                    </button>

                    <button type="button" onclick="triggerReportPrint('monthly')" class="btn btn-theme-outline text-start d-flex justify-content-between align-items-center p-3">
                        <div>
                            <strong class="d-block" style="color: var(--espresso);">Monthly Sales Summary</strong>
                            <small class="text-muted">Weekly revenue accumulation and category distribution.</small>
                        </div>
                        <i class="fa-solid fa-print fs-5" style="color: var(--caramel-deep);"></i>
                    </button>

                    <button type="button" onclick="triggerReportPrint('yearly')" class="btn btn-theme-outline text-start d-flex justify-content-between align-items-center p-3">
                        <div>
                            <strong class="d-block" style="color: var(--espresso);">Yearly Financial Overview</strong>
                            <small class="text-muted">Annual income metrics, cumulative tax, and unit sales.</small>
                        </div>
                        <i class="fa-solid fa-print fs-5" style="color: var(--caramel-deep);"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- HIDDEN PRINTABLE CONTAINER -->
<div id="printableArea" class="d-none p-5">
    <div class="text-center mb-4">
        <h2 class="fw-bold m-0" style="color: #000;">CAFELINEA COFFEE SHOP</h2>
        <p class="m-0 text-uppercase fw-bold" id="printReportTitle">SALES PERFORMANCE REPORT</p>
        <small>Generated on: {{ date('F d, Y h:i A') }}</small>
        <hr style="border-top: 2px solid #000;">
    </div>

    <div class="row my-4">
        <div class="col-3">
            <strong>Gross Revenue:</strong><br>
            <span>₱{{ number_format($grossRevenue, 2) }}</span>
        </div>
        <div class="col-3">
            <strong>Subtotal Collected:</strong><br>
            <span>₱{{ number_format($subTotalCollected, 2) }}</span>
        </div>
        <div class="col-3">
            <strong>Tax Accrued:</strong><br>
            <span>₱{{ number_format($taxAccrued, 2) }}</span>
        </div>
        <div class="col-3">
            <strong>Total Items Sold:</strong><br>
            <span>{{ number_format($totalItemsSold) }} units</span>
        </div>
    </div>

    <hr style="border-top: 1px dashed #000;">
    <h5 class="fw-bold my-3">Sales Summary Data</h5>
    <div id="printReportTableContainer">
        <!-- Injected via JavaScript upon print trigger -->
    </div>

    <div class="mt-5 pt-4 text-center border-top">
        <small>*** End of Generated Financial Statement - Cafelinea POS System ***</small>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // --- 1. RECEIVE RAW LARAVEL DATA ---
    const rawDaily = @json($daily);
    const rawMonthly = @json($monthly);
    const rawYearly = @json($yearly);
    const categoryJson = @json($categoryData);

    // --- 2. TIME PERFORMANCE LOGIC CONFIGS ---
    const dailyLabels = Array.from({length: 24}, (_, i) => `${String(i).padStart(2, '0')}:00`);
    const dailyData = Array.from({length: 24}, (_, i) => rawDaily.revenue[i] || 0);

    const monthlyLabels = Object.keys(rawMonthly.revenue).map(wk => `Week ${wk}`);
    const monthlyData = Object.values(rawMonthly.revenue);

    const yearlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const yearlyData = Array.from({length: 12}, (_, i) => rawYearly.revenue[i + 1] || 0);

    // --- 3. RENDER TREND BAR CANVAS CHART ---
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: yearlyLabels, 
            datasets: [{
                label: 'Gross Revenue (₱)',
                data: yearlyData,
                borderColor: '#A4692A',
                backgroundColor: 'rgba(198, 134, 59, 0.8)',
                borderWidth: 2,
                borderRadius: 4
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

    // --- 4. ENGINE RUNNER FOR TIME PERIOD CHANGING ---
    function changeChartPeriod(period, buttonElement) {
        const buttons = document.querySelectorAll('#timeframeButtonGroup .btn');
        buttons.forEach(btn => {
            btn.classList.remove('active');
            btn.style.backgroundColor = 'transparent';
            btn.style.color = 'var(--ink-soft)';
            btn.style.borderColor = 'var(--paper-warm)';
        });

        buttonElement.classList.add('active');
        buttonElement.style.backgroundColor = 'var(--espresso)';
        buttonElement.style.color = '#fff';
        buttonElement.style.borderColor = 'var(--espresso)';

        const titleElement = document.getElementById('trendChartTitle');
        if (period === 'daily') {
            trendChart.data.labels = dailyLabels;
            trendChart.data.datasets[0].data = dailyData;
            titleElement.innerText = "Periodic Performance Trend (Daily/Hourly)";
        } else if (period === 'monthly') {
            trendChart.data.labels = monthlyLabels;
            trendChart.data.datasets[0].data = monthlyData;
            titleElement.innerText = "Periodic Performance Trend (Monthly/Weekly)";
        } else if (period === 'yearly') {
            trendChart.data.labels = yearlyLabels;
            trendChart.data.datasets[0].data = yearlyData;
            titleElement.innerText = "Periodic Performance Trend (Yearly/Monthly)";
        }
        trendChart.update();
    }

    // --- 5. RENDER DYNAMIC CATEGORY DOUGHNUT MIX CHART ---
    const categoryLabels = Object.keys(categoryJson);
    const categoryDataValues = Object.values(categoryJson);

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryLabels.length ? categoryLabels : ['No Data Available'],
            datasets: [{
                data: categoryDataValues.length ? categoryDataValues : [1],
                backgroundColor: ['#C6863B', '#40291B', '#3F6B4C', '#EAD9B7', '#A8432E'],
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

    // --- 6. POPULAR ITEMS BACKEND FETCH & SEARCH ---
    let tableState = {
        search: '',
        sort_by: 'units_sold',
        sort_dir: 'desc',
        apiUrl: "{{ Route::has('admin.popular_items_data') ? route('admin.popular_items_data') : '#' }}"
    };

    let searchDebounceTimeout = null;

    document.addEventListener("DOMContentLoaded", function() {
        if (tableState.apiUrl !== '#') {
            fetchPopularItems();
        }
    });

    function fetchPopularItems() {
        const url = new URL(tableState.apiUrl);
        url.searchParams.append('search', tableState.search);
        url.searchParams.append('sort_by', tableState.sort_by);
        url.searchParams.append('sort_dir', tableState.sort_dir);

        fetch(url)
            .then(response => response.json())
            .then(data => renderTableRows(data))
            .catch(error => console.error('Error fetching database metrics:', error));
    }

    function renderTableRows(items) {
        const tbody = document.getElementById('popularItemsTableBody');
        tbody.innerHTML = '';

        if (items.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center text-muted py-4">No matching item entries found in database.</td></tr>`;
            return;
        }

        items.forEach(item => {
            const formattedIncome = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(item.total_income);
            const formattedUnits = new Intl.NumberFormat().format(item.units_sold);

            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="fw-semibold text-capitalize">${item.name}</td>
                <td><span class="badge" style="background-color: var(--caramel-tint); color: var(--caramel-deep); padding: 0.35em 0.65em; border-radius: 0.25rem;">${item.category}</span></td>
                <td class="text-center fw-bold">${formattedUnits}</td>
                <td class="text-end fw-bold">${formattedIncome}</td>
            `;
            tbody.appendChild(row);
        });
    }

    function handleBackendSort(column) {
        document.getElementById('sortIcon_units_sold').className = "fa-solid fa-sort ms-1 text-muted";
        document.getElementById('sortIcon_total_income').className = "fa-solid fa-sort ms-1 text-muted";

        if (tableState.sort_by === column) {
            tableState.sort_dir = tableState.sort_dir === 'desc' ? 'asc' : 'desc';
        } else {
            tableState.sort_by = column;
            tableState.sort_dir = 'desc';
        }

        const dynamicIcon = document.getElementById(`sortIcon_${column}`);
        dynamicIcon.className = tableState.sort_dir === 'asc' ? "fa-solid fa-sort-up ms-1" : "fa-solid fa-sort-down ms-1";

        fetchPopularItems();
    }

    function handleSearchInput() {
        clearTimeout(searchDebounceTimeout);
        searchDebounceTimeout = setTimeout(() => {
            tableState.search = document.getElementById('tableFilterInput').value;
            fetchPopularItems();
        }, 300);
    }

    // --- 7. PRINTABLE REPORT GENERATOR FUNCTION ---
    function triggerReportPrint(type) {
        let labels = [];
        let values = [];
        let title = '';

        if (type === 'daily') {
            title = 'DAILY SALES REPORT';
            labels = dailyLabels;
            values = dailyData;
        } else if (type === 'monthly') {
            title = 'MONTHLY SALES REPORT';
            labels = monthlyLabels;
            values = monthlyData;
        } else if (type === 'yearly') {
            title = 'YEARLY SALES REPORT';
            labels = yearlyLabels;
            values = yearlyData;
        }

        document.getElementById('printReportTitle').innerText = title;

        // Generate print table HTML
        let tableHTML = `<table class="table table-bordered w-100 align-middle">
            <thead>
                <tr class="table-light">
                    <th>Timeframe Period</th>
                    <th class="text-end">Revenue Generated</th>
                </tr>
            </thead>
            <tbody>`;

        labels.forEach((label, idx) => {
            const amount = values[idx] || 0;
            const formatted = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP' }).format(amount);
            tableHTML += `<tr>
                <td>${label}</td>
                <td class="text-end">${formatted}</td>
            </tr>`;
        });

        tableHTML += `</tbody></table>`;
        document.getElementById('printReportTableContainer').innerHTML = tableHTML;

        // Dismiss Modal
        const modalEl = document.getElementById('printReportModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        if (modalInstance) modalInstance.hide();

        // Reveal print container & trigger printer
        const printableArea = document.getElementById('printableArea');
        printableArea.classList.remove('d-none');

        window.print();

        // Re-hide print area after printing
        setTimeout(() => {
            printableArea.classList.add('d-none');
        }, 1000);
    }
</script>

{{-- @endsection --}}