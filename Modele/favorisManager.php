<?php

require 'vendor/autoload.php';

class favorisManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;

        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }
    public function ajouterAuxFavoris($userId, $vehiculeId)
    {
        $sqlCheck = "SELECT id FROM favoris WHERE user_id = ? AND vehicle_id = ?";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        if (!$stmtCheck) {
            return false;
        }
        $stmtCheck->bind_param("ii", $userId, $vehiculeId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        if ($resultCheck->num_rows > 0) {
            return false;
        }

        $sql = "INSERT INTO favoris (user_id, vehicle_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ii", $userId, $vehiculeId);
        if (!$stmt->execute()) {
            return false;
        }
        return true;
    }


    public function retirerDesFavoris($userId, $vehiculeId)
    {
        $sql = "DELETE FROM favoris WHERE user_id = ? AND vehicle_id = ?";
        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
        }
        $stmt->bind_param("ii", $userId, $vehiculeId);
        return $stmt->execute();
    }
    public function viewFavoris($userId)
    {
        $sql = "SELECT * FROM favoris WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
