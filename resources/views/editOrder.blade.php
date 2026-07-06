<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <a href="{{ route('home') }}" class="btn btn-outline-secondary me-3 btn-sm" title="Exit Editor"><i class="bi bi-x-lg"></i> Exit</a>
            <h5 class="mb-0 fw-bold" style="color: var(--theme-primary);"><i class="bi bi-magic me-2"></i>Live Menu Editor</h5>
        </div>
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
                            <div class="category-wrapper" data-category="{{ $firstCategory->category }}" data-id="{{ $firstCategory->id }}">
                                <button
                                    data-category="{{ $firstCategory->category }}"
                                    class="categoryButton btn btn-theme btn-outline-theme px-4 py-2 rounded-pill">

                                    <i class="bi {{ $firstCategory->icon }} me-1 opacity-50"></i>
                                    {{ $firstCategory->category }}

                                </button>

                                <div class="delete-cat-badge delete-category-btn">
                                    <i class="bi bi-x"></i>
                                </div>
                            </div>
                        @endif

                        @foreach($categories as $category)
                        <div class="category-wrapper" data-category="{{$category->category}}" data-id="{{ $category->id }}">
                            
                            <button data-category="{{$category->category}}" data-id="{{ $category->id }}" class="categoryButton btn btn-outline-theme px-4 py-2 rounded-pill"><i class="bi {{ $category->icon }} me-1 opacity-50"></i>{{ $category->category }}</button>
                            <div class="delete-cat-badge delete-category-btn"><i class="bi bi-x"></i></div>
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
                            data-id="{{ $item->id }}"
                            data-category="{{ $item->category }}">

                            <div class="card item-card h-100 p-3">
                                <h6 class="fw-bold mb-1" id="itemChangedName">{{ $item->name }}</h6>
                                <p class="text-muted small mb-3">{{ $item->category }}</p>

                                <div class="mt-auto fw-bold text-primary" id="itemChangedPrice"
                                    style="color: var(--theme-primary) !important;">
                                    ₱{{ $item->price }}
                                </div>
                                
                                <div class="item-actions shadow-sm">
                                    <!-- Added 'edit-item-btn' here -->
                                    <button class="btn btn-sm btn-primary rounded-circle edit-item-btn">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <!-- We'll add 'delete-item-btn' here early for later -->
                                    <button class="btn btn-sm btn-danger rounded-circle delete-item-btn">
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
                                <span>₱4.75</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Total</h5>
                            <h4 class="mb-0 fw-bold" style="color: var(--theme-primary);">₱4.75</h4>
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
                          <div class="icon-option active" data-icon="bi-cup-hot" data-name="Hot Drinks"><i class="bi bi-cup-hot"></i></div>
                          <div class="icon-option" data-icon="bi-cup" data-name="None Coffee"><i class="bi bi-cup"></i></div>
                          <div class="icon-option" data-icon="bi-droplet-half" data-name="Drinks"><i class="bi bi-droplet-half"></i></div>
                          <div class="icon-option" data-icon="bi-snow" data-name="Cold Drinks"><i class="bi bi-snow"></i></div>
                          <div class="icon-option" data-icon="bi-egg-fried" data-name="Food"><i class="bi bi-egg-fried"></i></div>
                          <div class="icon-option" data-icon="bi-brightness-high" data-name="Morning Snacks"><i class="bi bi-brightness-high"></i></div>
                          <div class="icon-option" data-icon="bi-moon-stars" data-name="Midnight Snacks"><i class="bi bi-moon-stars"></i></div>
                          <div class="icon-option" data-icon="bi-emoji-smile" data-name="Happy Meals"><i class="bi bi-emoji-smile"></i></div>
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
                <input type="hidden" name="category" id="categoryInput" value="{{ $firstCategory }}">
                <!-- Item Name -->
                <div class="mb-4">
                    <label for="itemName" class="form-label fw-bold small text-uppercase">Item Name</label>
                    <input type="text" name="name" class="form-control border-0 shadow-sm p-2" id="itemName" placeholder="e.g. Caramel Macchiato" required>
                </div>
                <!-- Price Input -->
                <div class="mb-3">
                    <label for="itemPrice" class="form-label fw-bold small text-uppercase">Price</label>
                    <div class="input-group shadow-sm border-0 rounded">
                        <span class="input-group-text bg-white border-0 text-muted fw-bold">₱</span>
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
  <!-- Edit Item Modal -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            
            <div class="modal-header text-white" style="background-color: var(--theme-primary); border-radius: 12px 12px 0 0;">
                <h5 class="modal-title fw-bold" id="editItemModalLabel">Edit Menu Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" style="background-color: #fffaf1;">
                <form id="editItemForm">
                <!-- Hidden input to store the item ID being edited -->
                <input type="hidden" name="id" id="editItemId">
                
                <div class="mb-4">
                    <label for="editItemName" class="form-label fw-bold small text-uppercase">Item Name</label>
                    <input type="text" name="name" class="form-control border-0 shadow-sm p-2" id="editItemName" required>
                </div>
                
                <div class="mb-3">
                    <label for="editItemPrice" class="form-label fw-bold small text-uppercase">Price</label>
                    <div class="input-group shadow-sm border-0 rounded">
                        <span class="input-group-text bg-white border-0 text-muted fw-bold">₱</span>
                        <input name="price" type="number" class="form-control border-0" id="editItemPrice" step="0.01" min="0" required>
                    </div>
                </div>
                
                <div class="modal-footer border-0 p-4" style="background-color: #fffaf1; border-radius: 0 0 12px 12px;">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-theme px-4 py-2 fw-bold">Update Item</button>
                </div>
                </form>
            </div>
        </div>
        </div>
    </div>
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="deleteItemModalLabel">Delete Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Are you sure you want to delete <strong id="deleteItemName"></strong>? This action cannot be undone.</p>
                    <form id="deleteItemForm">
                        <input type="hidden" name="id" id="deleteItemId">
                    </form>
                </div>
                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="deleteItemForm" class="btn btn-danger px-4 py-2 fw-bold">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
    {{-- delete category --}}
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">

                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="deleteCategoryModalLabel">
                        Delete Category
                    </h5>

                    <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <p>
                        Are you sure you want to delete
                        <strong id="deleteCategoryName"></strong>?
                        This action cannot be undone.
                    </p>

                    <form id="deleteCategoryForm">
                        <input type="hidden" name="id" id="deleteCategoryId">
                    </form>
                </div>

                <div class="modal-footer border-0 p-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2"
                        data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit"
                        form="deleteCategoryForm"
                        class="btn btn-danger px-4 py-2 fw-bold">
                        Yes, Delete
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // 1. SortableJS for Categories
        const categoryList = document.getElementById('categorySortable');
        new Sortable(categoryList, {
            animation: 150,
            ghostClass: 'sortable-ghost-cat',
            filter: '.btn-light', 
            onEnd: async function (evt) {
                // Grab all category wrapper IDs in their new order
                const orderedCategoryIds = Array.from(categoryList.querySelectorAll('.category-wrapper'))
                    .map(el => el.dataset.id);

                try {
                    // Send the new array of IDs to your Laravel backend
                    await api.post('/categories/reorder', { order: orderedCategoryIds });
                    console.log('Category order saved:', orderedCategoryIds);
                } catch (error) {
                    console.error('Failed to save category order', error);
                }
            }
        });

        // 2. SortableJS for Items
        const itemList = document.getElementById('itemSortable');
        new Sortable(itemList, {
            animation: 150,
            ghostClass: 'sortable-ghost-item',
            filter: '.ignore-drag', 
            onEnd: async function (evt) {
                // We only want to save the order of the currently visible category
                const activeCategory = document.getElementById('categoryInput').value;

                // Grab all item IDs that belong to the active category
                const orderedItemIds = Array.from(itemList.querySelectorAll(`.categoryItems[data-category="${activeCategory}"]`))
                    .map(el => el.dataset.id);

                try {
                    // Send the new array to Laravel
                    await api.post('/items/reorder', { order: orderedItemIds, category: activeCategory });
                    console.log('Item order saved:', orderedItemIds);
                } catch (error) {
                    console.error('Failed to save item order', error);
                }
            }
        });

        // // 3. Save Category Modal Logic
        // document.getElementById('categoryForm').addEventListener('submit', async function(e) {
        //     e.preventDefault(); // Stop standard form submission

        //     // Convert form data to a JSON object
        //     const formData = new FormData(this);
        //     const data = Object.fromEntries(formData.entries());

        //     try {
        //         const response = await api.post(this.action, data);
                
        //         // Close the modal cleanly
        //         const modalInstance = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
        //         modalInstance.hide();
                
        //         this.reset(); // Clear the form
                
        //         // TODO: Dynamically inject the new category HTML here (we can do this later)
        //         console.log('Category successfully saved to DB!', response);
                
        //         // Temporary fallback to see changes immediately:
        //         // window.location.reload(); 
        //     } catch (error) {
        //         console.error('Error saving category:', error);
        //     }
        // });

        // // 4. Save Item Modal Logic
        // document.getElementById('itemForm').addEventListener('submit', async function(e) {
        //     e.preventDefault();

        //     const formData = new FormData(this);
        //     const data = Object.fromEntries(formData.entries());

        //     try {
        //         const response = await api.post(this.action, data);
                
        //         const modalInstance = bootstrap.Modal.getInstance(document.getElementById('addItemModal'));
        //         modalInstance.hide();
                
        //         this.reset();
                
        //         // TODO: Dynamically inject the new item card HTML here (we can do this later)
        //         console.log('Item successfully saved to DB!', response);
                
        //     } catch (error) {
        //         console.error('Error saving item:', error);
        //     }
        // });

        // 5. Open Edit Modal and Populate Data
        document.getElementById('itemSortable').addEventListener('click', function(e) {
            // Check if the clicked element (or its parent) is the edit button
            const editBtn = e.target.closest('.edit-item-btn');

            if (editBtn) {
                // Climb up the DOM to find the main item card wrapper
                const itemCard = editBtn.closest('.categoryItems');
                
                // Extract data from the DOM
                const itemId = itemCard.dataset.id;
                const itemName = itemCard.querySelector('h6').innerText;
                // Grab the price and strip out the dollar sign/whitespace
                const itemPrice = itemCard.querySelector('.mt-auto').innerText.replace('$', '').trim();

                // Populate the modal inputs
                document.getElementById('editItemId').value = itemId;
                document.getElementById('editItemName').value = itemName.toUpperCase();
                document.getElementById('editItemPrice').value = itemPrice;

                // Open the modal
                const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
                editModal.show();
            }
        });

        // 6. Save Edit Modal Logic
        document.getElementById('editItemForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            const itemId = data.id; // Grab the ID from the hidden field

            const categoryItems = document.querySelector(`.categoryItems[data-id="${itemId}"]`);
            const ItemName = categoryItems.querySelector('#itemChangedName');
            const ItemPrice = categoryItems.querySelector('#itemChangedPrice');
            try {
                // We'll use your api.update wrapper and assume your Laravel route follows RESTful conventions like /items/{id}
                const response = await api.update(`/items/${itemId}/update`, data);
                
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('editItemModal'));
                modalInstance.hide();
                
                console.log('Item updated successfully in DB!', response);

                ItemName.textContent = data.name.toUpperCase();
                ItemPrice.textContent = data.price;
                
            } catch (error) {
                console.error('Error updating item:', error);
            }
        });

        // 7. Trigger Delete Modal
        document.getElementById('itemSortable').addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-item-btn');
            
            if (deleteBtn) {
                const itemCard = deleteBtn.closest('.categoryItems');
                const itemId = itemCard.dataset.id;
                const itemName = itemCard.querySelector('h6').innerText;

                // Populate modal with item info
                document.getElementById('deleteItemId').value = itemId;
                document.getElementById('deleteItemName').innerText = itemName;

                // Show the modal
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteItemModal'));
                deleteModal.show();
            }
        });

        // 8. Confirm Delete Action
        document.getElementById('deleteItemForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const itemId = document.getElementById('deleteItemId').value;

            try {
                // Call your API delete method
                await api.delete(`/items/${itemId}/delete`);
                
                // Hide the modal
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('deleteItemModal'));
                modalInstance.hide();
                
                // Remove the item from the UI immediately
                const itemElement = document.querySelector(`.categoryItems[data-id="${itemId}"]`);
                itemElement.remove();
                
                console.log('Item deleted successfully!');
            } catch (error) {
                console.error('Error deleting item:', error);
            }
        });

        // 9
        document.getElementById('categorySortable').addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.delete-category-btn');

            if (!deleteBtn) return;

            const category = deleteBtn.closest('.category-wrapper');

            const categoryId = category.dataset.id;
            const categoryName = category.dataset.category;

            const itemId = document.getElementById('deleteItemId').value = categoryId;
            
            document.getElementById('deleteCategoryName').textContent = categoryName;

            const modal = new bootstrap.Modal(
                document.getElementById('deleteCategoryModal')
            );

            modal.show();
        });

        // 10. Confirm Delete Category Action
        document.getElementById('deleteCategoryForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const itemId = document.getElementById('deleteItemId').value;

            try {
                // Call your API delete method
                await api.delete(`/category/${itemId}/delete`);
                
                // Hide the modal
                const modalInstance = bootstrap.Modal.getInstance(document.getElementById('deleteCategoryModal'));
                modalInstance.hide();
                
                // Remove the item from the UI immediately
                const itemElement = document.querySelector(`.categoryButton[data-id="${itemId}"]`);
                itemElement.remove();
                
                console.log('Item deleted successfully!');
            } catch (error) {
                console.error('Error deleting item:', error);
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

                const inptu = document.getElementById('categoryName');
                inptu.value = option.dataset.name.toUpperCase();
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
<script>
    const api = {
        get: async (url) => {
            const res = await fetch(url, {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                },
            });

            return res.json();
        },

        post: async (url, data) => {
            const res = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            return res.json();
        },

        update: async (url, data, method = "PUT") => {
            const res = await fetch(url, {
                method: method, // PUT or PATCH
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify(data),
            });

            return res.json();
        },

        delete: async (url) => {
            const res = await fetch(url, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            });

            return res.json();
        }
    };
</script>
</body>
</html>