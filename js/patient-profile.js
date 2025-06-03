document.addEventListener('DOMContentLoaded', function () {
    // Get patient ID from URL parameters or a data attribute
    const urlParams = new URLSearchParams(window.location.search);
    const patientId = urlParams.get('id');

    if (patientId) {
        loadPatientData(patientId);
    }

    // Load patient data from API
    function loadPatientData(patientId) {
        // Show loading state
        showLoadingState();

        fetch(`api/patients.php?id=${patientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.patient) {
                    populatePatientData(data.patient);
                    populateUpcomingAppointments(data.upcoming_appointments || []);
                    populateMedicalHistory(data.medical_history || []);
                    hideLoadingState();
                } else {
                    showError('Failed to load patient data');
                }
            })
            .catch(error => {
                console.error('Error loading patient data:', error);
                showError('Error loading patient information');
            });
    }

    function populatePatientData(patient) {
        // Update avatar initial
        const avatarInitial = document.getElementById('avatarInitial');
        if (avatarInitial && patient.name) {
            avatarInitial.textContent = patient.name.charAt(0).toUpperCase();
        }

        // Update profile header
        const profileName = document.getElementById('profileName');
        if (profileName) {
            const statusBadge = patient.status === 'active' ?
                '<span class="badge bg-success fs-6 align-middle">Active</span>' :
                '<span class="badge bg-secondary fs-6 align-middle">Inactive</span>';
            profileName.innerHTML = `${patient.name} ${statusBadge}`;
        }

        // Update profile details
        const profileDetails = document.getElementById('profileDetails');
        if (profileDetails) {
            const genderText = patient.gender ? patient.gender.charAt(0).toUpperCase() + patient.gender.slice(1) : '';
            profileDetails.textContent = `${patient.breed || patient.species} • ${genderText} • ${patient.age} years old`;
        }

        // Update owner information
        const profileOwner = document.getElementById('profileOwner');
        const profilePhone = document.getElementById('profilePhone');
        const profileEmail = document.getElementById('profileEmail');

        if (profileOwner) profileOwner.textContent = patient.owner_name || '';
        if (profilePhone) profilePhone.textContent = patient.phone || '';
        if (profileEmail) profileEmail.textContent = patient.email || '';

        // Update allergies
        const profileAllergies = document.getElementById('profileAllergies');
        if (profileAllergies) {
            profileAllergies.textContent = patient.allergies || 'None listed';
        }

        // Update basic information card
        const infoSpecies = document.getElementById('infoSpecies');
        const infoBreed = document.getElementById('infoBreed');
        const infoDob = document.getElementById('infoDob');
        const infoGender = document.getElementById('infoGender');
        const infoWeight = document.getElementById('infoWeight');
        const infoMicrochip = document.getElementById('infoMicrochip');

        if (infoSpecies) infoSpecies.textContent = patient.species || '';
        if (infoBreed) infoBreed.textContent = patient.breed || '';

        if (infoDob && patient.date_of_birth) {
            const dobDate = new Date(patient.date_of_birth);
            const formattedDob = dobDate.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
            infoDob.textContent = `${formattedDob} (${patient.age} years)`;
        }

        if (infoGender) {
            const genderText = patient.gender ? patient.gender.charAt(0).toUpperCase() + patient.gender.slice(1) : '';
            // You could add neutered status here if you have that field in the database
            infoGender.textContent = genderText;
        }

        if (infoWeight) {
            infoWeight.textContent = patient.weight ? `${patient.weight}kg` : 'Not recorded';
        }

        if (infoMicrochip) {
            infoMicrochip.textContent = patient.microchip_id || 'Not recorded';
        }

        // Populate form fields for editing
        populateEditForm(patient);
    }

    function populateEditForm(patient) {
        // Populate the edit form with current patient data
        const formFields = {
            'petName': patient.name,
            'petStatus': patient.status,
            'petSpecies': patient.species,
            'petBreed': patient.breed,
            'petGender': patient.gender,
            'petDob': patient.date_of_birth,
            'petWeight': patient.weight,
            'petMicrochip': patient.microchip_id,
            'petAllergies': patient.allergies,
            'ownerName': patient.owner_name,
            'ownerPhone': patient.phone,
            'ownerEmail': patient.email
        };

        Object.entries(formFields).forEach(([fieldId, value]) => {
            const field = document.getElementById(fieldId);
            if (field && value) {
                field.value = value;
            }
        });
    }

    function populateUpcomingAppointments(appointments) {
        const appointmentContainer = document.querySelector('.card-body .appointment-item')?.parentElement;
        if (!appointmentContainer) return;

        if (appointments.length === 0) {
            appointmentContainer.innerHTML = `
                <div class="p-3 text-center text-muted">
                    <i class="bi bi-calendar-x fs-1 mb-2"></i>
                    <p class="mb-0">No upcoming appointments</p>
                </div>
            `;
            return;
        }

        appointmentContainer.innerHTML = appointments.map(appointment => {
            const startTime = new Date(appointment.start_time);
            const month = startTime.toLocaleDateString('en-US', { month: 'short' });
            const day = startTime.getDate();
            const time = startTime.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            });

            return `
                <div class="appointment-item mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="appointment-date me-3 text-center">
                            <div class="date-box">
                                <div class="month">${month}</div>
                                <div class="day">${day}</div>
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">${appointment.appointment_type}</h6>
                            <p class="text-muted small mb-0">${time} with ${appointment.staff_name || 'TBD'}</p>
                        </div>
                    </div>
                    <div class="d-flex mt-2 gap-2">
                        <button class="btn btn-sm btn-outline-primary flex-grow-1">
                            <i class="bi bi-calendar-x"></i> Reschedule
                        </button>
                        <button class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    function populateMedicalHistory(history) {
        const historyTableBody = document.querySelector('.table-responsive table tbody');
        if (!historyTableBody) return;

        if (history.length === 0) {
            historyTableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-3 text-muted">
                        No medical history records found
                    </td>
                </tr>
            `;
            return;
        }

        historyTableBody.innerHTML = history.map(record => {
            const date = new Date(record.start_time).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });

            return `
                <tr>
                    <td>${date}</td>
                    <td>${record.appointment_type}</td>
                    <td>${record.notes || 'No description'}</td>
                    <td>${record.staff_name || 'Unknown'}</td>
                    <td>
                        <span class="text-truncate d-inline-block" style="max-width: 150px;">
                            ${record.notes || 'No additional notes'}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function showLoadingState() {
        // Show loading indicators
        const profileName = document.getElementById('profileName');
        if (profileName) {
            profileName.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Loading...';
        }
    }

    function hideLoadingState() {
        // Hide loading indicators - data population will handle this
    }

    function showError(message) {
        // Show error message
        const main = document.querySelector('.dashboard-main');
        if (main) {
            main.innerHTML = `
                <div class="container mt-5">
                    <div class="alert alert-danger text-center">
                        <i class="bi bi-exclamation-triangle fs-1 mb-3"></i>
                        <h4>${message}</h4>
                        <p class="mb-0">Please try again or go back to the patients list.</p>
                        <a href="patients" class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-left"></i> Back to Patients
                        </a>
                    </div>
                </div>
            `;
        }
    }

    // Initialize the edit profile modal
    const editProfileBtn = document.getElementById('editProfileBtn');
    const editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    const saveChangesBtn = document.getElementById('saveProfileChangesBtn');

    // Show the edit profile modal when the edit button is clicked
    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', function () {
            editProfileModal.show();
        });
    }

    // Handle saving form changes and updating the page
    if (saveChangesBtn) {
        saveChangesBtn.addEventListener('click', function () {
            savePatientChanges(patientId);
        });
    }

    function savePatientChanges(patientId) {
        // Gather form input values
        const formData = {
            patient_id: patientId,
            name: document.getElementById('petName').value,
            status: document.getElementById('petStatus').value,
            species: document.getElementById('petSpecies').value,
            breed: document.getElementById('petBreed').value,
            gender: document.getElementById('petGender').value,
            date_of_birth: document.getElementById('petDob').value,
            weight: document.getElementById('petWeight').value,
            microchip_id: document.getElementById('petMicrochip').value,
            allergies: document.getElementById('petAllergies').value,
            owner_first_name: document.getElementById('ownerName').value.split(' ')[0],
            owner_last_name: document.getElementById('ownerName').value.split(' ').slice(1).join(' '),
            owner_phone: document.getElementById('ownerPhone').value,
            owner_email: document.getElementById('ownerEmail').value
        };

        // Save to API
        fetch('api/patients.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload patient data to reflect changes
                    loadPatientData(patientId);

                    // Close the modal
                    editProfileModal.hide();

                    // Show success message
                    const successToast = new bootstrap.Toast(document.getElementById('saveSuccessToast'));
                    successToast.show();
                } else {
                    alert('Error saving changes: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error saving patient changes:', error);
                alert('Error saving changes. Please try again.');
            });
    }
});
// Update page with new information
// Update pet name and status badge
const profileName = document.getElementById('profileName');
profileName.innerHTML = `${petName} <span class="badge ${petStatus === 'active' ? 'bg-success' : 'bg-secondary'} fs-6 align-middle">${petStatus === 'active' ? 'Active' : 'Inactive'}</span>`;

// Update pet details
const profileDetails = document.getElementById('profileDetails');
profileDetails.textContent = `${petBreed} • ${petGender.charAt(0).toUpperCase() + petGender.slice(1)} • ${age} years old`;

// Update owner information
const profileOwner = document.getElementById('profileOwner');
const profilePhone = document.getElementById('profilePhone');
const profileEmail = document.getElementById('profileEmail');

profileOwner.textContent = ownerName;
profilePhone.textContent = ownerPhone;
profileEmail.textContent = ownerEmail;

// Update allergies display
const profileAllergies = document.getElementById('profileAllergies');
profileAllergies.textContent = petAllergies;

// Update basic information card
document.getElementById('infoSpecies').textContent = petSpecies;
document.getElementById('infoBreed').textContent = petBreed;
document.getElementById('infoDob').textContent = `${formattedDob} (${age} years)`;

const neuteredText = petNeutered === 'yes' ? 'Neutered' : 'Not Neutered';
document.getElementById('infoGender').textContent = `${petGender.charAt(0).toUpperCase() + petGender.slice(1)} (${neuteredText})`;
document.getElementById('infoWeight').textContent = `${petWeight}kg (${new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })})`;
document.getElementById('infoMicrochip').textContent = petMicrochip;

// Update avatar initial if name changes
const avatarInitial = document.getElementById('avatarInitial');
avatarInitial.textContent = petName.charAt(0);

// Hide the modal and show a success toast after saving
// Close the modal
editProfileModal.hide();
// Show a success message
const successToast = new bootstrap.Toast(document.getElementById('saveSuccessToast'));
successToast.show();
