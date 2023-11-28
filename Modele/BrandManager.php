<?php

require 'vendor/autoload.php';

class BrandManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;

        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }

    public function displayBrandForm()
    {
        $brand = [];
        // Requête pour obtenir toutes les marques
        $sql = "SELECT id, text FROM brand";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $brand[] = $row;
            }

            return $brand; // Return the fetched data
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null; // Return null in case of an error
        }
    }
    public function deleteBrandByName($brandName)
    {
        $sql = "DELETE FROM brand WHERE text = ?";
        $query = $this->conn->prepare($sql);
        if ($query === false) {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
    
        $query->bind_param('s', $brandName);
        if (!$query->execute()) {
            throw new Exception("Execution failed: " . $query->error);
        }
    
        $affectedRows = $query->affected_rows;
        $query->close();
    
        if ($affectedRows > 0) {
            echo "Success, $brandName has been deleted.";
        } else {
            echo "No records deleted. Brand name $brandName may not exist.";
        }
    }
    
}
