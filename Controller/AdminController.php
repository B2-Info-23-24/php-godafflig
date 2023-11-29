<?php

namespace App\Controller;
// UserController.php

require 'vendor/autoload.php';
require_once 'Database/DatabaseManager.php';
require_once __DIR__ . '/../Modele/BrandManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/NbOfSeatManager.php'; // Chemin d'accès à UserManager


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UserManager;
use App\Database\DatabaseManager;
use BrandManager;
use NbOfSeatManager;

class AdminController
{
    protected $twig;
    private $loader;
    private $userManager;
    private $databaseManager;
    private $BrandManager;
    private $NbOfSeatManager;


    public function __construct()
    {
        //----------------------------logique twig -----------------------------------
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->BrandManager = new BrandManager($this->databaseManager->conn);
        $this->NbOfSeatManager = new NbOfSeatManager($this->databaseManager->conn);
        // Obtenez les données de l'utilisateur connecté, si elles existent
        //----------------------------------------------------------------------------
        //---------------------------verification si la sessions existe---------------
        $userSessionData = $this->userManager->getUserSession();
        //----------------------------------------------------------------------------

        //---------------------------display-----------------------------------------
        $brandFormHtml = $this->BrandManager->displayBrandForm();
        $NbOfSeatFormHtml = $this->NbOfSeatManager->displaynbofsaetForm();
        //----------------------------------------------------------------------------

        //----------------affichage des vue en fonction de l'utilisateur --------------
        $this->twig->display('header/header.twig', ['user' => $userSessionData]);
        $this->twig->display('admin/admin.html.twig', ['user' => $userSessionData, 'brandForm' => $brandFormHtml, 'nbofseatForm' => $NbOfSeatFormHtml ]);
        //-----------------------------------------------------------------------------
    }
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->BrandManager->deleteBrandByName($_GET['brand']);
            // $this->BrandManager->deletenbofseat($_GET['brand']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->BrandManager->createBrandByName($_POST['brandName']);
            echo ($_POST['brandName']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->BrandManager->updateBrandByName($_GET['brand'],$_POST['brandName']);
            
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
}
