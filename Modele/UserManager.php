<?php

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

    public function insererUtilisateur($username, $email, $phone_number, $last_name, $first_name, $password)
    {
        $query = $this->conn->prepare("INSERT INTO users (username, email, phone_number, lastname, firstname, password) VALUES (?, ?, ?, ?, ?, ?)");

        if ($query === false) {
            die("Erreur lors de la préparation de la requête: " . htmlspecialchars($this->conn->error));
        }

        $query->bind_param("ssssss", $username, $email, $phone_number, $last_name, $first_name, $password);
        $query->execute();
        $query->close();

        // Optionnel: Vous pouvez choisir de ne pas fermer la connexion ici, 
        // surtout si vous prévoyez d'effectuer plusieurs opérations avec la même connexion.
    }
    public function isUserLoggedIn($email, $password)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ? AND password = ?");
            if ($stmt === false) {
                die("Erreur lors de la préparation de la requête: " . htmlspecialchars($this->conn->error));
            }

            $stmt->bind_param("ss", $email, $password);
            $stmt->execute();

            // Récupération du résultat
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                // Utilisateur trouvé
                
                // // setcookie("password", $password, time() + 3600);
                //  isset($_COOKIE['userSession']); // cookie existe ?

                // // $_COOKIE['userSession']; //get la valeur d'un cookie
                 //setcookie("username", "test", time() + 3600);
                echo "Utilisateur connecté avec succès";
            } else {
                // Utilisateur non trouvé
                echo "Nom d'utilisateur ou mot de passe incorrect";
            }

            $stmt->close();
        }
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}
