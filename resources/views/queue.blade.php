<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelina - Order Status</title>
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
                
                <div class="flex-grow-1 overflow-auto pe-2" style="scrollbar-width: none;">
                    
                    <div class="order-card order-card-preparing">
                        <div>
                            <h3 class="order-number" style="color: var(--theme-primary);">#104</h3>
                            <div class="order-name">Emma R.</div>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 text-muted opacity-50 spin-icon"></i>
                    </div>

                    <div class="order-card order-card-preparing">
                        <div>
                            <h3 class="order-number" style="color: var(--theme-primary);">#105</h3>
                            <div class="order-name">Michael T.</div>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 text-muted opacity-50"></i>
                    </div>

                    <div class="order-card order-card-preparing">
                        <div>
                            <h3 class="order-number" style="color: var(--theme-primary);">#106</h3>
                            <div class="order-name">David S.</div>
                        </div>
                        <i class="bi bi-arrow-repeat fs-1 text-muted opacity-50"></i>
                    </div>

                </div>
            </div>

            <div class="col-md-6 h-100 d-flex flex-column">
                <h2 class="status-heading heading-ready">Now Serving</h2>
                
                <div class="flex-grow-1 overflow-auto pe-2" style="scrollbar-width: none;">
                    
                    <div class="order-card order-card-ready">
                        <div>
                            <h3 class="order-number text-success">#102</h3>
                            <div class="order-name text-dark fw-bold">Sarah W.</div>
                        </div>
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <div class="fw-bold text-success mt-1">Please Collect</div>
                        </div>
                    </div>

                    <div class="order-card order-card-ready">
                        <div>
                            <h3 class="order-number text-success">#103</h3>
                            <div class="order-name text-dark fw-bold">John D.</div>
                        </div>
                        <div class="text-center">
                            <i class="bi bi-check-circle-fill text-success fs-1"></i>
                            <div class="fw-bold text-success mt-1">Please Collect</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            
            hours = hours % 12;
            hours = hours ? hours : 12; 
            minutes = minutes < 10 ? '0' + minutes : minutes;
            
            document.getElementById('liveClock').textContent = hours + ':' + minutes + ' ' + ampm;
        }
        
        setInterval(updateClock, 1000);
        updateClock();
    </script>

</body>
</html>