<?php
namespace App\Controller;

require 'vendor/autoload.php';  // Utilisation de l'autoloader de Composer

use App\Database\DatabaseManager;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController {
    protected $twig;
    private $databaseManager; // Propriété pour la classe DatabaseManager

    public function __construct() {
        // Instancier le chargeur et l'environnement Twig
        $loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($loader);

        // Instancier DatabaseManager
        $this->databaseManager = new DatabaseManager();

        // Exécuter la logique principale
        $this->main();
    }

    private function main() {
        $sql = "SELECT * FROM vehicules";

        // Utilisez la méthode request de l'instance de DatabaseManager
        $result = $this->databaseManager->request($sql);

        // Vérifiez si le résultat est valide avant de l'utiliser
        if ($result === false) {
            // Gérer l'erreur ici (par exemple, enregistrer dans un fichier journal)
            die("Erreur lors de l'exécution de la requête SQL.");
        }

        // Transformer le résultat en tableau associatif
        $donnees = $this->databaseManager->request($sql); // Correction ici

        // Rendre le template avec les données
        $this->twig->display('header/header.twig');
        echo $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);
    }
}
