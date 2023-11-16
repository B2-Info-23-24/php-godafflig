<?php
require_once 'database/databaseconnexion.php';
function datafetchervehicules()
{ // Requête SQL
    global $conn;
    $sql = "SELECT * FROM vehicules";
    $result = $conn->query($sql);
    // Vérification s'il y a des résultats
    if ($result->num_rows > 0) {
        // Parcours des lignes de résultat
        while ($row = $result->fetch_assoc()) {
            // Ajout de chaque ligne dans le tableau
            $donnees[] = $row;
        }
    } else {
        echo "0 results";
    }
    return ($donnees);
}
