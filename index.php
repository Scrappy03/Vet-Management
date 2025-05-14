<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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

// Include the Composer autoloader
require_once(__DIR__ . '/includes/boot.include.php');

// Get the requested page
$page = isset($_GET['p']) ? $_GET['p'] : 'home';

// Check if the page requires authentication
if(in_array($page, $secure_pages)) {
    // Check if user is logged in
    if (!isset($_SESSION['is_loggedin']) || $_SESSION['is_loggedin'] !== true || 
        !isset($_SESSION['user_id']) || !isset($_SESSION['user_data'])) {
        
        // Clear any incomplete session data
        $_SESSION = array();
        
        // Redirect to login page
        header("Location: index.php?p=login&msg=session_expired");
        exit;
    }

    // Additional security check - verify user data structure
    if (!is_array($_SESSION['user_data']) || !isset($_SESSION['user_data']['email'])) {
        // Session data is corrupted
        $_SESSION = array();
        error_log("Corrupted session data detected for page: " . $page);
        header("Location: index.php?p=login&msg=session_error");
        exit;
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