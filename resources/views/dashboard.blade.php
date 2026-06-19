<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina - Analytics</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Custom Cafelina Theme */
        :root {
            --theme-bg: #FFEAC5;
            --theme-accent-light: #FFDBB5;
            --theme-primary: #6C4E31;
            --theme-dark: #603F26;
        }

        body {
            background-color: var(--theme-bg);
            color: var(--theme-dark);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Dashboard Header */
        .dashboard-header {
            background-color: #ffffff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 15px 30px;
            margin-bottom: 30px;
        }

        /* KPI Cards */
        .kpi-card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: none;
            display: flex;
            align-items: center;
            transition: transform 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-5px);
        }

        .kpi-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-right: 20px;
            background-color: rgba(108, 78, 49, 0.1);
            color: var(--theme-primary);
        }

        /* Chart Panels */
        .chart-panel {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            height: 100%;
        }

        /* Tables */
        .table-custom {
            margin-bottom: 0;
        }
        .table-custom thead th {
            background-color: var(--theme-accent-light);
            color: var(--theme-dark);
            border-bottom: none;
            font-weight: 600;
        }
        .table-custom tbody tr {
            transition: background-color 0.2s;
        }
        .table-custom tbody tr:hover {
            background-color: rgba(255, 234, 197, 0.3);
        }
        .table-custom td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="dashboard-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary me-3 rounded-circle" title="Back to POS"><i class="bi bi-arrow-left"></i></button>
            <h3 class="mb-0 fw-bold" style="color: var(--theme-primary);"><i class="bi bi-graph-up-arrow me-2"></i>Sales Analytics</h3>
        </div>
        <div class="d-flex gap-2">
            <select class="form-select bg-light border-0 w-auto">
                <option>Today</option>
                <option>Yesterday</option>
                <option>Last 7 Days</option>
                <option>This Month</option>
            </select>
            <button class="btn btn-primary" style="background-color: var(--theme-primary); border:none;"><i class="bi bi-download"></i></button>
        </div>
    </header>

    <div class="container-fluid px-4 pb-5">
        
        <!-- ROW 1: KPI Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="bi bi-currency-dollar"></i></div>
                    <div>
                        <p class="text-muted small mb-1 fw-bold text-uppercase">Gross Sales</p>
                        <h3 class="mb-0 fw-bold">$1,245.50</h3>
                        <small class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i> 12% vs yesterday</small>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="bi bi-receipt"></i></div>
                    <div>
                        <p class="text-muted small mb-1 fw-bold text-uppercase">Total Orders</p>
                        <h3 class="mb-0 fw-bold">142</h3>
                        <small class="text-success fw-bold"><i class="bi bi-arrow-up-short"></i> 5% vs yesterday</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="bi bi-cup-hot"></i></div>
                    <div>
                        <p class="text-muted small mb-1 fw-bold text-uppercase">Items Sold</p>
                        <h3 class="mb-0 fw-bold">310</h3>
                        <small class="text-danger fw-bold"><i class="bi bi-arrow-down-short"></i> 2% vs yesterday</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="kpi-icon"><i class="bi bi-star"></i></div>
                    <div>
                        <p class="text-muted small mb-1 fw-bold text-uppercase">Top Item</p>
                        <h4 class="mb-0 fw-bold">Iced Latte</h4>
                        <small class="text-muted">45 units sold</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 2: Charts -->
        <div class="row g-4 mb-4">
            <!-- Hourly Sales Line Chart -->
            <div class="col-lg-8">
                <div class="chart-panel">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Sales Over Time (Today)</h5>
                    </div>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>

            <!-- Categories Doughnut Chart -->
            <div class="col-lg-4">
                <div class="chart-panel">
                    <h5 class="fw-bold mb-4">Sales by Category</h5>
                    <div class="d-flex justify-content-center">
                        <canvas id="categoryChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ROW 3: Data Table -->
        <div class="row">
            <div class="col-12">
                <div class="chart-panel">
                    <h5 class="fw-bold mb-4">Top Selling Items</h5>
                    <div class="table-responsive">
                        <table class="table table-custom align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Item Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Quantity Sold</th>
                                    <th>Total Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="fw-bold">Iced Latte</td>
                                    <td><span class="badge bg-light text-dark border">Coffee</span></td>
                                    <td>$4.75</td>
                                    <td>45</td>
                                    <td class="fw-bold" style="color: var(--theme-primary);">$213.75</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td class="fw-bold">Americano</td>
                                    <td><span class="badge bg-light text-dark border">Coffee</span></td>
                                    <td>$4.00</td>
                                    <td>38</td>
                                    <td class="fw-bold" style="color: var(--theme-primary);">$152.00</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td class="fw-bold">Butter Croissant</td>
                                    <td><span class="badge bg-light text-dark border">Pastries</span></td>
                                    <td>$3.50</td>
                                    <td>24</td>
                                    <td class="fw-bold" style="color: var(--theme-primary);">$84.00</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td class="fw-bold">Matcha Green Tea</td>
                                    <td><span class="badge bg-light text-dark border">Tea</span></td>
                                    <td>$5.00</td>
                                    <td>18</td>
                                    <td class="fw-bold" style="color: var(--theme-primary);">$90.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Chart.js Initialization -->
    <script>
        // Use your theme colors for the charts
        const themePrimary = '#6C4E31';
        const themeDark = '#603F26';
        const themeAccent = '#FFDBB5';
        const themeBg = '#FFEAC5';

        // 1. Sales Line Chart
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: ['8 AM', '9 AM', '10 AM', '11 AM', '12 PM', '1 PM', '2 PM', '3 PM'],
                datasets: [{
                    label: 'Revenue ($)',
                    data: [45, 90, 150, 210, 320, 240, 110, 80],
                    borderColor: themePrimary,
                    backgroundColor: 'rgba(108, 78, 49, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4, // smooth curves
                    pointBackgroundColor: themeDark
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Category Doughnut Chart
        const ctxCat = document.getElementById('categoryChart').getContext('2d');
        new Chart(ctxCat, {
            type: 'doughnut',
            data: {
                labels: ['Coffee', 'Tea', 'Pastries', 'Food'],
                datasets: [{
                    data: [55, 15, 20, 10],
                    backgroundColor: [
                        themePrimary,
                        themeDark,
                        themeAccent,
                        '#e0c097' // complementary earth tone
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
                        labels: { padding: 20, usePointStyle: true }
                    }
                },
                cutout: '70%'
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>