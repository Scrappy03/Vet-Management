{extends file="layouts/dashboardTemp.tpl"}
{block name="body"}

    <body class="bg-light">
        <div class="container">
            <div class="row min-vh-100 justify-content-center align-items-center">
                <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form method="post" id="loginForm">
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
                                <div id="login-error-message" class="alert alert-danger mb-3" style="display: none;"></div>
                                <button type="submit" name="login" value="1" class="btn btn-primary w-100 mb-3">
                                    <span class="spinner-border spinner-border-sm me-2 d-none" id="login-spinner"
                                        role="status" aria-hidden="true"></span>
                                    Sign In
                                </button>
                                <p class="text-center mb-0">
                                    Don't have an account? <a href="#toggleForm" class="text-primary text-decoration-none"
                                    id="showRegistration">Sign up</a>
                            </p>
                        </form>

                        <!-- Registration Form (Hidden by default) -->
                        <form id="registrationForm" style="display: none;" method="post" class="form-hidden">
                            <div class="text-center mb-4" id="registration-header">
                                <h3 class="fw-bold text-primary mb-2">Create Account</h3>
                                <p class="text-muted">Register for a new account</p>
                            </div>
                            <div class="mb-3">
                                <label for="reg_email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="reg_email" name="email"
                                    value="{if isset($smarty.post.register)}{$email}{/if}" required>
                            </div>
                            <div class="mb-3">
                                <label for="reg_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="reg_password" name="password" required>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirm" class="form-label">Confirm Password</label>
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
                            <div id="registration-error-message" class="alert alert-danger mb-3" style="display: none;">
                            </div>
                            <button type="submit" name="register" value="1" class="btn btn-primary w-100 mb-3">
                                <span class="spinner-border spinner-border-sm me-2 d-none" id="register-spinner"
                                    role="status" aria-hidden="true"></span>
                                Register
                            </button>
                            <p class="text-center mb-0">
                                Already have an account? <a href="#toggleForm" class="text-primary text-decoration-none"
                                    id="showLogin">Sign in</a>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const registrationForm = document.getElementById('registrationForm');
            const showRegistration = document.getElementById('showRegistration');
            const showLogin = document.getElementById('showLogin');

            // Add CSS for smooth transitions
            const style = document.createElement('style');
            style.textContent = `
                #loginForm, #registrationForm {
                    transition: opacity 0.3s ease, transform 0.3s ease;
                    opacity: 1;
                    transform: translateY(0);
                    width: 100%;
                }
                .form-hidden {
                    opacity: 0 !important;
                    transform: translateY(20px) !important;
                    position: absolute !important;
                    pointer-events: none !important;
                    width: 100%;
                }
                .card-body {
                    position: relative;
                    min-height: 400px; /* Adjust based on your form height */
                }
            `;
            document.head.appendChild(style);

            showRegistration.addEventListener('click', function(e) {
                e.preventDefault();
                switchForms(loginForm, registrationForm);
            });

            showLogin.addEventListener('click', function(e) {
                e.preventDefault();
                switchForms(registrationForm, loginForm);
            });

            function switchForms(fromForm, toForm) {
                // Add hidden class to current form
                fromForm.classList.add('form-hidden');

                // Remove display:none from target form so it can be animated
                toForm.style.display = 'block';

                // Slight delay to allow display change to take effect before animation
                setTimeout(() => {
                    fromForm.style.display = 'none';
                    toForm.classList.remove('form-hidden');
                }, 50);
            }

            // Password strength checker
            const passwordInput = document.getElementById('reg_password');
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

            // Handle form submissions
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const loginSpinner = document.getElementById('login-spinner');
                const errorMessage = document.getElementById('login-error-message');
                const submitBtn = this.querySelector('button[type="submit"]');

                // Show spinner and disable button
                loginSpinner.classList.remove('d-none');
                submitBtn.disabled = true;

                // Hide any previous error message
                errorMessage.style.display = 'none';

                // Get form data
                const formData = new FormData(this);

                // Simulate form submission (replace with actual AJAX)
                setTimeout(() => {
                    // This is where you'd normally send an AJAX request
                        // For demo purposes, we're just submitting the form
                    loginForm.submit();

                    // If there's an error, you can show it like this:
                        // loginSpinner.classList.add('d-none');
                        // submitBtn.disabled = false;
                        // errorMessage.textContent = 'Invalid email or password';
                        // errorMessage.style.display = 'block';
                    }, 1000);
                });

                registrationForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const registerSpinner = document.getElementById('register-spinner');
                    const errorMessage = document.getElementById('registration-error-message');
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const password = document.getElementById('reg_password').value;
                    const confirmPassword = document.getElementById('password_confirm').value;

                    // Hide any previous error message
                    errorMessage.style.display = 'none';

                    // Validate password match
                    if (password !== confirmPassword) {
                        errorMessage.textContent = 'Passwords do not match!';
                        errorMessage.style.display = 'block';
                        return false;
                    }

                    // Show spinner and disable button
                    registerSpinner.classList.remove('d-none');
                    submitBtn.disabled = true;

                    // Get form data
                    const formData = new FormData(this);

                    // Simulate form submission (replace with actual AJAX)
                    setTimeout(() => {
                        // This is where you'd normally send an AJAX request
                    // For demo purposes, we're just submitting the form
                        registrationForm.submit();

                        // If there's an error, you can show it like this:
                    // registerSpinner.classList.add('d-none');
                    // submitBtn.disabled = false;
                    // errorMessage.textContent = 'Email already in use';
                    // errorMessage.style.display = 'block';
                    }, 1000);
                });
            });
        </script>
{/block}