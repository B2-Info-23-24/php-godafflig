<?php

namespace App\Model;

require 'vendor/autoload.php';

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

    public function createTable()
    {
        // Array of SQL statements
        $sqlStatements = [
            "CREATE TABLE IF NOT EXISTS `brand` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `text` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `color` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `text` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `nbOfseat` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nb_of_seat_int` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `review` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `content` text DEFAULT NULL,
                `nb_of_star` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `vehicules` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nbOfseat_id` int(11) DEFAULT NULL,
                `review_id` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`),
                FOREIGN KEY (`nbOfseat_id`) REFERENCES `nbOfseat`(`id`) ON DELETE SET NULL,
                FOREIGN KEY (`review_id`) REFERENCES `review`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `vehicle_color` (
                `vehicle_id` int(11) NOT NULL,
                `color_id` int(11) NOT NULL,
                PRIMARY KEY (`vehicle_id`, `color_id`),
                FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`color_id`) REFERENCES `color`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

            "CREATE TABLE IF NOT EXISTS `vehicle_brand` (
                `vehicle_id` int(11) NOT NULL,
                `brand_id` int(11) NOT NULL,
                PRIMARY KEY (`vehicle_id`, `brand_id`),
                FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`brand_id`) REFERENCES `brand`(`id`) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;"
        ];

        // Execute each SQL statement
        foreach ($sqlStatements as $sql) {
            if (!$this->conn->query($sql)) {
                die("Error creating table: " . $this->conn->error);
            }
        }
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}
