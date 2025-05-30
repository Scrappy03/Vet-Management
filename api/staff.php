<?php
// Enable error logging and prevent output
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('display_errors', 0);

// Start output buffering to catch any unexpected output
ob_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    require_once '../includes/boot.include.php';
    require_once '../classes/staff.class.php';
    
    // Check if database connection exists
    if (!isset($Conn) || !$Conn) {
        throw new Exception('Database connection not available');
    }
    
} catch (Exception $e) {
    ob_clean();
    echo json_encode([
        'success' => false,
        'message' => 'Failed to load dependencies: ' . $e->getMessage()
    ]);
    exit();
}

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

try {
    $staff = new Staff($Conn);
    $method = $_SERVER['REQUEST_METHOD'];
    
    switch ($method) {
        case 'GET':
            handleGetRequest($staff, $response);
            break;
            
        case 'POST':
            handlePostRequest($staff, $response);
            break;
            
        case 'PUT':
            handlePutRequest($staff, $response);
            break;
            
        case 'DELETE':
            handleDeleteRequest($staff, $response);
            break;
            
        default:
            $response['message'] = 'Method not allowed';
            http_response_code(405);
            break;
    }
    
} catch (Exception $e) {
    $response['message'] = 'Internal server error: ' . $e->getMessage();
    http_response_code(500);
}

// Clean any unexpected output and return JSON
ob_clean();
echo json_encode($response);
exit();

function handleGetRequest($staff, &$response) {
    $action = $_GET['action'] ?? 'list';
    
    switch ($action) {
        case 'list':
            $search = $_GET['search'] ?? '';
            $role = $_GET['role'] ?? '';
            $status = $_GET['status'] ?? '';
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $staff_list = $staff->getStaff($search, $role, $status, $limit, $offset);
            $total_count = $staff->getStaffCount($search, $role, $status);
            
            if ($staff_list !== false) {
                $response['success'] = true;
                $response['data'] = [
                    'staff' => $staff_list,
                    'total' => $total_count,
                    'limit' => $limit,
                    'offset' => $offset
                ];
                $response['message'] = 'Staff retrieved successfully';
            } else {
                $response['message'] = 'Failed to retrieve staff';
                $error_info = $staff->getErrorInfo();
                if (!empty($error_info)) {
                    $response['error'] = $error_info;
                }
            }
            break;
            
        case 'get':
            $staff_id = $_GET['id'] ?? null;
            
            if (!$staff_id) {
                $response['message'] = 'Staff ID is required';
                http_response_code(400);
                return;
            }
            
            $staff_member = $staff->getStaffById($staff_id);
            
            if ($staff_member !== false) {
                $response['success'] = true;
                $response['data'] = $staff_member;
                $response['message'] = 'Staff member retrieved successfully';
            } else {
                $response['message'] = 'Staff member not found';
                http_response_code(404);
            }
            break;
            
        default:
            $response['message'] = 'Invalid action';
            http_response_code(400);
            break;
    }
}

function handlePostRequest($staff, &$response) {
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Invalid JSON data: ' . json_last_error_msg();
        http_response_code(400);
        return;
    }
    
    // Set default status if not provided
    if (!isset($data['status'])) {
        $data['status'] = 'active';
    }
    
    $staff_id = $staff->addStaff($data);
    
    if ($staff_id !== false) {
        $response['success'] = true;
        $response['data'] = ['staff_id' => $staff_id];
        $response['message'] = 'Staff member added successfully';
        http_response_code(201);
    } else {
        $error_info = $staff->getErrorInfo();
        $response['message'] = $error_info['message'] ?? 'Failed to add staff member';
        
        // Set appropriate HTTP status code based on error type
        if (isset($error_info['code'])) {
            switch ($error_info['code']) {
                case 'VALIDATION_ERROR':
                case 'DUPLICATE_EMAIL':
                    http_response_code(400);
                    break;
                default:
                    http_response_code(500);
                    break;
            }
        } else {
            http_response_code(500);
        }
        
        if (!empty($error_info)) {
            $response['error'] = $error_info;
        }
    }
}

function handlePutRequest($staff, &$response) {
    $staff_id = $_GET['id'] ?? null;
    
    if (!$staff_id) {
        $response['message'] = 'Staff ID is required';
        http_response_code(400);
        return;
    }
    
    $json_input = file_get_contents('php://input');
    $data = json_decode($json_input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['message'] = 'Invalid JSON data';
        http_response_code(400);
        return;
    }
    
    $success = $staff->updateStaff($staff_id, $data);
    
    if ($success !== false) {
        $response['success'] = true;
        $response['message'] = 'Staff member updated successfully';
    } else {
        $error_info = $staff->getErrorInfo();
        $response['message'] = $error_info['message'] ?? 'Failed to update staff member';
        
        // Set appropriate HTTP status code based on error type
        if (isset($error_info['code'])) {
            switch ($error_info['code']) {
                case 'NOT_FOUND':
                    http_response_code(404);
                    break;
                case 'NO_DATA':
                    http_response_code(400);
                    break;
                default:
                    http_response_code(500);
                    break;
            }
        } else {
            http_response_code(500);
        }
        
        $response['error'] = $error_info;
    }
}

function handleDeleteRequest($staff, &$response) {
    $staff_id = $_GET['id'] ?? null;
    
    if (!$staff_id) {
        $response['message'] = 'Staff ID is required';
        http_response_code(400);
        return;
    }
    
    $success = $staff->deleteStaff($staff_id);
    
    if ($success !== false) {
        $response['success'] = true;
        $response['message'] = 'Staff member deleted successfully';
    } else {
        $error_info = $staff->getErrorInfo();
        $response['message'] = $error_info['message'] ?? 'Failed to delete staff member';
        
        // Set appropriate HTTP status code based on error type
        if (isset($error_info['code']) && $error_info['code'] === 'NOT_FOUND') {
            http_response_code(404);
        } else {
            http_response_code(500);
        }
        
        $response['error'] = $error_info;
    }
}
?>
