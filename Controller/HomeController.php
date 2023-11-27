<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use UserManager;
use VehiculeManager;

require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/VehiculeManager.php';
require_once 'Database/DatabaseManager.php';

class HomeController
{
    private $userManager;
    protected $twig;
    private $loader;
    private $databaseManager;
    private $vehiculeManager;
    public function __construct()
    { {
            //----------------------------logique twig -----------------------------------
            $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
            $this->twig = new Environment($this->loader);
            $this->databaseManager = new DatabaseManager();
            $this->userManager = new UserManager($this->databaseManager->conn);
            $this->vehiculeManager = new VehiculeManager($this->databaseManager->conn);
            // // Obtenez les données de l'utilisateur connecté, si elles existent
            //----------------------------------------------------------------------------
            //---------------------------verification si la sessions existe---------------
            $userSessionData = $this->userManager->getUserSession();
            $vehicules = $this->vehiculeManager->getVehicles();
            //----------------------------------------------------------------------------
            //----------------affichage des vue en fonction de l'utilisateur --------------
            $this->twig->display('header/header.twig', ['user' => $userSessionData]);
            $this->twig->display('home/index.html.twig', ['user' => $userSessionData]);
            $this->twig->display('home/index.html.twig', ['vehicules' => $vehicules ]);
            //-----------------------------------------------------------------------------
            // Exécuter la logique principale
        }
    }
}
