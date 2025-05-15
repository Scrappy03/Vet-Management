<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Development diagnostics
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_log("Dashboard: Session state: " . print_r($_SESSION, true));
}

// Retrieve authenticated staff profile
$user_data = get_current_user();

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Template data binding
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);

// Ensure consistent user display name
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';

if (!empty($first_name) || !empty($last_name)) {
    $user_name = trim($first_name . ' ' . $last_name);
    $Smarty->assign('user_name', $user_name);
}
// Future implementation: summary statistics and notifications