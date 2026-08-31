{{-- @extends('layouts.app') --}}
{{-- @section('content') --}}

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link
    href="https://fonts.googleapis.com/css2?family=Fraunces:wght@600;700&family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap"
    rel="stylesheet">

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

    body,
    .admin-wrapper {
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
        padding: 0.6rem 1.2rem;
        font-weight: 600;
    }

    .btn-theme-primary:hover {
        background-color: var(--caramel-deep);
        color: #fff;
    }

    .btn-theme-outline {
        background-color: transparent;
        color: var(--espresso);
        border: 2px solid var(--paper-warm);
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 600;
    }

    .btn-theme-outline:hover {
        background-color: var(--caramel-tint);
        color: var(--caramel-deep);
    }

    .table-custom th {
        color: var(--ink-soft);
        font-size: 0.78rem;
        text-transform: uppercase;
        border-bottom: 2px solid var(--caramel);
    }

    .table-custom td {
        border-bottom: 1px solid var(--paper-warm);
        vertical-align: middle;
    }
</style>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid" style="max-width: 900px;">

        <!-- Header Controls -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-outline shadow-sm">
                <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
            </a>
            <h4 class="fw-bold m-0" style="font-family: 'Fraunces', serif;">Order Receipt Details</h4>
        </div>

        @if (isset($order))
            <!-- Order Overview Card -->
            <div class="card analytic-card p-4 mb-4">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 border-bottom pb-3 mb-3"
                    style="border-color: var(--paper-warm) !important;">
                    <div>
                        <span class="text-uppercase small fw-bold" style="color: var(--ink-soft);">Order
                            Reference</span>
                        <h3 class="fw-bold m-0" style="color: var(--espresso);">#{{ $order->receipt_id ?? $order->id }}
                        </h3>
                    </div>
                    <div class="text-sm-end">
                        <span class="text-uppercase small fw-bold d-block" style="color: var(--ink-soft);">Status</span>
                        <div id="statusBadgeContainer">
                            @if (($order->status ?? 'completed') === 'refunded')
                                <span
                                    class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2 rounded fs-6">Refunded</span>
                            @else
                                <span
                                    class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded fs-6">Completed</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Customer & Transaction Metadata -->
                <div class="row g-3 mb-3">
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Customer / Table</small>
                        <strong class="text-capitalize">{{ $order->customer_name ?? 'Guest' }}</strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Date & Time</small>
                        <strong>{{ \Carbon\Carbon::parse($order->created_at ?? now())->format('M d, Y h:i A') }}</strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Payment Method</small>
                        <strong class="text-uppercase">{{ $order->payment_method ?? 'Cash' }}</strong>
                    </div>
                    <div class="col-6 col-sm-3">
                        <small class="text-muted d-block">Processed By</small>
                        <strong>{{ $order->user->name ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>

            <!-- Ordered Items Table Card -->
            <div class="card analytic-card p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="fa-solid fa-list me-2" style="color: var(--caramel);"></i>Purchased
                    Items</h6>
                <div class="table-responsive">
                    <table class="table table-custom align-middle m-0">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items ?? [] as $item)
                                <tr>
                                    <td class="fw-semibold text-capitalize">{{ $item->name }}</td>
                                    <td><span class="badge"
                                            style="background-color: var(--caramel-tint); color: var(--caramel-deep);">{{ $item->category }}</span>
                                    </td>
                                    <td class="text-center">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-center fw-bold">{{ $item->count }}</td>
                                    <td class="text-end fw-bold">
                                        ₱{{ number_format($item->price * $item->count, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">No items recorded for this
                                        order.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Financial Summary -->
                <div class="d-flex flex-column align-items-end mt-4 pt-3 border-top"
                    style="border-color: var(--paper-warm) !important;">
                    <div class="d-flex justify-content-between w-100" style="max-width: 280px;">
                        <span class="text-muted">Subtotal:</span>
                        <strong>₱{{ number_format($order->subtotal ?? 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between w-100 mt-1" style="max-width: 280px;">
                        <span class="text-muted">Tax:</span>
                        <strong>₱{{ number_format($order->tax ?? 0, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between w-100 mt-2 fs-5 border-top pt-2"
                        style="max-width: 280px; border-color: var(--caramel) !important;">
                        <strong style="color: var(--espresso);">Grand Total:</strong>
                        <strong style="color: var(--caramel-deep); font-family: 'Space Grotesk', sans-serif;">
                            ₱{{ number_format($order->total ?? 0, 2) }}
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Action Controls Widget (Refund / Revert Status) -->
            <div
                class="card analytic-card p-4 d-flex flex-row justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h6 class="fw-bold m-0">Order Actions</h6>
                    <small class="text-muted">Toggle order completion state or process a full refund.</small>
                </div>

                <div class="d-flex gap-2" id="actionButtonsContainer">
                    @if (($order->status ?? 'completed') === 'refunded')
                        <button type="button" onclick="updateOrderStatus('completed')"
                            class="btn btn-outline-success fw-semibold">
                            <i class="fa-solid fa-rotate-right me-1"></i> Change Back to Completed
                        </button>
                    @else
                        <button type="button" onclick="updateOrderStatus('refunded')"
                            class="btn btn-outline-danger fw-semibold">
                            <i class="fa-solid fa-rotate-left me-1"></i> Mark as Refunded
                        </button>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty State if search yields no results -->
            <div class="card analytic-card p-5 text-center">
                <i class="fa-solid fa-circle-exclamation fs-1 mb-3" style="color: var(--stamp);"></i>
                <h5 class="fw-bold">No Order Found</h5>
                <p class="text-muted">We couldn't find any receipt or order matching your query.</p>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-primary">Try Another Search</a>
                </div>
            </div>
        @endif

    </div>
</div>

<script>
    async function updateOrderStatus(newStatus) {
        const orderId = "{{ $order->id ?? '' }}";
        if (!orderId) return;

        const confirmText = newStatus === 'refunded' ?
            'Are you sure you want to mark this order as REFUNDED?' :
            'Are you sure you want to change this order back to COMPLETED?';

        if (!confirm(confirmText)) return;

        try {
            const response = await fetch("{{ route('orders.update_status') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: newStatus
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                // Refresh the view automatically to re-render Blade variables
                window.location.reload();
            } else {
                alert(result.message || 'Failed to update order status.');
            }
        } catch (error) {
            console.error('Error updating order status:', error);
            alert('An error occurred while processing your request.');
        }
    }
</script>
{{-- @endsection --}}
