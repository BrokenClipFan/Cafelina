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
<link
    href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet">

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

    body,
    .admin-wrapper {
        background-color: var(--paper);
        color: var(--ink);
        font-family: 'Inter', 'Figtree', sans-serif;
    }

    .admin-wrapper h1,
    .admin-wrapper h5,
    .admin-wrapper h6 {
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

    .btn-theme-primary:hover,
    .btn-theme-primary.active {
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
        padding: 0.6rem 1.2rem;
        transition: all 0.2s ease;
    }

    .time-filter-group .btn:hover:not(.active) {
        background-color: var(--caramel-tint);
        border-color: var(--caramel);
        color: var(--caramel-deep);
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

    .progress {
        background-color: var(--paper);
    }

    @keyframes pulse {
        0% {
            transform: scale(0.95);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.1);
            opacity: 1;
        }

        100% {
            transform: scale(0.95);
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 2s infinite ease-in-out;
    }

    /* Printable Report Styling */
    @media print {
        /* Hide all UI elements that shouldn't be printed */
        nav, header, .cf-navbar, .admin-wrapper, .modal, .no-print {
            display: none !important;
        }

        /* Ensure the printable area takes normal document flow */
        #printableArea {
            display: block !important;
            position: static !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        #printableArea * {
            visibility: visible !important;
        }

        /* Reset layout containers to prevent forced heights */
        body, .min-h-screen, main {
            background-color: white !important;
            min-height: auto !important;
            height: auto !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Fixed Header / Navbar -->
        <div
            class="admin-navbar mb-4 d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 52px; height: 52px; background-color: var(--espresso); color: var(--caramel-tint);">
                    <i class="fa-solid fa-mug-hot fs-4"></i>
                </div>
                <div>
                    <h1 class="fw-bold fs-3 m-0" style="color: var(--espresso);">Cafelinea Analytics</h1>
                    <p class="mb-0 small" style="color: var(--ink-soft);">Deep dive into item performance, transactions,
                        and revenue trends.</p>
                </div>
            </div>

            <!-- Navbar Control Actions -->
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <!-- Timeframe Dropdown & Print Group -->
                <div class="d-flex align-items-center gap-2 me-3">
                    <div class="dropdown shadow-sm">
                        <button class="btn dropdown-toggle px-3 py-2 fw-semibold" type="button" id="timeframeDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: var(--espresso); color:#fff; border-color:var(--espresso); border-radius: 8px;">
                            <i class="fa-regular fa-calendar me-2"></i> View: {{ ucfirst($period) }}
                        </button>
                        <ul class="dropdown-menu shadow" aria-labelledby="timeframeDropdown" style="border-radius: 8px; border: 1px solid var(--paper-warm);">
                            <li><button class="dropdown-item py-2 {{ $period === 'daily' ? 'active' : '' }}" type="button" onclick="openDatePickerModal('daily', this)">Daily</button></li>
                            <li><button class="dropdown-item py-2 {{ $period === 'weekly' ? 'active' : '' }}" type="button" onclick="openDatePickerModal('weekly', this)">Weekly</button></li>
                            <li><button class="dropdown-item py-2 {{ $period === 'monthly' ? 'active' : '' }}" type="button" onclick="openDatePickerModal('monthly', this)">Monthly</button></li>
                            <li><button class="dropdown-item py-2 {{ $period === 'yearly' ? 'active' : '' }}" type="button" onclick="openDatePickerModal('yearly', this)">Yearly</button></li>
                            <li><hr class="dropdown-divider" style="border-color: var(--paper-warm);"></li>
                            <li><button class="dropdown-item py-2 {{ $period === 'overall' ? 'active' : '' }}" type="button" onclick="window.location.href = '?period=overall'">Overall</button></li>
                        </ul>
                    </div>

                    <!-- Print Report Trigger -->
                    <button type="button" class="btn btn-theme-outline shadow-sm" onclick="triggerReportPrint()">
                        <i class="fa-solid fa-print me-2" style="color: var(--caramel-deep);"></i> Print Report
                    </button>
                </div>

                <a href="{{ url('/') }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-house me-2"></i> POS
                </a>
                <a href="{{ Route::has('queue.display') ? route('queue.display') : '#' }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-list-ol me-2"></i> Queue
                </a>
                <a href="{{ Route::has('orders.edit_mode') ? route('orders.edit_mode') : '#' }}"
                    class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Edit Mode
                </a>
                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}"
                    class="btn btn-theme-primary shadow-sm">
                    <i class="fa-solid fa-gear me-2"></i> Settings
                </a>
                <a href="{{ Route::has('admin.inventory') ? route('admin.inventory') : '#' }}"
                    class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-boxes-stacked me-2"></i> Inventory
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
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Gross Revenue</h6>
                        <span class="card-value">₱{{ number_format($grossRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Subtotal Collected</h6>
                        <span class="card-value">₱{{ number_format($subTotalCollected, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Taxes Accrued</h6>
                        <span class="card-value">₱{{ number_format($taxAccrued, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Inventory Cost (Restock)</h6>
                        <span class="card-value text-danger">₱{{ number_format($inventoryCost, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card analytic-card h-100 p-3">
                    <div class="card-body">
                        <h6 class="card-title-custom">Net Profit (Subtotal - Cost)</h6>
                        <span class="card-value text-success">₱{{ number_format($profit, 2) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-4">
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
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                        <h5 class="fw-bold m-0" id="trendChartTitle">Periodic Performance Trend ({{ ucfirst($period) }})</h5>
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
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                        <h5 class="fw-bold m-0"><i class="fa-solid fa-fire me-2" style="color: var(--stamp);"></i>Most
                            Popular Items</h5>
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i
                                    class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="tableFilterInput" onkeyup="handleSearchInput()"
                                class="form-control border-start-0" placeholder="Search database...">
                        </div>
                    </div>

                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-custom m-0" id="popularItemsTable">
                            <thead style="position: sticky; top: 0; z-index: 1; background: white;">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th class="text-center" onclick="handleBackendSort('units_sold')"
                                        style="cursor: pointer; user-select: none;">
                                        Units Sold <i class="fa-solid fa-sort-down ms-1" id="sortIcon_units_sold"></i>
                                    </th>
                                    <th class="text-end" onclick="handleBackendSort('total_income')"
                                        style="cursor: pointer; user-select: none;">
                                        Total Income <i class="fa-solid fa-sort ms-1 text-muted"
                                            id="sortIcon_total_income"></i>
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
                                <i class="fa-solid fa-users me-2" style="color: var(--caramel-deep);"></i>Staff
                                Directory
                            </h5>
                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded fw-semibold"
                                style="font-size: 0.8rem;">
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
                                            @if ($isOnline)
                                                <span
                                                    class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle animate-pulse"
                                                    title="Online Now"></span>
                                            @else
                                                <span
                                                    class="position-absolute bottom-0 end-0 p-1 bg-secondary border border-white rounded-circle opacity-75"
                                                    title="Offline"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="m-0 fw-bold text-capitalize" style="font-size: 0.95rem;">
                                                {{ $employee->name }}</h6>
                                            <small class="text-muted" style="font-size: 0.78rem;">
                                                @if ($isOnline)
                                                    <i class="fa-solid fa-circle text-success me-1"
                                                        style="font-size: 0.5rem;"></i>Active Shift
                                                @else
                                                    <i class="fa-regular fa-clock me-1"></i>Offline
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                    <span class="badge text-uppercase"
                                        style="background-color: var(--caramel-tint); color: var(--caramel-deep); font-size: 0.7rem;">
                                        {{ $employee->role ?? 'Employee' }}
                                    </span>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fa-solid fa-user-slash d-block mb-2 fs-4"
                                        style="color: var(--ink-soft);"></i>
                                    No employees registered in database.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="pt-2 border-top" style="border-color: var(--paper-warm) !important;">
                        <a href="{{ Route::has('admin.employees') ? route('admin.employees') : '#' }}"
                            class="btn btn-theme-outline w-100 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                            <i class="fa-solid fa-users-viewfinder"></i> Manage Employees
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Receipt Lookup and Recent Orders -->
        <div class="row g-4">
            <div class="col-12 col-lg-5">
                <div class="card analytic-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 48px; height: 48px; background-color: var(--caramel-tint); color: var(--caramel-deep);">
                            <i class="fa-solid fa-receipt fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold m-0">Receipt Order Lookup</h5>
                            <small style="color: var(--ink-soft);">Inspect transactions or process refunds.</small>
                        </div>
                    </div>

                    <!-- GET / POST Form Navigating to Details View -->
                    <form action="{{ route('receipt.search') }}" method="POST" class="d-flex flex-column gap-2 flex-grow-1">
                        @csrf
                        <div class="position-relative">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </span>
                                <input type="text" name="search" id="receiptSearchInput" class="form-control border-start-0"
                                    placeholder="Enter Order # or Receipt ID..." required autocomplete="off">
                            </div>
                            <!-- Dropdown Suggestions -->
                            <ul class="list-group position-absolute w-100 shadow-sm d-none" 
                                id="receiptSuggestions" 
                                style="top: 100%; left: 0; z-index: 1050; max-height: 200px; overflow-y: auto;">
                            </ul>
                        </div>
                        <div class="mt-auto">
                            <button type="submit"
                                class="btn btn-theme-primary w-100 d-flex align-items-center justify-content-center gap-2">
                                <span>Search Database</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card analytic-card p-4 h-100">
                    <h5 class="fw-bold m-0 mb-3"><i class="fa-solid fa-clock-rotate-left me-2" style="color: var(--stamp);"></i>10 Most Recent Orders</h5>
                    
                    <div class="table-responsive" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-custom m-0">
                            <thead style="position: sticky; top: 0; z-index: 1; background: white;">
                                <tr>
                                    <th>Time</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="recentOrdersTableBody">
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td class="text-muted" style="font-size: 0.85rem;">{{ $order->created_at->diffForHumans() }}</td>
                                        <td class="fw-bold">#{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                        <td class="text-capitalize">{{ $order->name ?? 'Walk-in' }}</td>
                                        <td class="text-end fw-bold">₱{{ number_format($order->total, 2) }}</td>
                                        <td class="text-center">
                                            <!-- Post request inside a form masked as a button or just a button -->
                                            <form action="{{ route('receipt.search') }}" method="POST" class="m-0 p-0">
                                                @csrf
                                                <input type="hidden" name="search" value="{{ $order->id }}">
                                                <button type="submit" class="btn btn-sm btn-theme-outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">View</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">No orders found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- DATE PICKER MODAL -->
<div class="modal fade" id="datePickerModal" tabindex="-1" aria-labelledby="datePickerModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background-color: var(--paper); border-radius: 14px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="datePickerModalLabel"
                    style="color: var(--espresso); font-family: 'Fraunces', serif;">
                    Select Timeframe
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted small mb-3">Choose the specific date/timeframe for the chart.</p>
                <div class="mb-4">
                    <input type="date" class="form-control shadow-sm" id="chartDateInput" style="border: 1px solid var(--paper-warm);">
                    <div class="form-text mt-2" style="color: var(--ink-soft); font-size: 0.8rem;">
                        <i class="fa-solid fa-circle-info me-1"></i>Leave this empty to automatically use the current date (today).
                    </div>
                    <input type="hidden" id="chartPeriodType">
                </div>
                <button type="button" class="btn btn-theme-primary w-100" onclick="applyChartPeriod()">
                    View Chart Data
                </button>
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
    const activePeriod = @json($period);
    const activeChartData = @json($activeChartData);
    
    const categoryJson = @json($categoryData);

    // --- 2. TIME PERFORMANCE LOGIC CONFIGS ---
    function get12HourFormat(hour) {
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hr = hour % 12 || 12;
        return `${hr}${ampm}`;
    }

    let currentLabels = [];
    let currentData = [];

    if (activePeriod === 'daily') {
        currentLabels = Array.from({length: 24}, (_, i) => get12HourFormat(i));
        currentData = Array.from({length: 24}, (_, i) => activeChartData.revenue[i] || 0);
    } else if (activePeriod === 'weekly') {
        currentLabels = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        currentData = Array.from({length: 7}, (_, i) => activeChartData.revenue[i + 1] || 0);
    } else if (activePeriod === 'monthly') {
        currentLabels = Object.keys(activeChartData.revenue).map(wk => `Week ${wk}`);
        currentData = Object.values(activeChartData.revenue);
    } else if (activePeriod === 'overall') {
        currentLabels = Object.keys(activeChartData.revenue).map(yr => `${yr}`);
        currentData = Object.values(activeChartData.revenue);
    } else {
        currentLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        currentData = Array.from({length: 12}, (_, i) => activeChartData.revenue[i + 1] || 0);
    }

    // --- 3. RENDER TREND BAR CANVAS CHART ---
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: currentLabels,
            datasets: [{
                label: 'Gross Revenue (₱)',
                data: currentData,
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
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (₱)'
                    },
                    grid: {
                        color: 'rgba(64, 41, 27, 0.05)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Time Period'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // --- 4. ENGINE RUNNER FOR TIME PERIOD CHANGING ---
    function openDatePickerModal(period, buttonElement) {
        document.getElementById('chartPeriodType').value = period;

        const dateInput = document.getElementById('chartDateInput');
        if (period === 'daily') {
            dateInput.type = 'date';
        } else if (period === 'weekly') {
            dateInput.type = 'week';
        } else if (period === 'monthly') {
            dateInput.type = 'month';
        } else if (period === 'yearly') {
            dateInput.type = 'number';
            dateInput.min = '2000';
            dateInput.max = '2100';
            dateInput.placeholder = 'YYYY';
        }
        
        // Show the date picker modal
        const modal = new bootstrap.Modal(document.getElementById('datePickerModal'));
        modal.show();
    }

    function applyChartPeriod() {
        const period = document.getElementById('chartPeriodType').value;
        let dateVal = document.getElementById('chartDateInput').value;
        
        if (period === 'yearly' && dateVal) {
            // Laravel expects a parsable date like 2024-01-01 for year parsing
            dateVal = dateVal + '-01-01';
        } else if (period === 'monthly' && dateVal) {
            dateVal = dateVal + '-01';
        }

        const url = new URL(window.location.href);
        url.searchParams.set('period', period);
        if (dateVal) {
            url.searchParams.set('date', dateVal);
        } else {
            url.searchParams.delete('date');
        }

        window.location.href = url.toString();
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
                    labels: {
                        boxWidth: 12,
                        padding: 15
                    }
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
        const baseUrl = tableState.apiUrl === '#' ? window.location.href : tableState.apiUrl;
        const url = new URL(baseUrl, window.location.origin);
        url.searchParams.append('search', tableState.search);
        url.searchParams.append('sort_by', tableState.sort_by);
        url.searchParams.append('sort_dir', tableState.sort_dir);
        url.searchParams.append('period', activePeriod);
        @if($date)
        url.searchParams.append('date', '{{ $date }}');
        @endif

        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => renderTableRows(data))
            .catch(error => {
                console.error('Error fetching database metrics:', error);
                const tbody = document.getElementById('popularItemsTableBody');
                tbody.innerHTML = `<tr><td colspan="4" class="text-center text-danger py-4">Failed to load data.</td></tr>`;
            });
    }

    function renderTableRows(items) {
        const tbody = document.getElementById('popularItemsTableBody');
        tbody.innerHTML = '';

        if (items.length === 0) {
            tbody.innerHTML =
                `<tr><td colspan="4" class="text-center text-muted py-4">No matching item entries found in database.</td></tr>`;
            return;
        }

        items.forEach(item => {
            const formattedIncome = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(item.total_income);
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
        dynamicIcon.className = tableState.sort_dir === 'asc' ? "fa-solid fa-sort-up ms-1" :
            "fa-solid fa-sort-down ms-1";

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
    function triggerReportPrint() {
        let title = activePeriod.toUpperCase() + ' SALES REPORT';
        if (activePeriod === 'overall') {
            title = 'OVERALL LIFETIME SALES REPORT';
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

        let totalSum = 0;

        let hasRows = false;
        currentLabels.forEach((label, idx) => {
            const amount = parseFloat(currentData[idx]) || 0;
            if (amount === 0) return; // Skip 0 revenue rows

            hasRows = true;
            totalSum += amount;
            const formatted = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(amount);
            tableHTML += `<tr>
                <td>${label}</td>
                <td class="text-end">${formatted}</td>
            </tr>`;
        });

        if (!hasRows) {
            tableHTML += `<tr>
                <td colspan="2" class="text-center text-muted py-3">No revenue generated for this period.</td>
            </tr>`;
        }

        // Add a total row at the bottom
        const formattedTotal = new Intl.NumberFormat('en-PH', {
            style: 'currency',
            currency: 'PHP'
        }).format(totalSum);

        tableHTML += `
            <tfoot class="table-light fw-bold">
                <tr>
                    <td class="text-end">Total Revenue:</td>
                    <td class="text-end" style="color: var(--caramel-deep);">${formattedTotal}</td>
                </tr>
            </tfoot>
        </tbody></table>`;

        // Add Most Popular Items Table
        const topItems = @json($topItems);
        if (topItems && topItems.length > 0) {
            tableHTML += `
                <h5 class="fw-bold my-3 mt-4">Top 5 Most Popular Items</h5>
                <table class="table table-bordered w-100 align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Item Name</th>
                            <th>Category</th>
                            <th class="text-center">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            topItems.forEach(item => {
                tableHTML += `
                    <tr>
                        <td class="text-capitalize">${item.name}</td>
                        <td>${item.category}</td>
                        <td class="text-center fw-bold">${item.units_sold}</td>
                    </tr>
                `;
            });
            tableHTML += `</tbody></table>`;
        }
        
        document.getElementById('printReportTableContainer').innerHTML = tableHTML;

        // Reveal print container & trigger printer
        const printableArea = document.getElementById('printableArea');
        printableArea.classList.remove('d-none');

        window.print();

        // Re-hide print area after printing
        setTimeout(() => {
            printableArea.classList.add('d-none');
        }, 1000);
    }

    // --- 8. RECENT ORDERS POLLING ---
    function fetchRecentOrders() {
        const url = '{{ route("admin.recent_orders_data") }}';
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(res => {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(orders => {
            const tbody = document.getElementById('recentOrdersTableBody');
            if (!tbody) return;

            if (orders.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-3">No orders found.</td></tr>`;
                return;
            }

            let html = '';
            orders.forEach(order => {
                html += `
                    <tr>
                        <td class="text-muted" style="font-size: 0.85rem;">${order.time_diff}</td>
                        <td class="fw-bold">#${order.padded_id}</td>
                        <td class="text-capitalize">${order.customer_name}</td>
                        <td class="text-end fw-bold">₱${order.total_formatted}</td>
                        <td class="text-center">
                            <form action="{{ route('receipt.search') }}" method="POST" class="m-0 p-0">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="search" value="${order.id}">
                                <button type="submit" class="btn btn-sm btn-theme-outline" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;">View</button>
                            </form>
                        </td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        })
        .catch(err => console.error('Failed to poll recent orders:', err));
    }

    // Poll every 5 seconds
    setInterval(fetchRecentOrders, 5000);

    // --- 9. RECEIPT AUTOCOMPLETE ---
    const searchInput = document.getElementById('receiptSearchInput');
    const suggestionsBox = document.getElementById('receiptSuggestions');
    let autocompleteTimeout;

    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            clearTimeout(autocompleteTimeout);
            const query = this.value.trim();

            if (query.length === 0) {
                suggestionsBox.classList.add('d-none');
                return;
            }

            autocompleteTimeout = setTimeout(() => {
                fetch(`{{ route('receipt.autocomplete') }}?q=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(order => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center cursor-pointer';
                                li.style.cursor = 'pointer';
                                li.innerHTML = `
                                    <div>
                                        <strong class="d-block">#${order.padded_id}</strong>
                                        <small class="text-muted text-capitalize">${order.name}</small>
                                    </div>
                                    <span class="badge bg-light text-dark border">₱${order.total}</span>
                                `;
                                li.addEventListener('click', () => {
                                    searchInput.value = order.id;
                                    suggestionsBox.classList.add('d-none');
                                    // Submit the form automatically when they click a suggestion
                                    searchInput.closest('form').submit();
                                });
                                suggestionsBox.appendChild(li);
                            });
                            suggestionsBox.classList.remove('d-none');
                        } else {
                            suggestionsBox.innerHTML = '<li class="list-group-item text-muted text-center small py-2">No matching orders</li>';
                            suggestionsBox.classList.remove('d-none');
                        }
                    })
                    .catch(err => console.error(err));
            }, 300); // Debounce typing
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.add('d-none');
            }
        });
    }

</script>

{{-- @endsection --}}
