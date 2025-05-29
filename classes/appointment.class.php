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
}