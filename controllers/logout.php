<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include auth helper functions
require_once(__DIR__ . '/../includes/auth.include.php');

// Use the logout function
logout_user();

// Note: The function above already handles the redirect so we won't reach this point
// But just in case, let's add a fallback redirect
header("Location: index.php?msg=logout_success");
exit;