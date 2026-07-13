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
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Header -->
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
                        <!-- Timeframe Controls integrated directly to chart container -->
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
                        Based on dynamic <code>purchase_items.category</code> data metrics
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Popular Items & Payment Method Tables -->
        <div class="row g-4">
            <div class="col-12 col-lg-7">
                <div class="card analytic-card h-100 p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-4 gap-2">
                        <h5 class="fw-bold m-0"><i class="fa-solid fa-fire me-2" style="color: var(--stamp);"></i>Most Popular Items</h5>
                        <!-- Search Box Filter running AJAX requests -->
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" id="tableFilterInput" onkeyup="handleSearchInput()" class="form-control border-start-0" placeholder="Search database...">
                        </div>
                    </div>
                    
                    <!-- ⬇️ THIS WRAPPER FIXES THE OVERFLOW HEIGHT AND MAKES IT SCROLLABLE ⬇️ -->
                    <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                        <table class="table table-custom m-0" id="popularItemsTable">
                            <!-- Keep header sticky so it stays visible while scrolling -->
                            <thead style="position: sticky; top: 0; bg-color: #ffffff; z-index: 1; background: white;">
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

            <!-- Dynamic Active Employees Roster Widget -->
        <div class="col-12 col-lg-5">
            <div class="card analytic-card h-100 p-4 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold m-0">
                            <i class="fa-solid fa-circle-dot me-2 text-success animate-pulse"></i>Active Employees
                        </h5>
                        <span class="badge bg-success-subtle text-success px-2 py-1 rounded fw-semibold" style="font-size: 0.8rem;">
                            {{ $onlineEmployees->count() }} Online
                        </span>
                    </div>

                    <!-- Employee List Container -->
                    <div class="d-flex flex-column gap-3 overflow-y-auto mb-3" style="max-height: 220px;">
                        @forelse($onlineEmployees as $employee)
                            <div class="d-flex align-items-center justify-content-between p-2 rounded" style="background-color: rgba(234, 217, 183, 0.2); border: 1px solid rgba(234, 217, 183, 0.4);">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Status Indicator Avatar -->
                                    <div class="position-relative">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" 
                                            style="width: 40px; height: 40px; background-color: var(--espresso); font-size: 0.9rem;">
                                            {{ strtoupper(substr($employee->name, 0, 2)) }}
                                        </div>
                                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" title="Online Now"></span>
                                    </div>
                                    <!-- Meta Data -->
                                    <div>
                                        <h6 class="m-0 fw-bold text-capitalize" style="font-size: 0.95rem;">{{ $employee->name }}</h6>
                                        <small class="text-muted" style="font-size: 0.78rem;">
                                            <i class="fa-regular fa-clock me-1"></i>Active Shift
                                        </small>
                                    </div>
                                </div>
                                <span class="badge text-uppercase" style="background-color: var(--caramel-tint); color: var(--caramel-deep); font-size: 0.7rem;">
                                    {{ $employee->role ?? 'Staff' }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa-solid fa-user-slash d-block mb-2 fs-4" style="color: var(--ink-soft);"></i>
                                No employees currently active online.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sticky Footer Action Button -->
                <div class="pt-2 border-top" style="border-color: var(--paper-warm) !important;">
                    <a href="{{ route('admin.employees')}}" class="btn btn-theme-outline w-100 py-2 d-flex align-items-center justify-content-center gap-2 shadow-sm">
                        <i class="fa-solid fa-users-viewfinder"></i> View All Employees
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

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

    // --- 6. INSTANT SEARCH/FILTER RUNNER FOR MOST POPULAR ITEMS ---
    function filterPopularItemsTable() {
        const input = document.getElementById('tableFilterInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('popularItemsTable');
        const tr = table.getElementsByTagName('tr');

        // Loop through all data rows skipping head row 0
        for (let i = 1; i < tr.length; i++) {
            const nameEl = tr[i].querySelector('.target-name');
            const catEl = tr[i].querySelector('.target-category');
            
            if (nameEl || catEl) {
                const nameText = nameEl ? nameEl.textContent || nameEl.innerText : "";
                const catText = catEl ? catEl.textContent || catEl.innerText : "";
                
                if (nameText.toLowerCase().indexOf(filter) > -1 || catText.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // --- 7. FAST CLIENT-SIDE TABLE COLUMN SORTING ---
    let currentSortCol = null;
    let isAscending = true;

    function sortPopularTable(colIndex, type) {
        const table = document.getElementById("popularItemsTable");
        const tbody = table.querySelector("tbody");
        const rows = Array.from(tbody.querySelectorAll("tr.item-row"));
        
        if (rows.length === 0) return;

        // Reset all sort icons first
        document.getElementById("sortIcon2").className = "fa-solid fa-sort ms-1 text-muted";
        document.getElementById("sortIcon3").className = "fa-solid fa-sort ms-1 text-muted";

        // Toggle sort direction if clicking the same column
        if (currentSortCol === colIndex) {
            isAscending = !isAscending;
        } else {
            currentSortCol = colIndex;
            isAscending = false; // Default to highest number first (descending)
        }

        // Apply updated arrow UI
        const activeIcon = document.getElementById(`sortIcon${colIndex}`);
        activeIcon.className = isAscending ? "fa-solid fa-sort-up ms-1" : "fa-solid fa-sort-down ms-1";
        activeIcon.classList.remove("text-muted");

        // Execute Sorting Matrix
        rows.sort((a, b) => {
            const cellA = a.cells[colIndex].getAttribute("data-val");
            const cellB = b.cells[colIndex].getAttribute("data-val");
            
            const valA = parseFloat(cellA) || 0;
            const valB = parseFloat(cellB) || 0;

            return isAscending ? valA - valB : valB - valA;
        });

        // Re-inject sorted rows into the DOM
        rows.forEach(row => tbody.appendChild(row));
    }

    let tableState = {
        search: '',
        sort_by: 'units_sold',
        sort_dir: 'desc',
        apiUrl: "{{ route('admin.popular_items_data') }}"
    };

    let searchDebounceTimeout = null;

    // Trigger initial table population on dashboard load
    document.addEventListener("DOMContentLoaded", function() {
        fetchPopularItems();
    });

    // Fetch matching datasets from your database storage asynchronously
    function fetchPopularItems() {
        const url = new URL(tableState.apiUrl);
        url.searchParams.append('search', tableState.search);
        url.searchParams.append('sort_by', tableState.sort_by);
        url.searchParams.append('sort_dir', tableState.sort_dir);

        fetch(url)
            .then(response => response.json())
            .then(data => renderTableRows(data))
            .catch(error => console.error('Error fetching database sorting metrics:', error));
    }

    // Render data nodes instantly inside table markup body
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

    // Handle interactive sorting click triggers
    function handleBackendSort(column) {
        // Reset sorting chevron icons
        document.getElementById('sortIcon_units_sold').className = "fa-solid fa-sort ms-1 text-muted";
        document.getElementById('sortIcon_total_income').className = "fa-solid fa-sort ms-1 text-muted";

        if (tableState.sort_by === column) {
            // Toggle direction if clicking current sorted column
            tableState.sort_dir = tableState.sort_dir === 'desc' ? 'asc' : 'desc';
        } else {
            tableState.sort_by = column;
            tableState.sort_dir = 'desc'; // Default back to highest value first
        }

        // Apply updated chevron indicator icon
        const dynamicIcon = document.getElementById(`sortIcon_${column}`);
        dynamicIcon.className = tableState.sort_dir === 'asc' ? "fa-solid fa-sort-up ms-1" : "fa-solid fa-sort-down ms-1";

        fetchPopularItems();
    }

    // Debounce search typing input so it won't bombard the database on every key stroke
    function handleSearchInput() {
        clearTimeout(searchDebounceTimeout);
        searchDebounceTimeout = setTimeout(() => {
            tableState.search = document.getElementById('tableFilterInput').value;
            fetchPopularItems();
        }, 300); // 300ms wait threshold
    }
</script>

{{-- @endsection --}}