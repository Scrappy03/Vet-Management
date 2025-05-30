<?php

// Get authenticated user data
$user_data = get_current_user();
$Smarty->assign('user', $user_data);

// Format user name for display with fallback
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';

if (!empty($first_name) || !empty($last_name)) {
    $user_name = trim($first_name . ' ' . $last_name);
    $Smarty->assign('user_name', $user_name);
}

// Fetch patients from database
$patient = new Patient($Conn);

// Get filter parameters from GET request
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$species = isset($_GET['species']) ? trim($_GET['species']) : '';
$status = isset($_GET['status']) ? trim($_GET['status']) : '';
$owner = isset($_GET['owner']) ? trim($_GET['owner']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 50; // Patients per page
$offset = ($page - 1) * $limit;

// Fetch patients with filters
$patients = $patient->getPatients($search, $species, $status, $owner, $limit, $offset);

if ($patients === false) {
    // Log error and use empty array
    error_log("Failed to fetch patients: " . print_r($patient->getErrorInfo(), true));
    $patients = [];
}

// Format patient IDs and other data for display
foreach ($patients as &$p) {
    $p['formatted_id'] = sprintf("P%03d", $p['patient_id']);
    $p['species_formatted'] = ucfirst(strtolower($p['species']));
    $p['gender_formatted'] = ucfirst(strtolower($p['gender']));
    $p['status_formatted'] = ucfirst(strtolower($p['status']));
    $p['name_initial'] = strtoupper(substr($p['name'], 0, 1));
    $p['last_visit_formatted'] = !empty($p['last_visit']) ? date('M j, Y', strtotime($p['last_visit'])) : 'No visits';
}

// Calculate statistics
$total_patients = count($patients);
$under_treatment = 0;
$scheduled_today = 0;
$new_this_week = 0;

foreach ($patients as $p) {
    if (isset($p['status']) && strtolower($p['status']) === 'under treatment') {
        $under_treatment++;
    }
    // Add more stat calculations as needed
}

// Assign data to template
$Smarty->assign('patients', $patients);
$Smarty->assign('total_patients', $total_patients);
$Smarty->assign('under_treatment', $under_treatment);
$Smarty->assign('scheduled_today', $scheduled_today);
$Smarty->assign('new_this_week', $new_this_week);
$Smarty->assign('search', $search);
$Smarty->assign('species_filter', $species);
$Smarty->assign('status_filter', $status);
$Smarty->assign('owner_filter', $owner);
$Smarty->assign('current_page', $page);

