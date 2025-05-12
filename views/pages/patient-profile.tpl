{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}

    <div class="dashboard-sidebar">
        <div class="text-center mb-4">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4">
            <a href="dashboard.html" class="dashboard-nav-link">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="calendar.html" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="patients.html" class="dashboard-nav-link active">
                <i class="bi bi-clipboard2-pulse"></i>
                Patients
            </a>
            <a href="#" class="dashboard-nav-link">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="#" class="dashboard-nav-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>
    </div>

    <main class="dashboard-main">
        <!-- Back button and action buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="patients.html" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Back to Patients
                </a>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary">
                    <i class="bi bi-calendar-plus"></i> Schedule Appointment
                </button>
                <button class="btn btn-outline-primary" id="editProfileBtn">
                    <i class="bi bi-pencil"></i> Edit Profile
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" id="moreActionsDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        More Actions
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="moreActionsDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-medical"></i> Add Medical
                                Record</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-capsule"></i> Prescribe Medication</a>
                        </li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-printer"></i> Print Records</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash"></i> Archive
                                Patient</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Pet Profile Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <div class="patient-profile-avatar mx-auto mb-3 mb-md-0">
                            <div class="avatar-placeholder avatar-placeholder-large">
                                <span id="avatarInitial">M</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-1" id="profileName">Max <span class="badge bg-success fs-6 align-middle">Active</span>
                        </h3>
                        <p class="text-muted mb-2" id="profileDetails">Golden Retriever • Male • 5 years old</p>
                        <p class="mb-1"><strong>Owner:</strong> <span id="profileOwner">John Smith</span></p>
                        <p class="mb-1"><strong>Phone:</strong> <span id="profilePhone">(+44) 123456789</span></p>
                        <p class="mb-0"><strong>Email:</strong> <span id="profileEmail">john.smith@example.com</span>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column h-100 justify-content-center">
                            <div class="alert alert-info mb-2">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <div>
                                        <strong>Next visit:</strong> March 15, 2025 - Vaccination
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-warning mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <div>
                                        <strong>Allergies:</strong> <span id="profileAllergies">Adverse reactions
                                            N/A</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-8">
                <!-- Basic Information Card -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted small">Species</p>
                                <p class="mb-3" id="infoSpecies">Dog</p>

                                <p class="mb-1 text-muted small">Breed</p>
                                <p class="mb-3" id="infoBreed">Golden Retriever</p>

                                <p class="mb-1 text-muted small">Date of Birth</p>
                                <p class="mb-0" id="infoDob">April 12, 2020 (5 years)</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="mb-1 text-muted small">Gender</p>
                                <p class="mb-3" id="infoGender">Male (Neutered)</p>

                                <p class="mb-1 text-muted small">Weight</p>
                                <p class="mb-3" id="infoWeight">34kg (18 May 2024)</p>

                                <p class="mb-1 text-muted small">Microchip ID</p>
                                <p class="mb-0" id="infoMicrochip">915683528564</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical History Card -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Medical History</h5>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> Add</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Description</th>
                                        <th>Vet</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Mar 15, 2024</td>
                                        <td>Check-up</td>
                                        <td>Annual wellness examination</td>
                                        <td>Dr. Parker</td>
                                        <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Healthy
                                                overall. Ears, smell infection further testing required, </span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i
                                                    class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mar 15, 2024</td>
                                        <td>Treatment</td>
                                        <td>Ear infection</td>
                                        <td>Dr. Lee</td>
                                        <td><span class="text-truncate d-inline-block" style="max-width: 150px;">Prescribed
                                                ear drops for 7 days.</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i
                                                    class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Mar 15, 2024</td>
                                        <td>Vaccination</td>
                                        <td>Vaccines up to date</td>
                                        <td>Dr. Parker</td>
                                        <td><span class="text-truncate d-inline-block" style="max-width: 150px;">No
                                                adverse reactions.</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary"><i
                                                    class="bi bi-eye"></i></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="text-primary">View Full Medical History</a>
                    </div>
                </div>

                <!-- Prescriptions & Medications -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Prescriptions & Medications</h5>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i> Add</button>
                    </div>
                    <div class="card-body">
                        <div class="medication-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Heartworm Prevention</h6>
                                    <p class="text-muted small mb-2">Monthly tablet - 1 tablet on the 1st of each month
                                    </p>
                                    <p class="mb-0 small"><strong>Status:</strong> <span class="text-success">Active</span>
                                    </p>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary">Refill</button>
                                </div>
                            </div>
                        </div>
                        <div class="medication-item mb-3 p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Flea and Tick Prevention</h6>
                                    <p class="text-muted small mb-2">Monthly chewable - 1 tablet on the 15th of each
                                        month</p>
                                    <p class="mb-0 small"><strong>Status:</strong> <span class="text-success">Active</span>
                                    </p>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-primary">Refill</button>
                                </div>
                            </div>
                        </div>
                        <div class="medication-item p-3 border rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Ear Drops (Otibiotic)</h6>
                                    <p class="text-muted small mb-2">Apply 3 drops in each ear daily for 7 days</p>
                                    <p class="mb-0 small"><strong>Status:</strong> <span class="text-secondary">Completed
                                            (Dec 17, 2024)</span></p>
                                </div>
                                <div>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Completed</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Upcoming Appointments Card -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Upcoming Appointments</h5>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="appointment-item mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <div class="appointment-date me-3 text-center">
                                    <div class="date-box">
                                        <div class="month">May</div>
                                        <div class="day">15</div>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Vaccination</h6>
                                    <p class="text-muted small mb-0">10:30 AM with Dr. Parker</p>
                                </div>
                            </div>
                            <div class="d-flex mt-2 gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1"><i class="bi bi-calendar-x"></i>
                                    Reschedule</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Cancel</button>
                            </div>
                        </div>
                        <div class="appointment-item">
                            <div class="d-flex align-items-center mb-2">
                                <div class="appointment-date me-3 text-center">
                                    <div class="date-box">
                                        <div class="month">Sep</div>
                                        <div class="day">20</div>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Annual Check-up</h6>
                                    <p class="text-muted small mb-0">2:45 PM with Dr. Lee</p>
                                </div>
                            </div>
                            <div class="d-flex mt-2 gap-2">
                                <button class="btn btn-sm btn-outline-primary flex-grow-1"><i class="bi bi-calendar-x"></i>
                                    Reschedule</button>
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vaccination History -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Vaccination History</h5>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-plus-lg"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Vaccine</th>
                                        <th>Date</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>First Vaccine</td>
                                        <td>Sep 5, 2020</td>
                                        <td>Sep 5, 2020</td>
                                    </tr>
                                    <tr>
                                        <td>Second Vaccine</td>
                                        <td>Sep 22, 2020</td>
                                        <td>Sep 22, 2020</td>
                                    </tr>
                                    <tr>
                                        <td>Booster</td>
                                        <td>March 25, 2025</td>
                                        <td class="text-danger">Overdue</td>
                                    </tr>
                                    <tr>
                                        <td>Rabies</td>
                                        <td>August 29, 2024</td>
                                        <td>August 29, 2024</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                <div class="card mb-4">
                    <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Notes</h5>
                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge bg-warning">Behavior</span>
                            <p class="mt-2 mb-0">Tends to get anxious during nail trims. muzzle if necessary.</p>
                        </div>
                        <div>
                            <span class="badge bg-info">Diet</span>
                            <p class="mt-2 mb-0">On prescription diet for sensitive stomach. Owner reports good results,
                                continue recommendation.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- View Medical Record Modal -->
    <div class="modal fade" id="viewMedicalRecordModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Medical Record Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Date</p>
                            <p class="mb-3">March 15, 2023</p>

                            <p class="mb-1 text-muted small">Vet</p>
                            <p class="mb-3">Dr. Sarah Parker</p>

                            <p class="mb-1 text-muted small">Type</p>
                            <p class="mb-0">Annual wellness examination</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Weight</p>
                            <p class="mb-3">33 kg</p>

                            <p class="mb-1 text-muted small">Temperature</p>
                            <p class="mb-3">37°C</p>

                            <p class="mb-1 text-muted small">Heart Rate</p>
                            <p class="mb-0">80 bpm</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 text-muted small">Examination Notes</p>
                        <p>Overall in good health. Coat is healthy. Slight buildup on teeth, recommend
                            dental cleaning in next 3-6 months. Eyes and ears clear treatment went well. Heart and lung
                            sounds normal.
                            Slightly higher body condition, recommend reducing daily food intake by 10% and increasing
                            exercise.
                        </p>
                    </div>

                    <div class="mb-3">
                        <p class="mb-1 text-muted small">Diagnosis</p>
                        <p>Healthy with mild dental tartar and body condition 6 recommending 5.</p>
                    </div>

                    <div>
                        <p class="mb-1 text-muted small">Treatment Plan</p>
                        <ul>
                            <li>Schedule dental cleaning</li>
                            <li>Reduce food intake by 10%</li>
                            <li>Increase daily exercise by 15-20 minutes</li>
                            <li>Continue current preventative medications</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary"><i class="bi bi-printer"></i> Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Patient Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPatientForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="petName" class="form-label">Pet Name</label>
                                <input type="text" class="form-control" id="petName" value="Max">
                            </div>
                            <div class="col-md-6">
                                <label for="petStatus" class="form-label">Status</label>
                                <select class="form-select" id="petStatus">
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="petSpecies" class="form-label">Species</label>
                                <input type="text" class="form-control" id="petSpecies" value="Dog">
                            </div>
                            <div class="col-md-4">
                                <label for="petBreed" class="form-label">Breed</label>
                                <input type="text" class="form-control" id="petBreed" value="Golden Retriever">
                            </div>
                            <div class="col-md-4">
                                <label for="petGender" class="form-label">Gender</label>
                                <select class="form-select" id="petGender">
                                    <option value="male" selected>Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="petNeutered" class="form-label">Neutered/Spayed</label>
                                <select class="form-select" id="petNeutered">
                                    <option value="yes" selected>Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="petDob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="petDob" value="2020-04-12">
                            </div>
                            <div class="col-md-4">
                                <label for="petWeight" class="form-label">Weight (kg)</label>
                                <input type="number" class="form-control" id="petWeight" value="34">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="petMicrochip" class="form-label">Microchip ID</label>
                                <input type="text" class="form-control" id="petMicrochip" value="915683528564">
                            </div>
                            <div class="col-md-6">
                                <label for="petAllergies" class="form-label">Allergies</label>
                                <input type="text" class="form-control" id="petAllergies" value="N/A">
                            </div>
                        </div>

                        <h5 class="mt-4 mb-3">Owner Information</h5>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ownerName" class="form-label">Owner Name</label>
                                <input type="text" class="form-control" id="ownerName" value="John Smith">
                            </div>
                            <div class="col-md-6">
                                <label for="ownerPhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="ownerPhone" value="(+44) 123456789">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="ownerEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="ownerEmail" value="john.smith@example.com">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveProfileChangesBtn">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="saveSuccessToast" class="toast align-items-center text-white bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="bi bi-check-circle me-2"></i> Patient profile successfully updated!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
{/block}