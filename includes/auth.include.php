<?php
/**
 * Authentication helper functions
 */

/**
 * Check if current user is logged in
 * 
 * @return boolean True if user is logged in, false otherwise
 */
function is_logged_in() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION['is_loggedin']) && $_SESSION['is_loggedin'] === true && 
           isset($_SESSION['user_id']) && isset($_SESSION['user_data']);
}

/**
 * Get current user data
 * 
 * @return array|null User data array or null if not logged in
 */
if (!function_exists('get_current_user')) {
    function get_current_user() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        return isset($_SESSION['user_data']) ? $_SESSION['user_data'] : null;
    }
}

/**
 * Check if session contains valid user data
 * 
 * @return boolean True if user data is valid, false otherwise
 */
function validate_user_session() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    return isset($_SESSION['user_data']) && is_array($_SESSION['user_data']) && 
           isset($_SESSION['user_data']['email']) && isset($_SESSION['user_data']['staff_id']);
}

/**
 * Redirect to login page with a custom message
 * 
 * @param string $message Message code to display on login page
 * @return void
 */
function redirect_to_login($message = 'session_expired') {
    // Clear any existing session data
    if (isset($_SESSION)) {
        $_SESSION = array();
    }
    
    header("Location: index.php?p=login&msg=" . urlencode($message));
    exit;
}

/**
 * Log out the current user
 * 
 * @param boolean $redirect Whether to redirect after logout
 * @return void
 */
function logout_user($redirect = true) {
    if (!isset($_SESSION)) {
        session_start();
    }
    
    // Log the logout action
    if(isset($_SESSION['user_email'])) {
        error_log("User logged out: " . $_SESSION['user_email']);
    }
    
    // Get session cookie parameters
    $params = session_get_cookie_params();
    
    // Unset all session variables
    $_SESSION = array();
    
    // Delete the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
    
    // Clear remember me cookie if it exists
    if (isset($_COOKIE['remember'])) {
        setcookie('remember', '', time() - 3600, '/');
    }
    
    if ($redirect) {
        // Redirect to home page
        header("Location: index.php?msg=logout_success");
        exit;
    }
}