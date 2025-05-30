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
    $Smarty->assign('patient_id', 0);
} else {
    // Pass patient ID to template for display
    $Smarty->assign('patient_id', $patient_id);
    
    // Initialize Patient class to fetch patient details
    try {
        $Patient = new Patient($Conn);
        $patient_data = $Patient->getPatientById($patient_id);
        
        if ($patient_data) {
            // Format patient data for display
            $patient_data['name_initial'] = strtoupper(substr($patient_data['name'], 0, 1));
            $patient_data['status_formatted'] = ucfirst(strtolower($patient_data['status']));
            $patient_data['gender_formatted'] = ucfirst(strtolower($patient_data['gender']));
            $patient_data['species_formatted'] = ucfirst(strtolower($patient_data['species']));
            
            $Smarty->assign('patient', $patient_data);
        } else {
            $Smarty->assign('error', 'Patient not found.');
            $Smarty->assign('patient_id', 0);
        }
    } catch (Exception $e) {
        error_log("Error fetching patient data: " . $e->getMessage());
        $Smarty->assign('error', 'Error loading patient information.');
        $Smarty->assign('patient_id', 0);
    }
}