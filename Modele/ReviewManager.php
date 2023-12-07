<?php

require 'vendor/autoload.php';

class ReviewManager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;

        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }

    public function updateReview($vehiculeId, $review, $rating)
    {
        $stmt = $this->conn->prepare("UPDATE vehicules SET review = ?, nb_of_star = ? WHERE id = ?");
        $stmt->bind_param("sii", $review, $rating, $vehiculeId);
        $stmt->execute();
        // Vous pouvez gérer les erreurs ou la confirmation ici si nécessaire
    }
}
