<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
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
            overflow: hidden;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Header Styling */
        .queue-header {
            background-color: var(--theme-primary);
            color: #ffffff;
            padding: 20px 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 10;
        }

        /* Column Headers */
        .status-heading {
            font-size: 2.5rem;
            font-weight: 800;
            text-align: center;
            padding: 20px 0;
            border-bottom: 4px solid;
            margin-bottom: 30px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .heading-preparing {
            color: var(--theme-primary);
            border-color: var(--theme-accent-light);
        }

        .heading-ready {
            color: #198754; /* Bootstrap Success Green for visibility */
            border-color: #198754;
        }

        /* Dynamic Card Animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulseReady {
            0% { box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2); }
            50% { box-shadow: 0 0 25px rgba(25, 135, 84, 0.6); }
            100% { box-shadow: 0 4px 15px rgba(25, 135, 84, 0.2); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px); /* Slides up into place */
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-card-preparing {
            /* Optional: custom smooth scroll behavior if the list is scrollable */
            scroll-behavior: smooth; 
        }

        .animate-slide-in {
            animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* Optional bonus: make that loading icon rotate */
        .icon-spin {
            animation: spin 4s linear infinite;
        }
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }

        /* Order Cards */
        .order-card {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            animation: slideInUp 0.5s ease-out forwards;
            box-shadow: 0 6px 12px rgba(0,0,0,0.05);
        }

        .order-card-preparing {
            border-left: 8px solid var(--theme-accent-light);
        }

        .order-card-ready {
            border-left: 8px solid #198754;
            background-color: #f8fff9;
            animation: slideInUp 0.5s ease-out forwards, pulseReady 2s infinite;
        }

        /* Typography for high visibility */
        .order-number {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1;
            margin: 0;
        }

        .order-name {
            font-size: 1.5rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .preparingList {
            height: 75vh; /* adjust this to fit your kitchen display layout */
            overflow-y: scroll;
            scroll-behavior: smooth; /* makes JavaScript .scrollTo look silky smooth */
            
            /* Hide scrollbars for Chrome, Safari and Opera */
            &::-webkit-scrollbar {
                display: none;
            }
            /* Hide scrollbars for IE, Edge and Firefox */
            -ms-overflow-style: none;  
            scrollbar-width: none;  
        }
        
        /* Staggered animation delays for the mock items */
        .order-card:nth-child(1) { animation-delay: 0.1s; }
        .order-card:nth-child(2) { animation-delay: 0.2s; }
        .order-card:nth-child(3) { animation-delay: 0.3s; }
        .order-card:nth-child(4) { animation-delay: 0.4s; }
        .order-card:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>

    <header class="queue-header d-flex justify-content-between align-items-center">
        <h1 class="mb-0 fw-bold"><i class="bi bi-cup-hot me-3"></i>Cafelina Order Status</h1>
        <div id="liveClock" class="fs-4 fw-bold opacity-75">12:00 PM</div>
    </header>

    <div class="container-fluid flex-grow-1 p-4 overflow-hidden">
        <div class="row h-100 g-5">
            
            <div class="col-md-6 h-100 d-flex flex-column">
                    <h2 class="status-heading heading-preparing">Preparing</h2>
                
                <div class="flex-grow-1 overflow-auto pe-2 preparingList" style="scrollbar-width: none;">
                </div>
            </div>

            <div class="col-md-6 h-100 d-flex flex-column">
                <h2 class="status-heading heading-ready">Now Serving</h2>
                
                <div class="flex-grow-1 overflow-auto pe-2 nowServingContainer" style="scrollbar-width: none;">
                    
                    

                    {{-- <div class="order-card order-card-ready">
                        <div>
                            <h3 class="order-number text-success">#103</h3>
                            <div class="order-name text-dark fw-bold">John D.</div>
                        </div>
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <div class="fw-bold text-success mt-1">Please Collect</div>
                        </div>
                    </div> --}}

                </div>
            </div>

        </div>
    </div>

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
        };
    </script>    
    <script>

        const preparingListContainer = document.querySelector('.preparingList');
        const nowServingListContainer = document.querySelector('.nowServingContainer');

        // 1. Keep track of the last rendered state globally
        let previousOrdersTracker = "";

        function makePrepareCard(position, name) {
            return `<div class="order-card order-card-preparing animate-slide-in d-flex align-items-center p-4 bg-warning bg-opacity-10 border-start border-warning border-5 rounded-3 shadow mb-3">
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

        function makeServingCard(position, name) {
            return `<div class="order-card order-card-ready animate-slide-in d-flex align-items-center p-4 bg-success bg-opacity-10 border-start border-success border-5 rounded-3 shadow mb-3">
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

        async function loadKitchenOrders() {
            try {
                const response = await api.get('/get/queue');
                const ordersArray = response.data?.data || response.data || [];
                
                const preparingOrders = ordersArray.filter(order => order.status === 'preparing');
                const servingOrders = ordersArray.filter(order => order.status === 'ready');

                // 2. Track changes in BOTH arrays so status transitions trigger an update
                const currentOrdersTracker = "prep:" + preparingOrders.map(o => o.name).join(',') + 
                                            "|serve:" + servingOrders.map(o => o.name).join(',');

                if (currentOrdersTracker === previousOrdersTracker) {
                    return; 
                }

                previousOrdersTracker = currentOrdersTracker;
                
                // 3. Clear BOTH containers to avoid ghost layouts
                preparingListContainer.innerHTML = ''; 
                nowServingListContainer.innerHTML = ''; 

                // 4. Populate Preparing orders
                preparingOrders.forEach((order, index) => {
                    const displayPosition = index + 1; 
                    const cardHtml = makePrepareCard(displayPosition, order.name);
                    preparingListContainer.insertAdjacentHTML('beforeend', cardHtml);
                });

                // 5. Populate Serving orders (Fixed target container)
                servingOrders.forEach((order, index) => {
                    const displayPosition = index + 1; 
                    const cardHtml = makeServingCard(displayPosition, order.name);
                    nowServingListContainer.insertAdjacentHTML('beforeend', cardHtml);
                });

                // 6. Fire up separate scroll loops for both panels
                startAutoScroll(nowServingListContainer);
                startAutoScroll(preparingListContainer);

            } catch (error) {
                console.error('Failed to load kitchen queue:', error);
            }
        }
        
        function updateClock() {
            const now = new Date();
            
            // 1. Format the date (Options: 'long', 'short', or 'numeric')
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateString = now.toLocaleDateString('en-US', options);

            // 2. Existing clock logic
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12; 
            minutes = minutes < 10 ? '0' + minutes : minutes;
            
            const timeString = hours + ':' + minutes + ' ' + ampm;

            // 3. Display both together (or split them if you have a separate HTML element)
            document.getElementById('liveClock').textContent = dateString + ' | ' + timeString;
        }
        
        setInterval(() => {
            loadKitchenOrders();
            updateClock();
        }, 1000);
    </script>

    <script>
        let scrollDirection = 1; // 1 = down, -1 = up
        let scrollInterval;

        function startAutoScroll(container) {
            // Clear any previous interval tied to this specific DOM element
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

                if (container.scrollTop >= maxScroll) {
                    direction = -1;
                } else if (container.scrollTop <= 0) {
                    direction = 1;
                }
            }, 30);

            // Save the interval ID onto the element data-attribute
            container.dataset.scrollIntervalId = intervalId;
        }
    </script>
</body>
</html>