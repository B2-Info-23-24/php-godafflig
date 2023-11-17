<?php

// UserController.php
require 'vendor/autoload.php';
require_once 'database/databaseconnexion.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController {
    protected $twig;
    private $loader;
   

   public function __construct() {
    // Instancier le chargeur de templates
    $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

    // Instancier l'environnement Twig
    $this->twig = new Environment($this->loader);
    
}
    public function show() {
        
        $sql = "SELECT * FROM vehicules";

        $donnees = (request($sql));
        //print_r($donnees);

        echo $donnees[1]['brand'];


        // Charger et afficher le template avec les données
        $this->twig->display('header/header.twig');
      
         // ajoute les donnée fetch depuis la fonction datafetchervehicules() dasn la template index.html.twig
        echo $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);

       
    }
}