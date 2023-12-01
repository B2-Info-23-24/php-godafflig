<?php

require 'vendor/autoload.php';

class Manager
{
    private $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;

        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
    }

    public function deleteRecord($table, $identifier, $value)
    {
        $sql = "DELETE FROM {$table} WHERE {$identifier} = ?";
        $query = $this->conn->prepare($sql);
        if ($query === false) {
            throw new Exception("Failed to prepare delete query: " . $this->conn->error);
        }
        $query->bind_param('s', $value);
        if (!$query->execute()) {
            throw new Exception("Delete execution failed: " . $query->error);
        }
        $query->close();
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
    public function displaycolorForm()
    {
        $color = [];
        // Requête pour obtenir toutes les marques
        $sql = "SELECT id, text FROM color";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $color[] = $row;
            }

            return $color; // Return the fetched data
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null; // Return null in case of an error
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
            return $brand;
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null;
        }
    }
    public function displayusersForm()
    {
        $users = [];
        // Requête pour obtenir toutes les marques
        $sql = "SELECT * FROM users";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null;
        }
    }
    public function displaycarForm()
    {
        $users = [];
        // Requête pour obtenir toutes les marques
        $sql = "SELECT * FROM vehicules";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
            return $users;
        } else {
            echo ("Statement failed: " . $this->conn->error . "<br>");
            return null;
        }
    }
    public function createRecord($tableName, $column, $value)
    {
        $sql = "INSERT INTO {$tableName} ({$column}) VALUES (?)";
        $query = $this->conn->prepare($sql);
        if ($query === false) {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
        $query->bind_param('s', $value);
        if (!$query->execute()) {
            throw new Exception("Execution failed: " . $query->error);
        }
        $affectedRows = $query->affected_rows;
        $query->close();
        return $affectedRows > 0;
    }
    public function updateRecord($tableName, $columnNameToUpdate, $newValue, $identifierColumn, $identifierValue)
    {
        $this->conn->begin_transaction();
        try {
            $sql = "UPDATE {$tableName} SET {$columnNameToUpdate} = ? WHERE {$identifierColumn} = ?";
            $query = $this->conn->prepare($sql);
            if ($query === false) {
                throw new Exception("Failed to prepare update query: " . $this->conn->error);
            }

            $query->bind_param('ss', $newValue, $identifierValue);

            if (!$query->execute()) {
                throw new Exception("Update execution failed: " . $query->error);
            }
            $affectedRows = $query->affected_rows;
            $query->close();

            // Valider la transaction
            $this->conn->commit();

            // Retourner vrai si un enregistrement a été mis à jour, faux autrement
            return $affectedRows > 0;
        } catch (Exception $e) {
            // Annuler la transaction en cas d'erreur
            $this->conn->rollback();
            throw $e; // Il est préférable de relancer l'exception pour permettre une gestion d'erreur plus flexible en amont
        }
    }
    public function updateUser($userId, $userData)
    {
        $updated = false;
        $this->conn->begin_transaction();
        try {
            foreach ($userData as $column => $value) {
                $updated |= $this->updateRecord('users', $column, $value, 'id', $userId);
            }
            $this->conn->commit();
            return $updated;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    public function updatevehicule($vehiculeId, $carData)
    {
        $updated = false;
        $this->conn->begin_transaction();
        try {
            foreach ($carData as $column => $value) {
                $updated |= $this->updateRecord('vehicules', $column, $value, 'id', $vehiculeId);
            }
            $this->conn->commit();
            return $updated;
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }
    public function getSeats() {
        $seats = [];
        $sql = "SELECT id, nb_of_seat_int FROM nbOfseat"; // Adaptez avec le nom réel de votre colonne et table
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $seats[] = $row;
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
        return $seats;
    }

    public function getColors() {
        $colors = [];
        $sql = "SELECT id, text FROM color"; 
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $colors[] = $row;
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
        return $colors;
    }

    public function getBrands() {
        $brands = [];
        $sql = "SELECT id, text FROM brand"; // Adaptez avec le nom réel de votre colonne et table
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
        return $brands;
    }
    public function getReviews() {
        $brands = [];
        $sql = "SELECT id, content FROM review"; // Adaptez avec le nom réel de votre colonne et table
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $brands[] = $row;
            }
            $stmt->close();
        } else {
            throw new Exception("Failed to prepare query: " . $this->conn->error);
        }
        return $brands;
    }
    
    public function insererUtilisateur($last_name, $first_name, $email, $password, $phone_number)
    {
        $query = $this->conn->prepare("INSERT INTO users ( lastName, firstName,email, passwordUser , phoneNumber ) VALUES ( ?, ?, ?, ?, ?)");

        if ($query === false) {
            die("Erreur lors de la préparation de la requête post: " . htmlspecialchars($this->conn->error));
        }

        $query->bind_param("sssss", $last_name, $first_name, $email, $password, $phone_number);
        $query->execute();
        $query->close();
    }
    public function createvehicule($nbOfseat, $review_id, $color_id, $priceDay, $brand_id)
{
    try {
        $query = $this->conn->prepare("INSERT INTO vehicules (nbOfseat_id, review_id, color_id, priceDay, brand_id) VALUES (?, ?, ?, ?, ?)");

        if ($query === false) {
            throw new \Exception("Erreur lors de la préparation de la requête : " . htmlspecialchars($this->conn->error));
        }

        if (!$query->bind_param("iiiii", $nbOfseat, $review_id, $color_id, $priceDay, $brand_id)) {
            throw new \Exception("Erreur lors du bind_param : " . htmlspecialchars($query->error));
        }

        if (!$query->execute()) {
            throw new \Exception("Erreur lors de l'exécution de la requête : " . htmlspecialchars($query->error));
        }

        $query->close();
    } catch (\Exception $e) {
        // Ici, vous pouvez gérer l'erreur ou la retransmettre
        throw $e;
    }
}
public function recordExists($tableName, $id) {
    // Préparation de la requête SQL. Utilisation de sprintf pour insérer le nom de la table
    // Attention : cela peut être vulnérable si le nom de la table n'est pas contrôlé
    $sql = sprintf("SELECT COUNT(*) FROM %s WHERE id = ?", $tableName);

    // Préparation de la requête
    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        throw new \Exception("Erreur de préparation de la requête : " . $this->conn->error);
    }

    // Liaison du paramètre et exécution de la requête
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Récupération et retour du résultat
    $result = $stmt->get_result()->fetch_row();
    $stmt->close();

    return $result[0] > 0;
}


}
