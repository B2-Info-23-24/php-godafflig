<?php

$servername = "mysql"; 
$username = "user";    
$password = "password"; 
$database = "database"; 
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully yes";

