<?php

// UserController.php
require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class MyAccountController {
    protected $twig;
    private $loader;

   public function __construct() {
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
}
?>
