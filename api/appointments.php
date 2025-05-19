<?php
require_once(__DIR__ . '/../includes/boot.include.php');
require_once(__DIR__ . '/../includes/auth.include.php');

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
            return [
                'id' => $appt['appointment_id'],
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