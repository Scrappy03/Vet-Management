<?php
require_once(__DIR__ . '/../includes/boot.include.php');
require_once(__DIR__ . '/../includes/auth.include.php');

// Set appropriate CORS headers
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

// Initialize Appointment class
$Appointment = new Appointment($Conn);

// Handle GET requests (fetch appointments)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle notifications request
    if (isset($_GET['notifications'])) {
        try {
            $notifications = $Appointment->getDashboardNotifications();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'notifications' => $notifications,
                'count' => count($notifications)
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch notifications: ' . $e->getMessage()]);
            exit;
        }
    }
    
    // Handle schedule summary request
    if (isset($_GET['schedule_summary'])) {
        try {
            $summary = $Appointment->getTodayScheduleSummary();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'summary' => $summary
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch schedule summary: ' . $e->getMessage()]);
            exit;
        }
    }
    
    // Handle recent activity request
    if (isset($_GET['recent_activity'])) {
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        try {
            $activity = $Appointment->getRecentActivity($limit);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'activity' => $activity,
                'count' => count($activity)
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch recent activity: ' . $e->getMessage()]);
            exit;
        }
    }
    
    // Handle performance metrics request
    if (isset($_GET['performance_metrics'])) {
        try {
            $metrics = $Appointment->getPerformanceMetrics();
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'metrics' => $metrics
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch performance metrics: ' . $e->getMessage()]);
            exit;
        }
    }
    
    // Handle mini calendar data request
    if (isset($_GET['calendar_data'])) {
        $month = isset($_GET['month']) ? (int)$_GET['month'] : null;
        $year = isset($_GET['year']) ? (int)$_GET['year'] : null;
        try {
            $calendarData = $Appointment->getMiniCalendarData($month, $year);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'calendar_data' => $calendarData,
                'month' => $month ?: date('m'),
                'year' => $year ?: date('Y')
            ]);
            exit;
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch calendar data: ' . $e->getMessage()]);
            exit;
        }
    }
    
    // Handle search requests first
    if (isset($_GET['search'])) {
        $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
        $date = isset($_GET['date']) ? $_GET['date'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : 'all';
        $dateRange = isset($_GET['date_range']) ? $_GET['date_range'] : null;
        $species = isset($_GET['species']) ? $_GET['species'] : null;
        $appointmentType = isset($_GET['appointment_type']) ? $_GET['appointment_type'] : null;
        $sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'start_time';
        $sortOrder = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'ASC';
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
        $ageRange = isset($_GET['age_range']) ? $_GET['age_range'] : null;
        $urgencyLevel = isset($_GET['urgency']) ? $_GET['urgency'] : null;
        
        try {
            $searchResults = $Appointment->searchAppointments(
                $searchTerm, 
                $date, 
                $type, 
                $dateRange, 
                $species, 
                $appointmentType, 
                $sortBy, 
                $sortOrder, 
                $limit,
                $ageRange,
                $urgencyLevel
            );
            
            if ($searchResults === false) {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Failed to search appointments', 'details' => $Appointment->getErrorInfo()]);
                exit;
            }
            
            // Handle CSV export
            if (isset($_GET['export']) && $_GET['export'] === 'csv') {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="appointments_export_' . date('Y-m-d') . '.csv"');
                
                $output = fopen('php://output', 'w');
                
                // CSV headers
                fputcsv($output, [
                    'Pet Name', 'Species', 'Breed', 'Pet Age', 
                    'Owner Name', 'Owner Phone', 'Owner Email',
                    'Appointment Date', 'Appointment Time', 'Appointment Type',
                    'Status', 'Staff Member', 'Notes', 'Care Status'
                ]);
                
                // CSV data
                foreach ($searchResults as $appointment) {
                    $startTime = new DateTime($appointment['start_time']);
                    fputcsv($output, [
                        $appointment['pet_name'],
                        $appointment['species'],
                        $appointment['breed'] ?? '',
                        $appointment['pet_age'] ?? '',
                        $appointment['owner_name'],
                        $appointment['phone'] ?? '',
                        $appointment['email'] ?? '',
                        $startTime->format('Y-m-d'),
                        $startTime->format('H:i'),
                        $appointment['appointment_type'],
                        $appointment['status'],
                        $appointment['staff_name'] ?? 'Not assigned',
                        $appointment['notes'] ?? '',
                        $appointment['care_status'] ?? ''
                    ]);
                }
                
                fclose($output);
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode($searchResults);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Handle filter options request
    if (isset($_GET['filter_options'])) {
        try {
            $filterOptions = $Appointment->getFilterOptions();
            header('Content-Type: application/json');
            echo json_encode($filterOptions);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Handle search statistics request
    if (isset($_GET['search_stats'])) {
        $searchTerm = isset($_GET['q']) ? $_GET['q'] : '';
        $date = isset($_GET['date']) ? $_GET['date'] : null;
        $type = isset($_GET['type']) ? $_GET['type'] : 'all';
        $dateRange = isset($_GET['date_range']) ? $_GET['date_range'] : null;
        $species = isset($_GET['species']) ? $_GET['species'] : null;
        $appointmentType = isset($_GET['appointment_type']) ? $_GET['appointment_type'] : null;
        $ageRange = isset($_GET['age_range']) ? $_GET['age_range'] : null;
        $urgencyLevel = isset($_GET['urgency']) ? $_GET['urgency'] : null;
        
        try {
            $stats = $Appointment->getSearchStatistics(
                $searchTerm, 
                $date, 
                $type, 
                $dateRange, 
                $species, 
                $appointmentType,
                $ageRange,
                $urgencyLevel
            );
            
            if ($stats === false) {
                header('HTTP/1.1 500 Internal Server Error');
                echo json_encode(['error' => 'Failed to get search statistics', 'details' => $Appointment->getErrorInfo()]);
                exit;
            }
            
            header('Content-Type: application/json');
            echo json_encode($stats);
        } catch (Exception $e) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }
    
    // Special case for single appointment
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id']; // Ensure integer type
        error_log("Fetching appointment with ID: " . $id);
        $appointment = $Appointment->getAppointmentById($id);
        
        if ($appointment) {
            header('Content-Type: application/json');
            error_log("Appointment found: " . json_encode($appointment));
            echo json_encode($appointment);
        } else {
            header('HTTP/1.1 404 Not Found');
            error_log("Appointment not found. Error: " . json_encode($Appointment->getErrorInfo()));
            echo json_encode([
                'error' => 'Appointment not found',
                'details' => $Appointment->getErrorInfo(),
                'query' => $Appointment->getLastQuery()
            ]);
        }
        exit;
    }

    $start = isset($_GET['start']) ? $_GET['start'] : null;
    $end = isset($_GET['end']) ? $_GET['end'] : null;
    
    // Basic validation
    if (!$start || !$end) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Start and end dates are required']);
        exit;
    }
    
    try {
        $appointments = $Appointment->getAppointmentsForRange($start, $end);
        
        if ($appointments === false) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to fetch appointments', 'details' => $Appointment->getErrorInfo()]);
            exit;
        }
        
        // Format data for FullCalendar
        $events = array_map(function($appt) {
            // Make sure the appointment_id is sent as an integer for client-side processing
            $appointmentId = (int)$appt['appointment_id'];
            error_log("Formatting appointment ID: " . $appointmentId);
            
            return [
                'id' => $appointmentId,
                'title' => $appt['appointment_type'] . ' - ' . $appt['pet_name'],
                'start' => $appt['start_time'],
                'end' => $appt['end_time'],
                'type' => $appt['appointment_type'],
                'pet_name' => $appt['pet_name'] . ' (' . $appt['species'] . ')',
                'owner_name' => $appt['owner_name'],
                'status' => $appt['status'],
                'notes' => $appt['notes'],
                'care_status' => $appt['care_status']
            ];
        }, $appointments);
        
        header('Content-Type: application/json');
        echo json_encode($events);
        
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
}
// Handle POST requests (create appointment)
else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get data from request
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Basic validation
    if (!isset($data['patient_id']) || !isset($data['staff_id']) || 
        !isset($data['start_time']) || !isset($data['appointment_type'])) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    try {
        // Calculate end time (default to 1 hour after start)
        $start = new DateTime($data['start_time']);
        $end = clone $start;
        $end->modify('+1 hour');
        
        $appointment_id = $Appointment->createAppointment(
            $data['patient_id'],
            $data['staff_id'],
            $data['appointment_type'],
            $data['start_time'],
            $end->format('Y-m-d H:i:s'),
            $data['status'] ?? 'upcoming',
            $data['notes'] ?? null,
            $data['care_status'] ?? null
        );
        
        if ($appointment_id) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'appointment_id' => $appointment_id
            ]);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Failed to create appointment', 'details' => $Appointment->getErrorInfo()]);
        }
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
}
// Handle PUT requests (update appointment)
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    
    // Get data from request and log it for debugging
    $jsonData = json_decode(file_get_contents('php://input'), true);
    $data = $jsonData;
    error_log("Processing update request with data: " . json_encode($data));
    
    // Basic validation with detailed logging
    $requiredFields = [
        'appointment_id',
        'patient_id',
        'staff_id',
        'start_time',
        'appointment_type'
    ];
    
    $missingFields = [];
    foreach ($requiredFields as $field) {
        if (!isset($data[$field])) {
            $missingFields[] = $field;
        }
    }
    
    if (!empty($missingFields)) {
        header('HTTP/1.1 400 Bad Request');
        $errorMsg = [
            'error' => 'Missing required fields',
            'missing' => $missingFields,
            'received_data' => $data
        ];
        error_log("Validation failed: " . json_encode($errorMsg));
        echo json_encode($errorMsg);
        exit;
    }
    
    // Ensure appointment_id is an integer
    $data['appointment_id'] = (int)$data['appointment_id'];
    error_log("Update operation for appointment ID: " . $data['appointment_id']);
    
    try {
        // Calculate end time (default to 1 hour after start)
        $start = new DateTime($data['start_time']);
        $end = isset($data['end_time']) ? new DateTime($data['end_time']) : clone $start;
        
        if (!isset($data['end_time'])) {
            $end->modify('+1 hour');
        }
        
        error_log("About to call updateAppointment with appointment_id: " . $data['appointment_id']);
        $result = $Appointment->updateAppointment(
            $data['appointment_id'],
            $data['patient_id'],
            $data['staff_id'],
            $data['appointment_type'],
            $data['start_time'],
            $end->format('Y-m-d H:i:s'),
            $data['status'] ?? 'upcoming',
            $data['notes'] ?? null,
            $data['care_status'] ?? null
        );
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'appointment_id' => $data['appointment_id']
            ]);
            error_log("Update successful for appointment ID: " . $data['appointment_id']);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            error_log("Update failed: " . json_encode($Appointment->getErrorInfo()));
            echo json_encode([
                'error' => 'Failed to update appointment', 
                'details' => $Appointment->getErrorInfo(),
                'query' => $Appointment->getLastQuery()
            ]);
        }
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
}
// Handle DELETE requests (delete appointment)
else if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Get data from request
    $jsonData = json_decode(file_get_contents('php://input'), true);
    
    // Check if we have an ID either in JSON body or query string
    $appointmentId = null;
    if (isset($jsonData['appointment_id'])) {
        $appointmentId = (int)$jsonData['appointment_id'];
    } else if (isset($_GET['id'])) {
        $appointmentId = (int)$_GET['id'];
    }
    
    // Validate we have an appointment ID
    if (!$appointmentId) {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Missing appointment ID']);
        exit;
    }
    
    error_log("Delete operation for appointment ID: " . $appointmentId);
    
    try {
        $result = $Appointment->deleteAppointment($appointmentId);
        
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'message' => 'Appointment deleted successfully'
            ]);
            error_log("Delete successful for appointment ID: " . $appointmentId);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            error_log("Delete failed: " . json_encode($Appointment->getErrorInfo()));
            echo json_encode([
                'error' => 'Failed to delete appointment', 
                'details' => $Appointment->getErrorInfo(),
                'query' => $Appointment->getLastQuery()
            ]);
        }
    } catch (Exception $e) {
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(['error' => $e->getMessage()]);
    }
}