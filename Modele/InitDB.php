<?php

namespace App\Model;

use Exception;

// require 'vendor/autoload.php';
require_once __DIR__ . '/../vendor/autoload.php';



class InitDb
{
    private $conn;

    public function __construct()
    {
        $servername = "mysql";
        $username = "user";
        $password = "password";
        $database = "database";
        $this->conn = new \mysqli($servername, $username, $password, $database);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }






    // --------------creation de la BDD--------------------------------------
    public function createTable()
    {
        $sqlStatements = [

            "CREATE TABLE IF NOT EXISTS `brand` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `text` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `color` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `text` varchar(255) DEFAULT NULL UNIQUE,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `nbOfseat` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nb_of_seat_int` int(11) DEFAULT NULL UNIQUE,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `favoris` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `user_id` int(10) unsigned NOT NULL, -- L'ID de l'utilisateur associé à la réservation
                `vehicle_id` int(11) NOT NULL, -- L'ID du véhicule associé à la réservation
                 PRIMARY KEY (`id`),
                FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
                FOREIGN KEY (`vehicle_id`) REFERENCES `vehicules`(`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS vehicules (
            `id` int NOT NULL AUTO_INCREMENT,
            `nbOfseat_id` int DEFAULT NULL,
            `review` varchar(255) DEFAULT NULL,
            `nb_of_star` int(11) DEFAULT NULL,
            `color_id` int DEFAULT NULL ,
            `priceDay` int DEFAULT NULL ,
            `image` varchar(255) DEFAULT NULL,
            `brand_id` int DEFAULT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (nbOfseat_id) REFERENCES nbOfseat(id) ON DELETE SET NULL,
            FOREIGN KEY (color_id) REFERENCES color(id) ON DELETE SET NULL,
         FOREIGN KEY (brand_id) REFERENCES brand(id) ON DELETE SET NULL
)        ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",


            "CREATE TABLE IF NOT EXISTS `users` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `lastName` varchar(255) DEFAULT NULL,
              `firstName` varchar(255) DEFAULT NULL,
              `email` varchar(255) DEFAULT NULL,
              `passwordUser` varchar(255) DEFAULT NULL,
              `phoneNumber` int(11) DEFAULT NULL,
              `isAdmin` tinyint(1) DEFAULT '0',
              PRIMARY KEY (`id`),
              CONSTRAINT unique_email_phoneNumber UNIQUE (`email`, `phoneNumber`)
            ) ENGINE = InnoDB AUTO_INCREMENT = 14 DEFAULT CHARSET = latin1",

            "CREATE TABLE IF NOT EXISTS `reservations` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(10) unsigned NOT NULL, -- L'ID de l'utilisateur associé à la réservation
            `vehicle_id` int(11) NOT NULL, -- L'ID du véhicule associé à la réservation
            `start_date` DATE NOT NULL, -- Date de début de la réservation
            `end_date` DATE NOT NULL, -- Date de fin de la réservation
            `reservation_price` int(11) NOT NULL, -- Prix de la reservation
            PRIMARY KEY (`id`),
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
            FOREIGN KEY (`vehicle_id`) REFERENCES `vehicules`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];
        foreach ($sqlStatements as $sql) {
            try {

                $this->conn->query($sql);
            } catch (Exception $e) {
                echo "Erreur lors de l'exécution de la requête SQL : " . $e->getMessage();
            }
        }
    }
    public function closeConnection()
    {
        $this->conn->close();
    }
    //------------------------------------------------------------------------------------------------------------------------------------------------------------




    //----------------------ajoute un fake user ---------------------------------------------------------------------------------------------------------
    public function addFakeUsers()
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $lastName = $this->conn->real_escape_string($faker->lastName);
            $firstName = $this->conn->real_escape_string($faker->firstName);
            $email = $this->conn->real_escape_string($faker->email);
            // Générer un mot de passe factice - à remplacer par un mot de passe hashé pour une utilisation réelle
            $password = $this->conn->real_escape_string($faker->password);
            $phoneNumber = $faker->randomNumber(9);
            $isAdmin = 0; // 0 pour false, 1 pour true

            $sql = "INSERT INTO users (lastName, firstName, email, passwordUser, phoneNumber, isAdmin) VALUES ('$lastName', '$firstName', '$email', '$password', $phoneNumber, $isAdmin)";

            try {
                $this->conn->query($sql);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    //-------------------------------------------------------------------------------------------------------------





    //-----------------------------ajoute un fake vehicules--------------------------------------------------------
    public function addFakeVehicules()
    {
        $faker = (new \Faker\Factory())::create('fr_FR');

        $color_ids = $this->getIds('color');
        $brand_ids = $this->getIds('brand');
        $seat_ids = $this->getIds('nbOfseat');

        try {
            $sql = "INSERT INTO vehicules (nbOfseat_id, review, color_id, priceDay, image, brand_id ,nb_of_star) VALUES (?, ?, ?, ?, ?, ?,?)";
            $stmt = $this->conn->prepare($sql);

            if ($stmt === false) {
                throw new Exception("Unable to prepare statement: " . $this->conn->error);
            }
            for ($i = 0; $i < 10; $i++) {
                $nbOfseat_id = $faker->randomElement($seat_ids);
                $review = $faker->sentence();
                $color_id = $faker->randomElement($color_ids);
                $brand_id = $faker->randomElement($brand_ids);
                $priceDay = $faker->numberBetween(50, 500);
                $imagePath = $this->getRandomImageFromFolder('/Public/img'); // Assurez-vous que cette méthode retourne le chemin correct
                $nbofstar = $faker->numberBetween(0, 5);
                $stmt->bind_param("isiisii", $nbOfseat_id, $review, $color_id, $priceDay, $imagePath, $brand_id, $nbofstar);
                $stmt->execute();
            }
        } catch (\mysqli_sql_exception $e) {
            echo "MySQLi Error Code: " . $e->getCode() . "\n";
            echo "MySQLi Error Message: " . $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "General Error: " . $e->getMessage() . "\n";
        }
    }
    //-------------------------------------------------------------------------------------------------------------





    //-------------------------------------------------------------------------------------------------------------
    public function getRandomImageFromFolder($folderPath)
    {
        // Le chemin relatif correct pour remonter au dossier parent de 'src'
        $fullFolderPath = __DIR__ . '/../' . $folderPath;
        if (!file_exists($fullFolderPath)) {
            throw new Exception("Le répertoire $fullFolderPath n'existe pas.");
        }
        $files = scandir($fullFolderPath);
        $images = array_filter($files, function ($file) use ($fullFolderPath) {
            return is_file($fullFolderPath . '/' . $file) && in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['png']);
        });

        if (count($images) > 0) {
            $randomImage = $images[array_rand($images)];
            return $folderPath . '/' . $randomImage;
        } else {
            return 'path/to/default/image.png';
        }
    }
    //-------------------------------------------------------------------------------------------------------------







    //-----------------------------------function get id of any table --------------------------------------------
    private function getIds($tableName)
    {
        $sql = "SELECT id FROM $tableName";
        $result = $this->conn->query($sql);
        $ids = [];
        while ($row = $result->fetch_assoc()) {
            $ids[] = $row['id'];
        }
        return $ids;
    }
    //-------------------------------------------------------------------------------------------------------------











    //-----------------------------------function add colors in table --------------------------------------------
    public function addColors()
    {
        $colors = ['Rouge', 'Blanc', 'Gris', 'Noir', 'Noir Mat', 'Bleu Turquoise', 'Bleu Marine'];
        foreach ($colors as $color) {
            $sql = "INSERT IGNORE INTO color (text) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception($this->conn->error);
            }
            $stmt->bind_param("s", $color);
            $stmt->execute();
        }
    }
    //-----------------------------------------------------------------------------------------------------------






    //---------------------------add randomNbOfSeat-----------------------------------------------------------------
    public function addNbOfSeats()
    {
        $seatNumbers = [2, 3, 4, 5, 7, 9];
        foreach ($seatNumbers as $number) {
            $sql = "INSERT IGNORE INTO nbOfseat (nb_of_seat_int) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception($this->conn->error);
            }
            $stmt->bind_param("i", $number);
            $stmt->execute();
        }
    }
    //---------------------------------------------------------------------------------------------------------


    //---------------------------add randomBrand-----------------------------------------------------------------

    public function addBrands()
    {
        echo   "\n " . ' database is init  ' . "\n";
        echo "if you are an admin you can register in your account with  " . "\n" . "password : admin " . "\n " . "mail : " . "admin.fr" . "\n" . "enjoy ca_roule_mapoule" . "\n";
        $brands = ['Nissan', 'Renault', 'Volvo', 'Tesla', 'Fiat', 'Peugeot', 'Volkswagen', 'Ferrari', 'Hyundai', 'Kia'];
        foreach ($brands as $brand) {
            $sql = "INSERT IGNORE INTO brand (text) VALUES (?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $brand);
            $stmt->execute();
        }
    }

    //---------------------------------------------------------------------------------------------------------




    //------------------------------------------Méthode pour ajouter un utilisateur administrateur---------------------------------------------------------------
    public function addAdminUser($email, $password, $lastname, $firstname)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe
        $isAdmin = 1; // Valeur pour un administrateur

        $sql = "INSERT INTO users (email, passwordUser, isAdmin , lastName, firstName) VALUES (?, ?, ?, ?,?)";

        try {
            $stmt = $this->conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Unable to prepare statement: " . $this->conn->error);
            }

            $stmt->bind_param("ssiss", $email, $hashedPassword, $isAdmin, $lastname, $firstname);
            $stmt->execute();
        } catch (Exception $e) {
            echo "Erreur lors de l'ajout de l'administrateur : " . $e->getMessage();
        }
    }
    //---------------------------------------------------------------------------------------------------------




    //----------------------------------function initializeDatabaseWithData-------------------------------------------------------------
    public function initializeDatabaseWithData()
    {
        $this->createTable();
        $this->addColors();
        $this->addBrands();
        $this->addNbOfSeats();
        $this->addFakeVehicules();
        $this->addFakeUsers();
        $this->addAdminUser("admin@fr", "admin", "admin", "garage"); // Ajoutez ici l'utilisateur administrateur
    }

    //---------------------------------------------------------------------------------------------------------
}
$initDb = new InitDb();
$initDb->initializeDatabaseWithData();
$initDb->createTable();
$initDb->closeConnection();
