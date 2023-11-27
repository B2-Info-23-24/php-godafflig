<?php
require 'vendor/autoload.php';
class ReservationManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }



    public function createReservation($userId, $vehicleId, $startDate, $endDate)
    {
        if ($this->isVehicleAvailable($vehicleId, $startDate, $endDate)) {
            $sql = "INSERT INTO reservations (user_id, vehicle_id, start_date, end_date) VALUES (?, ?, ?, ?)";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param('iiss', $userId, $vehicleId, $startDate, $endDate);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        }

        return false;
    }
    public function isVehicleAvailable($vehicleId, $startDate, $endDate)
    {
        $sql = "SELECT COUNT(*) FROM reservations 
                WHERE vehicle_id = ? AND 
                (start_date BETWEEN ? AND ? OR 
                end_date BETWEEN ? AND ? OR 
                ? BETWEEN start_date AND end_date OR 
                ? BETWEEN start_date AND end_date)";
    
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('isssss', $vehicleId, $startDate, $endDate, $startDate, $endDate, $startDate, $endDate);
            $stmt->execute();
            $stmt->store_result();
            $count = $stmt->num_rows;
            $stmt->close();
    
            return $count == 0;
        } else {
            return false;
        }
    }
    



    // Other methods such as checkAvailability...
}
