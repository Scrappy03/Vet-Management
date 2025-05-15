<?php
// Handle password change request processing
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

// Retrieve authenticated user profile
$user_data = get_current_user();

// Refresh user data from database to ensure current information
$User = new User($Conn);
$latest_user_data = $User->getUserById($_SESSION['user_id']);

// Fix for array_merge issue - ensure both variables are arrays
if ($latest_user_data) {
    // Make sure $user_data is an array
    if (!is_array($user_data)) {
        $user_data = array();
    }
    
    // Make sure $latest_user_data is an array
    if (!is_array($latest_user_data)) {
        $latest_user_data = array();
    }
    
    $user_data = array_merge($user_data, $latest_user_data);
    $_SESSION['user_data'] = $user_data; // Sync session with database
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