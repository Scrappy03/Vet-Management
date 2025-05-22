{extends file="layouts/setting.tpl"}
{block name="body"}
    <!-- Script to toggle password visibility -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-password').forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    // Toggle password visibility
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.replace('bi-eye', 'bi-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.replace('bi-eye-slash', 'bi-eye');
                    }
                });
            });
        });
    </script>

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
            <a href="staff" class="dashboard-nav-link">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="settings" class="dashboard-nav-link active">
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

    <div class="settings-container">
        <h1 class="mb-4 pb-2 border-bottom" style="color: #2A6562; font-weight: 700;">Settings</h1>

        <div class="card settings-card"
            style="border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); border-left: 5px solid #7DBBB9;">
            <h2 style="color: #2A6562; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.25rem;">Appearance</h2>
            <div class="form-group">
                <label for="theme">Theme</label>
                <select id="theme" name="theme" class="form-control">
                    <option value="light">Light</option>
                    <option value="dark">Dark</option>
                    <option value="system">Use System Preference</option>
                </select>
            </div>
            <div class="form-group">
                <label for="font-size">Font Size</label>
                <select id="font-size" name="font-size" class="form-control">
                    <option value="small">Small</option>
                    <option value="medium" selected>Medium</option>
                    <option value="large">Large</option>
                    <option value="x-large">Extra Large</option>
                </select>
            </div>
        </div>

        <div class="card settings-card"
            style="border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); border-left: 5px solid #7DBBB9;">
            <h2 style="color: #2A6562; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.25rem;">Notifications</h2>
            <div class="form-group">
                <label class="checkbox-container">Email Notifications
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <label class="checkbox-container">Push Notifications
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <label class="checkbox-container">Newsletter Subscription
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>

        <div class="card settings-card"
            style="border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); border-left: 5px solid #7DBBB9;">
            <h2 style="color: #2A6562; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.25rem;">Privacy</h2>
            <div class="form-group">
                <label class="checkbox-container">Allow Cookies
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <label class="checkbox-container">Data Collection for Analytics
                    <input type="checkbox" checked>
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <label class="checkbox-container">Make Profile Public
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
        </div>

        <div class="card settings-card"
            style="border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); border-left: 5px solid #7DBBB9;">
            <h2 style="color: #2A6562; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.25rem;">Account</h2>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"
                    value="{if isset($user.email) && $user.email != ''}{$user.email}{else}user@example.com{/if}"
                    class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <button type="button" id="change-password-btn" class="btn btn-secondary" data-bs-toggle="modal"
                    data-bs-target="#changePasswordModal">Change
                    Password</button>
            </div>
            <div class="form-group">
                <label for="timezone" style="font-weight: 500; margin-bottom: 0.5rem; color: #495057;">Timezone</label>
                <select id="timezone" name="timezone" class="form-control"
                    style="border-radius: 8px; border: 1px solid #ced4da; padding: 0.625rem;">
                    <option value="utc">UTC</option>
                    <option value="est">Eastern Time</option>
                    <option value="cst">Central Time</option>
                    <option value="mst">Mountain Time</option>
                    <option value="pst">Pacific Time</option>
                </select>
            </div>
        </div>

        <div class="card settings-card"
            style="border-radius: 12px; box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05); border-left: 5px solid #7DBBB9;">
            <h2 style="color: #2A6562; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.25rem;">Advanced</h2>
            <div class="form-group">
                <label class="checkbox-container">Enable Experimental Features
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <button id="clear-cache" class="btn btn-secondary">Clear Cache</button>
            </div>
        </div>

        <div class="form-group">
            <button id="delete-account" class="btn btn-danger">Delete Account</button>
        </div>
    </div>

    <div class="btn-container"
        style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 2rem; margin-bottom: 3rem;">
        <button class="btn btn-secondary cancel">Cancel</button>
        <button class="btn btn-primary save">Save Changes</button>
    </div>
    </div>

    <!-- Change Password Modal with Enhanced UI -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header settings-modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">
                        <i class="bi bi-shield-lock-fill me-2"></i>Change Password
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {if $passwordMessage neq ''}
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <div>{$passwordMessage}</div>
                        </div>
                    {/if}
                    {if $passwordError neq ''}
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>{$passwordError}</div>
                        </div>
                    {/if}

                    <form id="changePasswordForm" method="post" action="settings">
                        <div class="mb-4">
                            <label for="current_password" class="form-label">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-text password-input-icon">
                                    <i class="bi bi-key"></i>
                                </span>
                                <input type="password" class="form-control password-input" id="current_password"
                                    name="current_password" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="new_password" class="form-label">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text password-input-icon">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input type="password" class="form-control password-input" id="new_password"
                                    name="new_password" minlength="6" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">Password must be at least 6 characters long</div>
                        </div>
                        <div class="mb-4">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <span class="input-group-text password-input-icon">
                                    <i class="bi bi-lock-fill"></i>
                                </span>
                                <input type="password" class="form-control password-input" id="confirm_password"
                                    name="confirm_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button" tabindex="-1">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="change_password" value="1">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end modal-button-group">
                            <button type="button" class="btn btn-light modal-cancel-btn" data-bs-dismiss="modal">
                                <i class="bi bi-x me-1"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary modal-submit-btn" id="changePasswordButton"
                                disabled>
                                <i class="bi bi-check2 me-1"></i>Change Password
                            </button>
                        </div>
                    </form>

                    <!-- Password strength indicator will be added here by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced script to handle setting changes with improved UX
        document.addEventListener('DOMContentLoaded', function() {
            const saveButton = document.querySelector('.save');
            const cancelButton = document.querySelector('.cancel');

            // Create toast notification function
            function showToast(message, type = 'success') {
                // Create toast container if it doesn't exist
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }

            // Create the toast
            const toastEl = document.createElement('div');
            toastEl.className = 'toast align-items-center text-white border-0';
            // Set background color based on type
            toastEl.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
            toastEl.setAttribute('role', 'alert');
            toastEl.setAttribute('aria-live', 'assertive');
            toastEl.setAttribute('aria-atomic', 'true');

            // Create toast content (without using template literals)
            const toastBody = document.createElement('div');
            toastBody.className = 'd-flex';

            const messageDiv = document.createElement('div');
            messageDiv.className = 'toast-body';
            messageDiv.textContent = message;

            const closeButton = document.createElement('button');
            closeButton.className = 'btn-close btn-close-white me-2 m-auto';
            closeButton.setAttribute('data-bs-dismiss', 'toast');
            closeButton.setAttribute('aria-label', 'Close');

            toastBody.appendChild(messageDiv);
            toastBody.appendChild(closeButton);
            toastEl.appendChild(toastBody);
            toastContainer.appendChild(toastEl);

            // Initialize and show the toast
            const toast = new bootstrap.Toast(toastEl, {
                animation: true,
                autohide: true,
                delay: 3000
            });
            toast.show();

            // Remove toast after it's hidden
                toastEl.addEventListener('hidden.bs.toast', function() {
                    toastEl.remove();
                });
            }

            // Save button handler with improved feedback
            saveButton.addEventListener('click', function() {
                // Simulate saving settings with a slight delay to show "working"
                saveButton.disabled = true;
                saveButton.innerHTML =
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';

                setTimeout(() => {
                    saveButton.disabled = false;
                    saveButton.innerHTML = 'Save Changes';
                    // Here you would typically save the settings to localStorage or send to a server
                    showToast('Settings saved successfully!', 'success');
                }, 800);
            });

            // Cancel button with improved UX
            cancelButton.addEventListener('click', function() {
                // Reset form or navigate away
                const modal = new bootstrap.Modal(document.createElement('div'));

                // Create a better confirmation dialog instead of using the browser's alert
            const confirmDialog = document.createElement('div');
            confirmDialog.className = 'modal fade';
            confirmDialog.id = 'confirmDiscardModal';
            confirmDialog.innerHTML =
                '<div class="modal-dialog modal-dialog-centered">' +
                '<div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">' +
                '<div class="modal-header bg-warning text-dark" style="border-bottom: none;">' +
                '<h5 class="modal-title">Discard Changes?</h5>' +
                '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                '</div>' +
                '<div class="modal-body">' +
                'Are you sure you want to discard all changes? This action cannot be undone.' +
                '</div>' +
                '<div class="modal-footer" style="border-top: none;">' +
                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
                '<button type="button" class="btn btn-warning" id="confirmDiscard">Discard Changes</button>' +
                '</div>' +
                '</div>' +
                '</div>';

            document.body.appendChild(confirmDialog);
            const discardModal = new bootstrap.Modal(confirmDialog);
            discardModal.show();

            document.getElementById('confirmDiscard').addEventListener('click', function() {
                window.location.reload();
            });

            confirmDialog.addEventListener('hidden.bs.modal', function() {
                confirmDialog.remove();
            });
        });

        // Clear cache with better confirmation
        document.getElementById('clear-cache').addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'clearCacheModal';
            modal.innerHTML =
                '<div class="modal-dialog modal-dialog-centered">' +
                '<div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">' +
                '<div class="modal-header bg-info text-white" style="border-bottom: none;">' +
                '<h5 class="modal-title">Clear Cache</h5>' +
                '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>' +
                '</div>' +
                '<div class="modal-body">' +
                'Are you sure you want to clear your application cache? This will reset any temporary data stored in your browser.' +
                '</div>' +
                '<div class="modal-footer" style="border-top: none;">' +
                '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
                '<button type="button" class="btn btn-info text-white" id="confirmClearCache">Clear Cache</button>' +
                '</div>' +
                '</div>' +
                '</div>';

            document.body.appendChild(modal);
            const cacheModal = new bootstrap.Modal(modal);
            cacheModal.show();

            document.getElementById('confirmClearCache').addEventListener('click', function() {
                cacheModal.hide();
                // Clear cache logic would go here
                showToast('Cache cleared successfully', 'success');
            });

            modal.addEventListener('hidden.bs.modal', function() {
                modal.remove();
            });
        });

        // Delete account with stronger confirmation
        document.getElementById('delete-account').addEventListener('click', function() {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.id = 'deleteAccountModal';
            modal.innerHTML =
                '<div class="modal-dialog modal-dialog-centered">' +
                '<div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">' +
                '<div class="modal-header bg-danger text-white" style="border-bottom: none;">' +
                '<h5 class="modal-title">Delete Account</h5>' +
                '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>' +
                '</div>' +
                '<div class="modal-body">' +
                '<p class="fw-bold text-danger">WARNING: This action is irreversible!</p>' +
                '<p>Deleting your account will permanently remove all of your data, including:</p>' +
                '<ul>' +
                '<li>Your profile information</li>' +
                '<li>Patient records you\'ve created</li>' +
                    '<li>Appointment history</li>' +
                    '<li>Custom settings</li>' +
                    '</ul>' +
                    '<div class="form-check mt-3">' +
                    '<input class="form-check-input" type="checkbox" id="confirmDeleteCheck">' +
                    '<label class="form-check-label" for="confirmDeleteCheck">' +
                    'I understand that this action cannot be undone' +
                    '</label>' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-footer" style="border-top: none;">' +
                    '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>' +
                    '<button type="button" class="btn btn-danger" id="confirmDeleteAccount" disabled>Delete My Account</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

                document.body.appendChild(modal);
                const deleteModal = new bootstrap.Modal(modal);
                deleteModal.show();

                // Add functionality to the checkbox
                document.getElementById('confirmDeleteCheck').addEventListener('change', function() {
                    document.getElementById('confirmDeleteAccount').disabled = !this.checked;
                });

                document.getElementById('confirmDeleteAccount').addEventListener('click', function() {
                    deleteModal.hide();
                    // Delete account logic would go here
                    showToast('Account deletion request submitted', 'success');
                });

                modal.addEventListener('hidden.bs.modal', function() {
                    modal.remove();
                });
            });

            // Enhanced password change form validation
            const changePasswordForm = document.getElementById('changePasswordForm');
            if (changePasswordForm) {
                const newPasswordField = document.getElementById('new_password');
                const confirmPasswordField = document.getElementById('confirm_password');
                const currentPasswordField = document.getElementById('current_password');

                // Real-time password validation
                function validatePassword() {
                    const newPassword = newPasswordField.value;
                    const confirmPassword = confirmPasswordField.value;
                    const currentPassword = currentPasswordField.value;

                    let isValid = true;

                    // Create or get password feedback element
                    let feedbackElement = document.getElementById('password-feedback');
                    if (!feedbackElement) {
                        feedbackElement = document.createElement('div');
                        feedbackElement.id = 'password-feedback';
                        feedbackElement.className = 'mt-3 p-3 border rounded';
                        confirmPasswordField.parentNode.appendChild(feedbackElement);
                    }

                    // Password strength and validation checks without template literals
                    let feedback = '<h6>Password Requirements:</h6><ul class="mb-0">';

                    // Check password length
                    const lengthClass = newPassword.length >= 6 ? 'text-success' : 'text-danger';
                    const lengthIcon = newPassword.length >= 6 ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                    feedback += '<li class="' + lengthClass + '"><i class="bi ' + lengthIcon +
                        '"></i> At least 6 characters long</li>';

                    // Check passwords match
                    const matchClass = confirmPassword === newPassword ? 'text-success' : 'text-danger';
                    const matchIcon = confirmPassword === newPassword ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                    feedback += '<li class="' + matchClass + '"><i class="bi ' + matchIcon +
                        '"></i> Passwords match</li>';

                    // Check current password provided
                    const currentClass = currentPassword.length > 0 ? 'text-success' : 'text-danger';
                    const currentIcon = currentPassword.length > 0 ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
                    feedback += '<li class="' + currentClass + '"><i class="bi ' + currentIcon +
                        '"></i> Current password provided</li>';

                    feedback += '</ul>';

                    feedbackElement.innerHTML = feedback;

                    // Validate form for submission
                    if (newPassword.length < 6 || confirmPassword !== newPassword || currentPassword.length === 0) {
                        isValid = false;
                    }

                    // Update submit button state
                    const submitBtn = changePasswordForm.querySelector('button[type="submit"]');
                    submitBtn.disabled = !isValid;
                }

                // Add input event listeners
                newPasswordField.addEventListener('input', validatePassword);
                confirmPasswordField.addEventListener('input', validatePassword);
                currentPasswordField.addEventListener('input', validatePassword);

                // Form submission with enhanced validation
                changePasswordForm.addEventListener('submit', function(e) {
                    const newPassword = newPasswordField.value;
                    const confirmPassword = confirmPasswordField.value;

                    if (newPassword !== confirmPassword) {
                        e.preventDefault();
                        showToast('New passwords do not match!', 'danger');
                        return false;
                    }

                    if (newPassword.length < 6) {
                        e.preventDefault();
                        showToast('Password must be at least 6 characters long!', 'danger');
                        return false;
                    }

                    // Form is valid, allow submission
                    return true;
                });

                // Initialize validation on page load
                validatePassword();
            }

            // Show password change modal if there was a submission error
            {if $passwordError neq ''}
                new bootstrap.Modal(document.getElementById('changePasswordModal')).show();
            {/if}

            // Show toast for password change success
            {if $passwordMessage neq ''}
                setTimeout(() => {
                    showToast("{$passwordMessage}", 'success');
                }, 500);
            {/if}
        });
    </script>
{/block}