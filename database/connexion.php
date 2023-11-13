<?php

$servername = "mysql"; // Use the service name defined in docker-compose.yml
$username = "user";    // Your MySQL username
$password = "password"; // Your MySQL password
$database = "database"; // Your database name

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully yes";