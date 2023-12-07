<?php
require 'vendor/autoload.php';

class ReservationManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;


        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }
    public function createReservation($userId, $vehicleId, $startDate, $endDate, $totalprice)
    {

        if ($this->isVehicleAvailable($vehicleId, $startDate, $endDate)) {
            $sql = "INSERT INTO reservations (user_id, vehicle_id, start_date, end_date, reservation_price) VALUES (?, ?, ?, ? ,?)";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param('iissi', $userId, $vehicleId, $startDate, $endDate, $totalprice);
                $result = $stmt->execute();
                $stmt->close();
                return $result;
            }
        }

        return false;
    }
    public function isVehicleAvailable($vehicleId, $startDate, $endDate)
    {
        // SQL query to check for any reservations that overlap with the given date range
        $sql = "SELECT COUNT(*) FROM reservations 
            WHERE vehicle_id = ? AND 
            ((start_date BETWEEN ? AND ?) OR (end_date BETWEEN ? AND ?))";

        if ($stmt = $this->conn->prepare($sql)) {
            // Bind parameters: vehicle ID, start date, end date, start date, end date
            $stmt->bind_param('issss', $vehicleId, $startDate, $endDate, $startDate, $endDate);
            $stmt->execute();

            // Bind the result of COUNT(*) to a variable
            $count = 0;
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            // If count is 0, the vehicle is available (no overlapping reservations)
            return $count == 0;
        } else {
            return false;
        }
    }
    public function getUserReservations()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $reservations = [];

        if (isset($_SESSION['userSession']['id'])) {
            $userId = $_SESSION['userSession']['id'];

            $sql = "SELECT r.*,  v.image, 
        v.priceDay, b.text AS brandName, c.text AS colorName, n.nb_of_seat_int AS numberOfSeats
        FROM reservations r
        JOIN vehicules v ON r.vehicle_id = v.id
        JOIN brand b ON v.brand_id = b.id
        JOIN color c ON v.color_id = c.id
        JOIN nbOfseat n ON v.nbOfseat_id = n.id
        WHERE r.user_id = ?";

            if ($stmt = $this->conn->prepare($sql)) {
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $reservations[] = $row;
                }

                $stmt->close();
            }
        } else {
            // echo "Utilisateur non identifié.";
        }

        return $reservations;
    }
}
    



    // Other methods such as checkAvailability...
