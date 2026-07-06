<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina — Order Status</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,600;1,9..144,500&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           Cafelina — token system
           A cafe order board styled after the physical object it
           replaces: a pickup buzzer. Numbers live in round tokens
           with a lanyard notch; the two queues are separated by a
           perforated ticket line; a ready order gets an ink stamp.
           ============================================================ */
        :root {
            --paper: #F7EFE0;
            --paper-warm: #EFE0C4;
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

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
            }
        }

        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            background: var(--paper);
            color: var(--ink);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        /* ---------------- Header ---------------- */
        .board-header {
            background: var(--espresso);
            color: var(--paper);
            padding: 22px 44px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 6px 18px rgba(0,0,0,0.18);
            z-index: 10;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-mark {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--caramel);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: var(--espresso);
            animation: warmPulse 3s ease-in-out infinite;
        }

        @keyframes warmPulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(198, 134, 59, 0.45); }
            50% { box-shadow: 0 0 0 10px rgba(198, 134, 59, 0); }
        }

        .brand-text h1 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 4rem;
            margin: 0;
            letter-spacing: 0.3px;
        }
        
        .fontshit {
            font-family: 'Fraunces', serif;
        }

        .brand-text .eyebrow {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 0.72rem;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--caramel-tint);
            opacity: 0.8;
        }

        .board-clock {
            text-align: right;
            font-family: 'Space Grotesk', sans-serif;
        }

        .board-clock .time {
            font-size: 1.7rem;
            font-weight: 700;
        }

        .board-clock .date {
            font-size: 0.85rem;
            opacity: 0.7;
        }

        /* ---------------- Layout ---------------- */
        .board {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            padding: 32px 44px;
            overflow: hidden;
            position: relative;
        }

        /* Perforated ticket divider between the two queues */
        .board::before {
            content: '';
            position: absolute;
            top: 24px;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            border-left: 3px dashed var(--paper-warm);
        }

        .panel {
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 0 36px;
        }

        .panel:first-child { padding-left: 4px; }
        .panel:last-child { padding-right: 4px; }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 18px;
            margin-bottom: 22px;
            border-bottom: 2px solid var(--paper-warm);
        }

        .panel-head .who {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .panel-icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
        }

        .panel--preparing .panel-icon { background: var(--caramel-tint); color: var(--caramel-deep); }
        .panel--ready .panel-icon { background: var(--moss-tint); color: var(--moss-deep); }

        .panel-head h2 {
            font-family: 'Fraunces', serif;
            font-style: italic;
            font-weight: 500;
            font-size: 1.9rem;
            margin: 0;
            line-height: 1.1;
        }

        .panel--preparing h2 { color: var(--caramel-deep;); font-size: 3.5rem; }
        .panel--ready h2 { color: var(--moss-deep); font-size: 3.5rem; }

        .panel-head .subtitle {
            font-size: 0.85rem;
            color: var(--ink-soft);
        }

        .count-pill {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 7px 16px;
            border-radius: 999px;
            color: #fff;
            white-space: nowrap;
        }

        .panel--preparing .count-pill { background: var(--caramel-deep); }
        .panel--ready .count-pill { background: var(--moss-deep); }

        .queue-list {
            flex: 1;
            overflow-y: scroll;
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding-right: 4px;
        }
        .queue-list::-webkit-scrollbar { display: none; }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--ink-soft);
            font-family: 'Space Grotesk', sans-serif;
            text-align: center;
            gap: 10px;
            opacity: 0.7;
        }
        .empty-state i { font-size: 2.2rem; opacity: 0.5; }

        /* ---------------- Order tokens (ticket cards) ---------------- */
        @keyframes riseIn {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .ticket {
            position: relative;
            background: #fff;
            border-radius: 14px;
            padding: 18px 22px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 14px rgba(64, 41, 27, 0.08);
            animation: riseIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        .ticket--preparing { border: 1.5px solid var(--caramel-tint); }
        .ticket--ready {
            border: 1.5px solid var(--moss-tint);
            background: var(--moss-tint);
        }

        /* Numbered token, styled like a pickup buzzer with a lanyard hole */
        .token {
            position: relative;
            flex-shrink: 0;
            width: 78px;
            height: 78px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 1.7rem;
            color: #fff;
        }

        .token::after {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--paper);
            box-shadow: 0 0 0 3px currentColor;
        }

        .ticket--preparing .token { background: var(--caramel); color: var(--caramel-deep); }
        .ticket--ready .token {
            background: var(--moss);
            color: var(--moss-deep);
            animation: readyGlow 2.2s ease-in-out infinite;
        }

        @keyframes readyGlow {
            0%, 100% { box-shadow: 0 0 0 0 rgba(63, 107, 76, 0.35); }
            50% { box-shadow: 0 0 0 9px rgba(63, 107, 76, 0); }
        }

        .ticket-body { flex: 1; min-width: 0; }

        .ticket-name {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            font-size: 1.3rem;
            margin: 0 0 2px;
            color: var(--ink);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ticket-status {
            font-size: 0.92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .ticket--preparing .ticket-status { color: var(--caramel-deep); }
        .ticket--ready .ticket-status { color: var(--moss-deep); }

        .ticket-status i.bi-arrow-repeat {
            animation: spin 3.5s linear infinite;
        }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Ink stamp flourish on ready tickets */
        .stamp {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 2px;
            color: var(--stamp);
            border: 2px solid var(--stamp);
            border-radius: 8px;
            padding: 6px 12px;
            transform: rotate(-6deg);
            opacity: 0.85;
            mix-blend-mode: multiply;
            white-space: nowrap;
        }
    </style>
</head>
<body>

    <header class="board-header">
        <div class="brand">
            <div class="brand-mark"><i class="bi bi-cup-hot-fill"></i></div>
            <div class="brand-text">
                <div class="eyebrow">Order Status</div>
                <h1>Cafelina</h1>
            </div>
        </div>
        <div class="board-clock">
            <div class="time" id="clockTime">12:00 PM</div>
            <div class="date" id="clockDate">—</div>
        </div>
    </header>

    <div class="board">

        <section class="panel panel--preparing">
            <div class="panel-head">
                <div class="who">
                    <div class="panel-icon"><i class="bi bi-cup-hot-fill"></i></div>
                    <div>
                        <h2>Preparing</h2>
                        <div class="subtitle">Freshly brewing right now</div>
                    </div>
                </div>
                <span class="count-pill" data-count="preparing">0 orders</span>
            </div>
            <div class="queue-list" data-list="preparing"></div>
        </section>

        <section class="panel panel--ready">
            <div class="panel-head">
                <div class="who">
                    <div class="panel-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <div>
                        <h2>Ready for pickup</h2>
                        <div class="subtitle">Grab it at the counter</div>
                    </div>
                </div>
                <span class="count-pill" data-count="ready">0 orders</span>
            </div>
            <div class="queue-list" data-list="ready"></div>
        </section>

    </div>

    <script>
        const api = {
            get: async (url) => {
                const res = await fetch(url, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                });
                return res.json();
            },
        };
    </script>

    <script>
        const preparingList = document.querySelector('[data-list="preparing"]');
        const readyList = document.querySelector('[data-list="ready"]');
        const preparingCount = document.querySelector('[data-count="preparing"]');
        const readyCount = document.querySelector('[data-count="ready"]');

        let previousOrdersTracker = '';

        function ticketPreparing(position, name) {
            return `<div class="fontshit order-card order-card-preparing animate-slide-in d-flex align-items-center p-4 bg-warning bg-opacity-10 border-start border-warning border-5 rounded-3 shadow mb-3">
                        <div class="display-5 fw-extrabold text-warning me-4" style="line-height: 1;">
                            #${position}
                        </div>
                        
                        <div class="flex-grow-1">
                            <h2 class="mb-1 fw-bold text-dark">${name}</h2>
                            <span class="fs-5 text-muted fw-semibold">Preparing your order...</span>
                        </div>
                        
                        <i class="bi bi-arrow-repeat fs-1 text-muted opacity-50 icon-spin"></i>
                    </div>`;

        }

        function ticketReady(position, name) {
            return `<div class="fontshit order-card order-card-ready animate-slide-in d-flex align-items-center p-4 bg-success bg-opacity-10 border-start border-success border-5 rounded-3 shadow mb-3">
                        <div class="display-5 fw-extrabold text-success me-4" style="line-height: 1;">
                            #${position}
                        </div>
                        
                        <div class="flex-grow-1">
                            <h2 class="mb-1 fw-bold text-dark">${name}</h2>
                            <span class="fs-5 text-muted fw-semibold">Ready for pickup</span>
                        </div>
                        
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-check-circle-fill fs-3"></i>
                        </div>
                    </div>`;
        }

        function emptyState(icon, text) {
            return `<div class="empty-state"><i class="bi ${icon}"></i><span>${text}</span></div>`;
        }

        async function loadKitchenOrders() {
            try {
                const response = await api.get('/get/queue');
                const ordersArray = response.data?.data || response.data || [];

                const preparingOrders = ordersArray.filter(o => o.status === 'preparing');
                const servingOrders = ordersArray.filter(o => o.status === 'ready');

                const currentTracker = 'prep:' + preparingOrders.map(o => o.name).join(',') +
                                        '|serve:' + servingOrders.map(o => o.name).join(',');

                if (currentTracker === previousOrdersTracker) return;
                previousOrdersTracker = currentTracker;

                preparingCount.textContent = `${preparingOrders.length} order${preparingOrders.length === 1 ? '' : 's'}`;
                readyCount.textContent = `${servingOrders.length} order${servingOrders.length === 1 ? '' : 's'}`;

                preparingList.innerHTML = preparingOrders.length
                    ? preparingOrders.map((o, i) => ticketPreparing(i + 1, o.name)).join('')
                    : emptyState('bi-cup-hot', 'No orders brewing yet');

                readyList.innerHTML = servingOrders.length
                    ? servingOrders.map((o, i) => ticketReady(i + 1, o.name)).join('')
                    : emptyState('bi-hourglass-split', 'Nothing waiting for pickup');

                startAutoScroll(preparingList);
                startAutoScroll(readyList);

            } catch (error) {
                console.error('Failed to load kitchen queue:', error);
            }
        }

        function updateClock() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;

            document.getElementById('clockTime').textContent = `${hours}:${minutes} ${ampm}`;
            document.getElementById('clockDate').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        setInterval(() => {
            loadKitchenOrders();
            updateClock();
        }, 1000);
        updateClock();
        loadKitchenOrders();
    </script>

    <script>
        function startAutoScroll(container) {
            if (container.dataset.scrollIntervalId) {
                clearInterval(parseInt(container.dataset.scrollIntervalId));
            }

            let direction = 1;

            const intervalId = setInterval(() => {
                const maxScroll = container.scrollHeight - container.clientHeight;
                if (maxScroll <= 0) {
                    container.scrollTop = 0;
                    return;
                }

                container.scrollTop += direction;

                if (container.scrollTop >= maxScroll) direction = -1;
                else if (container.scrollTop <= 0) direction = 1;
            }, 30);

            container.dataset.scrollIntervalId = intervalId;
        }
    </script>
</body>
</html>