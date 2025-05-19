<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Access current user profile
$user_data = get_current_user();

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Get all active patients for the appointment form dropdown
$Patient = new Patient($Conn);
$patients = $Patient->getPatients('', '', 'active');

// Add error handling for patients data
if ($patients === false) {
    // If getPatients() fails, provide empty array instead of false
    $patients = [];
    // Log the error
    error_log("Error fetching patients in calendar controller: " . print_r($Patient->getErrorInfo(), true));
}

// Get all active staff members for appointment assignments
$User = new User($Conn);
$staff = $User->getAllStaffByRole('veterinarian');

// Add error handling for staff data
if ($staff === false) {
    $staff = [];
    error_log("Error fetching staff in calendar controller: " . print_r($User->getErrorInfo(), true));
}

// Assign to Smarty
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);
$Smarty->assign('patients', $patients);
$Smarty->assign('staff', $staff);

// Add the current date and time for the calendar view
$Smarty->assign('currentDate', date('Y-m-d'));
$Smarty->assign('currentTime', date('H:i:s'));