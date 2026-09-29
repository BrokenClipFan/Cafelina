<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelinea Inventory</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --ink-soft: #4A3B32;
            --espresso: #1A0F0A;
            --caramel: #C87941;
            --caramel-tint: #F3E5D8;
            --caramel-deep: #8C4A1D;
            --success: #2A5A3B;
            --danger: #8C2A2A;
        }

        body {
            background-color: var(--paper);
            color: var(--ink);
            font-family: 'Inter', sans-serif;
        }

        .admin-wrapper {
            max-width: 1400px;
            margin: 0 auto;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Space Grotesk', sans-serif;
        }

        .btn-theme {
            background-color: var(--espresso);
            color: #fff;
            border-radius: 8px;
        }
        .btn-theme:hover {
            background-color: var(--ink-soft);
            color: #fff;
        }

        .btn-theme-outline {
            background-color: transparent;
            color: var(--espresso);
            border: 1px solid var(--espresso);
            border-radius: 8px;
        }
        .btn-theme-outline:hover {
            background-color: var(--espresso);
            color: #fff;
        }

        .card-custom {
            background: #fff;
            border: 1px solid var(--paper-warm);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .table-custom th {
            background-color: var(--paper);
            color: var(--ink-soft);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 2px solid var(--paper-warm);
        }
        
        .badge-success { background-color: #d1e7dd; color: #0f5132; }
        .badge-warning { background-color: #fff3cd; color: #664d03; }
        .badge-danger { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Header -->
        <div class="admin-navbar mb-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 52px; height: 52px; background-color: var(--espresso); color: var(--caramel-tint);">
                    <i class="fa-solid fa-boxes-stacked fs-4"></i>
                </div>
                <div>
                    <h1 class="fw-bold fs-3 m-0" style="color: var(--espresso);">Inventory Management</h1>
                    <p class="mb-0 small" style="color: var(--ink-soft);">Track raw materials, restocks, and prices.</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-outline"><i class="fa-solid fa-arrow-left me-2"></i> Dashboard</a>
                <button class="btn btn-theme ms-2" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <i class="fa-solid fa-plus me-2"></i> Add Item
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Low Stock Alerts -->
        @php
            $lowStockItems = $items->filter(function($item) {
                return $item->status == 'Low Stock' || $item->status == 'Out of Stock';
            });
        @endphp

        @if($lowStockItems->count() > 0)
        <div class="alert alert-warning shadow-sm border-warning mb-4">
            <h5 class="alert-heading text-danger fw-bold"><i class="fa-solid fa-triangle-exclamation me-2"></i> Inventory Alerts!</h5>
            <p class="mb-1 text-dark">The following items need your attention:</p>
            <ul class="mb-0 fw-semibold">
                @foreach($lowStockItems as $lsi)
                    <li>{{ $lsi->name }} ({{ $lsi->current_stock }} {{ $lsi->unit }} left) - <span class="text-danger">{{ $lsi->status }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Controls -->
        <div class="card card-custom p-3 mb-4">
            <form method="GET" action="{{ route('admin.inventory') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Search item name..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-select">
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Sort by Name</option>
                        <option value="unit" {{ request('sort_by') == 'unit' ? 'selected' : '' }}>Sort by Unit (kilo, pack, etc)</option>
                        <option value="current_stock" {{ request('sort_by') == 'current_stock' ? 'selected' : '' }}>Sort by Stock Amount</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-2 text-end">
                    <button type="submit" class="btn btn-theme w-100">Filter</button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="card card-custom p-0 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Item Name</th>
                            <th>Status</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Price / Unit</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $item->name }}</td>
                            <td>
                                @if($item->status == 'In Stock')
                                    <span class="badge badge-success">{{ $item->status }}</span>
                                @elseif($item->status == 'Low Stock')
                                    <span class="badge badge-warning">{{ $item->status }}</span>
                                @else
                                    <span class="badge badge-danger">{{ $item->status }}</span>
                                @endif
                            </td>
                            <td>{{ number_format($item->current_stock, 2) }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>₱{{ number_format($item->price_per_unit, 2) }}</td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-outline-success me-1" title="Restock" onclick="openRestockModal({{ $item->id }}, '{{ $item->name }}')">
                                    <i class="fa-solid fa-truck-ramp-box"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info me-1" title="History" onclick="viewHistory({{ $item->id }}, '{{ $item->name }}')">
                                    <i class="fa-solid fa-clock-rotate-left"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary me-1" title="Edit" onclick="openEditModal({{ json_encode($item) }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.inventory.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this item?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No inventory items found. Add one above!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.inventory.store') }}" method="POST" id="bulkAddForm">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add New Items</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Input section -->
                    <div class="border rounded p-3 mb-4 bg-light">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-pen me-2"></i>Item Details</h6>
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label small mb-1">Name</label>
                                <input type="text" id="temp_name" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Stock</label>
                                <input type="number" step="0.01" id="temp_stock" class="form-control form-control-sm" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Unit</label>
                                <input type="text" id="temp_unit" class="form-control form-control-sm" list="unitOptions">
                                <datalist id="unitOptions">
                                    <option value="Kilo">
                                    <option value="Grams">
                                    <option value="Litre">
                                    <option value="mL">
                                    <option value="Pack">
                                    <option value="Pcs">
                                </datalist>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Price/Unit</label>
                                <input type="number" step="0.01" id="temp_price" class="form-control form-control-sm" value="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Max Stock</label>
                                <input type="number" step="0.01" id="temp_max" class="form-control form-control-sm" value="0">
                            </div>
                            <div class="col-12 mt-3 text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary fw-semibold" id="addToListBtn">
                                    <i class="fa-solid fa-arrow-down me-1"></i> Add to List
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Table section -->
                    <h6 class="fw-bold mb-2">Items to Save</h6>
                    <div class="table-responsive border rounded" style="max-height: 250px; overflow-y: auto;">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Name</th>
                                    <th>Stock</th>
                                    <th>Unit</th>
                                    <th>Price</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tempItemsTableBody">
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-3">No items added yet. Fill the details above and click "Add to List".</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Hidden container for form inputs -->
                    <div id="hiddenInputsContainer"></div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme" id="saveItemsBtn" disabled>Save 0 Items</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Current Stock</label>
                            <input type="number" step="0.01" name="current_stock" id="edit_current_stock" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Unit</label>
                            <input type="text" name="unit" id="edit_unit" class="form-control" required list="unitOptions">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Price Per Unit</label>
                            <input type="number" step="0.01" name="price_per_unit" id="edit_price_per_unit" class="form-control" required>
                        </div>
                        <div class="col-12"><hr></div>
                        <div class="col-md-12">
                            <label class="form-label">Max Stock (Used for 25% Low Stock Alert)</label>
                            <input type="number" step="0.01" name="max_stock" id="edit_max_stock" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme">Update Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Restock Modal -->
<div class="modal fade" id="restockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="restockForm" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Restock: <span id="restockItemName" class="text-primary"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount Added <small class="text-muted">(in item unit)</small></label>
                        <input type="number" step="0.01" name="added_amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Cost <small class="text-muted">(₱)</small></label>
                        <input type="number" step="0.01" name="cost" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Remarks / Notes</label>
                        <input type="text" name="remarks" class="form-control" placeholder="Optional">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Confirm Restock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Restock History: <span id="historyItemName" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount Added</th>
                                <th>Total Cost</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- Populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openEditModal(item) {
        document.getElementById('edit_name').value = item.name;
        document.getElementById('edit_current_stock').value = item.current_stock;
        document.getElementById('edit_unit').value = item.unit;
        document.getElementById('edit_price_per_unit').value = item.price_per_unit;
        document.getElementById('edit_max_stock').value = item.max_stock;
        
        let form = document.getElementById('editForm');
        form.action = `/admin/inventory/${item.id}`;
        
        new bootstrap.Modal(document.getElementById('editItemModal')).show();
    }

    function openRestockModal(id, name) {
        document.getElementById('restockItemName').innerText = name;
        let form = document.getElementById('restockForm');
        form.action = `/admin/inventory/${id}/restock`;
        
        new bootstrap.Modal(document.getElementById('restockModal')).show();
    }

    function viewHistory(id, name) {
        document.getElementById('historyItemName').innerText = name;
        document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="4" class="text-center">Loading...</td></tr>';
        
        new bootstrap.Modal(document.getElementById('historyModal')).show();

        fetch(`/admin/inventory/${id}/history`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                if(data.logs.length === 0) {
                    html = '<tr><td colspan="4" class="text-center text-muted">No restock history found.</td></tr>';
                } else {
                    data.logs.forEach(log => {
                        let date = new Date(log.created_at).toLocaleString();
                        html += `
                            <tr>
                                <td>${date}</td>
                                <td><span class="text-success fw-bold">+${log.added_amount}</span></td>
                                <td>₱${log.cost}</td>
                                <td>${log.remarks || '-'}</td>
                            </tr>
                        `;
                    });
                }
                document.getElementById('historyTableBody').innerHTML = html;
            })
            .catch(err => {
                document.getElementById('historyTableBody').innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading history.</td></tr>';
            });
    }

    // Multi-item add logic
    let tempItems = [];
    
    document.getElementById('addToListBtn')?.addEventListener('click', function() {
        const name = document.getElementById('temp_name').value.trim();
        const stock = document.getElementById('temp_stock').value;
        const unit = document.getElementById('temp_unit').value.trim();
        const price = document.getElementById('temp_price').value;
        const max = document.getElementById('temp_max').value;

        if (!name || !unit) {
            alert('Name and Unit are required.');
            return;
        }

        const item = {
            id: Date.now(), // temporary unique ID
            name: name,
            current_stock: stock,
            unit: unit,
            price_per_unit: price,
            max_stock: max
        };

        tempItems.push(item);
        renderTempTable();

        // Clear inputs
        document.getElementById('temp_name').value = '';
        document.getElementById('temp_stock').value = '0';
        document.getElementById('temp_unit').value = '';
        document.getElementById('temp_price').value = '0';
        document.getElementById('temp_max').value = '0';
        
        document.getElementById('temp_name').focus();
    });

    function renderTempTable() {
        const tbody = document.getElementById('tempItemsTableBody');
        const hiddenContainer = document.getElementById('hiddenInputsContainer');
        const saveBtn = document.getElementById('saveItemsBtn');

        tbody.innerHTML = '';
        hiddenContainer.innerHTML = '';

        if (tempItems.length === 0) {
            tbody.innerHTML = '<tr id="emptyRow"><td colspan="5" class="text-center text-muted py-3">No items added yet. Fill the details above and click "Add to List".</td></tr>';
            saveBtn.disabled = true;
            saveBtn.innerText = 'Save 0 Items';
            return;
        }

        saveBtn.disabled = false;
        saveBtn.innerText = `Save ${tempItems.length} Items`;

        tempItems.forEach((item, index) => {
            // Add table row
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${item.name}</td>
                <td>${item.current_stock}</td>
                <td>${item.unit}</td>
                <td>₱${item.price_per_unit}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm text-danger border-0 p-0" onclick="removeTempItem(${item.id})">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);

            // Add hidden inputs for form submission
            hiddenContainer.innerHTML += `
                <input type="hidden" name="items[${index}][name]" value="${item.name}">
                <input type="hidden" name="items[${index}][current_stock]" value="${item.current_stock}">
                <input type="hidden" name="items[${index}][unit]" value="${item.unit}">
                <input type="hidden" name="items[${index}][price_per_unit]" value="${item.price_per_unit}">
                <input type="hidden" name="items[${index}][max_stock]" value="${item.max_stock}">
            `;
        });
    }

    function removeTempItem(id) {
        tempItems = tempItems.filter(item => item.id !== id);
        renderTempTable();
    }

    // Auto-dismiss success alerts after 5 seconds
    document.addEventListener("DOMContentLoaded", function() {
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(successAlert);
                bsAlert.close();
            }, 5000);
        }
    });
</script>
</body>
</html>
