{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}

    <div class="dashboard-sidebar">
        <div class="text-center mb-4 content-transition">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4 content-transition">
            <a href="index.php?p=dashboard" class="dashboard-nav-link">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="index.php?p=calendar" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="index.php?p=patients" class="dashboard-nav-link">
                <i class="bi bi-clipboard2-pulse"></i>
                Patients
            </a>
            <a href="index.php?p=staff" class="dashboard-nav-link active">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="index.php?p=settings" class="dashboard-nav-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>

        <div class="mt-auto">
            <a href="index.php?p=logout" class="btn btn-sm btn-outline-danger w-100">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>

    <div class="dashboard-container">
        <div class="dashboard-main">
            <div class="dashboard-content">
                <div class="d-flex justify-content-between align-items-center mb-4 content-transition">
                    <h4 class="mb-0">Staff Management</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="bi bi-person-plus"></i> Add New Staff
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-calendar-check"></i> Manage Schedules
                        </button>
                    </div>
                </div>

                <!-- Filter section -->
                <div class="filter-container card mb-4">
                    <div class="card-body">
                        <form class="row g-3">
                            <div class="col-md-4">
                                <label for="searchStaff" class="form-label">Search</label>
                                <input type="text" class="form-control" id="searchStaff"
                                    placeholder="Search staff by name...">
                            </div>
                            <div class="col-md-3">
                                <label for="filterRole" class="form-label">Role</label>
                                <select class="form-select" id="filterRole">
                                    <option value="all">All Roles</option>
                                    <option value="veterinarian">Veterinarian</option>
                                    <option value="technician">Vet Technician</option>
                                    <option value="receptionist">Receptionist</option>
                                    <option value="assistant">Assistant</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterStatus" class="form-label">Status</label>
                                <select class="form-select" id="filterStatus">
                                    <option value="all">All Statuses</option>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label d-none d-md-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="content-body">
                    <div class="staff-cards">
                        <div class="row">
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="staff-card card h-100">
                                    <div class="staff-status available"></div>
                                    <div class="staff-initials-placeholder">ES</div>
                                    <div class="card-body">
                                        <h5 class="card-title">Dr. Emily Smith</h5>
                                        <p class="staff-role">Head Veterinarian</p>
                                        <p class="staff-specialty"><i class="fas fa-stethoscope"></i> Surgery, Internal
                                            Medicine</p>
                                        <div class="staff-stats">
                                            <div class="stat">
                                                <span class="stat-value">12</span>
                                                <span class="stat-label">Years</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">87</span>
                                                <span class="stat-label">Patients</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">4.9</span>
                                                <span class="stat-label">Rating</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewScheduleModal">
                                            <i class="bi bi-calendar-week"></i> Schedule
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#viewProfileModal">
                                            <i class="bi bi-person"></i> Profile
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Card 2 -->
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="staff-card card h-100">
                                    <div class="staff-status unavailable"></div>
                                    <div class="staff-initials-placeholder">MJ</div>
                                    <div class="card-body">
                                        <h5 class="card-title">Dr. Marcus Johnson</h5>
                                        <p class="staff-role">Veterinarian</p>
                                        <p class="staff-specialty"><i class="bi bi-heart-pulse"></i> Dermatology,
                                            Dentistry
                                        </p>
                                        <div class="staff-stats">
                                            <div class="stat">
                                                <span class="stat-value">8</span>
                                                <span class="stat-label">Years</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">62</span>
                                                <span class="stat-label">Patients</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">4.7</span>
                                                <span class="stat-label">Rating</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewScheduleModal">
                                            <i class="bi bi-calendar-week"></i> Schedule
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#viewProfileModal">
                                            <i class="bi bi-person"></i> Profile
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Add more staff cards here -->
                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="staff-card card h-100">
                                    <div class="staff-status available"></div>
                                    <div class="staff-initials-placeholder">MR</div>
                                    <div class="card-body">
                                        <h5 class="card-title">Maria Rodriguez</h5>
                                        <p class="staff-role">Vet Technician</p>
                                        <p class="staff-specialty"><i class="bi bi-bandaid"></i> Emergency Care, Lab
                                            Tests
                                        </p>
                                        <div class="staff-stats">
                                            <div class="stat">
                                                <span class="stat-value">5</span>
                                                <span class="stat-label">Years</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">124</span>
                                                <span class="stat-label">Patients</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">4.8</span>
                                                <span class="stat-label">Rating</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewScheduleModal">
                                            <i class="bi bi-calendar-week"></i> Schedule
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#viewProfileModal">
                                            <i class="bi bi-person"></i> Profile
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                                <div class="staff-card card h-100">
                                    <div class="staff-status available"></div>
                                    <div class="staff-initials-placeholder">JK</div>
                                    <div class="card-body">
                                        <h5 class="card-title">Jessica Kim</h5>
                                        <p class="staff-role">Receptionist</p>
                                        <p class="staff-specialty"><i class="bi bi-telephone"></i> Scheduling, Client
                                            Relations</p>
                                        <div class="staff-stats">
                                            <div class="stat">
                                                <span class="stat-value">3</span>
                                                <span class="stat-label">Years</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">-</span>
                                                <span class="stat-label">Patients</span>
                                            </div>
                                            <div class="stat">
                                                <span class="stat-value">4.9</span>
                                                <span class="stat-label">Rating</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#viewScheduleModal">
                                            <i class="bi bi-calendar-week"></i> Schedule
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal"
                                            data-bs-target="#viewProfileModal">
                                            <i class="bi bi-person"></i> Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Schedule Modal -->
    <div class="modal fade" id="viewScheduleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Staff Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Appointments</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Monday</td>
                                    <td>8:00 AM</td>
                                    <td>5:00 PM</td>
                                    <td>6</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                </tr>
                                <tr>
                                    <td>Tuesday</td>
                                    <td>8:00 AM</td>
                                    <td>5:00 PM</td>
                                    <td>8</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                </tr>
                                <tr>
                                    <td>Wednesday</td>
                                    <td>10:00 AM</td>
                                    <td>7:00 PM</td>
                                    <td>5</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                </tr>
                                <tr>
                                    <td>Thursday</td>
                                    <td>8:00 AM</td>
                                    <td>5:00 PM</td>
                                    <td>7</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                </tr>
                                <tr>
                                    <td>Friday</td>
                                    <td>8:00 AM</td>
                                    <td>5:00 PM</td>
                                    <td>4</td>
                                    <td><span class="badge bg-success">Available</span></td>
                                </tr>
                                <tr>
                                    <td>Saturday</td>
                                    <td>9:00 AM</td>
                                    <td>2:00 PM</td>
                                    <td>3</td>
                                    <td><span class="badge bg-warning">Limited</span></td>
                                </tr>
                                <tr>
                                    <td>Sunday</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td>-</td>
                                    <td><span class="badge bg-danger">Unavailable</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Edit Schedule</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Profile Modal -->
    <div class="modal fade" id="viewProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Staff Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <div class="profile-initials">ES</div>
                        <h4 class="mt-2">Dr. Emily Smith</h4>
                        <p class="text-muted">Head Veterinarian</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Email:</strong> emily.smith@vetcare.com</p>
                            <p><strong>Phone:</strong> 123456789</p>
                            <p><strong>Joined:</strong> January 15, 2012</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Specialties:</strong> Surgery, Internal Medicine</p>
                            <p><strong>Education:</strong> DVM, Cornell University</p>
                            <p><strong>Languages:</strong> English, Spanish</p>
                        </div>
                    </div>

                    <h6>About</h6>
                    <p>Dr. Smith has over 12 years of experience in veterinary medicine with a special focus on surgery
                        and
                        internal medicine. She has been with VetCare since 2012 and is passionate about providing
                        comprehensive care for all animals.</p>

                    <h6>Certifications</h6>
                    <ul>
                        <li>American College of Veterinary Surgeons (ACVS)</li>
                        <li>Certified Veterinary Pain Practitioner</li>
                        <li>Advanced Cardiac Life Support</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Edit Profile</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStaffForm" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" required>
                                <div class="invalid-feedback">
                                    Please provide a first name.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" required>
                                <div class="invalid-feedback">
                                    Please provide a last name.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="staffEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="staffEmail" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="staffPhone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="staffPhone" required>
                                <div class="invalid-feedback">
                                    Please provide a phone number.
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="staffRole" class="form-label">Role</label>
                                <select class="form-select" id="staffRole" required>
                                    <option value="" selected disabled>Select a role</option>
                                    <option value="veterinarian">Veterinarian</option>
                                    <option value="technician">Veterinary Technician</option>
                                    <option value="assistant">Veterinary Assistant</option>
                                    <option value="receptionist">Receptionist</option>
                                    <option value="manager">Practice Manager</option>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a role.
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="startDate" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="startDate" required>
                                <div class="invalid-feedback">
                                    Please provide a start date.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="specialties" class="form-label">Specialties/Skills</label>
                            <input type="text" class="form-control" id="specialties"
                                placeholder="e.g., Surgery, Dentistry, Emergency Care">
                        </div>

                        <div class="mb-3">
                            <label for="education" class="form-label">Education/Certifications</label>
                            <textarea class="form-control" id="education" rows="2"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="staffBio" class="form-label">Bio</label>
                            <textarea class="form-control" id="staffBio" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addStaffForm" class="btn btn-primary">Add Staff Member</button>
                </div>
            </div>
        </div>
    </div>
{/block}