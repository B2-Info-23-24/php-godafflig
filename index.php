<?php
use App\Model\InitDb;
include __DIR__ . "/Modele/InitDB.php";
// $loader = new \Twig\Loader\FilesystemLoader('vue/helloworld.twig'); // Chemin vers vos templates
// echo $twig->render('helloworld.twig', ['message' => 'Hello World']);
//http://172.21.41.167:8080/
//$loader = new \Twig\Loader\FilesystemLoader('/var/www/html/src/vue'); // Chemin correct vers votre dossier de templates

$initDb = new InitDb();
//$initDb->initializeDatabaseWithData();
$initDb->createTable();

$initDb->closeConnection();

require_once __DIR__ . '/vendor/autoload.php';



// Assurez-vous que ce chemin est correct   


require_once __DIR__ . '/Controller/HomeController.php';
require_once __DIR__ . '/Controller/InscriptionController.php';
require_once __DIR__ . '/Controller/MyAccountController.php';
require_once __DIR__ . '/Controller/ConnexionController.php';




// Initialisation de la base de données



$routes = [
    '/' => ['controller' => 'App\\Controller\\HomeController'],
    '/home' => ['controller' => 'App\\Controller\\HomeController'],
    '/inscription' => ['controller' => 'App\\Controller\\InscriptionController'],
    '/connexion' => ['controller' => 'App\\Controller\\ConnexionController'],
    '/login' => ['controller' => 'App\\Controller\\ConnexionController', 'method' => 'login'],
    '/register' => ['controller' => 'App\\Controller\\InscriptionController', 'method' => 'register'],
    '/Myaccount' => ['controller' => 'App\\Controller\\MyAccountController'],
    '/disconnect' => ['controller' => 'App\\Controller\\MyAccountController', 'method' => 'disconnect']
];


$request = $_SERVER['REQUEST_URI'];

if (array_key_exists($request, $routes)) {
    $controllerName = $routes[$request]['controller'];
    $controller = new $controllerName();

    // Vérifiez d'abord si la clé 'method' existe et si c'est une chaîne
    if (isset($routes[$request]['method']) && is_string($routes[$request]['method'])) {
        $methodName = $routes[$request]['method'];
        $controller->$methodName();
    } else {
        if (method_exists($controller, 'index')) {
            $controller->index();
        } else {

            // echo "PAS DE METHODE TROUVER";
            http_response_code(404);  
        }
    }
} else {
    http_response_code(404);
}
