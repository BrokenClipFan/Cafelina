{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
    :root {
        --paper: #F7EFE0;
        --paper-warm: #EAD9B7;
        --ink: #2E1D14;
        --ink-soft: #6B5647;
        --espresso: #40291B;
        --caramel: #C6863B;
        --caramel-deep: #A4692A;
        --caramel-tint: #F6E7C9;
        --stamp: #A8432E;
    }

    body, .admin-wrapper {
        background-color: var(--paper);
        color: var(--ink);
        font-family: 'Inter', sans-serif;
    }

    .analytic-card {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(64, 41, 27, 0.06);
    }

    .btn-theme-primary {
        background-color: var(--espresso);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 0.6rem 1.4rem;
        font-weight: 600;
        transition: background-color 0.2s ease;
    }
    .btn-theme-primary:hover { background-color: var(--caramel-deep); color: #fff; }

    .btn-theme-outline {
        background-color: transparent;
        color: var(--espresso);
        border: 2px solid var(--paper-warm);
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
    }
    .btn-theme-outline:hover { background-color: var(--caramel-tint); color: var(--caramel-deep); }

    .form-control:focus {
        border-color: var(--caramel);
        box-shadow: 0 0 0 0.25rem rgba(198, 134, 59, 0.25);
    }

    /* Live Thermal Receipt Preview Box */
    .receipt-preview-box {
        background: #fff;
        border: 1px dashed var(--espresso);
        border-radius: 8px;
        width: 100%;
        max-width: 300px;
        font-family: 'Courier New', Courier, monospace;
        font-size: 13px;
        color: #000;
        padding: 16px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid" style="max-width: 1100px;">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0" style="font-family: 'Fraunces', serif;">Receipt & Store Settings</h2>
                <p class="mb-0" style="color: var(--ink-soft);">Manage printed receipt headers, contact information, and default tax percentage.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-outline shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="background-color: #e1ebe0; color: #2e5038;" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Form Column -->
            <div class="col-12 col-lg-7">
                <div class="card analytic-card p-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-3" style="border-color: var(--paper-warm) !important;">
                        <i class="fa-solid fa-sliders me-2" style="color: var(--caramel);"></i>Receipt Configuration
                    </h5>

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('POST')

                        <!-- Tax Rate Field -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: var(--ink-soft);">Tax Rate (%)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100" name="tax" id="inputTax" 
                                       class="form-control" value="{{ old('tax', ($settings['tax'] * 100) ?? '12') }}" 
                                       oninput="updateReceiptPreview()" required>
                                <span class="input-group-text bg-white" style="border-color: var(--paper-warm);"><i class="fa-solid fa-percent"></i></span>
                            </div>
                            <small class="text-muted">Set to 0 if sales tax is disabled or included in menu prices.</small>
                        </div>

                        <!-- Store Address Field -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color: var(--ink-soft);">Store Address</label>
                            <textarea name="address" id="inputAddress" rows="3" class="form-control" 
                                      oninput="updateReceiptPreview()" required>{{ old('address', $settings['address'] ?? '123 Main Street, Cebu City') }}</textarea>
                        </div>

                        <!-- Telephone Number Field -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--ink-soft);">Telephone / Contact Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white" style="border-color: var(--paper-warm);"><i class="fa-solid fa-phone"></i></span>
                                <input type="text" name="phone" id="inputPhone" class="form-control" 
                                       value="{{ old('phone', $settings['phone'] ?? '(032) 123-4567') }}" 
                                       oninput="updateReceiptPreview()" required>
                            </div>
                        </div>

                        <!-- Admin Email Field -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold" style="color: var(--ink-soft);">Admin Email Account</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white" style="border-color: var(--paper-warm);"><i class="fa fa-envelope" aria-hidden="true"></i></span>
                                <input type="email" name="email" id="inputEmail" class="form-control" 
                                       value="{{ old('email', $settings['email'] ?? 'johndoe@gmail.com') }}" 
                                        required>
                            </div>
                        </div>

                        <!-- 🛠️ EXTENSIBLE TEMPLATE SECTION: ADD FUTURE FIELDS HERE -->
                        <div class="p-3 mb-4 rounded" style="background-color: var(--paper); border: 1px dashed var(--caramel);">
                            {{-- <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-uppercase small" style="color: var(--caramel-deep);">
                                    <i class="fa-solid fa-puzzle-piece me-1"></i> Custom Template Section
                                </span>
                                <span class="badge bg-white text-muted border">Modular Slot</span>
                            </div> --}}
                            
                            {{-- EXAMPLE FUTURE FIELD (Uncomment when needed):
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Receipt Footer Note</label>
                                <input type="text" name="footer_note" class="form-control form-control-sm" placeholder="e.g., WiFi: cafelina2026">
                            </div>
                            --}}
                            <p class="small text-muted m-0">You can change the settings whatever you want later</p>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-theme-primary shadow-sm">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Live Receipt Preview Column -->
            <div class="col-12 col-lg-5 d-flex flex-column align-items-center">
                <div class="card analytic-card p-4 w-100 d-flex flex-column align-items-center">
                    <h6 class="fw-bold mb-3 align-self-start" style="color: var(--ink-soft);">
                        <i class="fa-solid fa-eye me-2" style="color: var(--caramel);"></i>Live Thermal Receipt Preview
                    </h6>

                    <div class="receipt-preview-box">
                        <div class="text-center fw-bold fs-6">CAFELINEA</div>
                        <div class="text-center small" id="previewAddress">123 Main Street, Cebu City</div>
                        <div class="text-center small" id="previewPhone">Tel: (032) 123-4567</div>
                        <div class="text-center small my-1">--------------------------------</div>
                        <div class="small">Date: {{ date('m/d/Y h:i A') }}</div>
                        <div class="small">Order: #REC-PREVIEW</div>
                        <div class="text-center small my-1">--------------------------------</div>
                        
                        <div class="d-flex justify-content-between small">
                            <span>1x Iced Cappuccino</span>
                            <span>₱120.00</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>1x Blueberry Muffin</span>
                            <span>₱85.00</span>
                        </div>
                        
                        <div class="text-center small my-1">--------------------------------</div>
                        <div class="d-flex justify-content-between small">
                            <span>Subtotal:</span>
                            <span>₱205.00</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span id="previewTaxLabel">Tax (12%):</span>
                            <span id="previewTaxVal">₱24.60</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold fs-6 mt-1">
                            <span>TOTAL:</span>
                            <span id="previewTotalVal">₱229.60</span>
                        </div>
                        <div class="text-center small my-1">--------------------------------</div>
                        <div class="text-center small mt-2">Thank you for dining with us!</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    // Real-time Receipt Preview Updater
    function updateReceiptPreview() {
        const address = document.getElementById('inputAddress').value || 'Store Address Here';
        const phone = document.getElementById('inputPhone').value || 'Phone Number Here';
        const taxRate = parseFloat(document.getElementById('inputTax').value) || 0;

        document.getElementById('previewAddress').innerText = address;
        document.getElementById('previewPhone').innerText = 'Tel: ' + phone;
        document.getElementById('previewTaxLabel').innerText = `Tax (${taxRate}%):`;

        // Calculate sample total
        const sampleSubtotal = 205.00;
        const taxAmount = sampleSubtotal * (taxRate / 100);
        const grandTotal = sampleSubtotal + taxAmount;

        document.getElementById('previewTaxVal').innerText = '₱' + taxAmount.toFixed(2);
        document.getElementById('previewTotalVal').innerText = '₱' + grandTotal.toFixed(2);
    }

    updateReceiptPreview();
</script>