<?php


require 'vendor/autoload.php';
class VehiculeManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getVehicles($limit = 20)
    {
        $vehicules = [];

        $sql = "SELECT v.*, c.text AS colorName, n.nb_of_seat_int AS numberOfSeats 
        FROM vehicules v 
        JOIN color c ON v.color_id = c.id 
        JOIN nbOfseat n ON v.nbOfseat_id = n.id 
        LIMIT ?";

        if ($stmt = $this->conn->prepare($sql)) {
            // Bind the limit parameter
            $stmt->bind_param('i', $limit);

            // Execute the query
            $stmt->execute();

            // Get the result of the query
            $result = $stmt->get_result();

            // Fetch data as an associative array
            while ($row = $result->fetch_assoc()) {
                $vehicules[] = $row;
            }

            // Close the statement
            $stmt->close();
        }

        return $vehicules;
    }
}
