<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina - Live Menu Editor</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    
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
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Editor Top Bar */
        .editor-header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            z-index: 10;
        }

        /* Reusable Panel Styling */
        .pos-panel {
            background-color: #ffffff;
            border-radius: 12px;
            height: calc(100vh - 90px); /* Adjusted for editor header */
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            position: relative;
        }

        /* Preview State for Grids 2 & 3 */
        .preview-panel {
            opacity: 0.7;
            pointer-events: none; /* Prevents clicking on the preview areas */
        }
        .preview-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--theme-dark);
            color: #fff;
            font-size: 0.7rem;
            padding: 4px 8px;
            border-radius: 4px;
            z-index: 5;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Custom Buttons */
        .btn-theme {
            background-color: var(--theme-primary);
            color: #fff !important;
            border: none;
        }
        .btn-theme:hover { background-color: var(--theme-dark); color: #fff; }
        .btn-outline-theme {
            color: var(--theme-primary);
            border-color: var(--theme-primary);
        }
        .btn-outline-theme:hover { background-color: var(--theme-primary); color: #fff; }

        /* Category Scroll & Editing */
        .category-scroll {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 10px;
            min-height: 55px;
        }
        .category-wrapper {
            display: inline-block;
            position: relative;
            cursor: grab;
        }
        .category-wrapper:active { cursor: grabbing; }
        
        /* Delete badge for categories */
        .delete-cat-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #dc3545;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 5;
        }
        .category-wrapper:hover .delete-cat-badge { opacity: 1; }

        /* Item Card & Editing Overlays */
        .item-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            transition: all 0.2s ease;
            position: relative;
            cursor: grab;
        }
        .item-card:active { cursor: grabbing; }
        
        /* Edit Actions Overlay */
        .item-actions {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 2;
        }
        .item-card:hover .item-actions { opacity: 1; }
        .item-card:hover { border-color: var(--theme-primary); }

        /* Add New Card */
        .add-new-card {
            border: 2px dashed var(--theme-primary);
            background-color: transparent;
            color: var(--theme-primary);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
        }
        .add-new-card:hover {
            background-color: rgba(108, 78, 49, 0.05);
        }

        /* Sortable Ghost Styles */
        .sortable-ghost-cat { opacity: 0.3; }
        .sortable-ghost-item {
            opacity: 0.4;
            background-color: var(--theme-accent-light);
            border: 2px dashed var(--theme-primary);
        }

                /* Icon Picker Styles */
        .icon-option {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            cursor: pointer;
            border-radius: 8px;
            border: 2px solid transparent;
            transition: all 0.2s;
            background-color: #f8f9fa;
            color: var(--theme-dark);
        }

        .icon-option:hover {
            background-color: var(--theme-accent-light);
        }

        .icon-option.active {
            border-color: var(--theme-primary);
            background-color: var(--theme-accent-light);
            color: var(--theme-primary);
        }

        .border-dashed {
            border-style: dashed !important;
        }
    </style>
</head>
<body>

    <div class="editor-header p-3 d-flex justify-content-between align-items-center mb-2">
        <div class="d-flex align-items-center">
            <button class="btn btn-outline-secondary me-3 btn-sm" title="Exit Editor"><i class="bi bi-x-lg"></i> Exit</button>
            <h5 class="mb-0 fw-bold" style="color: var(--theme-primary);"><i class="bi bi-magic me-2"></i>Live Menu Editor</h5>
        </div>
        <button class="btn btn-success fw-bold px-4"><i class="bi bi-check2-all me-2"></i>Publish Changes</button>
    </div>

    <div class="container-fluid flex-grow-1 px-3 pb-3">
        <div class="row g-3 h-100">
            
            <div class="col-lg-6 col-md-12 h-100">
                <div class="pos-panel d-flex flex-column border border-2" style="border-color: var(--theme-primary) !important;">
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1 fw-bold">Menu Canvas</h4>
                            <p class="text-muted small mb-0">Drag to reorder. Hover to edit or delete.</p>
                        </div>
                    </div>

                    <div class="category-scroll mb-4 pt-4 d-flex gap-2" id="categorySortable">
                        
                        @if($firstCategory)
                            <div class="category-wrapper" data-category="{{ $firstCategory->category }}">
                                <button
                                    data-category="{{ $firstCategory->category }}"
                                    class="categoryButton btn btn-theme btn-outline-theme px-4 py-2 rounded-pill">

                                    <i class="bi {{ $firstCategory->icon }} me-1 opacity-50"></i>
                                    {{ $firstCategory->category }}

                                </button>

                                <div class="delete-cat-badge">
                                    <i class="bi bi-x"></i>
                                </div>
                            </div>
                        @endif

                        @foreach($categories as $category)
                        <div class="category-wrapper" data-category="{{$category->category}}">
                            
                            <button data-category="{{$category->category}}" class="categoryButton btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi {{ $category->icon }} me-1 opacity-50"></i>{{ $category->category }}</button>
                            <div class="delete-cat-badge"><i class="bi bi-x"></i></div>
                        </div>
                        @endforeach

                        <button class="btn btn-light text-primary border-dashed px-3 py-2 rounded-pill fw-bold" 
                              style="border: 2px dashed var(--theme-primary);"
                              data-bs-toggle="modal" 
                              data-bs-target="#addCategoryModal">
                          <i class="bi bi-plus-lg me-1"></i> Add Category
                      </button>

                    </div>

                    @php
                        $firstCategory = $firstCategory->category ?? null;
                    @endphp

                    <div class="row g-3 overflow-auto flex-grow-1 align-content-start" id="itemSortable">

                    @foreach($items as $item)
                        <div class="col-md-4 col-sm-6 categoryItems
                            {{ $item->category !== $firstCategory ? 'd-none' : '' }}"
                            data-id="item-1"
                            data-category="{{ $item->category }}">

                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1">{{ $item->name }}</h6>
                                <p class="text-muted small mb-3">Coffee</p>

                                <div class="mt-auto fw-bold text-primary"
                                    style="color: var(--theme-primary) !important;">
                                    $3.50
                                </div>

                                <div class="item-actions shadow-sm">
                                    <button class="btn btn-sm btn-primary rounded-circle">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger rounded-circle">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach

                        <div class="col-md-4 col-sm-6 ignore-drag" 
                          data-bs-toggle="modal" 
                          data-bs-target="#addItemModal">
                        <div class="card item-card add-new-card h-100 p-3">
                            <div class="text-center">
                                <i class="bi bi-plus-circle fs-3 d-block mb-1"></i>
                                <span class="fw-bold">Add Item</span>
                            </div>
                        </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel preview-panel d-flex flex-column">
                    <div class="preview-badge"><i class="bi bi-eye me-1"></i>Preview</div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                        <h5 class="fw-bold mb-0">Current Order</h5>
                        <span class="text-muted small">Clear</span>
                    </div>
                    <div class="mb-3">
                        <div class="form-control bg-light border-0 text-muted">Enter order name...</div>
                    </div>
                    <div class="flex-grow-1 overflow-auto">
                        <div class="border-bottom border-light pb-2 mb-2">
                            <h6 class="mb-0">Latte</h6>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>1x</span>
                                <span>$4.75</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Total</h5>
                            <h4 class="mb-0 fw-bold" style="color: var(--theme-primary);">$4.75</h4>
                        </div>
                        <button class="btn btn-secondary w-100 py-3 fw-bold fs-6 opacity-50" disabled>Complete Order</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel preview-panel d-flex flex-column" style="background-color: var(--theme-accent-light);">
                    <div class="preview-badge"><i class="bi bi-eye me-1"></i>Preview</div>
                    <h5 class="fw-bold mb-3 mt-2">Active Kitchen Orders</h5>
                    <div class="flex-grow-1">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-warning text-dark">Preparing</span>
                                </div>
                                <h6 class="fw-bold">Order: Example</h6>
                                <div class="text-muted small">Sample items will appear here</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow">
              <div class="modal-header text-white" style="background-color: var(--theme-primary); border-radius: 12px 12px 0 0;">
                  <h5 class="modal-title fw-bold" id="addCategoryModalLabel">Create New Category</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              {{-- Add Category --}}
              <div class="modal-body p-4" style="background-color: #fffaf1;">
                  <form id="categoryForm" action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="categoryName" class="form-label fw-bold small text-uppercase">Category Name</label>
                        <input type="text" name="category" class="form-control border-0 shadow-sm" id="categoryName" placeholder="e.g. Signature Coffee" required>
                    </div>

                    <div class="mb-3">
                      <input type="hidden" name="icon" id="selectedIcon" value="bi-cup-hot">
                      <label class="form-label fw-bold small text-uppercase">Select Icon</label>
                      <div class="d-flex flex-wrap gap-2 p-3 bg-white rounded shadow-sm border" id="iconPicker">
                          <div class="icon-option active" data-icon="bi-cup-hot"><i class="bi bi-cup-hot"></i></div>
                          <div class="icon-option" data-icon="bi-cup"><i class="bi bi-cup"></i></div>
                          <div class="icon-option" data-icon="bi-droplet-half"><i class="bi bi-droplet-half"></i></div>
                          <div class="icon-option" data-icon="bi-snow"><i class="bi bi-snow"></i></div>
                          <div class="icon-option" data-icon="bi-cake2"><i class="bi bi-cake2"></i></div>
                          <div class="icon-option" data-icon="bi-baguette"><i class="bi bi-baguette"></i></div>
                          <div class="icon-option" data-icon="bi-egg-fried"><i class="bi bi-egg-fried"></i></div>
                          <div class="icon-option" data-icon="bi-pie-chart"><i class="bi bi-pie-chart"></i></div>
                          <div class="icon-option" data-icon="bi-brightness-high"><i class="bi bi-brightness-high"></i></div>
                          <div class="icon-option" data-icon="bi-moon-stars"><i class="bi bi-moon-stars"></i></div>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer border-0 p-4" style="background-color: #fffaf1; border-radius: 0 0 12px 12px;">
                      <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" form="categoryForm" class="btn btn-theme px-4 py-2 fw-bold">Create Category</button>
                  </div>
                </form>
          </div>
      </div>
  </div>
  
  <!-- Add Item Modal -->
  <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header text-white" style="background-color: var(--theme-primary); border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold" id="addItemModalLabel">Create New Menu Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" style="background-color: #fffaf1;">
              {{-- Save Item --}}
              <form id="itemForm" action="{{ route('item.store')}}" method="POST">
                @csrf
                <input type="hidden" name="category" id="categoryInput" placeholder="A man who cant be moved">
                <!-- Item Name -->
                <div class="mb-4">
                    <label for="itemName" class="form-label fw-bold small text-uppercase">Item Name</label>
                    <input type="text" name="name" class="form-control border-0 shadow-sm p-2" id="itemName" placeholder="e.g. Caramel Macchiato" required>
                </div>
                <!-- Price Input -->
                <div class="mb-3">
                    <label for="itemPrice" class="form-label fw-bold small text-uppercase">Price</label>
                    <div class="input-group shadow-sm border-0 rounded">
                        <span class="input-group-text bg-white border-0 text-muted fw-bold">$</span>
                        <input name="price" type="number" class="form-control border-0" id="itemPrice" placeholder="0.00" step="0.01" min="0" required>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4" style="background-color: #fffaf1; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="itemForm" class="btn btn-theme px-4 py-2 fw-bold">Save Item</button>
                </div>
              </form>
            </div>
        </div>
      </div>
  </div>

    <script>
        // SortableJS for Categories
        const categoryList = document.getElementById('categorySortable');
        new Sortable(categoryList, {
            animation: 150,
            ghostClass: 'sortable-ghost-cat',
            filter: '.btn-light', // Prevent the "Add Category" button from being dragged
            onEnd: function (evt) {
                console.log('Category moved:', evt.item.dataset.id);
            }
        });

        // SortableJS for Items Grid
        const itemList = document.getElementById('itemSortable');
        new Sortable(itemList, {
            animation: 150,
            ghostClass: 'sortable-ghost-item',
            filter: '.ignore-drag', // Prevent the "Add Item" card from being dragged
            onEnd: function (evt) {
                console.log('Item moved:', evt.item.dataset.id);
            }
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all icon options and the hidden input
        const iconOptions = document.querySelectorAll('.icon-option');
        const selectedIconInput = document.getElementById('selectedIcon');

        // Loop through each icon option and attach a click event
        iconOptions.forEach(option => {
            option.addEventListener('click', function() {
                // 1. Remove the 'active' class from ALL options
                iconOptions.forEach(opt => opt.classList.remove('active'));
                
                // 2. Add the 'active' class to the clicked option
                this.classList.add('active');
                
                // 3. Grab the data-icon value and assign it to the hidden input
                const chosenIcon = this.getAttribute('data-icon');
                selectedIconInput.value = chosenIcon;
            });
        });
    });
    document.addEventListener('DOMContentLoaded', function() {
        const categories = document.querySelectorAll('.categoryButton');
        const selectedCategoryInput = document.getElementById('categoryInput');
        categories.forEach(option => {
            option.addEventListener('click', function() {
                // 1. Remove the 'active' class from ALL options
                categories.forEach(opt => opt.classList.remove('btn-theme'));
                
                // 2. Add the 'active' class to the clicked option
                this.classList.add('btn-theme');
                
                // 3. Grab the data-icon value and assign it to the hidden input
                const selectedCategory = this.dataset.category;
                selectedCategoryInput.value = selectedCategory;
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".categoryButton");
    const items = document.querySelectorAll("#itemSortable [data-category]");

    buttons.forEach(btn => {
        btn.addEventListener("click", function () {
            const selectedCategory = this.dataset.category;

            items.forEach(item => {
                const itemCategory = item.dataset.category;

                if (selectedCategory === "all" || itemCategory === selectedCategory) {
                    item.classList.remove("d-none");
                } else {
                    item.classList.add("d-none");
                }
            });
        });
    });
});
</script>

</body>
</html>