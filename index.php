<?php
use App\Model\InitDb;
include __DIR__ . "/Modele/InitDB.php";
// $loader = new \Twig\Loader\FilesystemLoader('vue/helloworld.twig'); // Chemin vers vos templates
// echo $twig->render('helloworld.twig', ['message' => 'Hello World']);
//http://172.21.41.167:8080/
//$loader = new \Twig\Loader\FilesystemLoader('/var/www/html/src/vue'); // Chemin correct vers votre dossier de templates

$initDb = new InitDb();
// $initDb->initializeDatabaseWithData();
// $initDb->createTable();

$initDb->closeConnection();

require_once __DIR__ . '/vendor/autoload.php';
// Assurez-vous que ce chemin est correct   
require_once __DIR__ . '/Controller/HomeController.php';
require_once __DIR__ . '/Controller/InscriptionController.php';
require_once __DIR__ . '/Controller/MyAccountController.php';
require_once __DIR__ . '/Controller/ConnexionController.php';
require_once __DIR__ . '/Controller/VehiculeRentalController.php';
require_once __DIR__ . '/Controller/AdminController.php';







$routes = [
    '' => ['controller' => 'App\\Controller\\HomeController'],
    'home' => ['controller' => 'App\\Controller\\HomeController'],
    'inscription' => ['controller' => 'App\\Controller\\InscriptionController'],
    'connexion' => ['controller' => 'App\\Controller\\ConnexionController'],
    'login' => ['controller' => 'App\\Controller\\ConnexionController', 'method' => 'login'],
    'register' => ['controller' => 'App\\Controller\\InscriptionController', 'method' => 'register'],
    'Myaccount' => ['controller' => 'App\\Controller\\MyAccountController'],
    'disconnect' => ['controller' => 'App\\Controller\\MyAccountController', 'method' => 'disconnect'],
    'rental' => ['controller' => 'App\\Controller\\VehiculeRentalController', 'method' => 'viewVehicule'],
    'admin' => ['controller' => 'App\\Controller\\AdminController'],
    'deleteBrand' => ['controller' => 'App\\Controller\\AdminController' ,'method' => 'delete']
];


$request = $_SERVER['REQUEST_URI'];
$request2 = $_GET['action'];

if (array_key_exists($request2, $routes)) {
    
    $controllerName = $routes[$request2]['controller'];
    $controller = new $controllerName();

    if (isset($routes[$request2]['method']) && is_string($routes[$request2]['method'])) {
        $methodName = $routes[$request2]['method'];
        $controller->$methodName();
    } else {
        if (method_exists($controller, 'index')) {
            $controller->index();
        } else {
            http_response_code(404);  
        }
    }
} else {
    http_response_code(404);
}
