<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Access current user profile
$user_data = get_current_user();

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Assign to Smarty
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);

// Calendar data initialization 
// Static template data used until database integration complete

// Add the current date and time for the calendar view
$Smarty->assign('currentDate', date('Y-m-d'));
$Smarty->assign('currentTime', date('H:i:s'));