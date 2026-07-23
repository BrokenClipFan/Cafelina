<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cafelina POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;0,9..144,700;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           Cafelina POS — shares the cafe token system from the order
           board: paper/espresso palette, Fraunces + Space Grotesk.
           The order panel is styled like a printed receipt (dot-leader
           rows), which is literally what it's building.
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
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3, h4, h5, h6 { font-family: 'Fraunces', serif; }

        /* ---------------- Shell & panels ---------------- */
        .pos-shell {
            height: 100%;
            display: grid;
            grid-template-columns: 1.7fr 1fr 1fr;
            gap: 16px;
            padding: 16px;
        }

        @media (max-width: 992px) {
            .pos-shell { grid-template-columns: 1fr; grid-auto-rows: minmax(320px, 1fr); overflow-y: auto; height: auto; }
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
            height: 100%;
        }

        .panel-title {
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0 0 2px;
            color: var(--ink);
        }

        .panel-sub {
            font-size: 0.82rem;
            color: var(--ink-soft);
            margin-bottom: 18px;
        }

        /* ---------------- Menu panel ---------------- */
        .category-rail {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 12px;
            margin-bottom: 8px;
            scroll-behavior: smooth;
        }
        .category-rail::-webkit-scrollbar { height: 5px; }
        .category-rail::-webkit-scrollbar-thumb { background: var(--paper-warm); border-radius: 4px; }

        .category-btn {
            flex: 0 0 auto;
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
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .category-btn i { opacity: 0.7; }
        .category-btn:hover { background: var(--caramel-tint); }
        .category-btn[data-active="true"] {
            background: var(--caramel);
            color: #fff;
        }
        .category-btn[data-active="true"] i { opacity: 0.9; }

        .item-grid {
            flex: 1;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 14px;
            align-content: start;
            padding: 4px 4px 4px 0;
        }

        .menu-item {
            border: 1.5px solid var(--paper-warm);
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: transform 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .menu-item:hover {
            transform: translateY(-3px);
            border-color: var(--caramel);
            box-shadow: 0 6px 14px rgba(198, 134, 59, 0.18);
        }
        .menu-item:focus-visible {
            outline: 2px solid var(--caramel-deep);
            outline-offset: 2px;
        }
        .menu-item-img {
            width: 100%;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: var(--paper);
        }
        .menu-item .name {
            font-weight: 600;
            font-size: 0.98rem;
            margin-bottom: 2px;
            line-height: 1.2;
        }
        .menu-item .category {
            font-size: 0.76rem;
            color: var(--ink-soft);
            margin-bottom: 10px;
        }
        .menu-item .price {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--caramel-deep);
        }

        /* ---------------- Order / receipt panel ---------------- */
        .panel--order .panel-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .clear-btn {
            border: none;
            background: none;
            color: var(--stamp);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 6px;
        }
        .clear-btn:hover { text-decoration: underline; }

        .order-name-input {
            width: 100%;
            border: none;
            border-bottom: 2px dashed var(--paper-warm);
            background: transparent;
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-size: 1.05rem;
            padding: 6px 2px 10px;
            margin-bottom: 16px;
            color: var(--ink);
        }
        .order-name-input:focus { outline: none; border-color: var(--caramel); }
        .order-name-input::placeholder { color: var(--ink-soft); opacity: 0.6; }

        .cart-list {
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .cart-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--ink-soft);
            text-align: center;
            opacity: 0.7;
            font-size: 0.9rem;
        }
        .cart-empty i { font-size: 1.8rem; opacity: 0.5; }

        .receipt-line {
            padding-bottom: 12px;
            margin-bottom: 12px;
            border-bottom: 1px dashed var(--paper-warm);
        }

        /* dot-leader row: name .......... price, like a printed receipt */
        .receipt-row {
            display: flex;
            align-items: baseline;
            gap: 6px;
            margin-bottom: 8px;
        }
        .receipt-row .item-name {
            font-weight: 600;
            font-size: 0.98rem;
            white-space: nowrap;
        }
        .receipt-row .leader {
            flex: 1;
            border-bottom: 2px dotted var(--ink-soft);
            opacity: 0.35;
            transform: translateY(-4px);
        }
        .receipt-row .item-total {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: var(--caramel-deep);
            white-space: nowrap;
        }

        .receipt-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .receipt-meta .category-tag {
            font-size: 0.76rem;
            color: var(--ink-soft);
        }

        .qty-stepper {
            display: flex;
            align-items: center;
            border: 1px solid var(--paper-warm);
            border-radius: 8px;
            overflow: hidden;
        }
        .qty-stepper button {
            border: none;
            background: var(--paper);
            width: 26px;
            height: 26px;
            font-weight: 700;
            color: var(--caramel-deep);
            cursor: pointer;
        }
        .qty-stepper button:hover { background: var(--caramel-tint); }
        .qty-input {
            width: 30px;
            text-align: center;
            border: none;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 0.85rem;
            background: #fff;
        }

        .delete-btn {
            border: none;
            background: none;
            color: var(--stamp);
            opacity: 0.7;
            cursor: pointer;
            padding: 2px 4px;
        }
        .delete-btn:hover { opacity: 1; }

        .order-footer {
            border-top: 2px dashed var(--paper-warm);
            padding-top: 14px;
            margin-top: 10px;
        }
        .order-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .order-total-row .label {
            font-weight: 600;
            font-size: 1rem;
        }
        .order-total-row .total {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--caramel-deep);
        }

        .submit-btn {
            width: 100%;
            border: none;
            background: var(--espresso);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .submit-btn:hover { background: var(--caramel-deep); }
        .submit-btn:disabled { opacity: 0.6; cursor: not-allowed; }

        /* ---------------- Kitchen panel ---------------- */
        .panel--kitchen {
            background: var(--caramel-tint);
        }

        .kitchen-list {
            flex: 1;
            overflow-y: auto;
            padding-right: 2px;
        }

        .kitchen-empty {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--ink-soft);
            text-align: center;
            opacity: 0.7;
            font-size: 0.9rem;
        }

        .ticket {
            position: relative;
            background: #fff;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 12px;
            box-shadow: 0 3px 10px rgba(64, 41, 27, 0.07);
        }
        .ticket--ready { background: var(--moss-tint); }

        .ticket-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .status-tag {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 999px;
            color: #fff;
        }
        .status-tag--preparing { background: var(--caramel-deep); }
        .status-tag--ready { background: var(--moss-deep); }

        .time-ago { font-size: 0.78rem; color: var(--ink-soft); }

        .ticket-name {
            font-weight: 600;
            font-size: 1rem;
            margin: 0 0 6px;
        }

        .ticket-items {
            list-style: none;
            padding: 0;
            margin: 0 0 12px;
            font-size: 0.85rem;
            color: var(--ink-soft);
        }
        .ticket-items li { line-height: 1.5; }

        .ticket-action {
            width: 100%;
            border: none;
            border-radius: 8px;
            padding: 9px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
        }
        .ticket-action--ready {
            background: var(--caramel);
            color: #fff;
        }
        .ticket-action--ready:hover { background: var(--caramel-deep); }
        .ticket-action--clear {
            background: transparent;
            border: 1.5px solid var(--moss-deep);
            color: var(--moss-deep);
        }
        .ticket-action--clear:hover { background: var(--moss-tint); }
        .ticket-action:disabled { opacity: 0.6; cursor: not-allowed; }

        .dashboard-btn {
            background-color: var(--stamp) !important;
        }
    </style>
</head>
<body>

    <div class="pos-shell">

        <section class="panel panel--menu">
            <h4 class="panel-title">Cafelina POS</h4>
            <p class="panel-sub">Tap an item to add it to the order</p>

            <div class="category-rail"><!-- category buttons render here --></div>

            <div class="item-grid"><!-- item cards render here --></div>
        </section>

        <section class="panel panel--order">
            <div class="panel-head">
                <div>
                    <h5 class="panel-title">Current order</h5>
                </div>
                <button class="clear-btn clearCartBtn"><i class="bi bi-x-lg"></i> Clear</button>
            </div>

            <input type="text" class="order-name-input orderNameInput" placeholder="Order for…">

            <div class="cart-list">
                <div class="cart-empty">
                    <i class="bi bi-cup"></i>
                    <span>No items yet — tap something from the menu</span>
                </div>
            </div>

            <div class="order-footer">
                <div class="order-total-row">
                    <span class="label">Total</span>
                    <span class="total cartTotal">$0.00</span>
                </div>
                <button class="submit-btn submitCart">Send to kitchen</button>
            </div>
        </section>

        <section class="panel panel--kitchen">
            <h5 class="panel-title">Active kitchen orders</h5>
            <p class="panel-sub">Live from the queue</p>

            <div class="kitchen-list"></div>
            
            <a href="{{ route('dashboard') }}"><button class="submit-btn dashboard-btn">Dashboard</button></a>
        </section>

    </div>

    @include('partials.notifications')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const api = {
            get: async (url) => {
                const res = await fetch(url, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                });
                return res.json();
            },

            post: async (url, data) => {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
                });
                return res.json();
            },

            update: async (url, data, method = 'PUT') => {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(data),
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
        /* ---------------- Menu: categories + items ---------------- */
        const CATEGORIES = @json($categories);
        const ITEMS = @json($items);

        const categoryRail = document.querySelector('.category-rail');
        const itemGrid = document.querySelector('.item-grid');

        /* Enable horizontal mouse scroll on category rail */
        categoryRail.addEventListener('wheel', (e) => {
            if (e.deltaY !== 0) {
                e.preventDefault();
                categoryRail.scrollLeft += e.deltaY;
            }
        });

        function categoryButtonHtml(cat, isFirst) {
            return `
                <button class="category-btn"
                        data-category="${cat.category}"
                        data-id="${cat.id}"
                        data-active="${isFirst ? 'true' : 'false'}">
                    <i class="bi ${cat.icon}"></i> ${cat.category}
                </button>`;
        }

        CATEGORIES.forEach((cat, index) => {
            categoryRail.insertAdjacentHTML('beforeend', categoryButtonHtml(cat, index === 0));
        });

        function menuItemCard(item) {
            const card = document.createElement('div');
            card.className = 'menu-item';
            card.tabIndex = 0;
            card.dataset.id = item.id;
            card.dataset.category = item.category;
            card.dataset.name = item.name;
            card.dataset.price = item.price;

            const imgSrc = item.image_path || 'https://via.placeholder.com/150?text=No+Image';

            card.innerHTML = `
                <div>
                    <img src="${imgSrc}" alt="${item.name}" class="menu-item-img" onerror="this.src='https://via.placeholder.com/150?text=No+Image';">
                    <div class="name">${item.name}</div>
                    <div class="category">${item.category}</div>
                </div>
                <div class="price">₱${Number(item.price).toFixed(2)}</div>
            `;

            card.addEventListener('click', () => addToCart(card));
            card.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); addToCart(card); }
            });

            return card;
        }

        function renderItems(category) {
            if (!category) return;
            itemGrid.innerHTML = '';
            ITEMS.filter(item => item.category === category)
                 .forEach(item => itemGrid.appendChild(menuItemCard(item)));
        }

        function setActiveCategory(button) {
            categoryRail.querySelectorAll('.category-btn').forEach(b => b.dataset.active = 'false');
            button.dataset.active = 'true';
            renderItems(button.dataset.category);
        }

        categoryRail.addEventListener('click', (e) => {
            const btn = e.target.closest('.category-btn');
            if (!btn) return;
            setActiveCategory(btn);
        });

        const initialCategory = CATEGORIES[0]?.category;
        renderItems(initialCategory);
    </script>

    <script>
        /* ---------------- Cart ---------------- */
        const cartList = document.querySelector('.cart-list');
        const cartTotalEl = document.querySelector('.cartTotal');

        function cartEmptyHtml() {
            return `<div class="cart-empty"><i class="bi bi-cup"></i><span>No items yet — tap something from the menu</span></div>`;
        }

        function ensureCartNotEmpty() {
            const empty = cartList.querySelector('.cart-empty');
            if (empty) empty.remove();
        }

        function restoreCartEmptyIfNeeded() {
            if (!cartList.querySelector('.order-item')) {
                cartList.innerHTML = cartEmptyHtml();
            }
        }

        function addToCart(itemEl) {
            const name = itemEl.dataset.name;
            const price = parseFloat(itemEl.dataset.price);
            const category = itemEl.dataset.category;

            const existing = Array.from(cartList.querySelectorAll('.order-item'))
                .find(row => row.dataset.name === name);

            if (existing) {
                const input = existing.querySelector('.qty-input');
                input.value = parseInt(input.value) + 1;
                updateItemTotal(existing, price);
                totalAllCart();
                return;
            }

            ensureCartNotEmpty();

            const row = document.createElement('div');
            row.className = 'receipt-line order-item';
            row.dataset.category = category;
            row.dataset.name = name;

            row.innerHTML = `
                <div class="receipt-row">
                    <span class="item-name">${name}</span>
                    <span class="leader"></span>
                    <span class="item-total">₱${price.toFixed(2)}</span>
                </div>
                <div class="receipt-meta">
                    <span class="category-tag">${category}</span>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="qty-stepper">
                            <button class="minus-btn" type="button">−</button>
                            <input class="qty-input" type="text" value="1" readonly>
                            <button class="plus-btn" type="button">+</button>
                        </div>
                        <button class="delete-btn" type="button"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            `;

            attachRowEvents(row, price);
            cartList.appendChild(row);
            totalAllCart();
        }

        function updateItemTotal(row, price) {
            const qty = parseInt(row.querySelector('.qty-input').value);
            row.querySelector('.item-total').textContent = `₱${(qty * price).toFixed(2)}`;
        }

        function attachRowEvents(row, price) {
            row.querySelector('.plus-btn').addEventListener('click', () => {
                const input = row.querySelector('.qty-input');
                input.value = parseInt(input.value) + 1;
                updateItemTotal(row, price);
                totalAllCart();
            });

            row.querySelector('.minus-btn').addEventListener('click', () => {
                const input = row.querySelector('.qty-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateItemTotal(row, price);
                    totalAllCart();
                }
            });

            row.querySelector('.delete-btn').addEventListener('click', () => {
                row.remove();
                restoreCartEmptyIfNeeded();
                totalAllCart();
            });
        }

        function totalAllCart() {
            let total = Array.from(cartList.querySelectorAll('.item-total'))
                .reduce((sum, el) => sum + (parseFloat(el.textContent.replace('₱', '')) || 0), 0);

            total = total + (total * @json($taxDecimal));
            cartTotalEl.textContent = `₱${total.toFixed(2)}`;
        }

        function clearCart() {
            cartList.querySelectorAll('.order-item').forEach(row => row.remove());
            restoreCartEmptyIfNeeded();
            cartTotalEl.textContent = '₱0.00';
        }

        document.querySelector('.clearCartBtn').addEventListener('click', clearCart);
    </script>

    <script>
        /* ---------------- Submit order ---------------- */
        const submitCartBtn = document.querySelector('.submitCart');
        submitCartBtn.addEventListener('click', saveCart);

        async function saveCart() {
            const rows = document.querySelectorAll('.order-item');
            const orderName = document.querySelector('.orderNameInput').value;

            const cartData = Array.from(rows).map(row => ({
                name: row.dataset.name,
                quantity: parseInt(row.querySelector('.qty-input').value),
                category: row.dataset.category,
                price: parseFloat(row.querySelector('.item-total').textContent.replace('₱', '')) / parseInt(row.querySelector('.qty-input').value),
                orderName,
            }));

            if (cartData.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            submitCartBtn.disabled = true;
            submitCartBtn.textContent = 'Sending…';

            try {
                await api.post('/cart/checkout', { items: cartData });
                clearCart();
                document.querySelector('.orderNameInput').value = '';
                loadKitchenOrders();
            } catch (error) {
                if (error.response) {
                    console.error('Server Error Data:', error.response.data);
                    console.error('Server Status Code:', error.response.status);
                } else {
                    console.error('Failed to save cart:', error);
                }
                alert('Something went wrong sending the order. Please try again.');
            } finally {
                submitCartBtn.disabled = false;
                submitCartBtn.textContent = 'Send to kitchen';
            }
        }
    </script>

    <script>
        /* ---------------- Active kitchen orders ---------------- */
        const kitchenList = document.querySelector('.kitchen-list');

        function kitchenEmptyHtml() {
            return `<div class="kitchen-empty"><i class="bi bi-inbox"></i><span>No active orders right now</span></div>`;
        }

        function createOrderCard(order) {
            const isPreparing = order.status.toLowerCase() === 'preparing';

            const itemsListHtml = order.items.map(item => `
                <li>${item.count || item.quantity}x ${item.name}</li>
            `).join('');

            const actionButtonHtml = isPreparing
                ? `<button class="ticket-action ticket-action--ready btn-mark-ready" data-order-name="${order.name}">
                        <i class="bi bi-check2-circle me-1"></i> Mark as ready
                   </button>`
                : `<button class="ticket-action ticket-action--clear btn-complete-clear" data-order-name="${order.name}">
                        Complete &amp; clear
                   </button>`;

            const card = document.createElement('div');
            card.className = `ticket ${isPreparing ? 'ticket--preparing' : 'ticket--ready'}`;
            card.setAttribute('data-order-id', order.name);

            card.innerHTML = `
                <div class="ticket-top">
                    <span class="status-tag ${isPreparing ? 'status-tag--preparing' : 'status-tag--ready'}">${order.status}</span>
                    <span class="time-ago">${order.timeAgo}</span>
                </div>
                <h6 class="ticket-name">Order: ${order.name}</h6>
                <ul class="ticket-items">${itemsListHtml}</ul>
                ${actionButtonHtml}
            `;

            return card;
        }

        async function loadKitchenOrders() {
            try {
                const response = await api.get('/get/queue');
                const ordersArray = response.data?.data || response.data || [];

                kitchenList.innerHTML = '';

                if (ordersArray.length === 0) {
                    kitchenList.innerHTML = kitchenEmptyHtml();
                    return;
                }

                ordersArray.forEach(order => {
                    const formattedOrder = {
                        name: order.order_name || order.name,
                        status: order.status,
                        timeAgo: 'Just now',
                        items: order.items || [],
                    };
                    kitchenList.appendChild(createOrderCard(formattedOrder));
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

        kitchenList.addEventListener('click', async (event) => {
            const button = event.target.closest('.btn-mark-ready, .btn-complete-clear');
            if (!button) return;

            const orderName = button.getAttribute('data-order-name');
            const originalLabel = button.textContent;

            try {
                button.disabled = true;
                button.textContent = 'Processing…';

                if (button.classList.contains('btn-mark-ready')) {
                    await api.update(`/api/orders/${orderName}/ready`, { status: 'ready' });
                } else if (button.classList.contains('btn-complete-clear')) {
                    await api.delete(`/api/orders/${orderName}/remove`);
                }

                loadKitchenOrders();
            } catch (error) {
                console.error('API call failed:', error);
                button.disabled = false;
                button.textContent = originalLabel;
                alert('Something went wrong. Please try again.');
            }
        });

        loadKitchenOrders();
    </script>

</body>
</html>