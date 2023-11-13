<?php

// index.php ou un fichier inclus dans index.php
// require_once 'vendor/autoload.php'; // Assurez-vous que le chemin est correct
// $loader = new \Twig\Loader\FilesystemLoader('vue/helloworld.twig'); // Chemin vers vos templates
// echo $twig->render('helloworld.twig', ['message' => 'Hello World']);
$request = $_SERVER['REQUEST_URI'];
switch ($request) {
  case '/':
    require __DIR__ . '/vue/home.php';
    break;
  case '/inscription':
    require __DIR__ . '/vue/inscription.php';
    break;
  case '/Myaccount':
    require __DIR__ . '/vue/Myaccount.php';
    break;
  default:
    http_response_code(404);
    require __DIR__ . '/vue/home.php';
    break;
}
