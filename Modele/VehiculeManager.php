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
        $sql = "SELECT v.id, v.review, v.nb_of_star, v.priceDay, v.image, 
            n.nb_of_seat_int, c.text as colorName, b.text as brandName 
            FROM vehicules v
            JOIN nbOfseat n ON v.nbOfseat_id = n.id
            JOIN color c ON v.color_id = c.id
            JOIN brand b ON v.brand_id = b.id
            WHERE v.id = ?";
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
    public function getVehiclePricePerDay($id)
    {
        $sql = "SELECT priceDay FROM vehicules WHERE id = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                return $data['priceDay']; // Return just the priceDay value
            } else {
                echo "Aucun véhicule trouvé avec cet ID.";
                return null; // Return null if no data is found
            }
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null; // Return null in case of an error
        }
    }
}
