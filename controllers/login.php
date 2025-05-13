<?php
// Make sure we have the autoloader for the User class
require_once(__DIR__.'/../includes/autoloader.include.php');

// Start session for login management
if (!isset($_SESSION)) {
    session_start();
}

// Initialize variables
$error = "";
$success = "";
$email = "";

// Check if the form was submitted
if($_POST) {
    // Registration form processing
    if(isset($_POST['register'])) {
        // Get form data
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $password_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';
        
        // Basic validation
        if(empty($email)) {
            $error = "Email is required";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email";
        } elseif(empty($password)) {
            $error = "Password is required";
        } elseif(strlen($password) < 8) {
            $error = "Password must be at least 8 characters";
        } elseif($password !== $password_confirm) {
            $error = "Passwords do not match";
        } else {
            // All validation passed, attempt to register the user
            
            // Create user with the User class
            $User = new User($Conn);
            $attempt = $User->createUser($_POST);
            if($attempt) {
                $success = "Your account has been created. Please now login.";
            } else {
                $error = "An error occurred, please try again later.";
            }
        }
    }
    // Login form processing
    else if(isset($_POST['login'])) {
        // Get form data
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $remember = isset($_POST['remember']) ? true : false;
        
        // Basic validation
        if(empty($email)) {
            $error = "Email is required";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email";
        } elseif(empty($password)) {
            $error = "Password is required";
        } else {
            // All validation passed, attempt to log in
            
            // Attempt login with User class
            $User = new User($Conn);
            $user_data = $User->loginUser($email, $password);
            
            if($user_data) {
                // Login successful
                // Set session variables
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['user_email'] = $user_data['email'];
                
                // Set remember me cookie if selected
                if($remember) {
                    // Generate remember token and store in DB
                    $token = bin2hex(random_bytes(32));
                    $User->storeRememberToken($user_data['id'], $token);
                    
                    // Set cookie - 30 days expiry
                    setcookie('remember', $user_data['id'] . ':' . $token, time() + 30 * 24 * 60 * 60, '/');
                }
                
                // Redirect to dashboard
                header("Location: index.php?page=dashboard");
                exit;
            } else {
                $error = "Invalid email or password";
            }
            
            // Redirect to dashboard 
            header("Location: index.php?page=dashboard");
            exit;
        }
    }
}

// Pass variables to template
$Smarty->assign('error', $error);
$Smarty->assign('success', $success);
$Smarty->assign('email', $email);