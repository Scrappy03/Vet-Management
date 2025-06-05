<?php
// Use direct requires like in test_user_creation.php
require_once(__DIR__ . '/../includes/config.include.php');
require_once(__DIR__ . '/../includes/autoloader.include.php');
require_once(__DIR__ . '/../includes/db.include.php');
require_once(__DIR__ . '/../includes/auth.include.php');

// Debug database connection and ensure it's working properly
try {
    $testQuery = $Conn->query("SELECT 1");
    
    // Verify the database connection is working properly
    $stmt = $Conn->query("SELECT COUNT(*) FROM staff");
    $staffCount = $stmt->fetchColumn();
    
    // Show debug info when needed
    if(isset($_GET['debug'])) {
        echo "<div class='alert alert-info'>Debug mode: Database connection successful. Staff count: $staffCount<br>";
        echo "Host: " . DB_HOST . " | Database: " . DB_NAME . " | User: " . DB_USER . "</div>";
    }
} catch (PDOException $e) {
    // Echo error for visibility during development
    echo "<div class='alert alert-danger'>Database connection failed: " . $e->getMessage() . "</div>";
    
    // Log the error for server logs
    error_log("Database connection error in login.php: " . $e->getMessage());
    
    // Halt execution if database connection fails
    exit("Please check database settings and try again.");
}

// Initialize variables
$error = "";
$success = "";
$email = "";

// Check for messages in the URL
if (isset($_GET['msg'])) {
    switch ($_GET['msg']) {
        case 'logout_success':
            $success = "You have been successfully logged out.";
            break;
    }
}

// Check if the form was submitted
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registration form processing
    if(isset($_POST['register'])) {
        // Get form data
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $password_confirm = isset($_POST['password_confirm']) ? trim($_POST['password_confirm']) : '';
        $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';
        $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
        $specialties = isset($_POST['specialties']) ? trim($_POST['specialties']) : '';
        $education = isset($_POST['education']) ? trim($_POST['education']) : '';
        $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
        
        // Basic validation
        if(empty($first_name)) {
            $error = "First name is required";
        } elseif(empty($last_name)) {
            $error = "Last name is required";
        } elseif(empty($email)) {
            $error = "Email is required";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email";
        } elseif(empty($phone)) {
            $error = "Phone number is required";
        } elseif(empty($role)) {
            $error = "Role is required";
        } elseif(empty($start_date)) {
            $error = "Start date is required";
        } elseif(empty($password)) {
            $error = "Password is required";
        } elseif(strlen($password) < 6) {
            $error = "Password must be at least 6 characters";
        } elseif($password !== $password_confirm) {
            $error = "Passwords do not match";
        } else {
            
            $User = new User($Conn);
            
            // Check if email already exists
            if($User->emailExists($email)) {
                $error = "Email already registered";
            } else {
                // Log the registration attempt
                error_log("User registration attempt with email: $email");
                
                // Prepare user data with all fields
                $userData = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'role' => $role,
                    'start_date' => $start_date,
                    'status' => $status,
                    'specialties' => $specialties,
                    'education' => $education,
                    'bio' => $bio,
                    'profile_image' => ''
                ];
                
                // Debug output
                if(isset($_GET['debug'])) {
                    echo "<!-- User registration data: " . print_r($userData, true) . " -->";
                }
                
                // Add try-catch to capture any exceptions during user creation
                try {
                    $result = $User->createUser($userData);
                    if($result !== false) {
                        $success = "Staff account has been created successfully. Please login to continue.";
                        // Clear form data on success
                        $_POST = array();
                        if(isset($_GET['debug'])) {
                            $success .= " (User was successfully added to database)";
                        }
                    } else {
                        $error = "User creation failed.";
                        
                        // Get PDO error info if available
                        if(method_exists($User, 'getErrorInfo')) {
                            $errorInfo = $User->getErrorInfo();
                            $error .= " Error details: " . print_r($errorInfo, true);
                        }
                        
                        // Show the SQL query for debugging purposes
                        if(method_exists($User, 'getLastQuery') && isset($_GET['debug'])) {
                            $error .= "<br>SQL Query: " . $User->getLastQuery();
                        }
                    }
                } catch (Exception $e) {
                    $error = "Exception caught during registration: " . $e->getMessage();
                    
                    // Additional debugging
                    if(isset($_GET['debug'])) {
                        $error .= "<br>Exception trace: " . $e->getTraceAsString();
                    }
                }
            }
        }
    }
    // Login form processing
    else if(isset($_POST['login'])) {
        // Get form data
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $remember = isset($_POST['remember']) ? true : false;
        
        // Basic validation
        if(empty($email)) {
            $error = "Email is required";
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid email";
        } elseif(empty($password)) {
            $error = "Password is required";
        } else {
            // All validation passed, attempt to log in
            
            // Attempt login with User class
            $User = new User($Conn);
            
            try {
                $user_data = $User->loginUser($email, $password);
                
                if($user_data) {
                    // Login successful
                    // Set session variables
                    $_SESSION['is_loggedin'] = true;
                    $_SESSION['user_data'] = $user_data;
                    $_SESSION['user_id'] = $user_data['staff_id'];
                    $_SESSION['user_email'] = $user_data['email'];
                    $_SESSION['user_name'] = $user_data['first_name'] . ' ' . $user_data['last_name'];
                    
                    // Log successful login
                    error_log("Successful login: User ID {$user_data['staff_id']} - {$user_data['email']}");
                    
                    // Redirect to dashboard
                    header("Location: index.php?p=dashboard");
                    exit;
                } else {
                    // Get PDO error info
                    if(method_exists($User, 'getErrorInfo')) {
                        $errorInfo = $User->getErrorInfo();
                    }
                    $error = "Invalid email or password";
                }
            } catch (Exception $e) {
                $error = "Exception caught during login: " . $e->getMessage();
            }
        }
    }
}

// Pass variables to template
$Smarty->assign('error', $error);
$Smarty->assign('success', $success);
$Smarty->assign('email', $email);