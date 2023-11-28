<?php
session_start();
require 'vendor/autoload.php';
class UserManager
{

    public $conn;

    public function __construct($databaseConnection)
    {
        $this->conn = $databaseConnection;


        if (!$this->conn instanceof \mysqli) {
            die("Erreur de connexion à la base de données.");
        }
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
    public function userIsInDb($email, $password)
    {

        $stmt = $this->conn->prepare("SELECT id, email, firstName, lastName FROM users WHERE email = ? ");
        if ($stmt === false) {
            die("Erreur lors de la préparation de la requête: " . htmlspecialchars($this->conn->error));
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Récupération du résultat
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // Utilisateur trouvé
            $userData = mysqli_fetch_assoc($result);

            // Stocker les données de l'utilisateur dans la session
            $_SESSION['userSession'] = [
                'id' => $userData['id'],
                'email' => $userData['email'],
                'firstName' => $userData['firstName'],
                'lastName' => $userData['lastName']
            ];

            echo "Utilisateur connecté avec succès";
        } else {
            echo "Nom d'utilisateur ou mot de passe incorrect";
        }
        $stmt->close();
    }
    public function getUserSession()
    {
        if (isset($_SESSION['userSession'])) {
            return $_SESSION['userSession'];
        }
        return null;
    }


    public function closeConnection()
    {
        $this->conn->close();
    }
}
