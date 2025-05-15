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

// Fetch patients list

