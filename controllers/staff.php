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

// Staff listing