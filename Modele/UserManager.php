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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query = $this->conn->prepare("INSERT INTO users ( lastName, firstName,email, passwordUser , phoneNumber ) VALUES ( ?, ?, ?, ?, ?)");

        if ($query === false) {
            die("Erreur lors de la préparation de la requête post: " . htmlspecialchars($this->conn->error));
        }

        $query->bind_param("sssss", $last_name, $first_name, $email, $hashedPassword, $phone_number);
        $query->execute();
        $query->close();
    }
    public function userIsInDb($email, $password)
    {

        $stmt = $this->conn->prepare("SELECT id, email, firstName, lastName, passwordUser, isAdmin FROM users WHERE email = ?");
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

            if (password_verify($password, $userData['passwordUser'])) {
                // Le mot de passe est correct, stocker les données de l'utilisateur dans la session
                $_SESSION['userSession'] = [
                    'id' => $userData['id'],
                    'email' => $userData['email'],
                    'firstName' => $userData['firstName'],
                    'lastName' => $userData['lastName'],
                    'isAdmin' => $userData['isAdmin']
                ];

                if ($userData['isAdmin'] == 1) {
                    // L'utilisateur est un admin
                    echo "Utilisateur administrateur connecté avec succès";
                } else {
                    // L'utilisateur est un utilisateur normal
                    echo "Utilisateur connecté avec succès";
                }
            } else {
                // Le mot de passe est incorrect
                echo "Nom d'utilisateur ou mot de passe incorrect";
            }
        } else {
            // Aucun utilisateur correspondant trouvé
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
    public function getUserFavoris($userId) {
        $sql = "SELECT * FROM favoris WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public function closeConnection()
    {
        $this->conn->close();
    }
    //     public function isAdmin($email)
    //     {
    //         $sql = "SELECT isAdmin FROM users WHERE email = ?";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bind_param("s", $email);
    //         $stmt->execute();
    //         $result = $stmt->get_result();
    //         if ($result->num_rows > 0) {
    //             $user = $result->fetch_assoc();
    //             return $user['isAdmin'];
    //         } else {
    //             return false;
    //         }
    //     }
}
