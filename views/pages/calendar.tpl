{extends file="layouts/calendartemp.tpl"}
{block name="body"}
    <div class="dashboard-sidebar">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4">
            <a href="dashboard" class="dashboard-nav-link">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="calendar" class="dashboard-nav-link active">
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
            <a href="logout" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <main class="dashboard-main">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1">Appointment Calendar</h4>
                <p class="text-muted mb-0">Manage your appointments and schedule</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
                <i class="bi bi-plus-lg me-1"></i>New Appointment
            </button>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h5 class="mb-0" id="scheduleTitle">Today's Schedule</h5>
        </div>
        <div class="card-body">
            <div class="schedule-timeline" id="scheduleTimeline">
                <div class="schedule-item">
                    <div class="schedule-time">09:00 AM</div>
                    <div class="schedule-content">
                        <h6>Vaccination - Max (Dog)</h6>
                        <p class="mb-0 text-muted">Owner: Jane Smith</p>
                    </div>
                    <div class="schedule-status">
                        <span class="status-badge status-upcoming">Upcoming</span>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">11:30 AM</div>
                    <div class="schedule-content">
                        <h6>Regular Checkup - Luna (Cat)</h6>
                        <p class="mb-0 text-muted">Owner: Mike Johnson</p>
                    </div>
                    <div class="schedule-status">
                        <span class="status-badge status-completed">Completed</span>
                    </div>
                </div>
                <div class="schedule-item">
                    <div class="schedule-time">02:00 PM</div>
                    <div class="schedule-content">
                        <h6>Dental Cleaning - Rocky (Dog)</h6>
                        <p class="mb-0 text-muted">Owner: Sarah Williams</p>
                    </div>
                    <div class="schedule-status">
                        <span class="status-badge status-upcoming">Upcoming</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- New Appointment Modal -->
<div class="modal fade" id="newAppointmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule New Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="appointmentForm">
                    <div class="mb-3">
                        <label class="form-label">Pet</label>
                        <select class="form-select" id="pet" required>
                            <option selected disabled value="">Select pet</option>
                            {if isset($patients) && !empty($patients)}
                            {foreach from=$patients item=patient}
                            <option value="{$patient.patient_id}">{$patient.name} ({$patient.species})
                                {if isset($patient.owner_name)} - {$patient.owner_name}{/if}
                            </option>
                            {/foreach}
                            {else}
                            <option disabled>No patients available</option>
                            {/if}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointmentDate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointmentTime" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Appointment Type</label>
                        <select class="form-select" id="appointmentType" required>
                            <option>Check-up</option>
                            <option>Vaccination</option>
                            <option>Surgery</option>
                            <option>Dental Cleaning</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Animal Care Status</label>
                        <select class="form-select" id="animalCareStatus">
                            <option value="">None</option>
                            <option value="aggressive">Aggressive - Use Caution</option>
                            <option value="anxious">Anxious - Needs Gentle Handling</option>
                            <option value="special-needs">Special Needs - See Notes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Appointment Status</label>
                        <select class="form-select" id="appointmentStatus">
                            <option value="upcoming">Upcoming</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" rows="3" id="appointmentNotes"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Veterinarian</label>
                        <select class="form-select" id="staff" required>
                            <option selected disabled value="">Select veterinarian</option>
                            {if isset($staff) && !empty($staff)}
                            {foreach from=$staff item=member}
                            <option value="{$member.staff_id}">{$member.first_name} {$member.last_name}
                                {if isset($member.specialties) && $member.specialties != ''} -
                                {$member.specialties}{/if}
                            </option>
                            {/foreach}
                            {else}
                            <option disabled>No staff available</option>
                            {/if}
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="scheduleAppointmentBtn" data-mode="create">Schedule
                    Appointment</button>
            </div>
        </div>
    </div>
</div>
{/block}