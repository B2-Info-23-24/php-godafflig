<?php

// $loader = new \Twig\Loader\FilesystemLoader('vue/helloworld.twig'); // Chemin vers vos templates
// echo $twig->render('helloworld.twig', ['message' => 'Hello World']);
//http://172.21.41.167:8080/
//$loader = new \Twig\Loader\FilesystemLoader('/var/www/html/src/vue'); // Chemin correct vers votre dossier de templates

require_once __DIR__ . '/vendor/autoload.php';
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
    'ajouterFavori' => ['controller' => 'App\\Controller\\MyAccountController', 'method' => 'ajouterFavori'],
    'retirerFavori' => ['controller' => 'App\\Controller\\MyAccountController', 'method' => 'retirerFavori'],
    'admin' => ['controller' => 'App\\Controller\\AdminController'],
    'deleteBrand' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'deletebrand'],
    'deletenbofseat' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'deletenbofseat'],
    'deletecolor' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'deletecolor'],
    'adminAddVehicule' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'createVehicle'],
    'deleteUser' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'deletuser'],
    'updateBrand' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'updatebrand'],
    'updatenbOfseat' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'updatenbofseat'],
    'updateUser' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'updateUser'],
    'updatecolor' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'updatecolor'],
    'addnbofseat' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'createnbofseat'],
    'createcolor' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'createcolor'],
    'addBrand' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'createbrand'],
    'adminadduser' => ['controller' => 'App\\Controller\\AdminController', 'method' => 'createuser'],
    'booking' => ['controller' => 'App\\Controller\\VehiculeRentalController', 'method' => 'rentVehicule'],
    'addreview' => ['controller' => 'App\\Controller\\MyAccountController', 'method' => 'addreview'],

];
$request = $_SERVER['REQUEST_URI'];
$request2 = isset($_GET['action']) ? $_GET['action'] : "";
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
