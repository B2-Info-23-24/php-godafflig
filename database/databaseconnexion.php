<?php

function request($sql)
{


    $servername = "mysql";
    $username = "user";
    $password = "password";
    $database = "database";
    $conn = new mysqli($servername, $username, $password, $database);


    $result = $conn->query($sql);
    // echo "Connected successfully yes";
    if ($result === false) {
        return false;
    }

    if ($result === true) {
        return true;
    }

    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;

    $conn->close();
}
