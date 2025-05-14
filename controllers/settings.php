<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include auth helper functions
require_once(__DIR__ . '/../includes/auth.include.php');

// Process password change if submitted
$passwordMessage = '';
$passwordError = '';

if (isset($_POST['change_password'])) {
    $current_password = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    // Basic validation
    if (empty($current_password)) {
        $passwordError = "Please enter your current password";
    } elseif (empty($new_password)) {
        $passwordError = "Please enter a new password";
    } elseif (strlen($new_password) < 6) {
        $passwordError = "Password must be at least 6 characters";
    } elseif ($new_password !== $confirm_password) {
        $passwordError = "New passwords do not match";
    } else {
        // All validation passed, attempt to change password
        $User = new User($Conn);
        $result = $User->changeUserPassword($_SESSION['user_id'], $current_password, $new_password);
        
        if ($result) {
            $passwordMessage = "Your password has been successfully changed";
        } else {
            // Get error information
            if(method_exists($User, 'getErrorInfo')) {
                $errorInfo = $User->getErrorInfo();
                $passwordError = isset($errorInfo['message']) ? $errorInfo['message'] : "Failed to change password";
            } else {
                $passwordError = "Failed to change password";
            }
        }
    }
}

// Get user data for the view
$user_data = $_SESSION['user_data'];

// Load user info from database to ensure latest data
$User = new User($Conn);
$latest_user_data = $User->getUserById($_SESSION['user_id']);
if ($latest_user_data) {
    $user_data = array_merge($user_data, $latest_user_data);
    $_SESSION['user_data'] = $user_data; // Update session data
}

$Smarty->assign('user', $user_data);

// Format user name for display
if (isset($user_data['first_name']) || isset($user_data['last_name'])) {
    $first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
    $last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
    $user_name = trim($first_name . ' ' . $last_name);
    $Smarty->assign('user_name', $user_name);
}

// Pass messages to the template
$Smarty->assign('passwordMessage', $passwordMessage);
$Smarty->assign('passwordError', $passwordError);