<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Development diagnostics
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_log("Dashboard: Session state: " . print_r($_SESSION, true));
}

// Try to get user from session first
$user_data = get_current_user();

// Direct database query regardless of session state
if (isset($_SESSION['user_id'])) {
    // Get fresh user data directly from database
    $User = new User($Conn);
    $fresh_user_data = $User->getUserById($_SESSION['user_id']);
    
    if ($fresh_user_data) {
        // Use database data instead of session data
        $user_data = $fresh_user_data;
        
        // Update session with fresh data
        $_SESSION['user_data'] = $fresh_user_data;
    }
}

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Log what we found for debugging
error_log("Dashboard user data: " . print_r($user_data, true));
error_log("First name: '$first_name', Last name: '$last_name', Display name: '$user_name'");

// Template data binding
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);
// Future implementation: summary statistics and notifications