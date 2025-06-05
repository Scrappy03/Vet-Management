{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}

    <body class="bg-light">
        <div class="container">
            <div class="row min-vh-100 justify-content-center align-items-center">
                <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <!-- Login Form -->
                            {if !isset($smarty.post.register)}
                                <form method="post" id="loginForm" action="login">
                                    <div class="text-center mb-4" id="login-header">
                                        <h3 class="fw-bold text-primary mb-2">Welcome Back</h3>
                                        <p class="text-muted">Please enter your credentials</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{$email}"
                                            required>
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember"
                                                value="1">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        <a href="#" class="text-primary text-decoration-none">Forgot password?</a>
                                    </div>
                                    {if $error && !isset($smarty.post.register)}
                                        <div class="alert alert-danger mb-3">{$error}</div>
                                    {/if}
                                    <button type="submit" name="login" value="1" class="btn btn-primary w-100 mb-3">
                                        Sign In
                                    </button>

                                    <p class="text-center mb-0">
                                        Don't have an account? <a href="login?show=register"
                                    class="text-primary text-decoration-none">Sign up</a>
                            </p>
                        </form>
                        {/if}

                        <!-- Registration Form -->
                        {if isset($smarty.get.show) && $smarty.get.show == 'register' || isset($smarty.post.register)}
                        <form method="post" action="login" id="registrationForm">
                            <div class="text-center mb-4" id="registration-header">
                                <h3 class="fw-bold text-primary mb-2">Create Staff Account</h3>
                                <p class="text-muted">Register a new staff member</p>
                            </div>

                            <!-- Personal Information -->
                            <h6 class="mb-3 text-primary">Personal Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name"
                                        value="{if isset($smarty.post.register)}{$smarty.post.first_name}{/if}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="{if isset($smarty.post.register)}{$smarty.post.last_name}{/if}" required>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <h6 class="mb-3 text-primary">Contact Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="reg_email" name="email"
                                        value="{if isset($smarty.post.register)}{$email}{/if}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{if isset($smarty.post.register)}{$smarty.post.phone}{/if}" required>
                                </div>
                            </div>

                            <!-- Employment Information -->
                            <h6 class="mb-3 text-primary">Employment Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select a role</option>
                                        <option value="veterinarian"
                                            {if isset($smarty.post.register) && $smarty.post.role == 'veterinarian'}selected{/if}>
                                            Veterinarian</option>
                                        <option value="technician"
                                            {if isset($smarty.post.register) && $smarty.post.role == 'technician'}selected{/if}>
                                            Veterinary Technician</option>
                                        <option value="assistant"
                                            {if isset($smarty.post.register) && $smarty.post.role == 'assistant'}selected{/if}>
                                            Veterinary Assistant</option>
                                        <option value="receptionist"
                                            {if isset($smarty.post.register) && $smarty.post.role == 'receptionist'}selected{/if}>
                                            Receptionist</option>
                                        <option value="manager"
                                            {if isset($smarty.post.register) && $smarty.post.role == 'manager'}selected{/if}>
                                            Practice Manager</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{if isset($smarty.post.register)}{$smarty.post.start_date}{/if}"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active"
                                            {if !isset($smarty.post.register) || $smarty.post.status == 'active'}selected{/if}>
                                            Active</option>
                                        <option value="inactive"
                                            {if isset($smarty.post.register) && $smarty.post.status == 'inactive'}selected{/if}>
                                            Inactive</option>
                                        <option value="on_leave"
                                            {if isset($smarty.post.register) && $smarty.post.status == 'on_leave'}selected{/if}>
                                            On Leave</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Professional Information -->
                            <h6 class="mb-3 text-primary">Professional Information</h6>
                            <div class="mb-3">
                                <label for="specialties" class="form-label">Specialties/Skills</label>
                                <input type="text" class="form-control" id="specialties" name="specialties"
                                    placeholder="e.g., Surgery, Dentistry, Emergency Care"
                                    value="{if isset($smarty.post.register)}{$smarty.post.specialties}{/if}">
                            </div>

                            <div class="mb-3">
                                <label for="education" class="form-label">Education/Certifications</label>
                                <textarea class="form-control" id="education" name="education"
                                    rows="2">{if isset($smarty.post.register)}{$smarty.post.education}{/if}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio"
                                    rows="3">{if isset($smarty.post.register)}{$smarty.post.bio}{/if}</textarea>
                            </div>

                            <!-- Account Security -->
                            <h6 class="mb-3 text-primary">Account Security</h6>
                            <div class="mb-3">
                                <label for="reg_password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="reg_password" name="password" required>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="password_confirm"
                                    name="password_confirm" required>
                            </div>
                            <div class="mb-4">
                                <div class="password-strength-meter">
                                    <div class="password-strength-label d-flex justify-content-between">
                                        <small>Password Strength:</small>
                                        <small class="strength-text">No password</small>
                                    </div>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%;"
                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            {if $success && isset($smarty.post.register)}
                            <div class="alert alert-success mb-3">{$success}</div>
                            {elseif $error && isset($smarty.post.register)}
                            <div class="alert alert-danger mb-3">{$error}</div>
                            {/if}

                            {* Debug info when needed *}
                            {if isset($smarty.get.debug) && isset($smarty.post.register)}
                            <div class="alert alert-info mb-3">
                                <strong>Debug Info:</strong><br>
                                Registration attempt recorded.<br>
                                First Name:
                                {if isset($smarty.post.first_name) && $smarty.post.first_name != ''}{$smarty.post.first_name}{else}Not
                                provided{/if}<br>
                                Email:
                                {if isset($smarty.post.email) && $smarty.post.email != ''}{$smarty.post.email}{else}Not
                                provided{/if}<br>
                                Success: {if $success}Yes{else}No{/if}
                            </div>
                            {/if}

                            <button type="submit" name="register" value="1" class="btn btn-primary w-100 mb-3">
                                Register
                            </button>

                            <p class="text-center mb-0">
                                Already have an account? <a href="login" class="text-primary text-decoration-none">Sign
                                    in</a>
                            </p>
                        </form>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {* Simple password strength checker script *}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password strength checker
            const passwordInput = document.getElementById('reg_password');
            if (passwordInput) {
                const strengthMeter = document.querySelector('.progress-bar');
                const strengthText = document.querySelector('.strength-text');

                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    if (password.length >= 8) strength += 25;
                    if (password.match(/[a-z]/)) strength += 25;
                    if (password.match(/[A-Z]/)) strength += 25;
                    if (password.match(/[0-9]/)) strength += 25;

                    strengthMeter.style.width = strength + '%';

                    // Update color based on strength
                    if (strength < 25) {
                        strengthMeter.className = 'progress-bar bg-danger';
                        strengthText.textContent = 'Very weak';
                    } else if (strength < 50) {
                        strengthMeter.className = 'progress-bar bg-warning';
                        strengthText.textContent = 'Weak';
                    } else if (strength < 75) {
                        strengthMeter.className = 'progress-bar bg-info';
                        strengthText.textContent = 'Medium';
                    } else {
                        strengthMeter.className = 'progress-bar bg-success';
                        strengthText.textContent = 'Strong';
                    }

                    if (password === '') {
                        strengthText.textContent = 'No password';
                        strengthMeter.style.width = '0%';
                    }
                });
            }

            // Simple client-side password match validation
            const registrationForm = document.getElementById('registrationForm');
            if (registrationForm) {
                registrationForm.addEventListener('submit', function(e) {
                    const password = document.getElementById('reg_password').value;
                    const confirmPassword = document.getElementById('password_confirm').value;

                    if (password !== confirmPassword) {
                        e.preventDefault();
                        showToast('Passwords do not match!', 'error');
                                return false;
                            }
                        });
                    }
                });
            </script>
        </body>
    {/block}