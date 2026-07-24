<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

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
    }

    body {
        background-color: var(--paper);
        color: var(--ink);
        font-family: 'Inter', sans-serif;
    }

    h1, h5 {
        font-family: 'Fraunces', serif;
    }

    .analytic-card {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(64, 41, 27, 0.04);
        width: 100%;
    }

    .btn-theme-outline {
        background-color: #ffffff;
        color: var(--espresso);
        border: 1.5px solid var(--paper-warm);
        border-radius: 8px;
        padding: 0.4rem 0.8rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-theme-outline:hover {
        background-color: var(--caramel-tint);
        border-color: var(--caramel);
        color: var(--caramel-deep);
    }

    .btn-toggle-active {
        background-color: var(--espresso) !important;
        color: #ffffff !important;
        border-color: var(--espresso) !important;
    }

    .btn-receipt {
        background-color: var(--caramel-tint);
        color: var(--caramel-deep);
        border: 1px solid var(--paper-warm);
        font-weight: 600;
        font-size: 0.8rem;
        border-radius: 6px;
        padding: 0.25rem 0.6rem;
    }
    .btn-receipt:hover {
        background-color: var(--caramel);
        color: #ffffff;
    }

    .table-container-constrained {
        max-height: 320px;
        overflow-y: auto;
        border-radius: 8px;
    }

    .table-custom {
        table-layout: fixed;
        width: 100%;
        margin-bottom: 0;
    }

    .table-custom th {
        color: var(--ink-soft);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--paper-warm);
        font-weight: 700;
        padding: 0.75rem 1rem;
        position: sticky;
        top: 0;
        background-color: #ffffff;
        z-index: 2;
    }

    .table-custom td {
        border-bottom: 1px solid var(--paper-warm);
        padding: 0.75rem 1rem;
        vertical-align: middle;
        word-wrap: break-word;
    }

    .table-container-constrained::-webkit-scrollbar {
        width: 6px;
    }
    .table-container-constrained::-webkit-scrollbar-track {
        background: var(--paper);
    }
    .table-container-constrained::-webkit-scrollbar-thumb {
        background: var(--paper-warm);
        border-radius: 4px;
    }

    .day-badge {
        background-color: var(--paper);
        color: var(--espresso);
        border: 1px solid var(--paper-warm);
        font-size: 0.72rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    @media print {
        .no-print { display: none !important; }
        body { background-color: #ffffff; }
        .analytic-card { border: none !important; box-shadow: none !important; }
        .table-container-constrained { max-height: none !important; overflow: visible !important; }
    }
</style>

<div class="container-fluid p-3 p-md-4">
    <div class="d-flex flex-column gap-3">
        
        <!-- Top Navigation Bar -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 no-print">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.employees') }}" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="fw-bold m-0" style="font-size: 1.5rem;">Employee Performance Report</h1>
                    <p class="mb-0 small text-muted">Individual sales breakdown, order logs, and shift schedule.</p>
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <button onclick="window.print()" class="btn btn-theme-outline shadow-sm">
                    <i class="fa-solid fa-print me-1"></i> Print Summary
                </button>
            </div>
        </div>

        <!-- Header Metrics & Profile -->
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <div class="card analytic-card p-3 h-100 d-flex flex-row align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm flex-shrink-0"
                         style="width: 52px; height: 52px; background-color: var(--espresso); font-size: 1.2rem;">
                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                    </div>
                    <div class="overflow-hidden">
                        <h6 class="fw-bold m-0 text-truncate">{{ $employee->name }}</h6>
                        <p class="text-muted small m-0 mb-1 text-truncate" style="font-size: 0.8rem;">{{ $employee->email }}</p>
                        @if($employee->online_status)
                            <span class="badge bg-success-subtle text-success border-0 px-2 py-1" style="font-size: 0.68rem;">
                                <i class="fa-solid fa-circle me-1" style="font-size: 0.4rem;"></i> On Shift
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-muted border-0 px-2 py-1" style="font-size: 0.68rem;">Off Duty</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="card analytic-card p-3 h-100 d-flex justify-content-center">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Total Revenue Generated</small>
                    <h3 class="fw-bold m-0 text-success" style="font-family: 'Space Grotesk', sans-serif;">
                        ₱{{ number_format($employee->orders->flatMap->items->sum(fn($i) => $i->price * $i->count), 2) }}
                    </h3>
                </div>
            </div>

            <div class="col-6 col-md-4">
                <div class="card analytic-card p-3 h-100 d-flex justify-content-center">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Orders Processed</small>
                    <h3 class="fw-bold m-0" style="color: var(--espresso); font-family: 'Space Grotesk', sans-serif;">
                        {{ number_format($employee->orders->count()) }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Sales Analytics Chart Card -->
        <div class="card analytic-card p-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
                <div>
                    <h5 class="fw-bold m-0 fs-6"><i class="fa-solid fa-chart-column me-2" style="color: var(--caramel-deep);"></i>Sales Revenue Performance</h5>
                    <small class="text-muted">Track revenue contribution trends over time</small>
                </div>
                
                <div class="btn-group btn-group-sm no-print" role="group">
                    <button type="button" class="btn btn-theme-outline btn-toggle-active" id="btnDaily" onclick="switchChart('daily')">Daily (7 Days)</button>
                    <button type="button" class="btn btn-theme-outline" id="btnMonthly" onclick="switchChart('monthly')">Monthly (12 Months)</button>
                </div>
            </div>

            <div style="height: 240px; position: relative;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Purchase History Card -->
        <div class="card analytic-card p-3">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2 no-print">
                <h5 class="fw-bold m-0 fs-6"><i class="fa-solid fa-receipt me-2" style="color: var(--caramel-deep);"></i>Purchase History</h5>
                
                <div class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm" style="max-width: 240px;">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                        <input type="text" id="salesSearchInput" onkeyup="filterSalesTable()" class="form-control border-start-0" placeholder="Search purchases...">
                    </div>
                </div>
            </div>

            <div class="table-container-constrained">
                <table class="table table-custom align-middle" id="salesHistoryTable" style="font-size: 0.88rem;">
                    <thead>
                        <tr>
                            <th style="width: 15%;">Purchase ID</th>
                            <th style="width: 25%;">Date & Time</th>
                            <th style="width: 20%;" class="text-center">Items Handled</th>
                            <th style="width: 15%;" class="text-center">Total Qty</th>
                            <th style="width: 15%;" class="text-end">Total Amount</th>
                            <th style="width: 10%;" class="text-center no-print">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->orders as $purchase)
                            @php
                                $orderTotal = $purchase->items->sum(fn($item) => $item->price * $item->count);
                                $totalCount = $purchase->items->sum('count');
                            @endphp
                            <tr class="sales-data-row">
                                <td class="fw-bold target-order-id">#{{ $purchase->id }}</td>
                                <td class="text-muted" style="font-size: 0.83rem;">
                                    {{ \Carbon\Carbon::parse($purchase->created_at)->format('M d, Y • h:i A') }}
                                </td>
                                <td class="text-center fw-medium">
                                    {{ $purchase->items->count() }} line items
                                </td>
                                <td class="text-center fw-bold">
                                    {{ $totalCount }}
                                </td>
                                <td class="text-end fw-bold text-success">
                                    ₱{{ number_format($orderTotal, 2) }}
                                </td>
                                <td class="text-center no-print">
                                    <!-- Direct Navigation to Receipt Search Page -->
                                    <form action="{{ route('receipt.search') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="search" value="{{ $purchase->id }}">
                                        <button type="submit" class="btn btn-receipt shadow-sm">
                                            <i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Receipt
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-folder-open fs-4 d-block mb-2 opacity-50"></i>
                                    No purchase transactions recorded for this employee yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Work Schedule Table Card -->
        <div class="card analytic-card p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold m-0 fs-6"><i class="fa-solid fa-calendar-days me-2" style="color: var(--caramel-deep);"></i>Assigned Work Schedule</h5>
                <span class="badge bg-secondary-subtle text-dark px-2 py-1" style="font-size: 0.75rem;">
                    {{ $employee->schedules->count() }} Active Shift Slot(s)
                </span>
            </div>

            <div class="table-container-constrained">
                <table class="table table-custom align-middle" style="font-size: 0.88rem;">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Station / Role</th>
                            <th style="width: 35%;">Working Days</th>
                            <th style="width: 25%;" class="text-center">Shift Hours</th>
                            <th style="width: 15%;" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employee->schedules as $sched)
                            @php
                                $days = is_array($sched->days) ? $sched->days : json_decode($sched->days, true);
                            @endphp
                            <tr>
                                <td class="fw-bold" style="color: var(--espresso);">
                                    <i class="fa-solid fa-user-tag me-1 text-muted" style="font-size: 0.8rem;"></i>
                                    {{ $sched->station_role ?? 'General Staff' }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 flex-wrap">
                                        @foreach($days ?? [] as $day)
                                            <span class="day-badge">{{ $day }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center fw-medium">
                                    <i class="fa-regular fa-clock me-1 text-muted"></i>
                                    {{ \Carbon\Carbon::parse($sched->start_time)->format('h:i A') }} – {{ \Carbon\Carbon::parse($sched->end_time)->format('h:i A') }}
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-subtle text-success border-0 px-2 py-1" style="font-size: 0.72rem;">
                                        {{ $sched->status ?? 'Scheduled' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-regular fa-calendar-xmark fs-4 d-block mb-2 opacity-50"></i>
                                    No work schedules assigned to this employee.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    // Data injected from Controller
    const dailyLabels = @json($dailyLabels);
    const dailyData = @json($dailyData);
    const monthlyLabels = @json($monthlyLabels);
    const monthlyData = @json($monthlyData);

    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Initialize Chart.js Instance
    let salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Revenue (₱)',
                data: dailyData,
                backgroundColor: '#C6863B',
                hoverBackgroundColor: '#A4692A',
                borderRadius: 6,
                maxBarThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return ' Revenue: ₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#EAD9B7', borderDash: [4, 4] },
                    ticks: {
                        color: '#6B5647',
                        callback: function(value) { return '₱' + value; }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#6B5647' }
                }
            }
        }
    });

    // Toggle Chart Mode (Daily / Monthly)
    function switchChart(mode) {
        const btnDaily = document.getElementById('btnDaily');
        const btnMonthly = document.getElementById('btnMonthly');

        if (mode === 'daily') {
            btnDaily.classList.add('btn-toggle-active');
            btnMonthly.classList.remove('btn-toggle-active');
            salesChart.data.labels = dailyLabels;
            salesChart.data.datasets[0].data = dailyData;
        } else {
            btnMonthly.classList.add('btn-toggle-active');
            btnDaily.classList.remove('btn-toggle-active');
            salesChart.data.labels = monthlyLabels;
            salesChart.data.datasets[0].data = monthlyData;
        }
        salesChart.update();
    }

    // Purchase History Search Filter
    function filterSalesTable() {
        const input = document.getElementById('salesSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('salesHistoryTable');
        const tr = table.getElementsByClassName('sales-data-row');

        for (let i = 0; i < tr.length; i++) {
            const orderEl = tr[i].querySelector('.target-order-id');
            if (orderEl) {
                const text = orderEl.textContent || orderEl.innerText;
                tr[i].style.display = text.toLowerCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }
</script>