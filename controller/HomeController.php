<?php
namespace App\Controller;

require 'vendor/autoload.php'; // C'est tout ce dont vous avez besoin avec Composer
require_once 'database/DatabaseManager.php';


use App\Database\DatabaseManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController {
    protected $twig;
    private $loader;
    private $databaseManager; // Ajoutez une propriété pour la classe DatabaseManager

    public function __construct() {
        // Instancier le chargeur de templates
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

        // Instancier DatabaseManager
        $this->databaseManager = new DatabaseManager(); // Assurez-vous d'utiliser $this-> pour y accéder

        // Instancier l'environnement Twig
        $this->twig = new Environment($this->loader);

        $this->twig->display('header/header.twig'); // Peut-être retirer cette ligne si votre base.html.twig gère le header.
        $sql = "SELECT * FROM vehicules";

        // Utilisez la méthode request de l'instance de DatabaseManager
        $donnees = $this->databaseManager->request($sql); // Correction ici
        // Ajoute les données fetch depuis la base de données dans le template index.html.twig
         $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);
    }

    // public function show() {
    //     $sql = "SELECT * FROM vehicules";

    //     // Utilisez la méthode request de l'instance de DatabaseManager
    //     $donnees = $this->databaseManager->request($sql); // Correction ici

    //     // Charger et afficher le template avec les données
    //     // Note: Habituellement, vous n'avez pas besoin d'afficher le header séparément si votre base.html.twig inclut déjà le header.
    //     $this->twig->display('header/header.twig'); // Peut-être retirer cette ligne si votre base.html.twig gère le header.

    //     // Ajoute les données fetch depuis la base de données dans le template index.html.twig
    //      $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);
    // }
}
