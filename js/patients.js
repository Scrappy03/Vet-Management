// Patient Management System JavaScript
document.addEventListener('DOMContentLoaded', function () {
    // Initialize variables
    let currentEditingPatientId = null;

    // DOM elements
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardViewBtn = document.getElementById('cardViewBtn');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');
    const form = document.getElementById('newPatientForm');
    const saveButton = document.getElementById('savePatientBtn');
    const addPatientModal = document.getElementById('addPatientModal');
    const modalTitle = document.getElementById('addPatientModalLabel');

    // View switching functionality
    if (tableViewBtn && cardViewBtn && tableView && cardView) {
        tableViewBtn.addEventListener('click', function () {
            tableView.classList.remove('d-none');
            cardView.classList.add('d-none');
            tableViewBtn.classList.add('active');
            cardViewBtn.classList.remove('active');
        });

        cardViewBtn.addEventListener('click', function () {
            tableView.classList.add('d-none');
            cardView.classList.remove('d-none');
            tableViewBtn.classList.remove('active');
            cardViewBtn.classList.add('active');
        });
    }

    // Form submission handling
    if (saveButton && form) {
        saveButton.addEventListener('click', function (event) {
            event.preventDefault();

            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Disable button to prevent double submission
            saveButton.disabled = true;
            saveButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

            // Collect form data
            const formData = collectFormData();

            if (currentEditingPatientId) {
                // Update existing patient
                formData.patient_id = currentEditingPatientId;
                updatePatient(formData);
            } else {
                // Create new patient
                createPatient(formData);
            }
        });
    }

    // Reset form when modal is hidden
    if (addPatientModal) {
        addPatientModal.addEventListener('hidden.bs.modal', function () {
            resetForm();
        });
    }

    // Collect form data function
    function collectFormData() {
        const neuteredValue = document.getElementById('petNeutered').value;
        return {
            name: document.getElementById('petName').value.trim(),
            species: document.getElementById('petSpecies').value,
            breed: document.getElementById('petBreed').value.trim(),
            age: parseInt(document.getElementById('petAge').value),
            gender: document.getElementById('petGender').value,
            neutered: neuteredValue === '' ? null : parseInt(neuteredValue),
            microchip_id: document.getElementById('microchipID').value.trim() || null,
            owner_name: document.getElementById('ownerName').value.trim(),
            owner_email: document.getElementById('ownerEmail').value.trim(),
            owner_phone: document.getElementById('ownerPhone').value.trim(),
            owner_address: document.getElementById('ownerAddress').value.trim() || null,
            status: document.getElementById('patientStatus').value,
            allergies: document.getElementById('allergies').value.trim() || null
        };
    }

    // Create patient function
    async function createPatient(patientData) {
        try {
            const response = await fetch('./api/patients.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(patientData)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Patient created successfully!', 'success');
                closeModal();
                refreshPatientsList();
            } else {
                showToast('Error creating patient: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Error creating patient:', error);
            showToast('Network error. Please try again.', 'error');
        } finally {
            resetSaveButton();
        }
    }

    // Update patient function
    async function updatePatient(patientData) {
        try {
            const response = await fetch('./api/patients.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(patientData)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Patient updated successfully!', 'success');
                closeModal();
                refreshPatientsList();
            } else {
                showToast('Error updating patient: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Error updating patient:', error);
            showToast('Network error. Please try again.', 'error');
        } finally {
            resetSaveButton();
        }
    }

    // Delete patient function
    async function deletePatient(patientId) {
        showConfirmDialog(
            'Are you sure you want to delete this patient? This action cannot be undone.',
            function () {
                performPatientDeletion(patientId);
            }
        );
    }

    // Separate function to perform the actual deletion
    async function performPatientDeletion(patientId) {
        try {
            const response = await fetch('./api/patients.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ patient_id: patientId })
            });

            const result = await response.json();

            if (result.success) {
                showToast('Patient deleted successfully!', 'success');
                refreshPatientsList();
            } else {
                showToast('Error deleting patient: ' + result.error, 'error');
            }
        } catch (error) {
            console.error('Error deleting patient:', error);
            showToast('Network error. Please try again.', 'error');
        }
    }

    // Edit patient function
    async function editPatient(patientId) {
        try {
            const response = await fetch(`./api/patients.php?id=${patientId}`);
            const result = await response.json();

            if (result.success && result.patient) {
                populateForm(result.patient);
                currentEditingPatientId = patientId;
                modalTitle.textContent = 'Edit Patient';
                saveButton.textContent = 'Update Patient';

                // Show the modal
                const modal = new bootstrap.Modal(addPatientModal);
                modal.show();
            } else {
                showToast('Error loading patient data: ' + (result.error || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('Error loading patient:', error);
            showToast('Network error. Please try again.', 'error');
        }
    }

    // Populate form with patient data
    function populateForm(patient) {
        document.getElementById('petName').value = patient.name || '';
        document.getElementById('petSpecies').value = patient.species || '';
        document.getElementById('petBreed').value = patient.breed || '';
        document.getElementById('petAge').value = patient.age || '';
        document.getElementById('petGender').value = patient.gender || '';
        document.getElementById('petNeutered').value = patient.neutered !== null ? patient.neutered.toString() : '';
        document.getElementById('microchipID').value = patient.microchip_id || '';

        // Reconstruct owner name from first_name and last_name or use owner_name if available
        let ownerName = '';
        if (patient.owner_name) {
            ownerName = patient.owner_name;
        } else if (patient.first_name || patient.last_name) {
            ownerName = (patient.first_name || '') + ' ' + (patient.last_name || '');
            ownerName = ownerName.trim();
        }

        document.getElementById('ownerName').value = ownerName;
        document.getElementById('ownerEmail').value = patient.email || patient.owner_email || '';
        document.getElementById('ownerPhone').value = patient.phone || patient.owner_phone || '';
        document.getElementById('ownerAddress').value = patient.address || patient.owner_address || '';
        document.getElementById('patientStatus').value = patient.status || '';
        document.getElementById('allergies').value = patient.allergies || '';
    }

    // Reset form
    function resetForm() {
        if (form) {
            form.reset();
            form.classList.remove('was-validated');
        }
        currentEditingPatientId = null;
        modalTitle.textContent = 'Add New Patient';
        saveButton.textContent = 'Save Patient';
        resetSaveButton();
    }

    // Reset save button
    function resetSaveButton() {
        if (saveButton) {
            saveButton.disabled = false;
            saveButton.innerHTML = currentEditingPatientId ? 'Update Patient' : 'Save Patient';
        }
    }

    // Close modal
    function closeModal() {
        if (addPatientModal) {
            const modal = bootstrap.Modal.getInstance(addPatientModal);
            if (modal) {
                modal.hide();
            }
        }
    }

    // Refresh patients list
    function refreshPatientsList() {
        // Reload the page to show updated patient list
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    // Simple search functionality
    function initializeSearch() {
        const searchInput = document.getElementById('patientSearch');
        const speciesFilter = document.getElementById('speciesFilter');
        const clearButton = document.getElementById('clearSearch');

        if (!searchInput || !speciesFilter || !clearButton) return;

        // Get all patient rows and cards
        function getPatientElements() {
            return {
                tableRows: document.querySelectorAll('#tableView tbody tr'),
                cards: document.querySelectorAll('#cardView .col-md-6, #cardView .col-lg-4')
            };
        }

        // Filter patients based on search criteria
        function filterPatients() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedSpecies = speciesFilter.value.toLowerCase();
            const { tableRows, cards } = getPatientElements();

            // Filter table rows
            tableRows.forEach(row => {
                const cells = row.querySelectorAll('td');
                if (cells.length === 0) return; // Skip if no cells (header row)

                const petName = cells[1]?.textContent.toLowerCase() || '';
                const species = cells[2]?.textContent.toLowerCase() || '';
                const breed = cells[3]?.textContent.toLowerCase() || '';
                const owner = cells[5]?.textContent.toLowerCase() || '';

                const matchesSearch = !searchTerm ||
                    petName.includes(searchTerm) ||
                    breed.includes(searchTerm) ||
                    owner.includes(searchTerm) ||
                    species.includes(searchTerm);

                const matchesSpecies = !selectedSpecies || species.includes(selectedSpecies);

                row.style.display = (matchesSearch && matchesSpecies) ? '' : 'none';
            });

            // Filter cards
            cards.forEach(card => {
                const cardTitle = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
                const cardText = card.querySelector('.card-text')?.textContent.toLowerCase() || '';
                const patientDetails = card.querySelector('.patient-details')?.textContent.toLowerCase() || '';
                const cardContent = cardTitle + ' ' + cardText + ' ' + patientDetails;

                const matchesSearch = !searchTerm || cardContent.includes(searchTerm);
                const matchesSpecies = !selectedSpecies || cardContent.includes(selectedSpecies);

                card.style.display = (matchesSearch && matchesSpecies) ? '' : 'none';
            });

            // Update result count
            updateResultCount();
        }

        // Update result count display
        function updateResultCount() {
            const { tableRows, cards } = getPatientElements();
            const isTableView = !document.getElementById('tableView').classList.contains('d-none');

            let visibleCount = 0;
            if (isTableView) {
                visibleCount = Array.from(tableRows).filter(row =>
                    row.style.display !== 'none' && row.querySelectorAll('td').length > 0
                ).length;
            } else {
                visibleCount = Array.from(cards).filter(card =>
                    card.style.display !== 'none'
                ).length;
            }

            // Update search input placeholder with count
            if (searchInput.value.trim() || speciesFilter.value) {
                searchInput.placeholder = `${visibleCount} patient(s) found`;
            } else {
                searchInput.placeholder = 'Search patients by name, breed, or owner...';
            }
        }

        // Clear search
        function clearSearch() {
            searchInput.value = '';
            speciesFilter.value = '';
            filterPatients();
        }

        // Event listeners
        searchInput.addEventListener('input', filterPatients);
        speciesFilter.addEventListener('change', filterPatients);
        clearButton.addEventListener('click', clearSearch);
    }

    // Initialize search after DOM is loaded
    initializeSearch();

    // Make functions globally available
    window.editPatient = editPatient;
    window.deletePatient = deletePatient;
    window.scheduleAppointment = scheduleAppointment;
});