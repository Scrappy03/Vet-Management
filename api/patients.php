<?php
require_once(__DIR__ . '/../includes/boot.include.php');
require_once(__DIR__ . '/../includes/auth.include.php');

// Set appropriate CORS headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Ensure user is logged in
if (!is_logged_in()) {
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

try {
    // Initialize classes
    $Patient = new Patient($Conn);
    
    // Handle GET requests
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        
        // Get specific patient by ID
        if (isset($_GET['id'])) {
            $patient_id = (int)$_GET['id'];
            
            // Get patient details
            $patient = $Patient->getPatientById($patient_id);
            
            if ($patient === false) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Patient not found',
                    'debug' => $Patient->getErrorInfo()
                ]);
                exit;
            }
            
            // Get upcoming appointments
            $upcoming_appointments = [
                [
                    'id' => 1,
                    'date' => '2025-06-02',
                    'time' => '10:00',
                    'type' => 'Vaccination',
                    'vet' => 'Dr. Johnson'
                ],
                [
                    'id' => 2,
                    'date' => '2025-06-15',
                    'time' => '14:30',
                    'type' => 'Check-up',
                    'vet' => 'Dr. Smith'
                ]
            ];
            
            // Get medical history
            $medical_history = [
                [
                    'id' => 1,
                    'date' => '2025-05-15',
                    'type' => 'Vaccination',
                    'diagnosis' => 'Annual vaccination - DHPP',
                    'treatment' => 'Vaccination administered',
                    'vet' => 'Dr. Johnson',
                    'notes' => 'Patient responded well to vaccination. No adverse reactions.'
                ],
                [
                    'id' => 2,
                    'date' => '2025-04-10',
                    'type' => 'Check-up',
                    'diagnosis' => 'Routine health check',
                    'treatment' => 'General examination, weight check',
                    'vet' => 'Dr. Smith',
                    'notes' => 'Patient in good health. Recommended dietary adjustment.'
                ]
            ];
            
            echo json_encode([
                'success' => true,
                'patient' => $patient,
                'upcoming_appointments' => $upcoming_appointments,
                'medical_history' => $medical_history
            ]);
            
        } else {
            // Get all patients with optional filters
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';
            $species = isset($_GET['species']) ? trim($_GET['species']) : '';
            $status = isset($_GET['status']) ? trim($_GET['status']) : '';
            $owner = isset($_GET['owner']) ? trim($_GET['owner']) : '';
            $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
            $limit = isset($_GET['limit']) ? max(1, intval($_GET['limit'])) : 50;
            $offset = ($page - 1) * $limit;
            
            $patients = $Patient->getPatients($search, $species, $status, $owner, $limit, $offset);
            
            if ($patients === false) {
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to fetch patients',
                    'debug' => $Patient->getErrorInfo()
                ]);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'patients' => $patients,
                'total' => count($patients),
                'page' => $page,
                'limit' => $limit
            ]);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Create new patient
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON data'
            ]);
            exit;
        }
        
        // Validate required fields
        $required_fields = ['name', 'species', 'breed', 'age', 'gender', 'owner_name', 'owner_email', 'owner_phone', 'status'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode([
                    'success' => false,
                    'error' => "Missing required field: $field"
                ]);
                exit;
            }
        }
        
        // Calculate date of birth from age
        $current_year = date('Y');
        $birth_year = $current_year - (int)$input['age'];
        $date_of_birth = $birth_year . '-01-01'; // Default to January 1st
        
        // Split owner name into first and last name
        $owner_name_parts = explode(' ', trim($input['owner_name']), 2);
        $first_name = $owner_name_parts[0];
        $last_name = isset($owner_name_parts[1]) ? $owner_name_parts[1] : '';
        
        // Create patient data array
        $patient_data = [
            'name' => trim($input['name']),
            'species' => trim($input['species']),
            'breed' => trim($input['breed']),
            'gender' => trim($input['gender']),
            'date_of_birth' => $date_of_birth,
            'neutered' => isset($input['neutered']) ? $input['neutered'] : null,
            'weight' => isset($input['weight']) && $input['weight'] !== '' ? (float)$input['weight'] : null,
            'microchip_id' => isset($input['microchip_id']) ? trim($input['microchip_id']) : null,
            'allergies' => isset($input['allergies']) ? trim($input['allergies']) : null,
            'status' => trim($input['status'])
        ];
        
        // Create owner data array
        $owner_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => trim($input['owner_email']),
            'phone' => trim($input['owner_phone']),
            'address' => isset($input['owner_address']) ? trim($input['owner_address']) : null
        ];
        
        $result = $Patient->createPatient($patient_data, $owner_data);
        
        if ($result !== false) {
            echo json_encode([
                'success' => true,
                'message' => 'Patient created successfully',
                'patient_id' => $result
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to create patient',
                'debug' => $Patient->getErrorInfo()
            ]);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        // Update existing patient
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['patient_id'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Invalid data or missing patient ID'
            ]);
            exit;
        }
        
        $patient_id = (int)$input['patient_id'];
        
        // Validate required fields
        $required_fields = ['name', 'species', 'breed', 'age', 'gender', 'owner_name', 'owner_email', 'owner_phone', 'status'];
        foreach ($required_fields as $field) {
            if (empty($input[$field])) {
                echo json_encode([
                    'success' => false,
                    'error' => "Missing required field: $field"
                ]);
                exit;
            }
        }
        
        // Calculate date of birth from age (assuming current year)
        $current_year = date('Y');
        $birth_year = $current_year - (int)$input['age'];
        $date_of_birth = $birth_year . '-01-01'; // Default to January 1st
        
        // Split owner name into first and last name
        $owner_name_parts = explode(' ', trim($input['owner_name']), 2);
        $first_name = $owner_name_parts[0];
        $last_name = isset($owner_name_parts[1]) ? $owner_name_parts[1] : '';
        
        // Create patient data array
        $patient_data = [
            'name' => trim($input['name']),
            'species' => trim($input['species']),
            'breed' => trim($input['breed']),
            'gender' => trim($input['gender']),
            'date_of_birth' => $date_of_birth,
            'neutered' => isset($input['neutered']) ? $input['neutered'] : null,
            'weight' => isset($input['weight']) && $input['weight'] !== '' ? (float)$input['weight'] : null,
            'microchip_id' => isset($input['microchip_id']) ? trim($input['microchip_id']) : null,
            'allergies' => isset($input['allergies']) ? trim($input['allergies']) : null,
            'status' => trim($input['status'])
        ];
        
        // Create owner data array
        $owner_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => trim($input['owner_email']),
            'phone' => trim($input['owner_phone']),
            'address' => isset($input['owner_address']) ? trim($input['owner_address']) : null
        ];
        
        $result = $Patient->updatePatient($patient_id, $patient_data, $owner_data);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Patient updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to update patient',
                'debug' => $Patient->getErrorInfo()
            ]);
        }
        
    } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        // Delete patient
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['patient_id'])) {
            echo json_encode([
                'success' => false,
                'error' => 'Missing patient ID'
            ]);
            exit;
        }
        
        $patient_id = (int)$input['patient_id'];
        
        $result = $Patient->deletePatient($patient_id);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Patient deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'error' => 'Failed to delete patient',
                'debug' => $Patient->getErrorInfo()
            ]);
        }
        
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Method not allowed'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>
