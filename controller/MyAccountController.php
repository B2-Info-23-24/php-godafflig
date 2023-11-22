<?php

namespace App\Controller;
// UserController.php
require 'vendor/autoload.php';
require_once 'database/DatabaseManager.php';

use App\Database\DatabaseManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


class MyAccountController
{
    protected $twig;
    private $loader;
    private $databaseManager;
    public function __construct()
    {
        // $this->userLogged();
        // Instancier le chargeur de templates
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->databaseManager = new DatabaseManager();
        // Instancier l'environnement Twig
        $this->twig = new Environment($this->loader);
        $this->twig->display('header/header.twig');
        $this->twig->display('Myaccount/Myaccount.twig');
    }
    // Fonction pour afficher la page
    public function MyAccount()
    {
       
    }

    // public function userLogged()
    // {
    //     if (isset(($_COOKIE['sessionUser']))) {
    //         $sqlRequest = "SELECT * FROM users WHERE session = '" . $_COOKIE['sessionUser'] . "'";
    //         $result = $this->databaseManager->request($sqlRequest);
           
    //         if ($result) {
    //             "Sucess";
    //         } else {
    //             "fail";
    //         }
    //     }
    // }
    // public function isUserLoggedIn() {
    //     if (isset($_COOKIE['sessionUser'])) {
    //         $sqlRequest = "SELECT * FROM users WHERE session = ?";
    //         $params = [$_COOKIE['sessionUser']]; // Paramètres à lier
    //         $result = request($sqlRequest, $params);

    //         if ($result && count($result) > 0) {
    //             echo "session active";
    //             return true; // L'utilisateur est connecté
    //         } else {
    //             echo "pas de session user";
    //             return false; // L'utilisateur n'est pas connecté
    //         }
    //     }
    //     echo "pas de session user";
    //     return false; // L'utilisateur n'est pas connecté
    // }

}
