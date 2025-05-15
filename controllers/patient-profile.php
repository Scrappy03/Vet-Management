<?php
// Retrieve authenticated staff member data
$user_data = get_current_user();

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Assign to Smarty
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);

// Get patient ID from URL parameter
$patient_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($patient_id <= 0) {
    // Handle missing or invalid patient identifier
    $Smarty->assign('error', 'No patient selected. Please choose a patient from the patients list.');
} else {
    // Pass patient ID to template for display
    $Smarty->assign('patient_id', $patient_id);
    
    // Fetch patient details from the database
}