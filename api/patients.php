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
            
            // Get upcoming appointments (mock data for now)
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
            
            // Get medical history (mock data for now)
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
