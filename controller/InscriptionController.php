<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use UserManager;


// Assurez-vous d'inclure UserManager ici
require_once __DIR__ . '/../modele/UserManager.php'; // Chemin d'accès à UserManager
require_once 'database/DatabaseManager.php';

class inscriptionController
{
    protected $twig;
    private $loader;
    private $userManager;
    private $databaseManager;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->twig->display('header/header.twig');
        $this->twig->display('inscription/inscritpion.twig');
    }

    public function register()
    {

        // $this->twig->display('header/header.twig');
        // $this->twig->display('inscription/inscritpion.twig');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo 'METHOD POST';
            $username = isset($_POST['username']) ? $_POST['username'] : null;
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $phone_number = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;
            $last_name = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $first_name = isset($_POST['firstName']) ? $_POST['firstName'] : null;
            $password = isset($_POST['password']) ? $_POST['password'] : null;

            // Vérifiez si toutes les variables nécessaires sont présentes
             
                // Utilisation de userManager pour insérer l'utilisateur
                $this->userManager->insererUtilisateur($username, $email, $phone_number, $last_name, $first_name, $password);
                echo 'user ajouter';
                // Affichage des templates

                
            
        }
    }
    // Destructeur pour fermer la connexion à la base de données
    // public function __destruct()
    // {
    //     $this->userManager->closeConnection();
    // }
}
