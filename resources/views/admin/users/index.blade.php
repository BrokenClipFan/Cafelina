<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafelinea — User Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --paper: #F7EFE0;
            --paper-warm: #EAD9B7;
            --ink: #2E1D14;
            --ink-soft: #4A3B32;
            --espresso: #1A0F0A;
            --caramel: #C87941;
            --caramel-tint: #F3E5D8;
            --caramel-deep: #8C4A1D;
            --success: #2A5A3B;
            --danger: #8C2A2A;
        }
        body { background-color: var(--paper); color: var(--ink); font-family: 'Inter', sans-serif; }
        .admin-wrapper { max-width: 1400px; margin: 0 auto; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Space Grotesk', sans-serif; }

        .btn-theme { background-color: var(--espresso); color: #fff; border-radius: 8px; }
        .btn-theme:hover { background-color: var(--ink-soft); color: #fff; }
        .btn-theme-outline { background: transparent; color: var(--espresso); border: 1px solid var(--espresso); border-radius: 8px; }
        .btn-theme-outline:hover { background-color: var(--espresso); color: #fff; }

        .card-custom {
            background: #fff;
            border: 1px solid var(--paper-warm);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .table-custom th {
            background-color: var(--paper);
            color: var(--ink-soft);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            border-bottom: 2px solid var(--paper-warm);
        }
        .avatar {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--caramel-tint); color: var(--caramel-deep);
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; flex-shrink: 0;
        }
        .role-badge { font-size: .72rem; font-weight: 600; padding: .35em .7em; border-radius: 999px; }
        .role-admin { background: #1A0F0A; color: #F3E5D8; }
        .role-cashier { background: #d1e7dd; color: #0f5132; }
        .role-stock_clerk { background: #cfe2ff; color: #084298; }
        .status-approved { background: #d1e7dd; color: #0f5132; }
        .status-rejected { background: #f8d7da; color: #842029; }
        .status-pending { background: #fff3cd; color: #664d03; }
        .pending-row { border-left: 4px solid var(--caramel); }
    </style>
</head>
<body>

<div class="admin-wrapper min-vh-100 p-4">
    <div class="container-fluid">

        <!-- Header -->
        <div class="mb-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    style="width: 52px; height: 52px; background-color: var(--espresso); color: var(--caramel-tint);">
                    <i class="fa-solid fa-user-shield fs-4"></i>
                </div>
                <div>
                    <h1 class="fw-bold fs-3 m-0" style="color: var(--espresso);">User Verification</h1>
                    <p class="mb-0 small" style="color: var(--ink-soft);">Approve new registrations and manage staff roles.</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-outline">
                    <i class="fa-solid fa-arrow-left me-2"></i> Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Pending registrations -->
        <div class="card-custom p-4 mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="fw-bold m-0">
                    <i class="fa-solid fa-hourglass-half me-2" style="color: var(--caramel);"></i>
                    Pending Registrations
                </h5>
                <span class="badge rounded-pill status-pending px-3 py-2">{{ $pendingUsers->count() }} waiting</span>
            </div>

            @if($pendingUsers->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fa-regular fa-circle-check fs-2 mb-2 d-block" style="color: var(--success);"></i>
                    No accounts waiting for approval.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Registered</th>
                                <th style="width: 220px;">Assign Role</th>
                                <th class="text-end" style="width: 220px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingUsers as $pending)
                                <tr class="pending-row">
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="avatar">{{ strtoupper(substr($pending->name, 0, 1)) }}</span>
                                            <div>
                                                <div class="fw-semibold">{{ $pending->name }}</div>
                                                <div class="small text-muted">{{ $pending->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="small text-muted">
                                        {{ $pending->created_at?->format('M d, Y h:i A') }}<br>
                                        <span>{{ $pending->created_at?->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                        <select name="role" form="approve-{{ $pending->id }}" class="form-select form-select-sm" required>
                                            @foreach($roles as $value => $label)
                                                <option value="{{ $value }}" @selected($value === 'cashier')>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-end">
                                        <form id="approve-{{ $pending->id }}" action="{{ route('admin.users.approve', $pending) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa-solid fa-check me-1"></i> Approve
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#confirmModal"
                                            data-action="{{ route('admin.users.reject', $pending) }}"
                                            data-method="POST"
                                            data-title="Reject registration"
                                            data-message="Reject {{ $pending->name }}'s account? They will not be able to use the system."
                                            data-confirm="Reject">
                                            <i class="fa-solid fa-xmark me-1"></i> Reject
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- All users -->
        <div class="card-custom p-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-3">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-users me-2"></i>All Accounts</h5>
                <form method="GET" action="{{ route('admin.users') }}" class="d-flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Search name or email...">
                    <select name="role" class="form-select form-select-sm" style="width: 160px;" onchange="this.form.submit()">
                        <option value="">All roles</option>
                        @foreach($roles as $value => $label)
                            <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm btn-theme"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-custom table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th style="width: 260px;">Change Role</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                            @php $isSelf = $u->id === auth()->id(); @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                                        <div>
                                            <div class="fw-semibold">
                                                {{ $u->name }}
                                                @if($isSelf)<span class="small text-muted">(you)</span>@endif
                                            </div>
                                            <div class="small text-muted">{{ $u->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="role-badge status-{{ $u->status }}">{{ ucfirst($u->status) }}</span></td>
                                <td><span class="role-badge role-{{ $u->role }}">{{ $u->role_label }}</span></td>
                                <td>
                                    @if($u->status === 'approved')
                                        <form action="{{ route('admin.users.role', $u) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <select name="role" class="form-select form-select-sm" @disabled($isSelf)>
                                                @foreach($roles as $value => $label)
                                                    <option value="{{ $value }}" @selected($u->role === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-theme-outline" @disabled($isSelf)>Save</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.approve', $u) }}" method="POST" class="d-flex gap-2">
                                            @csrf
                                            <select name="role" class="form-select form-select-sm">
                                                @foreach($roles as $value => $label)
                                                    <option value="{{ $value }}" @selected($u->role === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-success text-nowrap">Re-approve</button>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    @unless($isSelf)
                                        @if($u->status === 'approved')
                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                data-bs-toggle="modal" data-bs-target="#confirmModal"
                                                data-action="{{ route('admin.users.reject', $u) }}"
                                                data-method="POST"
                                                data-title="Revoke access"
                                                data-message="Revoke {{ $u->name }}'s access? They will be blocked until re-approved."
                                                data-confirm="Revoke"
                                                title="Revoke access">
                                                <i class="fa-solid fa-ban"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" data-bs-target="#confirmModal"
                                            data-action="{{ route('admin.users.destroy', $u) }}"
                                            data-method="DELETE"
                                            data-title="Delete account"
                                            data-message="Permanently delete {{ $u->name }}'s account? This cannot be undone."
                                            data-confirm="Delete"
                                            title="Delete account">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No accounts found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Shared confirm modal (reject / revoke / delete) -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="confirmModalTitle">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <p id="confirmModalMessage" class="mb-0" style="color: var(--ink-soft);"></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="confirmModalForm" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="_method" id="confirmModalMethod" value="POST">
                    <button type="submit" class="btn btn-danger" id="confirmModalBtn">Confirm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('confirmModal').addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('confirmModalTitle').textContent = btn.dataset.title;
        document.getElementById('confirmModalMessage').textContent = btn.dataset.message;
        document.getElementById('confirmModalBtn').textContent = btn.dataset.confirm;
        document.getElementById('confirmModalForm').action = btn.dataset.action;
        document.getElementById('confirmModalMethod').value = btn.dataset.method;
    });

    // Auto-dismiss success alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.auto-dismiss').forEach(el => bootstrap.Alert.getOrCreateInstance(el).close());
    }, 5000);
</script>
</body>
</html>
