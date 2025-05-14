<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include auth helper functions
require_once(__DIR__ . '/../includes/auth.include.php');

// Debug session state (only log this in development)
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    error_log("Dashboard: Session state: " . print_r($_SESSION, true));
}

// Authentication is now handled by the central secure pages array in index.php
// This controller can focus on dashboard-specific functionality

// User is logged in and session is valid, continue with dashboard controller logic
$user_data = $_SESSION['user_data'];
$Smarty->assign('user', $user_data);

// Format user name for display with fallback
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';

if (!empty($first_name) || !empty($last_name)) {
    $user_name = trim($first_name . ' ' . $last_name);
    $Smarty->assign('user_name', $user_name);
}

// Add additional dashboard-specific code below
// For example, fetch statistics, recent activities, etc.