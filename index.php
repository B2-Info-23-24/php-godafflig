<?php


// $loader = new \Twig\Loader\FilesystemLoader('vue/helloworld.twig'); // Chemin vers vos templates
// echo $twig->render('helloworld.twig', ['message' => 'Hello World']);
//http://172.21.41.167:8080/
//$loader = new \Twig\Loader\FilesystemLoader('/var/www/html/src/vue'); // Chemin correct vers votre dossier de templates


require_once __DIR__ . '/vendor/autoload.php';
 


// Assurez-vous que ce chemin est correct   


  require_once __DIR__ . '/controller/HomeController.php';
  require_once __DIR__ . '/controller/inscriptionController.php';
  require_once __DIR__ . '/controller/MyAccountController.php';

$homeController = new HomeController();
$inscriptionController = new inscriptionController();
$MyAccountController = new MyAccountController();

$routes = [
    '/' => ['controller' => 'HomeController', 'method' => 'show'],
    '/home' => ['controller' => 'HomeController', 'method' => 'show'],
    '/inscription' => ['controller' => 'inscriptionController', 'method' => 'register'],
    '/connexion' => ['controller' => 'connexionController', 'method' => 'login'],
    '/Myaccount' => ['controller' => 'MyAccountController', 'method' => 'MyAccount']
];

$request = $_SERVER['REQUEST_URI'];

if (array_key_exists($request, $routes)) {
    $controllerName = $routes[$request]['controller'];
    $methodName = $routes[$request]['method'];
    $controller = new $controllerName();
    $controller->$methodName();
} else {
    http_response_code(404);
    
}

