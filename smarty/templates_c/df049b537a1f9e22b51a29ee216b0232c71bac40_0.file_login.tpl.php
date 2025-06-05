<?php
/* Smarty version 5.5.0, created on 2025-06-05 13:34:43
  from 'file:pages/login.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.5.0',
  'unifunc' => 'content_68419cf32639a5_27962871',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'df049b537a1f9e22b51a29ee216b0232c71bac40' => 
    array (
      0 => 'pages/login.tpl',
      1 => 1749081773,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_68419cf32639a5_27962871 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/pages';
$_smarty_tpl->getInheritance()->init($_smarty_tpl, true);
?>

<?php 
$_smarty_tpl->getInheritance()->instanceBlock($_smarty_tpl, 'Block_106541351068419cf0817282_76866764', "body");
$_smarty_tpl->getInheritance()->endChild($_smarty_tpl, "layouts/dashboardTemp.tpl", $_smarty_current_dir);
}
/* {block "body"} */
class Block_106541351068419cf0817282_76866764 extends \Smarty\Runtime\Block
{
public function callBlock(\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/Users/callum/Documents/University/AWD/VetCare/views/pages';
?>


    <body class="bg-light">
        <div class="container">
            <div class="row min-vh-100 justify-content-center align-items-center">
                <div class="col-11 col-sm-8 col-md-6 col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <!-- Login Form -->
                            <?php if (!(true && (true && null !== ($_POST['register'] ?? null)))) {?>
                                <form method="post" id="loginForm" action="login">
                                    <div class="text-center mb-4" id="login-header">
                                        <h3 class="fw-bold text-primary mb-2">Welcome Back</h3>
                                        <p class="text-muted">Please enter your credentials</p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email address</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_smarty_tpl->getValue('email');?>
"
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
                                    <?php if ($_smarty_tpl->getValue('error') && !(true && (true && null !== ($_POST['register'] ?? null)))) {?>
                                        <div class="alert alert-danger mb-3"><?php echo $_smarty_tpl->getValue('error');?>
</div>
                                    <?php }?>
                                    <button type="submit" name="login" value="1" class="btn btn-primary w-100 mb-3">
                                        Sign In
                                    </button>

                                                                        <p class="text-center small text-muted mt-2">
                                        Having trouble logging in? <a href="login_debug.php" class="text-decoration-none">Check
                                            system status</a>
                                    </p>
                                    <p class="text-center mb-0">
                                        Don't have an account? <a href="login?show=register"
                                    class="text-primary text-decoration-none">Sign up</a>
                            </p>
                        </form>
                        <?php }?>

                        <!-- Registration Form -->
                        <?php if ((true && (true && null !== ($_GET['show'] ?? null))) && $_GET['show'] == 'register' || (true && (true && null !== ($_POST['register'] ?? null)))) {?>
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
                                        value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['first_name'];
}?>"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name"
                                        value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['last_name'];
}?>" required>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <h6 class="mb-3 text-primary">Contact Information</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="reg_email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="reg_email" name="email"
                                        value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_smarty_tpl->getValue('email');
}?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['phone'];
}?>" required>
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
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['role'] == 'veterinarian') {?>selected<?php }?>>
                                            Veterinarian</option>
                                        <option value="technician"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['role'] == 'technician') {?>selected<?php }?>>
                                            Veterinary Technician</option>
                                        <option value="assistant"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['role'] == 'assistant') {?>selected<?php }?>>
                                            Veterinary Assistant</option>
                                        <option value="receptionist"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['role'] == 'receptionist') {?>selected<?php }?>>
                                            Receptionist</option>
                                        <option value="manager"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['role'] == 'manager') {?>selected<?php }?>>
                                            Practice Manager</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['start_date'];
}?>"
                                        required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active"
                                            <?php if (!(true && (true && null !== ($_POST['register'] ?? null))) || $_POST['status'] == 'active') {?>selected<?php }?>>
                                            Active</option>
                                        <option value="inactive"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['status'] == 'inactive') {?>selected<?php }?>>
                                            Inactive</option>
                                        <option value="on_leave"
                                            <?php if ((true && (true && null !== ($_POST['register'] ?? null))) && $_POST['status'] == 'on_leave') {?>selected<?php }?>>
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
                                    value="<?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['specialties'];
}?>">
                            </div>

                            <div class="mb-3">
                                <label for="education" class="form-label">Education/Certifications</label>
                                <textarea class="form-control" id="education" name="education"
                                    rows="2"><?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['education'];
}?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio"
                                    rows="3"><?php if ((true && (true && null !== ($_POST['register'] ?? null)))) {
echo $_POST['bio'];
}?></textarea>
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
                            <?php if ($_smarty_tpl->getValue('success') && (true && (true && null !== ($_POST['register'] ?? null)))) {?>
                            <div class="alert alert-success mb-3"><?php echo $_smarty_tpl->getValue('success');?>
</div>
                            <?php } elseif ($_smarty_tpl->getValue('error') && (true && (true && null !== ($_POST['register'] ?? null)))) {?>
                            <div class="alert alert-danger mb-3"><?php echo $_smarty_tpl->getValue('error');?>
</div>
                            <?php }?>

                                                        <?php if ((true && (true && null !== ($_GET['debug'] ?? null))) && (true && (true && null !== ($_POST['register'] ?? null)))) {?>
                            <div class="alert alert-info mb-3">
                                <strong>Debug Info:</strong><br>
                                Registration attempt recorded.<br>
                                First Name:
                                <?php if ((true && (true && null !== ($_POST['first_name'] ?? null))) && $_POST['first_name'] != '') {
echo $_POST['first_name'];
} else { ?>Not
                                provided<?php }?><br>
                                Email:
                                <?php if ((true && (true && null !== ($_POST['email'] ?? null))) && $_POST['email'] != '') {
echo $_POST['email'];
} else { ?>Not
                                provided<?php }?><br>
                                Success: <?php if ($_smarty_tpl->getValue('success')) {?>Yes<?php } else { ?>No<?php }?>
                            </div>
                            <?php }?>

                            <button type="submit" name="register" value="1" class="btn btn-primary w-100 mb-3">
                                Register
                            </button>

                            <p class="text-center mb-0">
                                Already have an account? <a href="login" class="text-primary text-decoration-none">Sign
                                    in</a>
                            </p>
                        </form>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php echo '<script'; ?>
>
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
            <?php echo '</script'; ?>
>
        </body>
    <?php
}
}
/* {/block "body"} */
}
