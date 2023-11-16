<?php

// UserController.php
require 'vendor/autoload.php';
require_once 'modele/dataFetcher.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class connexionController
{
    protected $twig;
    private $loader;


    public function __construct()
    {
        // Instancier le chargeur de templates
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

        // Instancier l'environnement Twig
        $this->twig = new Environment($this->loader);
    }
    public function show()
    {
        $donnees = datafetchervehicules();
        // Charger et afficher le template avec les données
        $this->twig->display('header/header.twig');


        echo $this->twig->render('connexion/connexion.twig');
    }
}
