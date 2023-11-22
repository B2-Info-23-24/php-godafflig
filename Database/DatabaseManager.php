<?php
namespace App\Database;
require 'vendor/autoload.php';
class DatabaseManager

{
   public $conn;

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

    public function request($sql, $params = null)
    {
        $stmt = $this->conn->prepare($sql);

        if ($params) {
            $stmt->bind_param(...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result === false) {
            $stmt->close();
            return false;
        }

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();
        return $data;
    }

    public function close()
    {
        $this->conn->close();
    }
}
