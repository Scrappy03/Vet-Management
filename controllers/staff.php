<?php

// Load authenticated user profile
$user_data = get_current_user();

// Format user name for display
$first_name = isset($user_data['first_name']) ? $user_data['first_name'] : '';
$last_name = isset($user_data['last_name']) ? $user_data['last_name'] : '';
$user_name = trim($first_name . ' ' . $last_name);

// Assign to Smarty
$Smarty->assign('user', $user_data);
$Smarty->assign('user_name', $user_name);

// Load staff data
require_once 'classes/staff.class.php';

$staff = new Staff($Conn);

// Get filter parameters
$search = $_GET['search'] ?? '';
$role = $_GET['role'] ?? '';
$status = $_GET['status'] ?? '';
$limit = 50; // Show 50 staff members per page
$offset = 0;

// Get staff list
$staff_list = $staff->getStaff($search, $role, $status, $limit, $offset);
$total_staff = $staff->getStaffCount($search, $role, $status);

// Handle errors
if ($staff_list === false) {
    $error_info = $staff->getErrorInfo();
    error_log("Error loading staff: " . $error_info['message']);
    $staff_list = [];
}

// Assign staff data to Smarty
$Smarty->assign('staff_list', $staff_list);
$Smarty->assign('total_staff', $total_staff);
$Smarty->assign('search_filters', [
    'search' => $search,
    'role' => $role,
    'status' => $status
]);