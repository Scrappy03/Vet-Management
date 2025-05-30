{extends file="layouts/staffTemp.tpl"}
{block name="body"}

    <div class="dashboard-sidebar">
        <div class="text-center mb-4 content-transition">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4 content-transition">
            <a href="dashboard" class="dashboard-nav-link">
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
            <a href="staff" class="dashboard-nav-link active">
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

    <div class="dashboard-container">
        <div class="dashboard-main">
            <div class="dashboard-content">
                <div class="d-flex justify-content-between align-items-center mb-4 content-transition">
                    <h4 class="mb-0">Staff Directory</h4>
                    <div class="d-flex gap-2">
                        <a href="login?show=register" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Add New Staff
                        </a>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-calendar-check"></i> Manage Schedules
                        </button>
                    </div>
                </div>

                <!-- Staff List Display -->
                <div class="content-body">
                    <div class="staff-cards">
                        <div class="row">
                            {if $staff_list}
                                {foreach from=$staff_list item=staff_member}
                                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                        <div class="staff-card card h-100">
                                            <div
                                                class="staff-status {if $staff_member.status == 'active'}available{else}unavailable{/if}">
                                            </div>
                                            <div class="staff-initials-placeholder">
                                                <span class="initials-js" data-first="{$staff_member.first_name}"
                                                    data-last="{$staff_member.last_name}"></span>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    {if $staff_member.role == 'veterinarian'}Dr. {/if}{$staff_member.first_name}
                                                    {$staff_member.last_name}
                                                </h5>
                                                <p class="staff-role">{$staff_member.role}</p>
                                                {if $staff_member.specialties}
                                                    <p class="staff-specialty">
                                                        <i class="fas fa-stethoscope"></i> {$staff_member.specialties}
                                                    </p>
                                                {/if}
                                                <div class="staff-contact-info mb-2">
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-envelope"></i> {$staff_member.email}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-telephone"></i> {$staff_member.phone}
                                                    </small>
                                                    <small class="text-muted d-block">
                                                        <i class="bi bi-calendar"></i> Started: {$staff_member.start_date}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {/foreach}
                            {else}
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="bi bi-people" style="font-size: 3rem; color: #dee2e6;"></i>
                                        <h4 class="mt-3 text-muted">No Staff Members Found</h4>
                                        <p class="text-muted">Get started by adding your first staff member.</p>
                                        <a href="login?show=register" class="btn btn-primary">
                                            <i class="bi bi-person-plus"></i> Add Staff Member
                                        </a>
                                    </div>
                                </div>
                            {/if}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include only the necessary scripts -->
    <script src="./js/scripts-vendor.min.js"></script>
    <script src="./js/scripts.min.js"></script>
    <script>
        // Simple initials generation
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.initials-js').forEach(function(el) {
                const first = el.getAttribute('data-first');
                const last = el.getAttribute('data-last');
                el.textContent = (first ? first.charAt(0) : '') + (last ? last.charAt(0) : '');
            });
        });
    </script>
{/block}