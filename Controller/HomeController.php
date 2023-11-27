<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use UserManager;

require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once 'Database/DatabaseManager.php';

class HomeController
{
    private $userManager;
    protected $twig;
    private $loader;
    private $databaseManager;

    public function __construct()
    { {
            //----------------------------logique twig -----------------------------------
            $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
            $this->twig = new Environment($this->loader);
            $this->databaseManager = new DatabaseManager();
            $this->userManager = new UserManager($this->databaseManager->conn);
            // Obtenez les données de l'utilisateur connecté, si elles existent
            //----------------------------------------------------------------------------
            //---------------------------verification si la sessions existe---------------
            $userSessionData = $this->userManager->getUserSession();
            //----------------------------------------------------------------------------
            //----------------affichage des vue en fonction de l'utilisateur --------------
            $this->twig->display('header/header.twig', ['user' => $userSessionData]);
            $this->twig->display('home/index.html.twig', ['user' => $userSessionData]);
            //-----------------------------------------------------------------------------
            // Exécuter la logique principale

        }
    }
    // private function main()
    // {
    //     $sql = "SELECT * FROM vehicules";

    //     // Utilisez la méthode request de l'instance de DatabaseManager
    //     $result = $this->databaseManager->request($sql);

    //     // Vérifiez si le résultat est valide avant de l'utiliser
    //     if ($result === false) {
    //         // Gérer l'erreur ici (par exemple, enregistrer dans un fichier journal)
    //         die("Erreur lors de l'exécution de la requête SQL.");
    //     }

    //     // Transformer le résultat en tableau associatif
    //     $donnees = $this->databaseManager->request($sql); // Correction ici

    // // Rendre le template avec les données
    // if (isset($_SESSION['userSession'])) {
    //     $this->twig->display('header/header.twig', ['connexion' => $_SESSION['userSession']]);
    //     echo $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);

    // } else {

    //     $this->twig->display('header/header.twig');
    //     echo $this->twig->render('home/index.html.twig', ['donnees' => $donnees]);
    // }


}
