document.addEventListener('DOMContentLoaded', function () {
    const tableViewBtn = document.getElementById('tableViewBtn');
    const cardViewBtn = document.getElementById('cardViewBtn');
    const tableView = document.getElementById('tableView');
    const cardView = document.getElementById('cardView');

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

    // Form validation
    const form = document.getElementById('newPatientForm');
    const saveButton = document.getElementById('savePatientBtn');

    saveButton.addEventListener('click', function () {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
        } else {
            // Form is valid, we would normally submit the data here
            // For now just show success and close the modal
            alert('Patient successfully added!');
            const modal = bootstrap.Modal.getInstance(document.getElementById('addPatientModal'));
            modal.hide();
            form.reset();
            form.classList.remove('was-validated');
        }
    });

    // Reset form when modal is hidden
    const addPatientModal = document.getElementById('addPatientModal');
    addPatientModal.addEventListener('hidden.bs.modal', function () {
        form.reset();
        form.classList.remove('was-validated');
    });
});