<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL & ~E_NOTICE);

// Define secure pages that require authentication
$secure_pages = array(
    'dashboard', 
    'patients', 
    'patient-profile', 
    'staff', 
    'calendar', 
    'settings'
);

// Include the Composer autoloader and boot file
require_once(__DIR__ . '/includes/boot.include.php');

// Include authentication helper functions
require_once(__DIR__ . '/includes/auth.include.php');

// Get the requested page
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

// Enforce authentication for secure pages
if(in_array($page, $secure_pages)) {
    // First check if user is logged in at all
    if (!is_logged_in()) {
        redirect_to_login('session_expired');
    }
    
    // Then validate session data structure and integrity
    if (!validate_user_session()) {
        error_log("Invalid user session data for page: " . $page . ". User ID: " . 
                  (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'unknown'));
        redirect_to_login('session_error');
    }
}

// Process the requested page
if(isset($_GET['p']) && $_GET['p']) {
    $Smarty->assign('view_name', $_GET['p']);
    require_once('controllers/'.$_GET['p'].'.php');
    $Smarty->display('pages/'.$_GET['p'].'.tpl');
}else{
    $Smarty->assign('view_name', 'home');
    require_once('controllers/home.php');
    $Smarty->display('pages/home.tpl');
}