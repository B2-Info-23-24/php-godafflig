<?php


require 'vendor/autoload.php';
class VehiculeManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;


        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }

    public function getVehicles($limit = 20)
    {
        $vehicules = [];

        $sql = "SELECT v.*, c.text AS colorName, n.nb_of_seat_int AS numberOfSeats, b.text AS brandName
        FROM vehicules v 
        JOIN color c ON v.color_id = c.id 
        JOIN nbOfseat n ON v.nbOfseat_id = n.id 
        JOIN brand b ON v.brand_id = b.id 
        LIMIT ?";

        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('i', $limit);
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
                $vehicules[] = $row;
            }

            // Close the statement
            $stmt->close();
        }

        return $vehicules;
    }
    public function vehiculesId($id)
    {
        $sql = "SELECT * FROM vehicules WHERE id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            return $data; // Return the fetched data
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null; // Return null in case of an error
        }
    }
}
