<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
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
            height: 100vh;
            overflow: hidden; /* Prevents whole-page scrolling, keeps scrolling in panels */
        }

        /* Reusable Panel Styling */
        .pos-panel {
            background-color: #ffffff;
            border-radius: 12px;
            height: calc(100vh - 30px);
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
        }

        /* Custom Button Overrides */
        .btn-theme {
            background-color: var(--theme-primary);
            color: #fff;
            border: none;
        }
        .btn-theme:hover {
            background-color: var(--theme-dark);
            color: #fff;
        }
        .btn-outline-theme {
            color: var(--theme-primary);
            border-color: var(--theme-primary);
        }
        .btn-outline-theme:hover, .btn-outline-theme.active {
            background-color: var(--theme-primary);
            color: #fff;
        }

        /* Category & Item Styling */
        .category-scroll {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
        }
        .category-scroll::-webkit-scrollbar { height: 6px; }
        .category-scroll::-webkit-scrollbar-thumb { background: var(--theme-accent-light); border-radius: 4px; }

        .item-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .item-card:hover {
            transform: translateY(-3px);
            border-color: var(--theme-primary);
            box-shadow: 0 4px 8px rgba(108, 78, 49, 0.15);
        }

        /* Order List Styling */
        .order-item {
            border-bottom: 1px solid var(--theme-accent-light);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .quantity-control input {
            width: 40px;
            text-align: center;
            border: none;
            background-color: var(--theme-bg);
            border-radius: 4px;
        }

        .quality-Inputs {
            width: 150px;
            border: none;
        }
    </style>
</head>
<body class="p-3">

    <div class="container-fluid h-100">
        <div class="row g-3 h-100">
            
            <div class="col-lg-6 col-md-12 h-100">
                <div class="pos-panel d-flex flex-column">
                    <h4 class="mb-1 fw-bold">Cafelina POS</h4>
                    <p class="text-muted small mb-3">Select items to add to order</p>

                    <div class="category-scroll mb-4 d-flex gap-2">
                        <button class="btn btn-theme px-4 py-2 rounded-pill"><i class="bi bi-cup-hot me-2"></i>Coffee</button>
                        <button class="btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi bi-cup me-2"></i>Tea</button>
                        <button class="btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi bi-pie-chart me-2"></i>Pizza</button>
                        <button class="btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi bi-egg-fried me-2"></i>Salad</button>
                        <button class="btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi bi-baguette me-2"></i>Pastries</button>
                    </div>

                    <div class="row g-3 overflow-auto flex-grow-1 align-content-start">
                        <div class="col-md-4 col-sm-6">
                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1">Espresso</h6>
                                <p class="text-muted small mb-3">Coffee</p>
                                <div class="mt-auto fw-bold text-primary" style="color: var(--theme-primary) !important;">$3.50</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1">Americano</h6>
                                <p class="text-muted small mb-3">Coffee</p>
                                <div class="mt-auto fw-bold text-primary" style="color: var(--theme-primary) !important;">$4.00</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1">Latte</h6>
                                <p class="text-muted small mb-3">Coffee</p>
                                <div class="mt-auto fw-bold text-primary" style="color: var(--theme-primary) !important;">$4.75</div>
                            </div>
                        </div>
                         <div class="col-md-4 col-sm-6">
                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1">Mocha</h6>
                                <p class="text-muted small mb-3">Coffee</p>
                                <div class="mt-auto fw-bold text-primary" style="color: var(--theme-primary) !important;">$5.25</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Current Order</h5>
                        <button class="btn btn-sm btn-link text-danger text-decoration-none"><i class="bi bi-x"></i> Clear</button>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control bg-light border-0" placeholder="Enter order name...">
                    </div>

                    <div class="flex-grow-1 overflow-auto">
                        <div class="order-item">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-0">Latte</h6>
                                    <small class="text-muted">$4.75 each</small>
                                </div>
                                <button class="btn btn-sm text-danger p-0"><i class="bi bi-trash"></i></button>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group btn-group-sm border rounded">
                                    <button class="btn btn-light bg-white border-0">-</button>
                                    <input class="quality-Inputs" type="text" value="1" readonly>
                                    <button class="btn btn-light bg-white border-0">+</button>
                                </div>
                                <span class="fw-bold" style="color: var(--theme-primary);">$4.75</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Total</h5>
                            <h4 class="mb-0 fw-bold" style="color: var(--theme-primary);">$4.75</h4>
                        </div>
                        <button class="btn btn-theme w-100 py-3 fw-bold fs-6">Complete Order</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel d-flex flex-column" style="background-color: var(--theme-accent-light);">
                    <h5 class="fw-bold mb-3">Active Kitchen Orders</h5>
                    
                    <div class="flex-grow-1 overflow-auto">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-warning text-dark">Preparing</span>
                                    <span class="text-muted small">2 mins ago</span>
                                </div>
                                <h6 class="fw-bold">Order: John D.</h6>
                                <ul class="list-unstyled small mb-3">
                                    <li>1x Latte</li>
                                    <li>2x Espresso</li>
                                </ul>
                                <button class="btn btn-sm btn-success w-100 fw-bold"><i class="bi bi-check2-circle me-1"></i> Mark as Ready</button>
                            </div>
                        </div>

                        <div class="card mb-3 border-0 shadow-sm opacity-75">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-success">Ready</span>
                                    <span class="text-muted small">Just now</span>
                                </div>
                                <h6 class="fw-bold">Order: Sarah W.</h6>
                                <ul class="list-unstyled small mb-3">
                                    <li>1x Americano</li>
                                </ul>
                                <button class="btn btn-sm btn-outline-secondary w-100">Complete & Clear</button>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>