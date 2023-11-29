<?php

require 'vendor/autoload.php';

class NbOfSeatManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;

        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }

    public function displaynbofsaetForm()
    {
        $nbofseat = [];
        // Requête pour obtenir toutes les marques
        $sql = "SELECT id, nb_of_seat_int FROM nbOfseat";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $nbofseat[] = $row;
            }

            return $nbofseat; // Return the fetched data
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null; // Return null in case of an error
        }
    }
    public function deleteBrandByName($nbofseat)
    {
        $sql = "DELETE FROM brand WHERE text = ?";
        $query = $this->conn->prepare($sql);
        if ($query === false) {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }

        $query->bind_param('s', $nbofseat);
        if (!$query->execute()) {
            throw new Exception("Execution failed: " . $query->error);
        }

        $affectedRows = $query->affected_rows;
        $query->close();

        if ($affectedRows > 0) {
            echo "Success, $nbofseat has been deleted.";
        } else {
            echo "No records deleted. Brand name $nbofseat may not exist.";
        }
    }
    public function createBrandByName($nbofseat)
    {
        $sql = "INSERT INTO brand (text) VALUE (?) ";
        $query = $this->conn->prepare($sql);
        if ($query === false) {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }

        $query->bind_param('s', $nbofseat);

        if (!$query->execute()) {
            throw new Exception("Execution failed: " . $query->error);
        }
        // $query->execute();
        $affectedRows = $query->affected_rows;
        $query->close();

        if ($affectedRows > 0) {
            echo "Success, $nbofseat has been create.";
        } else {
            echo "No records deleted. Brand name $nbofseat may not exist.";
        }
    }

    public function updateBrandByName($nbofseat,$nbofseatnew)
    {
        $sql = "UPDATE brand SET text = ? WHERE text = ? ;";
        $query = $this->conn->prepare($sql); 
        if ($query === false) {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }

        $query->bind_param('ss', $nbofseatnew ,$nbofseat );

        if (!$query->execute()) {
            throw new Exception("Execution failed: " . $query->error);
        }
        // $query->execute();
        $affectedRows = $query->affected_rows;
        $query->close();

        if ($affectedRows > 0) {
            echo "Success, $nbofseat has been create.";
        } else {
            echo "No records deleted. Brand name $nbofseat may not exist.";
        }
    }
}
