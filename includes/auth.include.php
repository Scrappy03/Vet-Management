<?php
function is_logged_in() {
    // Validate required authentication markers
    if (!isset($_SESSION['is_loggedin']) || $_SESSION['is_loggedin'] !== true || 
        !isset($_SESSION['user_id']) || !isset($_SESSION['user_data'])) {
        return false;
    }
    
    // Security validation for session structure
    if (!is_array($_SESSION['user_data']) || !isset($_SESSION['user_data']['email'])) {
        // Record security anomaly
        error_log("Warning: Malformed user_data in session - " . print_r($_SESSION['user_data'], true));
        return false;
    }
    
    return true;
}

if (!function_exists('get_current_user')) {
    function get_current_user() {
        // Log what we're working with
        error_log("get_current_user - Session contents: " . print_r($_SESSION, true));
        
        // First try to get from session
        if (isset($_SESSION['user_data']) && is_array($_SESSION['user_data'])) {
            return $_SESSION['user_data'];
        }
        
        // If user_id exists but user_data doesn't, try to get from database
        if (isset($_SESSION['user_id'])) {
            global $Conn;
            if ($Conn) {
                try {
                    $User = new User($Conn);
                    $user_data = $User->getUserById($_SESSION['user_id']);
                    
                    if ($user_data) {
                        // Update session with fresh data
                        $_SESSION['user_data'] = $user_data;
                        return $user_data;
                    }
                } catch (Exception $e) {
                    error_log("Error retrieving user data: " . $e->getMessage());
                }
            }
        }
        
        // Default return if nothing found
        return array();
    }
}

function validate_user_session() {
    return isset($_SESSION['user_data']) && is_array($_SESSION['user_data']) && 
           isset($_SESSION['user_data']['email']) && isset($_SESSION['user_data']['staff_id']);
}

function redirect_to_login($message = 'session_expired') {
    // Reset session state
    if (isset($_SESSION)) {
        $_SESSION = array();
    }
    
    header("Location: index.php?p=login&msg=" . urlencode($message));
    exit;
}

function logout_user($redirect = true) {
    // Record logout event
    if(isset($_SESSION['user_email'])) {
        error_log("User logged out: " . $_SESSION['user_email']);
    }
    
    // Get session cookie configuration
    $params = session_get_cookie_params();
    
    // Clear session data
    $_SESSION = array();
    
    // Remove session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    session_destroy();
    
    // Remove persistence cookie if present
    if (isset($_COOKIE['remember'])) {
        setcookie('remember', '', time() - 3600, '/');
    }
    
    if ($redirect) {
        // Return to application entry point
        header("Location: index.php?msg=logout_success");
        exit;
    }
}