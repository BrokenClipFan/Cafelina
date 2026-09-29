<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cafelina — Live Menu Editor</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           Cafelina editor — same token system as the POS and the
           order board. The two preview panels are built to look like
           true miniatures of those screens, so a change here reads as
           WYSIWYG rather than an abstract "preview" label.
           ============================================================ */
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

        * { box-sizing: border-box; }

        body {
            background-color: var(--paper);
            color: var(--ink);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Fraunces', serif; }

        /* ---------------- Top bar ---------------- */
        .editor-header {
            background: #ffffff;
            box-shadow: 0 2px 10px rgba(64, 41, 27, 0.06);
            padding: 14px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
        }

        .exit-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 999px;
            border: 1.5px solid var(--paper-warm);
            color: var(--ink-soft);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            margin-right: 14px;
        }
        .exit-link:hover { border-color: var(--caramel); color: var(--caramel-deep); }

        .editor-title {
            font-weight: 600;
            font-size: 1.15rem;
            color: var(--caramel-deep);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ---------------- Shell ---------------- */
        .editor-shell {
            flex: 1;
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr;
            gap: 16px;
            padding: 16px;
            min-height: 0;
        }

        @media (max-width: 992px) {
            .editor-shell { grid-template-columns: 1fr; overflow-y: auto; }
            body { overflow: auto; }
        }

        .panel {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(64, 41, 27, 0.06);
            padding: 20px 22px;
            display: flex;
            flex-direction: column;
            min-height: 0;
            min-width: 0;
            position: relative;
        }

        .panel--canvas {
            border: 2px solid var(--caramel);
        }

        .panel-title { font-weight: 600; font-size: 1.15rem; margin: 0 0 2px; }
        .panel-sub { font-size: 0.82rem; color: var(--ink-soft); margin-bottom: 4px; }

        /* Preview panels: miniature, inert copies of the real screens */
        .panel--preview {
            opacity: 0.75;
            pointer-events: none;
        }
        .preview-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: var(--espresso);
            color: #fff;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 4px 9px;
            border-radius: 999px;
        }

        /* ---------------- Category rail ---------------- */
        .category-scroll {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding: 16px 2px 10px;
            min-height: 55px;
        }
        .category-scroll::-webkit-scrollbar { height: 5px; }
        .category-scroll::-webkit-scrollbar-thumb { background: var(--paper-warm); border-radius: 4px; }

        .category-wrapper {
            position: relative;
            flex: 0 0 auto;
            cursor: grab;
        }
        .category-wrapper:active { cursor: grabbing; }

        .categoryButton {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 999px;
            border: 1.5px solid var(--caramel);
            background: transparent;
            color: var(--caramel-deep);
            font-weight: 600;
            font-size: 0.92rem;
            white-space: nowrap;
            cursor: grab;
        }
        .categoryButton.btn-theme {
            background: var(--caramel);
            color: #fff;
            border-color: var(--caramel);
        }

        .delete-cat-badge {
            position: absolute;
            top: -6px;
            right: -6px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--stamp);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 5;
        }
        .category-wrapper:hover .delete-cat-badge { opacity: 1; }

        .add-category-btn {
            flex: 0 0 auto;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 999px;
            border: 2px dashed var(--caramel);
            background: transparent;
            color: var(--caramel-deep);
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
        }
        .add-category-btn:hover { background: var(--caramel-tint); }

        /* ---------------- Item grid ---------------- */
        .item-grid {
            flex: 1;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
            align-content: start;
            padding: 4px 4px 4px 0;
        }

        .categoryItems.d-none { display: none; }

        .item-card {
            position: relative;
            border: 1.5px solid var(--paper-warm);
            border-radius: 12px;
            height: 220px; /* fixed card height */
            background: #fff;
            cursor: grab;
            display: flex;
            flex-direction: column;
            justify-content: flex-end; /* pushes text content to bottom */
            overflow: hidden;
            transition: transform 0.2s ease, border-color 0.2s ease;
        }
        .item-card:hover { 
            border-color: var(--caramel); 
            transform: translateY(-2px);
        }

        /* Background image filling the entire card */
        .item-card-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 1;
        }

        /* Dark gradient overlay + content container */
        .item-card-content {
            position: relative;
            z-index: 2;
            padding: 12px;
            background: linear-gradient(to top, rgb(0, 0, 0) 0%, rgba(0, 0, 0, 0.719) 60%, rgba(0, 0, 0, 0) 100%);
            color: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            width: 100%;
        }

        .item-card .name { 
            font-weight: 600; 
            font-size: 0.98rem; 
            margin-bottom: 2px; 
            line-height: 1.2; 
            color: #ffffff;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 1);
        }

        .item-card .category { 
            font-size: 0.76rem; 
            color: rgba(255, 255, 255, 0.8); 
            margin-bottom: 6px; 
        }

        .item-card .price {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: #F6E7C9; /* warm caramel tint for contrast */
            font-size: 1.05rem;
            text-shadow: 0 1px 3px rgba(0,0,0,0.5);
        }

        .item-actions {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 2;
        }
        .item-card:hover .item-actions { opacity: 1; }

        .icon-circle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #fff;
        }
        .icon-circle-btn.edit-item-btn { background: var(--caramel-deep); }
        .icon-circle-btn.edit-item-btn:hover { background: var(--espresso); }
        .icon-circle-btn.delete-item-btn { background: var(--stamp); }
        .icon-circle-btn.delete-item-btn:hover { background: #7c2e1f; }

        .add-new-card {
            border: 2px dashed var(--caramel);
            background: transparent;
            color: var(--caramel-deep);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 220px;
            border-radius: 12px;
            font-weight: 700;
        }
        .add-new-card:hover { background: var(--caramel-tint); }

        /* SortableJS ghost state */
        .sortable-ghost-cat { opacity: 0.3; }
        .sortable-ghost-item {
            opacity: 0.4;
            background: var(--caramel-tint);
            border: 2px dashed var(--caramel) !important;
        }

        /* ---------------- Image preview boxes ---------------- */
        .image-preview-container {
            width: 100%;
            height: 140px;
            border: 2px dashed var(--paper-warm);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--paper);
            margin-top: 8px;
        }
        .image-preview-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ---------------- Icon picker (inside Add Category modal) ---------------- */
        .icon-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 14px;
            background: var(--paper);
            border-radius: 10px;
            border: 1px solid var(--paper-warm);
        }
        .icon-option {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            cursor: pointer;
            border-radius: 8px;
            border: 2px solid transparent;
            background: #fff;
            color: var(--ink-soft);
            transition: all 0.15s ease;
        }
        .icon-option:hover { background: var(--caramel-tint); }
        .icon-option.active {
            border-color: var(--caramel);
            background: var(--caramel-tint);
            color: var(--caramel-deep);
        }

        /* ---------------- Preview: order panel (mini receipt) ---------------- */
        .mini-order-name {
            background: var(--paper);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 0.85rem;
            color: var(--ink-soft);
            font-style: italic;
            margin-bottom: 14px;
        }
        .mini-receipt-row {
            display: flex;
            align-items: baseline;
            gap: 6px;
            border-bottom: 1px dashed var(--paper-warm);
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .mini-receipt-row .name { font-weight: 600; font-size: 0.92rem; white-space: nowrap; }
        .mini-receipt-row .leader { flex: 1; border-bottom: 2px dotted var(--ink-soft); opacity: 0.35; transform: translateY(-4px); }
        .mini-receipt-row .price { font-family: 'Space Grotesk', sans-serif; font-weight: 700; color: var(--caramel-deep); white-space: nowrap; }

        .mini-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 2px dashed var(--paper-warm);
            padding-top: 12px;
            margin-top: 8px;
        }
        .mini-total-row .label { font-weight: 600; }
        .mini-total-row .value { font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 1.2rem; color: var(--caramel-deep); }

        .mini-submit-btn {
            width: 100%;
            margin-top: 12px;
            border: none;
            background: var(--espresso);
            color: #fff;
            font-weight: 700;
            padding: 11px;
            border-radius: 10px;
            opacity: 0.5;
        }

        /* ---------------- Preview: kitchen panel (mini ticket) ---------------- */
        .panel--kitchen-preview { background: var(--caramel-tint); }

        .mini-ticket {
            background: #fff;
            border-radius: 10px;
            padding: 12px 14px;
        }
        .mini-status-tag {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            background: var(--caramel-deep);
            color: #fff;
            padding: 3px 9px;
            border-radius: 999px;
        }
        .mini-ticket .name { font-weight: 600; font-size: 0.95rem; margin: 8px 0 4px; }
        .mini-ticket .note { font-size: 0.8rem; color: var(--ink-soft); }

        /* ---------------- Modals ---------------- */
        .modal-content { border: none; border-radius: 14px; overflow: hidden; }
        .modal-header {
            background: var(--espresso);
            color: #fff;
            border: none;
        }
        .modal-header .modal-title { font-weight: 600; }
        .modal-body, .modal-footer { background: var(--paper); border: none; }
        .modal-footer { border-top: 1px solid var(--paper-warm) !important; }

        .form-label {
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: var(--ink-soft);
        }
        .form-control, .input-group-text {
            border: none;
            background: #fff;
            box-shadow: 0 1px 4px rgba(64, 41, 27, 0.08);
        }
        .form-control:focus { box-shadow: 0 0 0 2px var(--caramel); }

        .btn-theme {
            background: var(--caramel);
            border: none;
            color: #fff !important;
            font-weight: 700;
        }
        .btn-theme:hover { background: var(--caramel-deep); color: #fff; }
        .btn-outline-secondary {
            border-color: var(--paper-warm);
            color: var(--ink-soft);
        }
        .btn-outline-secondary:hover { background: var(--paper-warm); color: var(--ink); }
        .btn-danger { background: var(--stamp); border: none; }
        .btn-danger:hover { background: #7c2e1f; }
    </style>
</head>
<body>

    <div class="editor-header">
        <div class="d-flex align-items-center">
            <a href="{{ route('home') }}" class="exit-link" title="Exit Editor"><i class="bi bi-x-lg"></i> Exit</a>
            <h5 class="editor-title"><i class="bi bi-magic"></i>Live Menu Editor</h5>
        </div>
        <div>
            <a href="{{ route('admin.inventory') }}" class="btn btn-outline-secondary btn-sm rounded-pill fw-semibold" target="_blank">
                <i class="bi bi-box-seam me-1"></i> Manage Inventory
            </a>
        </div>
    </div>

    <div class="editor-shell">

        <section class="panel panel--canvas">
            <h4 class="panel-title">Menu Canvas</h4>
            <p class="panel-sub">Drag to reorder. Hover a card to edit or delete.</p>

            <div class="category-scroll" id="categorySortable">

                @if($firstCategory)
                    <div class="category-wrapper" data-category="{{ $firstCategory->category }}" data-id="{{ $firstCategory->id }}">
                        <button data-category="{{ $firstCategory->category }}" data-id="{{ $firstCategory->id }}" class="categoryButton btn-theme">
                            <i class="bi {{ $firstCategory->icon }}"></i>
                            {{ $firstCategory->category }}
                        </button>
                        <div class="delete-cat-badge delete-category-btn"><i class="bi bi-x"></i></div>
                    </div>
                @endif

                @foreach($categories as $category)
                <div class="category-wrapper" data-category="{{ $category->category }}" data-id="{{ $category->id }}">
                    <button data-category="{{ $category->category }}" data-id="{{ $category->id }}" class="categoryButton">
                        <i class="bi {{ $category->icon }}"></i>{{ $category->category }}
                    </button>
                    <div class="delete-cat-badge delete-category-btn"><i class="bi bi-x"></i></div>
                </div>
                @endforeach

                <button class="add-category-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="bi bi-plus-lg"></i> Add category
                </button>
            </div>

            @php
                $firstCategory = $firstCategory->category ?? null;
            @endphp

            <div class="item-grid" id="itemSortable">

                @foreach($items as $item)
                    @php
                        $itemImg = asset('storage/' . $item->image_path);
                    @endphp
                    <div class="categoryItems {{ $item->category !== $firstCategory ? 'd-none' : '' }}"
                         data-id="{{ $item->id }}"
                         data-category="{{ $item->category }}"
                         data-image="{{ $itemImg }}">

                        <div class="item-card">
                            <!-- Full-bleed background image -->
                            <img src="{{ $itemImg }}" alt="{{ $item->name }}" class="item-card-img item-image-display">

                            <!-- Content overlay container -->
                            <div class="item-card-content">
                                <h6 class="name item-name-display">{{ $item->name }}</h6>
                                <p class="category">{{ $item->category }}</p>
                                <div class="price item-price-display">₱{{ $item->price }}</div>
                            </div>

                            <!-- Action buttons remain on top on hover -->
                            <div class="item-actions">
                                <button class="icon-circle-btn edit-item-btn"><i class="bi bi-pencil"></i></button>
                                <button class="icon-circle-btn delete-item-btn"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="ignore-drag" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    <div class="add-new-card">
                        <div class="text-center">
                            <i class="bi bi-plus-circle fs-3 d-block mb-1"></i>
                            <span>Add item</span>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <section class="panel panel--preview">
            <div class="preview-badge"><i class="bi bi-eye me-1"></i>Preview</div>
            <h5 class="panel-title mb-3 mt-1">Current order</h5>

            <div class="mini-order-name">Order for…</div>

            <div class="flex-grow-1">
                <div class="mini-receipt-row">
                    <span class="name">Latte</span>
                    <span class="leader"></span>
                    <span class="price">₱4.75</span>
                </div>
            </div>

            <div class="mini-total-row">
                <span class="label">Total</span>
                <span class="value">₱4.75</span>
            </div>
            <button class="mini-submit-btn" disabled>Send to kitchen</button>
        </section>

        <section class="panel panel--preview panel--kitchen-preview">
            <div class="preview-badge"><i class="bi bi-eye me-1"></i>Preview</div>
            <h5 class="panel-title mb-3 mt-1">Active kitchen orders</h5>

            <div class="mini-ticket">
                <span class="mini-status-tag">Preparing</span>
                <div class="name">Order: Example</div>
                <div class="note">Sample items will appear here</div>
            </div>
        </section>

    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Create new category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="categoryForm" action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-4">
                            <label for="categoryName" class="form-label">Category name</label>
                            <input type="text" name="category" class="form-control p-2 rounded" id="categoryName" placeholder="e.g. Signature Coffee" required>
                        </div>

                        <div class="mb-2">
                            <input type="hidden" name="icon" id="selectedIcon" value="bi-cup-hot">
                            <label class="form-label">Select icon</label>
                            <div class="icon-picker" id="iconPicker">
                                <div class="icon-option active" data-icon="bi-cup-hot" data-name="Hot Drinks"><i class="bi bi-cup-hot"></i></div>
                                <div class="icon-option" data-icon="bi-cup" data-name="Non-Coffee"><i class="bi bi-cup"></i></div>
                                <div class="icon-option" data-icon="bi-droplet-half" data-name="Drinks"><i class="bi bi-droplet-half"></i></div>
                                <div class="icon-option" data-icon="bi-snow" data-name="Cold Drinks"><i class="bi bi-snow"></i></div>
                                <div class="icon-option" data-icon="bi-egg-fried" data-name="Food"><i class="bi bi-egg-fried"></i></div>
                                <div class="icon-option" data-icon="bi-brightness-high" data-name="Morning Snacks"><i class="bi bi-brightness-high"></i></div>
                                <div class="icon-option" data-icon="bi-moon-stars" data-name="Midnight Snacks"><i class="bi bi-moon-stars"></i></div>
                                <div class="icon-option" data-icon="bi-emoji-smile" data-name="Happy Meals"><i class="bi bi-emoji-smile"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer p-4">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-theme px-4 py-2">Create category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Create new menu item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="itemForm" action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category" id="categoryInput" value="{{ $firstCategory }}">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item name</label>
                            <input type="text" name="name" class="form-control p-2 rounded" id="itemName" placeholder="e.g. Caramel Macchiato" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemPrice" class="form-label">Price</label>
                            <div class="input-group rounded overflow-hidden">
                                <span class="input-group-text fw-bold">₱</span>
                                <input name="price" type="number" class="form-control" id="itemPrice" placeholder="0.00" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="itemImage" class="form-label">Food Image</label>
                            <input name="image" type="file" class="form-control" id="itemImage" accept="image/*">
                            <div class="image-preview-container mt-2 d-none" id="createImagePreviewBox">
                                <img src="" id="createImagePreview" alt="Image preview">
                            </div>
                        </div>
                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Recipe / Inventory Linked</h6>
                        <p class="small text-muted mb-2">Select the raw materials deducted when this item is sold.</p>
                        <div id="createRecipeContainer">
                            <!-- Recipe rows will be added here via JS -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addCreateRecipeRowBtn">
                            <i class="bi bi-plus"></i> Add Raw Material
                        </button>
                    </div>
                    <div class="modal-footer p-4">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-theme px-4 py-2">Save item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel">Edit menu item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editItemForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editItemId">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label for="editItemName" class="form-label">Item name</label>
                            <input type="text" name="name" class="form-control p-2 rounded" id="editItemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="editItemPrice" class="form-label">Price</label>
                            <div class="input-group rounded overflow-hidden">
                                <span class="input-group-text fw-bold">₱</span>
                                <input name="price" type="number" class="form-control" id="editItemPrice" step="0.01" min="0" required>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="editItemImage" class="form-label">Food Image</label>
                            <input name="image" type="file" class="form-control" id="editItemImage" accept="image/*">
                            <div class="image-preview-container mt-2" id="editImagePreviewBox">
                                <img src="" id="editImagePreview" alt="Current image">
                            </div>
                        </div>
                        <hr class="my-4 text-muted">
                        <h6 class="fw-bold mb-3"><i class="bi bi-box-seam me-2"></i>Recipe / Inventory Linked</h6>
                        <p class="small text-muted mb-2">Select the raw materials deducted when this item is sold.</p>
                        <div id="editRecipeContainer">
                            <!-- Recipe rows will be added here via JS -->
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addEditRecipeRowBtn">
                            <i class="bi bi-plus"></i> Add Raw Material
                        </button>
                    </div>
                    <div class="modal-footer p-4">
                        <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-theme px-4 py-2">Update item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Item Modal -->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteItemModalLabel">Delete item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Are you sure you want to delete <strong id="deleteItemName"></strong>? This action cannot be undone.</p>
                    <form id="deleteItemForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="deleteItemId">
                    </form>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteItemForm" class="btn btn-danger px-4 py-2">Yes, delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteCategoryModalLabel">Delete category</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Are you sure you want to delete <strong id="deleteCategoryName"></strong>? This action cannot be undone.</p>
                    <form id="deleteCategoryForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="id" id="deleteCategoryId">
                    </form>
                </div>
                <div class="modal-footer p-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteCategoryForm" class="btn btn-danger px-4 py-2">Yes, delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const api = {
            get: async (url) => {
                const res = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });
                return res.json();
            },
            post: async (url, data) => {
                const isFormData = data instanceof FormData;
                const headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                };
                if (!isFormData) headers['Content-Type'] = 'application/json';

                const res = await fetch(url, {
                    method: 'POST',
                    headers,
                    body: isFormData ? data : JSON.stringify(data),
                });
                return res.json();
            },
            update: async (url, data) => {
                const isFormData = data instanceof FormData;
                const headers = {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                };
                
                // If uploading file via FormData, standard POST with _method=PUT or POST endpoint is best
                if (isFormData) {
                    data.append('_method', 'PUT');
                } else {
                    headers['Content-Type'] = 'application/json';
                }

                const res = await fetch(url, {
                    method: isFormData ? 'POST' : 'PUT',
                    headers,
                    body: isFormData ? data : JSON.stringify(data),
                });
                return res.json();
            },
            delete: async (url) => {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
                return res.json();
            },
        };
    </script>

    <script>
        // Drag-to-reorder: categories
        const categoryList = document.getElementById('categorySortable');
        new Sortable(categoryList, {
            animation: 150,
            ghostClass: 'sortable-ghost-cat',
            filter: '.add-category-btn',
            onEnd: async function () {
                const orderedCategoryIds = Array.from(categoryList.querySelectorAll('.category-wrapper'))
                    .map(el => el.dataset.id);
                try {
                    await api.post('/categories/reorder', { order: orderedCategoryIds });
                } catch (error) {
                    console.error('Failed to save category order', error);
                }
            }
        });

        // Drag-to-reorder: items within the active category
        const itemList = document.getElementById('itemSortable');
        new Sortable(itemList, {
            animation: 150,
            ghostClass: 'sortable-ghost-item',
            filter: '.ignore-drag',
            onEnd: async function () {
                const activeCategory = document.getElementById('categoryInput').value;
                const orderedItemIds = Array.from(itemList.querySelectorAll(`.categoryItems[data-category="${activeCategory}"]`))
                    .map(el => el.dataset.id);
                try {
                    await api.post('/items/reorder', { order: orderedItemIds, category: activeCategory });
                } catch (error) {
                    console.error('Failed to save item order', error);
                }
            }
        });

        // Live preview for Create Item Image
        document.getElementById('itemImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const box = document.getElementById('createImagePreviewBox');
            const img = document.getElementById('createImagePreview');
            if (file) {
                img.src = URL.createObjectURL(file);
                box.classList.remove('d-none');
            } else {
                box.classList.add('d-none');
            }
        });

        // Live preview for Edit Item Image
        document.getElementById('editItemImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const img = document.getElementById('editImagePreview');
            if (file) {
                img.src = URL.createObjectURL(file);
            }
        });

        // Open + populate the edit-item modal
        document.getElementById('itemSortable').addEventListener('click', function (e) {
            const editBtn = e.target.closest('.edit-item-btn');
            if (!editBtn) return;

            const itemCard = editBtn.closest('.categoryItems');
            const itemId = itemCard.dataset.id;
            const itemName = itemCard.querySelector('.item-name-display').innerText;
            const itemPrice = itemCard.querySelector('.item-price-display').innerText.replace('₱', '').trim();
            const itemImage = itemCard.dataset.image || itemCard.querySelector('.item-image-display')?.src;

            const form = document.getElementById('editItemForm');
            form.action = `/items/${itemId}/update`; // Set dynamic form action

            document.getElementById('editItemId').value = itemId;
            document.getElementById('editItemName').value = itemName.toUpperCase();
            document.getElementById('editItemPrice').value = itemPrice;
            document.getElementById('editImagePreview').src = itemImage;

            const itemData = allItems.find(i => i.id == itemId);
            document.getElementById('editRecipeContainer').innerHTML = '';
            editRecipeIndex = 0;
            if (itemData && itemData.recipes) {
                itemData.recipes.forEach(recipe => {
                    document.getElementById('editRecipeContainer').insertAdjacentHTML('beforeend', createRecipeRow(editRecipeIndex, true, recipe.inventory_item_id, recipe.quantity_used));
                    editRecipeIndex++;
                });
            }

            const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
            editModal.show();
        });

        // Trigger the delete-item modal
        document.getElementById('itemSortable').addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.delete-item-btn');
            if (!deleteBtn) return;

            const itemCard = deleteBtn.closest('.categoryItems');
            const form = document.getElementById('deleteItemForm');
            form.action = `/items/${itemCard.dataset.id}/delete`;

            document.getElementById('deleteItemId').value = itemCard.dataset.id;
            document.getElementById('deleteItemName').innerText = itemCard.querySelector('.item-name-display').innerText;

            new bootstrap.Modal(document.getElementById('deleteItemModal')).show();
        });

        // Trigger the delete-category modal
        document.getElementById('categorySortable').addEventListener('click', function (e) {
            const deleteBtn = e.target.closest('.delete-category-btn');
            if (!deleteBtn) return;

            const category = deleteBtn.closest('.category-wrapper');
            const form = document.getElementById('deleteCategoryForm');
            form.action = `/category/${category.dataset.id}/delete`;

            document.getElementById('deleteCategoryId').value = category.dataset.id;
            document.getElementById('deleteCategoryName').textContent = category.dataset.category;

            new bootstrap.Modal(document.getElementById('deleteCategoryModal')).show();
        });
    </script>

    <script>
        // Icon picker for the Add Category modal
        document.addEventListener('DOMContentLoaded', function () {
            const iconOptions = document.querySelectorAll('.icon-option');
            const selectedIconInput = document.getElementById('selectedIcon');

            iconOptions.forEach(option => {
                option.addEventListener('click', function () {
                    iconOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    selectedIconInput.value = this.getAttribute('data-icon');
                    document.getElementById('categoryName').value = this.dataset.name;
                });
            });
        });

        // Category tabs: set active state, update the add-item hidden input, filter the grid
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.categoryButton');
            const selectedCategoryInput = document.getElementById('categoryInput');
            const items = document.querySelectorAll('#itemSortable .categoryItems');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {
                    buttons.forEach(b => b.classList.remove('btn-theme'));
                    this.classList.add('btn-theme');

                    const selectedCategory = this.dataset.category;
                    selectedCategoryInput.value = selectedCategory;

                    items.forEach(item => {
                        item.classList.toggle('d-none', item.dataset.category !== selectedCategory);
                    });
                });
            });
        });
    </script>
    <datalist id="inventoryDatalist">
        @foreach($inventoryItems as $item)
            <option value="{{ $item->name }} ({{ $item->unit }})"></option>
        @endforeach
    </datalist>
    <script>
        const inventoryItems = @json($inventoryItems);
        const allItems = @json($items);

        function createRecipeRow(index, isEdit = false, selectedId = '', quantity = '') {
            let selectedItem = inventoryItems.find(i => i.id == selectedId);
            let selectedName = selectedItem ? `${selectedItem.name} (${selectedItem.unit})` : '';
            
            return `
                <div class="row g-2 mb-2 align-items-center recipe-row">
                    <div class="col-6">
                        <input type="text" class="form-control form-control-sm recipe-search-input" list="inventoryDatalist" placeholder="Search Material..." value="${selectedName}" required autocomplete="off">
                        <input type="hidden" name="recipes[${index}][inventory_item_id]" class="recipe-hidden-id" value="${selectedId}" required>
                    </div>
                    <div class="col-4">
                        <input type="number" name="recipes[${index}][quantity]" class="form-control form-control-sm" placeholder="Qty" step="0.01" min="0" value="${quantity}" required>
                    </div>
                    <div class="col-2 text-end">
                        <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-recipe-btn"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;
        }

        let createRecipeIndex = 0;
        document.getElementById('addCreateRecipeRowBtn').addEventListener('click', () => {
            const container = document.getElementById('createRecipeContainer');
            container.insertAdjacentHTML('beforeend', createRecipeRow(createRecipeIndex));
            createRecipeIndex++;
        });

        let editRecipeIndex = 0;
        document.getElementById('addEditRecipeRowBtn').addEventListener('click', () => {
            const container = document.getElementById('editRecipeContainer');
            container.insertAdjacentHTML('beforeend', createRecipeRow(editRecipeIndex, true));
            editRecipeIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-recipe-btn')) {
                e.target.closest('.recipe-row').remove();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('recipe-search-input')) {
                const val = e.target.value;
                const hiddenInput = e.target.nextElementSibling;
                const item = inventoryItems.find(i => `${i.name} (${i.unit})` === val);
                if (item) {
                    hiddenInput.value = item.id;
                    e.target.classList.remove('is-invalid');
                } else {
                    hiddenInput.value = '';
                    e.target.classList.add('is-invalid');
                }
            }
        });

        document.getElementById('addItemModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('createRecipeContainer').innerHTML = '';
            createRecipeIndex = 0;
        });
    </script>
</body>
</html>