{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}
    <div class="dashboard-sidebar">
        <div class="text-center mb-4 content-transition">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4 content-transition">
            <a href="dashboard" class="dashboard-nav-link active">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="calendar" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="patients" class="dashboard-nav-link">
                <i class="bi bi-clipboard2-pulse"></i>
                Patients
            </a>
            <a href="staff" class="dashboard-nav-link">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="settings" class="dashboard-nav-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>

        <div class="mt-auto">
            <a href="logout" class="btn btn-sm w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <main class="dashboard-main">
        <div class="d-flex justify-content-between align-items-center mb-4 content-transition">
            <div>
                <h4 class="mb-0">Dashboard Overview</h4>
                <p class="text-muted mb-0">Welcome,
                    {if isset($user_name) && $user_name != ''}{$user_name}{elseif isset($user.email)}{$user.email}{else}User{/if}
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="bi bi-plus-lg"></i> New Appointment
                </button>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-person-plus"></i> New Patient
                </button>
            </div>
        </div>

        <!-- Add search form -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-4">
                        <label for="searchQuery" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchQuery"
                            placeholder="Search patients, appointments...">
                    </div>
                    <div class="col-md-3">
                        <label for="searchType" class="form-label">Type</label>
                        <select class="form-select" id="searchType">
                            <option value="all">All</option>
                            <option value="patients">Patients</option>
                            <option value="appointments">Appointments</option>
                            <option value="staff">Staff</option>
                            <option value="staff">Medication</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="searchDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="searchDate">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3 class="fs-4 mb-1">24</h3>
                    <p class="text-muted mb-0">Today's Appointments</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-capsule"></i>
                </div>
                <h3 class="fs-4 mb-1">10</h3>
                <p class="text-muted mb-0">Prescription Requests</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h3 class="fs-4 mb-1">18</h3>
                <p class="text-muted mb-0">Appointments Completed</p>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-card-icon">
                    <i class="bi bi-clock"></i>
                </div>
                <h3 class="fs-4 mb-1">6</h3>
                <p class="text-muted mb-0">Appointments Pending</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Appointments</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0 appointments-table">
                        <thead>
                            <tr>
                                <th>Pet Name</th>
                                <th>Owner</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Alfie</td>
                                <td>John Smith</td>
                                <td>Today</td>
                                <td>14:30</td>
                                <td>Vaccination</td>
                                <td><span class="status-badge status-upcoming">Upcoming</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Bruce</td>
                                <td>Sarah Johnson</td>
                                <td>Today</td>
                                <td>11:45</td>
                                <td>Check-up</td>
                                <td><span class="status-badge status-completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                            <tr>
                                <td>Bella</td>
                                <td>Mike Wilson</td>
                                <td>Today</td>
                                <td>16:15</td>
                                <td>Surgery</td>
                                <td><span class="status-badge status-upcoming">Upcoming</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">View</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Today's Reminders</h5>
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#addReminderModal">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="reminders-list">
                            <div class="reminder-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="reminder1">
                                    <label class="form-check-label" for="reminder1">
                                        Call Mr. Smith about Alfie's medication
                                    </label>
                                </div>
                                <small class="text-muted">10:00 AM</small>
                            </div>
                            <div class="reminder-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="reminder2">
                                    <label class="form-check-label" for="reminder2">
                                        Order new vaccine supplies
                                    </label>
                                </div>
                                <small class="text-muted">2:00 PM</small>
                            </div>
                            <div class="reminder-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="reminder3">
                                    <label class="form-check-label" for="reminder3">
                                        Staff meeting - Q3 Planning
                                    </label>
                                </div>
                                <small class="text-muted">4:30 PM</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Reminder Modal -->
    <div class="modal fade" id="addReminderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Reminder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="reminderText" class="form-label">Reminder</label>
                            <input type="text" class="form-control" id="reminderText">
                        </div>
                        <div class="mb-3">
                            <label for="reminderTime" class="form-label">Time</label>
                            <input type="time" class="form-control" id="reminderTime">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Add Reminder</button>
                </div>
            </div>
        </div>
    </div>

{/block}