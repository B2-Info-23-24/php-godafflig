<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use UserManager;


// Assurez-vous d'inclure UserManager ici
require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once 'Database/DatabaseManager.php';


class MyAccountController
{
    private $userManager;
    protected $twig;
    private $loader;
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
        $this->twig->display('Myaccount/Myaccount.twig', ['user' => $userSessionData]);
        //-----------------------------------------------------------------------------
    }
    // Fonction pour afficher la page
    // public function MyAccount()
    // {
    // }
    public function disconnect()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // echo '<meta http-equiv="refresh" content="0">';
            unset($_SESSION['userSession']);
            $_SESSION = array();
            session_destroy();
        }
    }
}
