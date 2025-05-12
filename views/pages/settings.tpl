{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}

    <div class="dashboard-sidebar">
        <div class="text-center mb-4 content-transition">
            <h4 class="fw-bold text-primary mb-0">VetCare</h4>
            <p class="text-muted small">Management System</p>
        </div>

        <nav class="mb-4 content-transition">
            <a href="dashboard.html" class="dashboard-nav-link">
                <i class="bi bi-house-door"></i>
                Dashboard
            </a>
            <a href="calendar.html" class="dashboard-nav-link">
                <i class="bi bi-calendar"></i>
                Appointments
            </a>
            <a href="patients.html" class="dashboard-nav-link">
                <i class="bi bi-clipboard2-pulse"></i>
                Patients
            </a>
            <a href="staff.html" class="dashboard-nav-link active">
                <i class="bi bi-person"></i>
                Staff
            </a>
            <a href="#" class="dashboard-nav-link">
                <i class="bi bi-gear"></i>
                Settings
            </a>
        </nav>
    </div>

    <div class="container">
        <h1>Settings</h1>

        <div class="card">
            <h2>Appearance</h2>
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

        <div class="card">
            <h2>Notifications</h2>
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

        <div class="card">
            <h2>Privacy</h2>
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

        <div class="card">
            <h2>Account</h2>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="user@example.com" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <button id="change-password" class="btn btn-secondary">Change Password</button>
            </div>
            <div class="form-group">
                <label for="timezone">Timezone</label>
                <select id="timezone" name="timezone" class="form-control">
                    <option value="utc">UTC</option>
                    <option value="est">Eastern Time</option>
                    <option value="cst">Central Time</option>
                    <option value="mst">Mountain Time</option>
                    <option value="pst">Pacific Time</option>
                </select>
            </div>
        </div>

        <div class="card">
            <h2>Advanced</h2>
            <div class="form-group">
                <label class="checkbox-container">Enable Experimental Features
                    <input type="checkbox">
                    <span class="checkmark"></span>
                </label>
            </div>
            <div class="form-group">
                <button id="clear-cache" class="btn btn-secondary">Clear Cache</button>
            </div>
            <div class="form-group">
                <button id="delete-account" class="btn btn-danger">Delete Account</button>
            </div>
        </div>

        <div class="btn-container">
            <button class="btn btn-secondary cancel">Cancel</button>
            <button class="btn btn-primary save">Save Changes</button>
        </div>
    </div>

    <script>
        // Simple script to handle setting changes
        document.addEventListener('DOMContentLoaded', function() {
            const saveButton = document.querySelector('.save');
            const cancelButton = document.querySelector('.cancel');

            saveButton.addEventListener('click', function() {
                // Here you would typically save the settings to localStorage or send to a server
                alert('Settings saved successfully!');
            });

            cancelButton.addEventListener('click', function() {
                // Reset form or navigate away
                if (confirm('Discard changes?')) {
                    window.location.reload();
                }
            });

            document.getElementById('clear-cache').addEventListener('click', function() {
                if (confirm('Are you sure you want to clear your cache? This cannot be undone.')) {
                    // Clear cache logic would go here
                    alert('Cache cleared successfully');
                }
            });

            document.getElementById('delete-account').addEventListener('click', function() {
                if (confirm(
                        'WARNING: Are you absolutely sure you want to delete your account? This action cannot be undone.'
                    )) {
                    // Delete account logic would go here
                    alert('Account deletion request submitted');
                }
            });
        });
    </script>
{/block}