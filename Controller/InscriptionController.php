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

class InscriptionController
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
        $this->twig->display('inscription/inscription.twig', ['user' => $userSessionData]);
        //------------------------------------------------------------------------------
        }
    public function register()
    {
        // $this->twig->display('header/header.twig');
        // $this->twig->display('inscription/inscritpion.twig');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $phone_number = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;
            $last_name = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $first_name = isset($_POST['firstName']) ? $_POST['firstName'] : null;
            $password = isset($_POST['passwordUser']) ? $_POST['passwordUser'] : null;
            // Utilisation de userManager pour insérer l'utilisateur
            $this->userManager->insererUtilisateur($last_name, $first_name, $email, $password, $phone_number);
        }
    }
}
