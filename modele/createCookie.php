<?php

// setCookie.php
$username = 'userTest'; // Valeur en dur pour le nom d'utilisateur
$password = 'passTest'; // Valeur en dur pour le mot de passe

// Ici, nous définissons simplement le cookie sans vérifier l'authenticité des données
setcookie('userlogin', $username, time()+3600, '/');
setcookie('userpassword', $password, time()+3600, '/');

// Rediriger l'utilisateur vers une autre page où vous pouvez vérifier le cookie
header('Location: checkCookie.php');
exit;

?>
