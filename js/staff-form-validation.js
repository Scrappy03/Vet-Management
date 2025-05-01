document.addEventListener('DOMContentLoaded', function () {
    // Listen for DOM ready
    const forms = document.querySelectorAll('.needs-validation');

    Array.from(forms).forEach(form => {
        // Submit event handling, validation, data collection
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                event.preventDefault();

                // Collect data and log it
                const formData = {
                    firstName: document.getElementById('firstName').value,
                    lastName: document.getElementById('lastName').value,
                    email: document.getElementById('staffEmail').value,
                    phone: document.getElementById('staffPhone').value,
                    role: document.getElementById('staffRole').value,
                    startDate: document.getElementById('startDate').value,
                    specialties: document.getElementById('specialties').value,
                    education: document.getElementById('education').value,
                    bio: document.getElementById('staffBio').value
                };

                console.log('Staff data submitted:', formData);

                const modal = bootstrap.Modal.getInstance(document.getElementById('addStaffModal'));

                // Display success alert and hide modal
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success alert-dismissible fade show';
                alertDiv.setAttribute('role', 'alert');
                alertDiv.innerHTML = `
                    <strong>Success!</strong> New staff member ${formData.firstName} ${formData.lastName} has been added.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;

                modal.hide();

                // Show the alert at the top of the dashboard content
                const dashboardContent = document.querySelector('.dashboard-content');
                dashboardContent.insertBefore(alertDiv, dashboardContent.firstChild);

                // Reset form and auto-dismiss alert
                form.reset();
                form.classList.remove('was-validated');

                // Auto-dismiss alert after delay
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alertDiv);
                    bsAlert.close();
                }, 5000);
            }

            form.classList.add('was-validated');
        }, false);
    });

    // Validate email and phone fields
    // Validate email address
    const emailInput = document.getElementById('staffEmail');
    if (emailInput) {
        emailInput.addEventListener('input', function () {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailPattern.test(this.value)) {
                this.setCustomValidity('Please enter a valid email address');
            } else {
                this.setCustomValidity('');
            }
        });
    }

    // Validate phone number
    const phoneInput = document.getElementById('staffPhone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            // Basic phone validation - adjust as needed for your region
            const phonePattern = /^[0-9()\-\s+]{7,20}$/;
            if (this.value && !phonePattern.test(this.value)) {
                this.setCustomValidity('Please enter a valid phone number');
            } else {
                this.setCustomValidity('');
            }
        });
    }
});
