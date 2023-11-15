<?php

// UserController.php
require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController {
    protected $twig;
    private $loader;

   public function __construct() {
    // Instancier le chargeur de templates
    $this->loader = new FilesystemLoader(__DIR__ . '/../template');

    // Instancier l'environnement Twig
    $this->twig = new Environment($this->loader);
}
    public function show() {
        // Charger et afficher le template avec les données
        echo('hello');
    }
}