{extends file="layouts/dashboardTemp.tpl"}
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
            <a href="calendar" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="patients" class="dashboard-nav-link active">
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
            <h4 class="mb-0">Patients Registry</h4>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
                    <i class="bi bi-person-plus"></i> Add New Patient
                </button>
                <button class="btn btn-outline-primary">
                    <i class="bi bi-file-earmark-arrow-down"></i> Export Data
                </button>
            </div>
        </div>

        <!-- Search and filter form -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3">
                    <div class="col-md-3">
                        <label for="searchQuery" class="form-label">Search</label>
                        <input type="text" class="form-control" id="searchQuery" placeholder="Search by name, breed...">
                    </div>
                    <div class="col-md-2">
                        <label for="filterSpecies" class="form-label">Species</label>
                        <select class="form-select" id="filterSpecies">
                            <option value="">All Species</option>
                            <option value="dog">Dog</option>
                            <option value="cat">Cat</option>
                            <option value="rabbit">Rabbit</option>
                            <option value="bird">Bird</option>
                            <option value="reptile">Reptile</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterStatus" class="form-label">Status</label>
                        <select class="form-select" id="filterStatus">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="treatment">Under Treatment</option>
                            <option value="scheduled">Scheduled</option>
                            <option value="recovery">Recovery</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterOwner" class="form-label">Owner</label>
                        <input type="text" class="form-control" id="filterOwner" placeholder="Owner name">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-clipboard2-pulse"></i>
                    </div>
                    <h3 class="fs-4 mb-1">{$total_patients}</h3>
                    <p class="text-muted mb-0">Total Patients</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h3 class="fs-4 mb-1">{$under_treatment}</h3>
                    <p class="text-muted mb-0">Under Treatment</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <h3 class="fs-4 mb-1">{$scheduled_today}</h3>
                    <p class="text-muted mb-0">Scheduled Today</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-3 mb-3">
                <div class="stat-card">
                    <div class="stat-card-icon">
                        <i class="bi bi-plus-circle"></i>
                    </div>
                    <h3 class="fs-4 mb-1">{$new_this_week}</h3>
                    <p class="text-muted mb-0">New This Week</p>
                </div>
            </div>
        </div>

        <!-- View toggle buttons -->
        <div class="d-flex justify-content-end mb-3">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-primary active" id="tableViewBtn">
                    <i class="bi bi-list"></i> List View
                </button>
                <button type="button" class="btn btn-outline-primary" id="cardViewBtn">
                    <i class="bi bi-grid"></i> Card View
                </button>
            </div>
        </div>

        <!-- Table view -->
        <div class="card mb-4" id="tableView">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pet Name</th>
                                <th>Species</th>
                                <th>Breed</th>
                                <th>Age</th>
                                <th>Owner</th>
                                <th>Status</th>
                                <th>Last Visit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {if $patients}
                                {foreach $patients as $patient}
                                    <tr>
                                        <td>#{$patient.formatted_id}</td>
                                        <td>{$patient.name}</td>
                                        <td>{$patient.species_formatted}</td>
                                        <td>{$patient.breed}</td>
                                        <td>{if $patient.age}{$patient.age} year{if $patient.age != 1}s{/if}{else}N/A{/if}</td>
                                        <td>{$patient.owner_name}</td>
                                        <td>
                                            <span
                                                class="status-badge {if $patient.status == 'active'}status-upcoming{elseif $patient.status == 'under treatment'}status-cancelled{elseif $patient.status == 'scheduled'}status-completed{else}status-upcoming{/if}">
                                                {$patient.status_formatted}
                                            </span>
                                        </td>
                                        <td>{$patient.last_visit_formatted}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="patient-profile?id={$patient.patient_id}" class="btn btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <button class="btn btn-outline-primary"
                                                    onclick="editPatient({$patient.patient_id})">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button class="btn btn-outline-primary"
                                                    onclick="scheduleAppointment({$patient.patient_id})">
                                                    <i class="bi bi-calendar-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                {/foreach}
                            {else}
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="bi bi-inbox text-muted fs-1"></i>
                                        <p class="text-muted mt-2">No patients found</p>
                                    </td>
                                </tr>
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <nav>
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Card view -->
        <div id="cardView" class="d-none">
            {if $patients}
                <div class="row g-4">
                    {foreach $patients as $patient}
                        <div class="col-md-6 col-lg-4">
                            <div class="card patient-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="patient-avatar me-3">
                                            <div class="avatar-placeholder">
                                                <span>{$patient.name_initial}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="card-title mb-0">{$patient.name}</h5>
                                            <p class="card-text text-muted mb-0">{$patient.breed}</p>
                                        </div>
                                    </div>
                                    <div class="patient-details">
                                        <p class="mb-1"><i class="bi bi-person me-2"></i> Owner: {$patient.owner_name}</p>
                                        <p class="mb-1"><i class="bi bi-calendar3 me-2"></i> Age: {if $patient.age}{$patient.age}
                                                year{if $patient.age != 1}s{/if}{else}N/A{/if}</p>
                                            <p class="mb-1"><i class="bi bi-gender-ambiguous me-2"></i> {$patient.gender_formatted}</p>
                                            <p class="mb-1"><i class="bi bi-calendar-check me-2"></i> Last Visit:
                                                {$patient.last_visit_formatted}</p>
                                            <p class="mb-1"><i class="bi bi-info-circle me-2"></i> Status:
                                                <span
                                                    class="status-badge {if $patient.status == 'active'}status-upcoming{elseif $patient.status == 'under treatment'}status-cancelled{elseif $patient.status == 'scheduled'}status-completed{else}status-upcoming{/if}">
                                                    {$patient.status_formatted}
                                                </span>
                                            </p>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <a href="patient-profile?id={$patient.patient_id}" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye"></i> View Details
                                            </a>
                                            <button class="btn btn-sm btn-outline-primary"
                                                onclick="scheduleAppointment({$patient.patient_id})">
                                                <i class="bi bi-calendar-plus"></i> Schedule
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {else}
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No patients found</h5>
                        <p class="text-muted">Try adjusting your search filters or add a new patient.</p>
                    </div>
                {/if}

                <div class="d-flex justify-content-center mt-4">
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>

        <!-- Add New Patient Modal -->
        <div class="modal fade" id="addPatientModal" tabindex="-1" aria-labelledby="addPatientModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPatientModalLabel">Add New Patient</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="newPatientForm" class="needs-validation" novalidate>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Pet Information</h6>
                                    <div class="mb-3">
                                        <label for="petName" class="form-label">Pet Name *</label>
                                        <input type="text" class="form-control" id="petName" required>
                                        <div class="invalid-feedback">
                                            Please provide the pet's name.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="petSpecies" class="form-label">Species *</label>
                                <select class="form-select" id="petSpecies" required>
                                    <option value="">Select Species</option>
                                    <option value="dog">Dog</option>
                                    <option value="cat">Cat</option>
                                    <option value="rabbit">Rabbit</option>
                                    <option value="bird">Bird</option>
                                    <option value="reptile">Reptile</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select the pet's species.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="petBreed" class="form-label">Breed *</label>
                                        <input type="text" class="form-control" id="petBreed" required>
                                        <div class="invalid-feedback">
                                            Please provide the pet's breed.
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="petAge" class="form-label">Age *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="petAge" min="0" required>
                                        <select class="form-select" id="ageUnit">
                                            <option value="years">Years</option>
                                            <option value="months">Months</option>
                                            <option value="weeks">Weeks</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please provide the pet's age.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="petGender" class="form-label">Gender *</label>
                                            <select class="form-select" id="petGender" required>
                                                <option value="">Select Gender</option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                Please select the pet's gender.
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="microchipID" class="form-label">Microchip ID</label>
                                <input type="text" class="form-control" id="microchipID">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Owner Information</h6>
                            <div class="mb-3">
                                <label for="ownerName" class="form-label">Owner Name *</label>
                                <input type="text" class="form-control" id="ownerName" required>
                                <div class="invalid-feedback">
                                    Please provide the owner's name.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ownerEmail" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="ownerEmail" required>
                                        <div class="invalid-feedback">
                                            Please provide a valid email address.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ownerPhone" class="form-label">Phone *</label>
                                        <input type="tel" class="form-control" id="ownerPhone" required>
                                        <div class="invalid-feedback">
                                            Please provide a contact number.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="ownerAddress" class="form-label">Address</label>
                                        <textarea class="form-control" id="ownerAddress" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="mb-3">Medical Information</h6>
                                    <div class="mb-3">
                                        <label for="patientStatus" class="form-label">Status *</label>
                                        <select class="form-select" id="patientStatus" required>
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="treatment">Under Treatment</option>
                                            <option value="scheduled">Scheduled</option>
                                            <option value="recovery">Recovery</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select the patient's status.
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="medicalHistory" class="form-label">Medical History</label>
                                        <textarea class="form-control" id="medicalHistory" rows="3"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="allergies" class="form-label">Allergies/Special Notes</label>
                                        <textarea class="form-control" id="allergies" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="savePatientBtn">Save Patient</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="./js/scripts-vendor.min.js"></script>
        <script src="./js/scripts.min.js"></script>
        <script src="./js/patients.js"></script>
        <script src="./js/page-transitions.js"></script>
    {/block}