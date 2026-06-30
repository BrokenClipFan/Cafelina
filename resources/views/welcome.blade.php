<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

        .text-white {
            color: white;
        }

        .primary-text-color{
            color: var(--theme-primary);
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
                        {{-- Categories buttons --}}
                    </div>

                    <div class="item-scroll row g-3 overflow-auto flex-grow-1 align-content-start">
                        
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Current Order</h5>
                        <button class="btn btn-sm btn-link text-danger text-decoration-none clearCartBtn"><i class="bi bi-x"></i> Clear</button>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control bg-light border-0 orderNameInput" placeholder="Enter order name...">
                    </div>

                    <div class="flex-grow-1 overflow-auto cartList">
                        {{-- <div class="order-item">
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
                        </div> --}}
                    </div>

                    <div class="border-top pt-3 mt-2">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Total</h5>
                            <h4 class="mb-0 fw-bold cartTotal" style="color: var(--theme-primary);">$0.00</h4>
                        </div>
                        <button class="btn btn-theme w-100 py-3 fw-bold fs-6 submitCart">Complete Order</button>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 h-100">
                <div class="pos-panel pos-panel3 d-flex flex-column" style="background-color: var(--theme-accent-light);">
                    <h5 class="fw-bold mb-3">Active Kitchen Orders</h5>
                    
                    <div class="flex-grow-1 overflow-auto pos-panel-list">
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

    @include('partials.notifications');
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    const CATEGORIES = @json($categories);
    const ITEMS = @json($items);
    const categoryContainer = document.querySelector('.category-scroll');        

    CATEGORIES.forEach((cat, index) => {
        const categoryHTML = addCategory(cat.category, cat.id, cat.icon);

        categoryContainer.insertAdjacentHTML('beforeend', categoryHTML);

        // Add class to first item AFTER rendering
        if (index === 0) {
            const firstEl = categoryContainer.querySelector('.category-wrapper');
            if (firstEl) {
                firstEl.querySelector('.categoryButton')
                    .classList.add('btn-theme');
                firstEl.querySelector('.categoryButton')
                    .classList.add('text-white');

                firstEl.setAttribute('data-active', 'true');
            }
        }
    });

    function addCategory(category, id, icon) {
        return `
            <div class="category-wrapper category-buttons"
                data-category="${category}"
                data-id="${id}">

                <button
                    data-category="${category}"
                    data-id="${id}"
                    class="categoryButton btn btn-outline-theme px-4 py-2 rounded-pill">

                    <i class="bi ${icon} me-1 opacity-50"></i>
                    ${category}
                </button>

            </div>
        `;
    }

    const categoryButtons = document.querySelectorAll('.categoryButton');
    const itemContainer = document.querySelector('.item-scroll');

    // detect active category (fallback to first button if none marked active)
    let activeCategory =
        [...categoryButtons].find(btn => btn.dataset.active === "true")?.dataset.category
        || categoryButtons[0]?.dataset.category;

    // render function (clean + reusable)
    function renderItems(category) {
        if (!category) return;

        itemContainer.innerHTML = "";

        ITEMS
            .filter(item => item.category === category)
            .forEach(item => {
                itemContainer.appendChild(
                    createItemCard(item.id, item.category, item.name, item.price)
                );
            });

        const itemButtons = document.querySelectorAll('.item-wrapper');
        itemButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                addToCart(btn)
            });

        });
    }

    // initial render
    renderItems(activeCategory);

    // card builder
    function createItemCard(id, category, name, price) {
        const col = document.createElement('div');
        col.className = 'col-md-4 col-sm-6 item-wrapper';
        col.dataset.id = id;
        col.dataset.category = category;
        col.dataset.name = name;
        col.dataset.price = price;

        const card = document.createElement('div');
        card.className = 'card item-card h-100 p-3';

        const title = document.createElement('h6');
        title.className = 'fw-bold mb-1';
        title.textContent = name;

        const categoryEl = document.createElement('p');
        categoryEl.className = 'text-muted small mb-3';
        categoryEl.textContent = category;

        const priceEl = document.createElement('div');
        priceEl.className = 'mt-auto fw-bold primary-text-color';
        priceEl.style.color = 'var(--theme-primary)';
        priceEl.textContent = `$${price}`;

        card.appendChild(title);
        card.appendChild(categoryEl);
        card.appendChild(priceEl);
        col.appendChild(card);

        return col;
    }

    // optional: category switching (click buttons)
    categoryButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            categoryButtons.forEach(b => {
                b.dataset.active = "false";
                b.classList.remove('btn-theme');
                b.classList.remove('text-white');
            });
            btn.dataset.active = "true";
            btn.classList.add('btn-theme');
            btn.classList.add('text-white');

            renderItems(btn.dataset.category);
        });
    });

</script>
<script>
    function addToCart(btn) {
        const cartList = document.querySelector('.cartList');
        
        // 1. Get Item Data
        const name = btn.dataset.name;
        const price = parseFloat(btn.dataset.price);
        const category = btn.dataset.category;

        // 2. Check if item already exists in the cart
        const existingItems = Array.from(cartList.querySelectorAll('.order-item'));
        const existingItem = existingItems.find(item => item.querySelector('h6').textContent === name);

        if (existingItem) {
            // Update existing quantity
            const input = existingItem.querySelector('.quality-Inputs');
            input.value = parseInt(input.value) + 1;
            updateItemTotal(existingItem, price);
            totalAllCart();
        } else {
            // Create new element
            const newItem = document.createElement('div');
            newItem.className = 'order-item mb-3';
            newItem.setAttribute('data-category', category);
            newItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-start mb-2" >
                    <div>
                        <h6 class="mb-0">${name}</h6>
                        <small class="text-muted">$${price.toFixed(2)} each</small>
                    </div>
                    <div>
                        <button class="btn btn-sm text-danger p-0 delete-btn"><i class="bi bi-trash"></i></button>
                        </br>
                        <small>${category}</small>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group btn-group-sm border rounded">
                        <button class="btn btn-light bg-white border-0 minus-btn">-</button>
                        <input class="quality-Inputs border-0 text-center" style="width: 30px" type="text" value="1" readonly>
                        <button class="btn btn-light bg-white border-0 plus-btn">+</button>
                    </div>
                    <span class="fw-bold item-total" style="color: var(--theme-primary);">$${price.toFixed(2)}</span>
                </div>
            `;

            // Add event listeners for the buttons inside the new item
            attachEventListeners(newItem, price);
            
            cartList.appendChild(newItem);
            totalAllCart();
        }
    }

    // Helper to update prices
    function updateItemTotal(element, price) {
        const input = element.querySelector('.quality-Inputs');
        const totalSpan = element.querySelector('.item-total');
        const newQty = parseInt(input.value);
        totalSpan.textContent = `$${(newQty * price).toFixed(2)}`;
    }

    // Helper to handle button logic
    function attachEventListeners(element, price) {
        element.querySelector('.plus-btn').addEventListener('click', () => {
            const input = element.querySelector('.quality-Inputs');
            input.value = parseInt(input.value) + 1;
            updateItemTotal(element, price);
            totalAllCart();
        });

        element.querySelector('.minus-btn').addEventListener('click', () => {
            const input = element.querySelector('.quality-Inputs');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateItemTotal(element, price);
                totalAllCart();
            }
        });

        element.querySelector('.delete-btn').addEventListener('click', () => {
            element.remove();
            totalAllCart();
        });
    }
    
    function totalAllCart() {
        const cartTotal = document.querySelector('.cartTotal');
        const allItemTotals = document.querySelectorAll('.item-total');
        
        let total = 0;

        allItemTotals.forEach(item => {
            // Remove '$', convert to float, and add to running total
            const price = parseFloat(item.textContent.replace('$', ''));
            if (!isNaN(price)) {
                total += price;
            }
        });

        // Update the UI
        cartTotal.textContent = `$${total.toFixed(2)}`;
    }

    function clearCart() {
        // 1. Remove all items
        const items = document.querySelectorAll('.order-item');
        items.forEach(item => {
            console.log(item.dataset.category)
            item.remove();
        });

        // 2. Reset the total display
        const cartTotal = document.querySelector('.cartTotal');
        if (cartTotal) {
            cartTotal.textContent = "$0.00";
        }
    }
    
    const btn = document.querySelector('.clearCartBtn');
    btn.addEventListener('click', clearCart);
    </script>
    <script>
        const submitCartBtn = document.querySelector('.submitCart');
        submitCartBtn.addEventListener('click', saveCart);

        async function saveCart() {
            const items = document.querySelectorAll('.order-item');
            const cartData = []; 

            // Extracting data from each item
            items.forEach(item => {
                const orderName = document.querySelector('.orderNameInput').value;
                const name = item.querySelector('h6').textContent;
                const quantity = item.querySelector('.quality-Inputs').value;
                const category = item.dataset.category;
                const price = item.querySelector('.item-total').textContent.replace('$', '');
                
                cartData.push({
                    name: name,
                    quantity: parseInt(quantity),
                    category: category,
                    price: parseFloat(price),
                    orderName: orderName
                });
            });

            // Don't submit if cart is empty
            if (cartData.length === 0) {
                alert("Your cart is empty!");
                return;
            }

            console.log(cartData)
            
            try {
                // Send to your backend API endpoint
                const response = await api.post('/cart/checkout', { items: cartData });
                clearCart();
                window.showNotification(response.message, 'success');
            } catch (error) {
                if (error.response) {
                    console.error('Server Error Data:', error.response.data);
                    console.error('Server Status Code:', error.response.status);
                } else {
                    console.error('Failed to save cart:', error);
                }
                }
            }
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
    <script>
        function createOrderCard(order) {
            // 1. Determine status configuration
            const isPreparing = order.status.toLowerCase() === 'preparing';
            const badgeClass = isPreparing ? 'bg-warning text-dark' : 'bg-success';
            const opacityClass = isPreparing ? '' : 'opacity-75';
            
            // 2. Generate the items list elements safely
            const itemsListHtml = order.items.map(item => `
                <li>${item.count || item.quantity}x ${item.name}</li>
            `).join('');

            // 3. Generate the action button based on current status
            const actionButtonHtml = isPreparing 
                ? `<button class="btn btn-sm btn-success w-100 fw-bold btn-mark-ready" data-order-name="${order.name}">
                    <i class="bi bi-check2-circle me-1"></i> Mark as Ready
                </button>`
                : `<button class="btn btn-sm btn-outline-secondary w-100 btn-complete-clear" data-order-name="${order.name}">
                    Complete & Clear
                </button>`;

            // 4. Create the parent card element wrapper
            const cardElement = document.createElement('div');
            cardElement.className = `card mb-3 border-0 shadow-sm ${opacityClass}`;
            cardElement.setAttribute('data-order-id', order.name);

            // 5. Inject the layout template
            cardElement.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge ${badgeClass}">${order.status}</span>
                        <span class="text-muted small">${order.timeAgo}</span>
                    </div>
                    <h6 class="fw-bold">Order: ${order.name}</h6>
                    <ul class="list-unstyled small mb-3">
                        ${itemsListHtml}
                    </ul>
                    ${actionButtonHtml}
                </div>
            `;

            return cardElement;
        }

        // Target your scrolling panel layout container
        // const kitchenOrderContainer = document.querySelector('.pos-panel-list');

        // // Example data array received from your backend api
        // const activeOrders = [
        //     {
        //         name: "Olala",
        //         status: "Preparing",
        //         timeAgo: "2 mins ago",
        //         items: [
        //             { name: "Latte", count: 1 },
        //             { name: "Espresso", count: 2 }
        //         ]
        //     },
        //     {
        //         name: "19203",
        //         status: "Ready",
        //         timeAgo: "Just now",
        //         items: [
        //             { name: "Americano", count: 1 }
        //         ]
        //     }
        // ];

        // // Clear out static design mockups, build dynamic ones, and display them
        // kitchenOrderContainer.innerHTML = '';

        // activeOrders.forEach(order => {
        //     const generatedCard = createOrderCard(order);
        //     kitchenOrderContainer.appendChild(generatedCard);
        // });

        async function loadKitchenOrders() {
            try {
                // 2. CRITICAL: Add the 'await' keyword here so JS pauses until the server answers
                const response = await api.get('/get/queue');
                console.log(response);
                
                // Grab your payload wrapper (Axios packages the response in a 'data' property)
                // If your Laravel API returns json(['data' => ...]), your array lives in response.data.data
                const ordersArray = response.data.data || response.data;
                
                const container = document.querySelector('.pos-panel-list');
                if (!container) {
                    console.error("Container '.pos-panel-list' not found in the DOM.");
                    return;
                }
                
                container.innerHTML = ''; // Clear old static entries

                // Loop through the nested data array safely
                ordersArray.forEach(order => {
                    
                    // Format data to match what your element builder expects
                    const formattedOrder = {
                        name: order.order_name || order.name, // Adjust based on your schema column name
                        status: order.status,          
                        timeAgo: "Just now",           // You can calculate actual time from order.created_at
                        items: order.items || []       // Fallback to empty array if no items found
                    };

                    // Build the DOM element card
                    const card = createOrderCard(formattedOrder);
                    
                    // Append it directly to the dashboard
                    container.appendChild(card);
                });
            } catch (error) {
                if (error.response) {
                    console.error('Server Error Data:', error.response.data);
                    console.error('Server Status Code:', error.response.status);
                } else {
                    console.error('Failed to load kitchen queue:', error);
                }
            }
        }

        // Call it on page load
        loadKitchenOrders();

        // Refresh the kitchen dashboard every 30 seconds
        setInterval(loadKitchenOrders, 1000);
    </script>
</html>