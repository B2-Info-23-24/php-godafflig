<?php

// UserController.php
require 'vendor/autoload.php';
require_once 'database/databaseconnexion.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MyAccountController {
    protected $twig;
    private $loader;
   public function __construct() {
    $this->userLogged();
    // Instancier le chargeur de templates
    $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

    // Instancier l'environnement Twig
    $this->twig = new Environment($this->loader);
}
    // Fonction pour afficher la page
    public function MyAccount() {
        $this->twig->display('header/header.twig');
        $this->twig->display('Myaccount/Myaccount.twig');
    }

    public function userLogged() {
        if(isset(($_COOKIE['sessionUser']))){
            $sqlRequest = "SELECT * FROM users WHERE session = '" . $_COOKIE['sessionUser'] . "'";
            
            $result = request($sqlRequest);
            if($result){
                echo "Sucess";
            }else{
                echo "fail";
            }
        }
    }



}
?>
