<?php
class Appointment {
    protected $Conn;
    protected $lastError = [];
    protected $lastQuery = "";
    
    public function __construct($Conn){
        $this->Conn = $Conn;
    }
    
    public function getErrorInfo() {
        return $this->lastError;
    }
    
    public function getLastQuery() {
        return $this->lastQuery;
    }
    
    public function getAppointmentsForRange($start, $end) {
        $query = "SELECT a.*, p.name as pet_name, p.species, p.breed,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name
                  FROM appointments a
                  JOIN patients p ON a.patient_id = p.patient_id
                  JOIN owners o ON p.owner_id = o.owner_id
                  WHERE a.start_time BETWEEN :start AND :end
                  ORDER BY a.start_time";
                  
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getAppointmentsForRange: " . $e->getMessage());
            return false;
        }
    }
    
    public function createAppointment($patientId, $staffId, $type, $startTime, $endTime, $status = 'upcoming', $notes = null, $careStatus = null) {
        $query = "INSERT INTO appointments 
                 (patient_id, staff_id, appointment_type, start_time, end_time, status, notes, care_status)
                 VALUES (:patient_id, :staff_id, :type, :start_time, :end_time, :status, :notes, :care_status)";
                 
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':patient_id', $patientId);
            $stmt->bindParam(':staff_id', $staffId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':care_status', $careStatus);
            
            $result = $stmt->execute();
            
            if ($result) {
                // Return the newly created appointment ID
                return $this->Conn->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in createAppointment: " . $e->getMessage());
            return false;
        }
    }
    
    public function updateAppointment($appointmentId, $patientId, $staffId, $type, $startTime, $endTime, $status, $notes = null, $careStatus = null) {
        error_log("updateAppointment called with appointmentId: $appointmentId");
        
        // Make sure appointmentId is an integer
        $appointmentId = (int)$appointmentId;
        
        // Validate appointment exists before updating
        $checkQuery = "SELECT appointment_id FROM appointments WHERE appointment_id = :appointment_id";
        try {
            $checkStmt = $this->Conn->prepare($checkQuery);
            $checkStmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Appointment ID $appointmentId not found in database");
                $this->lastError = [
                    'code' => 'NOT_FOUND',
                    'message' => "Appointment with ID $appointmentId not found"
                ];
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error checking appointment existence: " . $e->getMessage());
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            return false;
        }
        
        $query = "UPDATE appointments 
                 SET patient_id = :patient_id,
                     staff_id = :staff_id, 
                     appointment_type = :type, 
                     start_time = :start_time, 
                     end_time = :end_time, 
                     status = :status, 
                     notes = :notes, 
                     care_status = :care_status
                 WHERE appointment_id = :appointment_id";
                 
        $this->lastQuery = $query;
        
        error_log("Executing update query for appointment ID: $appointmentId");
        error_log("Update params: " . json_encode([
            'patient_id' => $patientId,
            'staff_id' => $staffId,
            'type' => $type,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $status,
            'notes' => $notes,
            'care_status' => $careStatus
        ]));
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $stmt->bindParam(':patient_id', $patientId);
            $stmt->bindParam(':staff_id', $staffId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':start_time', $startTime);
            $stmt->bindParam(':end_time', $endTime);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':notes', $notes);
            $stmt->bindParam(':care_status', $careStatus);
            
            $result = $stmt->execute();
            
            if ($result) {
                error_log("Update successful for appointment ID: $appointmentId");
                return true;
            }
            
            error_log("Update failed for appointment ID: $appointmentId");
            $this->lastError = [
                'code' => $stmt->errorCode(),
                'info' => $stmt->errorInfo(),
                'message' => print_r($stmt->errorInfo(), true)
            ];
            error_log("SQL Error details: " . print_r($stmt->errorInfo(), true));
            return false;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in updateAppointment: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAppointmentById($appointmentId) {
        // Make sure appointmentId is an integer
        $appointmentId = (int)$appointmentId;
        
        $query = "SELECT a.*, p.name as pet_name, p.species, p.breed,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name
                  FROM appointments a
                  JOIN patients p ON a.patient_id = p.patient_id
                  JOIN owners o ON p.owner_id = o.owner_id
                  WHERE a.appointment_id = :appointment_id";
                  
        $this->lastQuery = $query;
        error_log("Getting appointment by ID: $appointmentId with query: $query");
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getAppointmentById: " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteAppointment($appointmentId) {
        error_log("deleteAppointment called with appointmentId: $appointmentId");
        
        // Make sure appointmentId is an integer
        $appointmentId = (int)$appointmentId;
        
        // Validate appointment exists before deleting
        $checkQuery = "SELECT appointment_id FROM appointments WHERE appointment_id = :appointment_id";
        try {
            $checkStmt = $this->Conn->prepare($checkQuery);
            $checkStmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->rowCount() === 0) {
                error_log("Appointment ID $appointmentId not found in database");
                $this->lastError = [
                    'code' => 'NOT_FOUND',
                    'message' => "Appointment with ID $appointmentId not found"
                ];
                return false;
            }
        } catch (PDOException $e) {
            error_log("Error checking appointment existence: " . $e->getMessage());
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            return false;
        }
        
        // Perform the delete operation
        $query = "DELETE FROM appointments WHERE appointment_id = :appointment_id";
        $this->lastQuery = $query;
        
        error_log("Executing delete query for appointment ID: $appointmentId");
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            
            if ($result) {
                error_log("Delete successful for appointment ID: $appointmentId");
                return true;
            }
            
            error_log("Delete failed for appointment ID: $appointmentId");
            $this->lastError = [
                'code' => $stmt->errorCode(),
                'info' => $stmt->errorInfo(),
                'message' => print_r($stmt->errorInfo(), true)
            ];
            error_log("SQL Error details: " . print_r($stmt->errorInfo(), true));
            return false;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in deleteAppointment: " . $e->getMessage());
            return false;
        }
    }
    
    public function getAppointmentStats($date = null) {
        // If no date provided, use today
        if ($date === null) {
            $date = date('Y-m-d');
        }
        
        // Calculate today's appointments
        $todayQuery = "SELECT COUNT(*) as count FROM appointments 
                       WHERE DATE(start_time) = :date";
        
        // Calculate completed appointments 
        $completedQuery = "SELECT COUNT(*) as count FROM appointments 
                          WHERE status = 'completed'";
        
        // Calculate upcoming appointments
        $upcomingQuery = "SELECT COUNT(*) as count FROM appointments 
                         WHERE status = 'upcoming' AND start_time >= NOW()";
        
        try {
            // Get today's appointments
            $todayStmt = $this->Conn->prepare($todayQuery);
            $todayStmt->bindParam(':date', $date);
            $todayStmt->execute();
            $todayResult = $todayStmt->fetch(PDO::FETCH_ASSOC);
            $todayCount = $todayResult['count'];
            
            // Get completed appointments
            $completedStmt = $this->Conn->prepare($completedQuery);
            $completedStmt->execute();
            $completedResult = $completedStmt->fetch(PDO::FETCH_ASSOC);
            $completedCount = $completedResult['count'];
            
            // Get upcoming appointments
            $upcomingStmt = $this->Conn->prepare($upcomingQuery);
            $upcomingStmt->execute();
            $upcomingResult = $upcomingStmt->fetch(PDO::FETCH_ASSOC);
            $upcomingCount = $upcomingResult['count'];
            
            return [
                'total' => $todayCount,
                'completed' => $completedCount,
                'upcoming' => $upcomingCount
            ];
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getAppointmentStats: " . $e->getMessage());
            return [
                'total' => 0,
                'completed' => 0,
                'upcoming' => 0
            ];
        }
    }

    public function getUpcomingAppointments($limit = 5) {
        $query = "SELECT a.*, p.name as pet_name, p.species,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name
                  FROM appointments a
                  JOIN patients p ON a.patient_id = p.patient_id
                  JOIN owners o ON p.owner_id = o.owner_id
                  WHERE a.status = 'upcoming' AND a.start_time >= NOW()
                  ORDER BY a.start_time ASC
                  LIMIT :limit";
                  
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Format the appointments for easier template display
            $formattedAppointments = [];
            foreach ($appointments as $appointment) {
                $start = new DateTime($appointment['start_time']);
                $today = new DateTime();
                
                // Format date to show "Today" or actual date
                $date = $start->format('Y-m-d') === $today->format('Y-m-d') ? 'Today' : $start->format('d M Y');
                
                $formattedAppointments[] = [
                    'id' => $appointment['appointment_id'],
                    'pet_name' => $appointment['pet_name'],
                    'owner' => $appointment['owner_name'],
                    'date' => $date,
                    'time' => $start->format('H:i'),
                    'type' => $appointment['appointment_type'],
                    'status' => $appointment['status'],
                    'notes' => $appointment['notes'],
                    'care_status' => $appointment['care_status']
                ];
            }
            
            return $formattedAppointments;
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getUpcomingAppointments: " . $e->getMessage());
            return [];
        }
    }

    public function searchAppointments($searchTerm, $date = null, $type = 'all', $dateRange = null, $species = null, $appointmentType = null, $sortBy = 'start_time', $sortOrder = 'ASC', $limit = 100, $ageRange = null, $urgencyLevel = null) {
        $query = "SELECT a.*, p.name as pet_name, p.species, p.breed, p.date_of_birth,
                  CONCAT(o.first_name, ' ', o.last_name) as owner_name, o.phone, o.email,
                  CONCAT(s.first_name, ' ', s.last_name) as staff_name,
                  TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) as pet_age
                  FROM appointments a
                  JOIN patients p ON a.patient_id = p.patient_id
                  JOIN owners o ON p.owner_id = o.owner_id
                  LEFT JOIN staff s ON a.staff_id = s.staff_id
                  WHERE 1=1";
        
        $params = [];
        
        // Enhanced search term condition with more fields
        if (!empty($searchTerm)) {
            $query .= " AND (p.name LIKE :search_term 
                       OR CONCAT(o.first_name, ' ', o.last_name) LIKE :search_term
                       OR o.first_name LIKE :search_term
                       OR o.last_name LIKE :search_term
                       OR a.appointment_type LIKE :search_term
                       OR a.notes LIKE :search_term
                       OR a.care_status LIKE :search_term
                       OR a.status LIKE :search_term
                       OR p.species LIKE :search_term
                       OR p.breed LIKE :search_term
                       OR o.phone LIKE :search_term
                       OR CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term)";
            $params[':search_term'] = '%' . $searchTerm . '%';
        }
        
        // Specific date filter
        if (!empty($date)) {
            $query .= " AND DATE(a.start_time) = :date";
            $params[':date'] = $date;
        }
        
        // Date range filter (overrides specific date)
        if (!empty($dateRange)) {
            switch ($dateRange) {
                case 'today':
                    $query .= " AND DATE(a.start_time) = CURDATE()";
                    break;
                case 'tomorrow':
                    $query .= " AND DATE(a.start_time) = DATE_ADD(CURDATE(), INTERVAL 1 DAY)";
                    break;
                case 'this_week':
                    $query .= " AND YEARWEEK(a.start_time) = YEARWEEK(CURDATE())";
                    break;
                case 'next_week':
                    $query .= " AND YEARWEEK(a.start_time) = YEARWEEK(DATE_ADD(CURDATE(), INTERVAL 1 WEEK))";
                    break;
                case 'this_month':
                    $query .= " AND YEAR(a.start_time) = YEAR(CURDATE()) AND MONTH(a.start_time) = MONTH(CURDATE())";
                    break;
                case 'next_month':
                    $query .= " AND YEAR(a.start_time) = YEAR(DATE_ADD(CURDATE(), INTERVAL 1 MONTH)) AND MONTH(a.start_time) = MONTH(DATE_ADD(CURDATE(), INTERVAL 1 MONTH))";
                    break;
                case 'past_week':
                    $query .= " AND a.start_time >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK) AND a.start_time < CURDATE()";
                    break;
                case 'past_month':
                    $query .= " AND a.start_time >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND a.start_time < CURDATE()";
                    break;
            }
        }
        
        // Status filter
        if (!empty($type) && $type !== 'all') {
            $query .= " AND a.status = :type";
            $params[':type'] = $type;
        }
        
        // Species filter
        if (!empty($species) && $species !== 'all') {
            $query .= " AND p.species = :species";
            $params[':species'] = $species;
        }
        
        // Appointment type filter
        if (!empty($appointmentType) && $appointmentType !== 'all') {
            $query .= " AND a.appointment_type = :appointment_type";
            $params[':appointment_type'] = $appointmentType;
        }
        
        // Pet age range filter
        if (!empty($ageRange)) {
            switch ($ageRange) {
                case 'young':
                    $query .= " AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) <= 2";
                    break;
                case 'adult':
                    $query .= " AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) BETWEEN 3 AND 7";
                    break;
                case 'senior':
                    $query .= " AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) >= 8";
                    break;
            }
        }
        
        // Urgency level filter (based on care_status)
        if (!empty($urgencyLevel)) {
            switch ($urgencyLevel) {
                case 'urgent':
                    $query .= " AND (a.care_status LIKE '%aggressive%' OR a.care_status LIKE '%emergency%' OR a.appointment_type LIKE '%emergency%')";
                    break;
                case 'routine':
                    $query .= " AND (a.care_status IS NULL OR a.care_status = '' OR a.care_status LIKE '%routine%')";
                    break;
                case 'follow_up':
                    $query .= " AND (a.appointment_type LIKE '%follow%' OR a.notes LIKE '%follow%')";
                    break;
            }
        }
        
        // Sorting
        $validSortColumns = ['start_time', 'pet_name', 'owner_name', 'appointment_type', 'status', 'species'];
        $validSortOrders = ['ASC', 'DESC'];
        
        if (in_array($sortBy, $validSortColumns) && in_array(strtoupper($sortOrder), $validSortOrders)) {
            if ($sortBy === 'pet_name') {
                $query .= " ORDER BY p.name " . strtoupper($sortOrder);
            } elseif ($sortBy === 'owner_name') {
                $query .= " ORDER BY CONCAT(o.first_name, ' ', o.last_name) " . strtoupper($sortOrder);
            } else {
                $query .= " ORDER BY a." . $sortBy . " " . strtoupper($sortOrder);
            }
        } else {
            $query .= " ORDER BY a.start_time ASC";
        }
        
        // Add limit
        if ($limit > 0) {
            $query .= " LIMIT :limit";
            $params[':limit'] = $limit;
        }
        
        $this->lastQuery = $query;
        
        try {
            $stmt = $this->Conn->prepare($query);
            
            // Bind all parameters
            foreach ($params as $param => $value) {
                if ($param === ':limit') {
                    $stmt->bindValue($param, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($param, $value);
                }
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in searchAppointments: " . $e->getMessage());
            return false;
        }
    }
    
    // Add method to get search statistics
    public function getSearchStatistics($searchTerm, $date = null, $type = 'all', $dateRange = null, $species = null, $appointmentType = null, $ageRange = null, $urgencyLevel = null) {
        try {
            // Get the search results first
            $results = $this->searchAppointments($searchTerm, $date, $type, $dateRange, $species, $appointmentType, 'start_time', 'ASC', 0, $ageRange, $urgencyLevel);
            
            if ($results === false) {
                return false;
            }
            
            $stats = [
                'total_results' => count($results),
                'by_status' => [],
                'by_species' => [],
                'by_type' => [],
                'by_age_group' => ['young' => 0, 'adult' => 0, 'senior' => 0],
                'upcoming_this_week' => 0,
                'average_age' => 0
            ];
            
            $totalAge = 0;
            $ageCount = 0;
            
            foreach ($results as $appointment) {
                // Count by status
                $status = $appointment['status'] ?? 'unknown';
                $stats['by_status'][$status] = ($stats['by_status'][$status] ?? 0) + 1;
                
                // Count by species
                $species = $appointment['species'] ?? 'unknown';
                $stats['by_species'][$species] = ($stats['by_species'][$species] ?? 0) + 1;
                
                // Count by type
                $type = $appointment['appointment_type'] ?? 'unknown';
                $stats['by_type'][$type] = ($stats['by_type'][$type] ?? 0) + 1;
                
                // Count by age group
                if (isset($appointment['pet_age']) && is_numeric($appointment['pet_age'])) {
                    $age = (int)$appointment['pet_age'];
                    $totalAge += $age;
                    $ageCount++;
                    
                    if ($age <= 2) {
                        $stats['by_age_group']['young']++;
                    } elseif ($age <= 7) {
                        $stats['by_age_group']['adult']++;
                    } else {
                        $stats['by_age_group']['senior']++;
                    }
                }
                
                // Count upcoming this week
                if (isset($appointment['start_time'])) {
                    $appointmentDate = new DateTime($appointment['start_time']);
                    $now = new DateTime();
                    $weekFromNow = new DateTime('+1 week');
                    
                    if ($appointmentDate >= $now && $appointmentDate <= $weekFromNow) {
                        $stats['upcoming_this_week']++;
                    }
                }
            }
            
            // Calculate average age
            if ($ageCount > 0) {
                $stats['average_age'] = round($totalAge / $ageCount, 1);
            }
            
            return $stats;
        } catch (Exception $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("Exception in getSearchStatistics: " . $e->getMessage());
            return false;
        }
    }
    
    // New method to get distinct values for filter dropdowns
    public function getFilterOptions() {
        try {
            // Get unique species
            $speciesQuery = "SELECT DISTINCT p.species FROM patients p ORDER BY p.species";
            $speciesStmt = $this->Conn->prepare($speciesQuery);
            $speciesStmt->execute();
            $species = $speciesStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get unique appointment types
            $typeQuery = "SELECT DISTINCT appointment_type FROM appointments ORDER BY appointment_type";
            $typeStmt = $this->Conn->prepare($typeQuery);
            $typeStmt->execute();
            $appointmentTypes = $typeStmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Get unique statuses
            $statusQuery = "SELECT DISTINCT status FROM appointments ORDER BY status";
            $statusStmt = $this->Conn->prepare($statusQuery);
            $statusStmt->execute();
            $statuses = $statusStmt->fetchAll(PDO::FETCH_COLUMN);
            
            return [
                'species' => array_filter($species),
                'appointment_types' => array_filter($appointmentTypes),
                'statuses' => array_filter($statuses)
            ];
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getFilterOptions: " . $e->getMessage());
            return [
                'species' => [],
                'appointment_types' => [],
                'statuses' => []
            ];
        }
    }
    
    /**
     * Get dashboard notifications including overdue appointments, upcoming appointments, and alerts
     */
    public function getDashboardNotifications() {
        $notifications = [];
        
        try {
            // Get overdue appointments (past end_time but still not completed)
            $overdueQuery = "SELECT a.*, p.name as pet_name, p.species,
                            CONCAT(o.first_name, ' ', o.last_name) as owner_name,
                            TIMESTAMPDIFF(HOUR, a.end_time, NOW()) as hours_overdue
                            FROM appointments a
                            JOIN patients p ON a.patient_id = p.patient_id
                            JOIN owners o ON p.owner_id = o.owner_id
                            WHERE a.end_time < NOW() 
                            AND a.status NOT IN ('completed', 'cancelled')
                            ORDER BY a.end_time ASC
                            LIMIT 10";
            
            $stmt = $this->Conn->prepare($overdueQuery);
            $stmt->execute();
            $overdueAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($overdueAppointments as $appointment) {
                $timestamp = new DateTime($appointment['end_time']);
                $notifications[] = [
                    'type' => 'overdue',
                    'priority' => 'high',
                    'title' => 'Overdue Appointment',
                    'message' => $appointment['pet_name'] . ' (' . $appointment['owner_name'] . ') - ' . $appointment['hours_overdue'] . ' hours overdue',
                    'appointment_id' => $appointment['appointment_id'],
                    'timestamp' => $appointment['end_time'],
                    'formatted_time' => $timestamp->format('H:i')
                ];
            }
            
            // Get appointments starting in the next 30 minutes
            $upcomingQuery = "SELECT a.*, p.name as pet_name, p.species,
                             CONCAT(o.first_name, ' ', o.last_name) as owner_name,
                             TIMESTAMPDIFF(MINUTE, NOW(), a.start_time) as minutes_until
                             FROM appointments a
                             JOIN patients p ON a.patient_id = p.patient_id
                             JOIN owners o ON p.owner_id = o.owner_id
                             WHERE a.start_time BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)
                             AND a.status = 'upcoming'
                             ORDER BY a.start_time ASC";
            
            $stmt = $this->Conn->prepare($upcomingQuery);
            $stmt->execute();
            $soonAppointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($soonAppointments as $appointment) {
                $timestamp = new DateTime($appointment['start_time']);
                $notifications[] = [
                    'type' => 'upcoming',
                    'priority' => 'medium',
                    'title' => 'Appointment Starting Soon',
                    'message' => $appointment['pet_name'] . ' (' . $appointment['owner_name'] . ') - starts in ' . $appointment['minutes_until'] . ' minutes',
                    'appointment_id' => $appointment['appointment_id'],
                    'timestamp' => $appointment['start_time'],
                    'formatted_time' => $timestamp->format('H:i')
                ];
            }
            
            // Get follow-up appointments due today
            $followUpQuery = "SELECT a.*, p.name as pet_name, p.species,
                             CONCAT(o.first_name, ' ', o.last_name) as owner_name
                             FROM appointments a
                             JOIN patients p ON a.patient_id = p.patient_id
                             JOIN owners o ON p.owner_id = o.owner_id
                             WHERE DATE(a.start_time) = CURDATE()
                             AND a.care_status = 'follow-up'
                             AND a.status = 'upcoming'
                             ORDER BY a.start_time ASC";
            
            $stmt = $this->Conn->prepare($followUpQuery);
            $stmt->execute();
            $followUps = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($followUps as $appointment) {
                $notifications[] = [
                    'type' => 'follow_up',
                    'priority' => 'medium',
                    'title' => 'Follow-up Appointment Today',
                    'message' => $appointment['pet_name'] . ' (' . $appointment['owner_name'] . ') - ' . date('H:i', strtotime($appointment['start_time'])),
                    'appointment_id' => $appointment['appointment_id'],
                    'timestamp' => $appointment['start_time']
                ];
            }
            
            // Get emergency appointments
            $emergencyQuery = "SELECT a.*, p.name as pet_name, p.species,
                              CONCAT(o.first_name, ' ', o.last_name) as owner_name
                              FROM appointments a
                              JOIN patients p ON a.patient_id = p.patient_id
                              JOIN owners o ON p.owner_id = o.owner_id
                              WHERE a.care_status = 'urgent'
                              AND a.status = 'upcoming'
                              AND DATE(a.start_time) = CURDATE()
                              ORDER BY a.start_time ASC";
            
            $stmt = $this->Conn->prepare($emergencyQuery);
            $stmt->execute();
            $emergencies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($emergencies as $appointment) {
                $notifications[] = [
                    'type' => 'emergency',
                    'priority' => 'urgent',
                    'title' => 'Emergency Appointment',
                    'message' => $appointment['pet_name'] . ' (' . $appointment['owner_name'] . ') - URGENT',
                    'appointment_id' => $appointment['appointment_id'],
                    'timestamp' => $appointment['start_time']
                ];
            }
            
            // Sort notifications by priority and timestamp
            usort($notifications, function($a, $b) {
                $priorityOrder = ['urgent' => 1, 'high' => 2, 'medium' => 3, 'low' => 4];
                $aPriority = $priorityOrder[$a['priority']] ?? 5;
                $bPriority = $priorityOrder[$b['priority']] ?? 5;
                
                if ($aPriority === $bPriority) {
                    return strtotime($a['timestamp']) - strtotime($b['timestamp']);
                }
                return $aPriority - $bPriority;
            });
            
            return array_slice($notifications, 0, 15); // Return max 15 notifications
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getDashboardNotifications: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get today's schedule summary
     */
    public function getTodayScheduleSummary() {
        try {
            $query = "SELECT 
                        COUNT(*) as total_appointments,
                        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                        COUNT(CASE WHEN status = 'upcoming' THEN 1 END) as upcoming,
                        COUNT(CASE WHEN status = 'in_progress' THEN 1 END) as in_progress,
                        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled,
                        COUNT(CASE WHEN care_status = 'urgent' THEN 1 END) as urgent,
                        COUNT(CASE WHEN care_status = 'follow-up' THEN 1 END) as follow_ups,
                        MIN(start_time) as first_appointment,
                        MAX(end_time) as last_appointment
                      FROM appointments 
                      WHERE DATE(start_time) = CURDATE()";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getTodayScheduleSummary: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get recent activity feed
     */
    public function getRecentActivity($limit = 10) {
        try {
            $query = "SELECT a.*, p.name as pet_name, p.species,
                      CONCAT(o.first_name, ' ', o.last_name) as owner_name,
                      CONCAT(s.first_name, ' ', s.last_name) as staff_name
                      FROM appointments a
                      JOIN patients p ON a.patient_id = p.patient_id
                      JOIN owners o ON p.owner_id = o.owner_id
                      LEFT JOIN staff s ON a.staff_id = s.staff_id
                      WHERE a.status IN ('completed', 'cancelled', 'in_progress')
                      OR (a.status = 'upcoming' AND a.start_time <= DATE_ADD(NOW(), INTERVAL 1 HOUR))
                      ORDER BY 
                        CASE 
                          WHEN a.status = 'completed' THEN a.end_time
                          WHEN a.status = 'cancelled' THEN a.start_time
                          ELSE a.start_time
                        END DESC
                      LIMIT :limit";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getRecentActivity: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get mini calendar data for dashboard widget
     */
    public function getMiniCalendarData($month = null, $year = null) {
        if (!$month) $month = date('m');
        if (!$year) $year = date('Y');
        
        try {
            $query = "SELECT DATE(start_time) as appointment_date,
                             COUNT(*) as appointment_count,
                             COUNT(CASE WHEN care_status = 'urgent' THEN 1 END) as urgent_count
                      FROM appointments 
                      WHERE MONTH(start_time) = :month 
                      AND YEAR(start_time) = :year
                      AND status != 'cancelled'
                      GROUP BY DATE(start_time)";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            
            $calendarData = [];
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($results as $row) {
                $calendarData[$row['appointment_date']] = [
                    'total' => $row['appointment_count'],
                    'urgent' => $row['urgent_count']
                ];
            }
            
            return $calendarData;
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getMiniCalendarData: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get performance metrics for dashboard
     */
    public function getPerformanceMetrics() {
        try {
            // Get metrics for the last 7 days
            $query = "SELECT 
                        DATE(start_time) as date,
                        COUNT(*) as total_appointments,
                        COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed,
                        COUNT(CASE WHEN status = 'cancelled' THEN 1 END) as cancelled,
                        AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as avg_duration,
                        COUNT(CASE WHEN care_status = 'urgent' THEN 1 END) as urgent_cases
                      FROM appointments 
                      WHERE start_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                      GROUP BY DATE(start_time)
                      ORDER BY date ASC";
            
            $stmt = $this->Conn->prepare($query);
            $stmt->execute();
            
            $metrics = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate summary statistics
            $totalAppointments = 0;
            $totalCompleted = 0;
            $totalCancelled = 0;
            $totalUrgent = 0;
            
            foreach ($metrics as $day) {
                $totalAppointments += $day['total_appointments'];
                $totalCompleted += $day['completed'];
                $totalCancelled += $day['cancelled'];
                $totalUrgent += $day['urgent_cases'];
            }
            
            $completionRate = $totalAppointments > 0 ? ($totalCompleted / $totalAppointments) * 100 : 0;
            $cancellationRate = $totalAppointments > 0 ? ($totalCancelled / $totalAppointments) * 100 : 0;
            $urgentRate = $totalAppointments > 0 ? ($totalUrgent / $totalAppointments) * 100 : 0;
            
            return [
                'daily_metrics' => $metrics,
                'summary' => [
                    'total_appointments' => $totalAppointments,
                    'completion_rate' => round($completionRate, 1),
                    'cancellation_rate' => round($cancellationRate, 1),
                    'urgent_rate' => round($urgentRate, 1),
                    'avg_daily_appointments' => round($totalAppointments / 7, 1)
                ]
            ];
            
        } catch (PDOException $e) {
            $this->lastError = [
                'code' => $e->getCode(),
                'message' => $e->getMessage()
            ];
            error_log("PDO Exception in getPerformanceMetrics: " . $e->getMessage());
            return false;
        }
    }
}