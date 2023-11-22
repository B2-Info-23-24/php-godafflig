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
        $sql = "CREATE TABLE IF NOT EXISTS `user` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `lastName` varchar(255) DEFAULT NULL,
                  `firstName` varchar(255) DEFAULT NULL,
                  `email` varchar(255) DEFAULT NULL,
                  `passwordUser` varchar(255) DEFAULT NULL,
                  `phoneNumber` int(11) DEFAULT NULL,
                  `isAdmin` tinyint(1) DEFAULT '0',
                  PRIMARY KEY (`id`),
                  CONSTRAINT unique_email_phoneNumber UNIQUE (`email`, `phoneNumber`)
                ) ENGINE = InnoDB AUTO_INCREMENT = 14 DEFAULT CHARSET = latin1";

        if (!$this->conn->query($sql)) {
            die("Error creating table: " . $this->conn->error);
        }
    }

    public function closeConnection()
    {
        $this->conn->close();
    }
}
