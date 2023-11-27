<?php

namespace App\Controller;
// UserController.php

require 'vendor/autoload.php';
require_once 'Database/DatabaseManager.php';


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
        $this->twig->display('connexion/connexion.twig', ['user' => $userSessionData]);
        //-----------------------------------------------------------------------------

    }
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<meta http-equiv="refresh" content="0">';
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $password = isset($_POST['passwordUser']) ? $_POST['passwordUser'] : null;
            if ($email && $password) {
                $this->userManager->userIsInDb($email, $password);
            } else {
                echo "Email ou mot de passe manquant.";
            }
        }
    }


    // Charger et afficher le template avec les données

}
