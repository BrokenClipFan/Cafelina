<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
        --moss: #3F6B4C;
        --stamp: #A8432E;
    }

    body, .admin-wrapper {
        background-color: var(--paper);
        color: var(--ink);
        font-family: 'Inter', sans-serif;
    }

    .admin-wrapper h1, .admin-wrapper h5 {
        font-family: 'Fraunces', serif;
    }

    .analytic-card {
        background-color: #ffffff;
        border: 1px solid var(--paper-warm);
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(64, 41, 27, 0.06);
    }

    .btn-theme-outline {
        background-color: transparent;
        color: var(--espresso);
        border: 2px solid var(--paper-warm);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-theme-outline:hover {
        background-color: var(--caramel-tint);
        border-color: var(--caramel);
        color: var(--caramel-deep);
    }

    .table-custom th {
        color: var(--ink-soft);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border-bottom: 2px solid var(--caramel);
        font-weight: 700;
    }
    .table-custom td {
        border-bottom: 1px solid var(--paper-warm);
        vertical-align: middle;
    }
    .shift-day-checkbox:checked + .day-label {
        background-color: var(--caramel-tint) !important;
        border-color: var(--caramel) !important;
        color: var(--caramel-deep) !important;
    }
</style>

<div class="admin-wrapper vh-100 d-flex flex-column p-4 overflow-hidden">
    <div class="container-fluid d-flex flex-column h-100 gap-3">
        
        <!-- Top Header Navigation (Fixed Height) -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 flex-shrink-0">
            <div>
                <h1 class="fw-bold m-0" style="font-size: 1.75rem;">Employee Staff Roster</h1>
                <p class="mb-0 small" style="color: var(--ink-soft);">Manage team parameters, production attributes, and shift assignments.</p>
            </div>
            <div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-theme-outline shadow-sm py-2">
                    <i class="fa-solid fa-chart-line me-2"></i> Return to Dashboard
                </a>
            </div>
        </div>

        <!-- 1. Team Overview Container (Balanced 50% split) -->
        <div class="card analytic-card p-3 flex-grow-1 overflow-hidden d-flex flex-column" style="min-height: 0;">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-2 gap-2 flex-shrink-0">
                <h5 class="fw-bold m-0 fs-6"><i class="fa-solid fa-users me-2" style="color: var(--caramel-deep);"></i>Team Overview</h5>
                <!-- Dynamic Filter Search Bar -->
                <div class="input-group input-group-sm" style="max-width: 240px;">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" id="employeeSearchInput" onkeyup="filterEmployeeRoster()" class="form-control border-start-0" placeholder="Search staff profiles...">
                </div>
            </div>

            <!-- Dynamic inner table scroll height engine -->
            <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                <table class="table table-custom m-0 align-middle" id="employeeRosterTable" style="font-size: 0.9rem;">
                    <thead style="position: sticky; top: 0; background: #ffffff; z-index: 1;">
                        <tr>
                            <th>Employee Name</th>
                            <th>Email Address</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Orders</th>
                            <th class="text-end">Income Managed</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $staff)
                            <tr class="employee-data-row">
                                <td class="fw-semibold text-capitalize target-staff-name">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                             style="width: 28px; height: 28px; background-color: var(--espresso); font-size: 0.75rem;">
                                            {{ strtoupper(substr($staff->name, 0, 2)) }}
                                        </div>
                                        <span>{{ $staff->name }}</span>
                                    </div>
                                </td>
                                <td class="text-muted target-staff-email" style="font-size: 0.85rem;">{{ $staff->email }}</td>
                                <td class="text-center">
                                    @if($staff->online_status)
                                        <span class="badge bg-success-subtle text-success border-0 px-2 py-1" style="font-size: 0.75rem;"><i class="fa-solid fa-circle me-1" style="font-size:0.5rem;"></i> Online</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted border-0 px-2 py-1" style="font-size: 0.75rem;">Offline</span>
                                    @endif
                                </td>
                                <td class="text-center fw-bold">{{ number_format($staff->total_orders_handled) }}</td>
                                <td class="text-end fw-bold text-success">₱{{ number_format($staff->total_sales_generated, 2) }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.employees.destroy', $staff->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to remove this employee profile entirely?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-0" style="font-size: 0.8rem;" title="Delete employee">
                                            <i class="fa-solid fa-trash-can"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No registered employee staff profiles found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 2. Employee Schedules Container (Balanced 50% split) -->
        <div class="card analytic-card p-3 flex-grow-1 overflow-hidden d-flex flex-column" style="min-height: 0;">
            <div class="d-flex justify-content-between align-items-center mb-2 flex-shrink-0">
                <h5 class="fw-bold m-0 fs-6"><i class="fa-regular fa-calendar-days me-2" style="color: var(--moss);"></i>Shift Assignments & Schedules</h5>
                <button class="btn btn-sm btn-theme-outline py-1" data-bs-toggle="modal" data-bs-target="#assignShiftModal" style="font-size: 0.8rem;">
                    <i class="fa-solid fa-plus me-1"></i> Assign Shift
                </button>
            </div>

            <!-- Dynamic inner table scroll height engine -->
            <div class="table-responsive flex-grow-1" style="overflow-y: auto;">
                <table class="table table-custom m-0 align-middle" style="font-size: 0.9rem;">
                  <thead style="position: sticky; top: 0; background: #ffffff; z-index: 1;">
                      <tr>
                          <th>Employee</th>
                          <th>Days Assigned</th>
                          <th class="text-center">Shift Hours</th>
                          <th class="text-center">Station / Role</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Actions</th> <!-- New Header Column -->
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($schedules as $schedule)
                          <tr>
                              <td class="fw-semibold text-capitalize">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-sm"
                                          style="width: 28px; height: 28px; background-color: var(--espresso); font-size: 0.75rem;">
                                        {{ strtoupper(substr($staff->name, 0, 2)) }}
                                    </div>
                                    <span> {{ $schedule->user->name ?? 'Unknown Staff' }}</span>
                                </div>            
                              </td>
                              <td class="text-muted" style="font-size: 0.85rem;">
                                  @if(is_array($schedule->days))
                                      {{ implode(', ', $schedule->days) }}
                                  @else
                                      {{ $schedule->days }}
                                  @endif
                              </td>
                              <td class="text-center fw-medium" style="font-size: 0.85rem;">
                                  {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                  {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                              </td>
                              <td class="text-center">
                                  <span class="badge text-uppercase" style="background-color: var(--caramel-tint); color: var(--caramel-deep); font-size: 0.7rem;">
                                      {{ $schedule->station_role }}
                                  </span>
                              </td>
                              <td class="text-center">
                                  <span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 0.75rem;">
                                      {{ $schedule->status }}
                                  </span>
                              </td>
                              <!-- Inline Delete Action Form -->
                              <td class="text-center">
                                  <form action="{{ route('admin.employees.destroy_schedule', $schedule->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to delete this shift allocation?');">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-0" style="font-size: 0.8rem;" title="Delete employee">
                                        <i class="fa-solid fa-trash-can"></i> Remove
                                      </button>
                                  </form>
                              </td>
                          </tr>
                      @empty
                          <tr>
                              <td colspan="6" class="text-center text-muted py-3">No active shift schedules recorded.</td>
                          </tr>
                      @endforelse
                  </tbody>
              </table>
            </div>
        </div>

    </div>
</div>
<!-- Assign Shift Bootstrap Modal Component -->
<div class="modal fade" id="assignShiftModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="assignShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 14px; background-color: #ffffff;">
            <div class="modal-header border-bottom-0 pt-4 px-4 pb-2">
                <h5 class="modal-title fw-bold" id="assignShiftModalLabel" style="font-family: 'Fraunces', serif; font-size: 1.25rem;">
                    <i class="fa-regular fa-calendar-plus me-2" style="color: var(--moss);"></i>Assign New Shift
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.employees.assign_shift') }}" method="POST" class="m-0">
                @csrf
                <div class="modal-body px-4 pb-4">
                    <p class="text-muted small mb-3">Configure working hours, active role context, and days of availability for the selected team member.</p>
                    
                    <!-- Employee Selection Box -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label small fw-bold text-muted text-uppercase mb-1">Select Employee</label>
                        <select name="user_id" id="user_id" class="form-select" required style="border-color: var(--paper-warm);">
                            <option value="" disabled selected>Choose staff member...</option>
                            @foreach($employees as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Shift Time Grid Layout -->
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label for="start_time" class="form-label small fw-bold text-muted text-uppercase mb-1">Start Time</label>
                            <input type="time" name="start_time" id="start_time" class="form-control" required style="border-color: var(--paper-warm);" value="08:00">
                        </div>
                        <div class="col-6">
                            <label for="end_time" class="form-label small fw-bold text-muted text-uppercase mb-1">End Time</label>
                            <input type="time" name="end_time" id="end_time" class="form-control" required style="border-color: var(--paper-warm);" value="17:00">
                        </div>
                    </div>

                    <!-- Operational Station Role Context -->
                    <div class="mb-3">
                        <label for="station_role" class="form-label small fw-bold text-muted text-uppercase mb-1">Station / Operational Role</label>
                        <select name="station_role" id="station_role" class="form-select" required style="border-color: var(--paper-warm);">
                            <option value="Front Counter" selected>Front Counter / Cashier</option>
                            <option value="Kitchen Staff">Kitchen / Prep Staff</option>
                            <option value="Barista">Barista</option>
                            <option value="Delivery">Delivery / Logistics</option>
                        </select>
                    </div>

                    <!-- Days Selection (Comma separated array representation) -->
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted text-uppercase mb-1">Assigned Days</label>
                        <div class="d-flex flex-wrap gap-2 pt-1">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <div class="form-check form-check-inline m-0">
                                    <input class="form-check-input d-none shift-day-checkbox" type="checkbox" name="days[]" value="{{ $day }}" id="day_{{ $day }}" checked>
                                    <label class="btn btn-sm btn-outline-secondary px-2 py-1 fw-semibold day-label" for="day_{{ $day }}" style="font-size: 0.75rem; border-radius: 6px;">
                                        {{ $day }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <!-- Action Confirmation Buttons -->
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3 py-2 fw-bold" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white px-4 py-2 fw-bold" style="background-color: var(--espresso); border-radius: 8px;">
                        <i class="fa-solid fa-check-double me-1"></i> Confirm Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Pure Javascript Fast Client-Side Search Engine Filter Row Matches
    function filterEmployeeRoster() {
        const input = document.getElementById('employeeSearchInput');
        const filter = input.value.toLowerCase();
        const table = document.getElementById('employeeRosterTable');
        const tr = table.getElementsByClassName('employee-data-row');

        for (let i = 0; i < tr.length; i++) {
            const nameEl = tr[i].querySelector('.target-staff-name');
            const emailEl = tr[i].querySelector('.target-staff-email');
            
            if (nameEl || emailEl) {
                const nameText = nameEl ? nameEl.textContent || nameEl.innerText : "";
                const emailText = emailEl ? emailEl.textContent || emailEl.innerText : "";
                
                if (nameText.toLowerCase().indexOf(filter) > -1 || emailText.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>