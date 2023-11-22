<?php

namespace App\Controller;
// UserController.php
require 'vendor/autoload.php';
require_once 'database/DatabaseManager.php';


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UserManager;
use App\Database\DatabaseManager;

class connexionController
{
    protected $twig;
    private $loader;
    private $userManager;
    private $databaseManager;


    public function __construct()
    {
        // Instancier le chargeur de templates
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

        // Instancier l'environnement Twig
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->twig->display('header/header.twig');
        echo $this->twig->render('connexion/connexion.twig');

        
    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

           
            if ($email && $password) {
                $this->userManager->isUserLoggedIn($email, $password);
            } else {
               
                echo "Email ou mot de passe manquant.";
            }
        }
    }

    // Charger et afficher le template avec les données

}
