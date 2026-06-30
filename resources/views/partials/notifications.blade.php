<!-- Global Notification Container -->
<div class="global-notification-container" id="globalNotificationContainer">

    <!-- Success Notification -->
    @if(session('success'))
        <div class="custom-toast toast-success show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-stripe"></div>
            <div class="toast-content">
                <div class="toast-icon">✓</div>
                <div class="toast-body-text">
                    <strong class="toast-title">Success</strong>
                    <p class="toast-message">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close-toast" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        </div>
    @endif

    <!-- Error/Danger Notification -->
    @if(session('error') || $errors->any())
        <div class="custom-toast toast-error show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-stripe"></div>
            <div class="toast-content">
                <div class="toast-icon">✕</div>
                <div class="toast-body-text">
                    <strong class="toast-title">Error Encountered</strong>
                    <p class="toast-message">
                        @if(session('error'))
                            {{ session('error') }}
                        @else
                            Please check the form for errors.
                        @endif
                    </p>
                </div>
                <button type="button" class="btn-close-toast" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        </div>
    @endif

    <!-- Warning Notification (Matches the yellow "Preparing" tag in image_bbb0bc.png) -->
    @if(session('warning'))
        <div class="custom-toast toast-warning show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-stripe"></div>
            <div class="toast-content">
                <div class="toast-icon">⚠</div>
                <div class="toast-body-text">
                    <strong class="toast-title">Warning</strong>
                    <p class="toast-message">{{ session('warning') }}</p>
                </div>
                <button type="button" class="btn-close-toast" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        </div>
    @endif



</div>

<!-- CSS Styles matching Cafelina POS Theme -->
<style>
    .global-notification-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 400px;
        width: 100%;
    }

    .custom-toast {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(96, 63, 38, 0.12);
        display: flex;
        overflow: hidden;
        animation: slideIn 0.3s ease-out forwards;
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .toast-stripe {
        width: 6px;
        flex-shrink: 0;
    }

    .toast-content {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        width: 100%;
        position: relative;
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
        margin-right: 12px;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .toast-body-text {
        flex-grow: 1;
        padding-right: 16px;
    }

    .toast-title {
        display: block;
        color: #603F26; /* --theme-dark */
        font-size: 0.95rem;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .toast-message {
        color: #6c757d;
        font-size: 0.85rem;
        margin: 0;
    }

    .btn-close-toast {
        background: none;
        border: none;
        color: #ced4da;
        font-size: 1.25rem;
        line-height: 1;
        padding: 0;
        cursor: pointer;
        position: absolute;
        top: 12px;
        right: 12px;
        transition: color 0.15s ease;
    }

    .btn-close-toast:hover {
        color: #603F26;
    }

    /* Toast Contextual Flavor Variations */
    /* Success: Inspired by the "Ready" green tag in image_bbb0bc.png */
    .toast-success .toast-stripe { background-color: #198754; }
    .toast-success .toast-icon { background-color: #e8f5e9; color: #198754; }

    /* Error: High contrast structural crimson */
    .toast-error .toast-stripe { background-color: #dc3545; }
    .toast-error .toast-icon { background-color: #fde8e8; color: #dc3545; }

    /* Warning: Inspired by the "Preparing" amber tag in image_bbb0bc.png */
    .toast-warning .toast-stripe { background-color: #ffc107; }
    .toast-warning .toast-icon { background-color: #fffde6; color: #ffc107; }

    /* Animations */
    @keyframes slideIn {
        from {
            transform: translateX(120%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from { opacity: 1; transform: scale(1); }
        to { opacity: 0; transform: scale(0.9); }
    }
</style>

<script>
    // 1. Helper function to create and dismiss toasts dynamically
    window.showNotification = function(message, type = 'success') {
        const container = document.getElementById('globalNotificationContainer');
        if (!container) return;

        // Map icons and titles
        const setups = {
            success: { icon: '✓', title: 'Success' },
            error: { icon: '✕', title: 'Error Encountered' },
            warning: { icon: '⚠', title: 'Warning' }
        };
        const config = setups[type] || setups.success;

        // Create toast element
        const toast = document.createElement('div');
        toast.className = `custom-toast toast-${type}`;
        toast.setAttribute('role', 'alert');
        
        toast.innerHTML = `
            <div class="toast-stripe"></div>
            <div class="toast-content">
                <div class="toast-icon">${config.icon}</div>
                <div class="toast-body-text">
                    <strong class="toast-title">${config.title}</strong>
                    <p class="toast-message">${message}</p>
                </div>
                <button type="button" class="btn-close-toast" onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        `;

        // Append to container
        container.appendChild(toast);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            toast.style.animation = 'fadeOut 0.3s ease-out forwards';
            toast.addEventListener('animationend', (e) => {
                if (e.animationName === 'fadeOut') toast.remove();
            });
        }, 5000);
    }

    // 2. Handle automatic dismissal for existing Blade-rendered messages
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-toast').forEach(toast => {
            setTimeout(() => {
                toast.style.animation = 'fadeOut 0.3s ease-out forwards';
                toast.addEventListener('animationend', (e) => {
                    if (e.animationName === 'fadeOut') toast.remove();
                });
            }, 5000);
        });
    });
</script>