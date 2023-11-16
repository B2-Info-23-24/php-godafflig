<?php
function insererUtilisateur($username, $email, $phone_number, $last_name, $first_name, $password)
{
    global $conn;
    // Vérifiez si la connexion est bien un objet mysqli
    if (!$conn instanceof mysqli) {
        die("Erreur de connexion à la base de données.");
    }

    $query = $conn->prepare("INSERT INTO users (username, email, phone_number, lastname, firstname, password) VALUES (?, ?, ?, ?, ?, ?)");
    
    if ($query === false) {
        die("Erreur lors de la préparation de la requête: " . htmlspecialchars($conn->error));
    }
    // Ici, nous supposons que tous les paramètres sont des strings, donc 's'
    $query->bind_param("ssssss", $username, $email, $phone_number, $last_name, $first_name, $password);
    $query->execute();
    $query->close();
    $conn->close();
}
