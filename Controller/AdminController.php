<?php

namespace App\Controller;
// UserController.php

require 'vendor/autoload.php';
require_once 'Database/DatabaseManager.php';
require_once __DIR__ . '/../Modele/BrandManager.php'; // Chemin d'accès à UserManager


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UserManager;
use App\Database\DatabaseManager;
use BrandManager;

class AdminController
{
    protected $twig;
    private $loader;
    private $userManager;
    private $databaseManager;
    private $BrandManager;


    public function __construct()
    {
        //----------------------------logique twig -----------------------------------
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->BrandManager = new BrandManager($this->databaseManager->conn);
        // Obtenez les données de l'utilisateur connecté, si elles existent
        //----------------------------------------------------------------------------
        //---------------------------verification si la sessions existe---------------
        $userSessionData = $this->userManager->getUserSession();
        //----------------------------------------------------------------------------

        //---------------------------display brand-----------------------------------------
        $brandFormHtml = $this->BrandManager->displayBrandForm();
        //----------------------------------------------------------------------------

        //  //---------------------------delete brand--------------------------------------------------------------
        //   $brandFormHtml = $this->BrandManager->deleteBrandForm();
        //-------------------------------------------------------------------------------------------------

        //---------------------------update brand--------------------------------------------------------------
        // $brandFormHtml = $this->BrandManager->displayBrandForm();
        //-------------------------------------------------------------------------------------------------

        //---------------------------create brand--------------------------------------------------------------
        //  $brandFormHtml = $this->BrandManager->displayBrandForm();
        //-------------------------------------------------------------------------------------------------

        //----------------affichage des vue en fonction de l'utilisateur --------------
        $this->twig->display('header/header.twig', ['user' => $userSessionData]);
        $this->twig->display('admin/admin.html.twig', ['user' => $userSessionData, 'brandForm' => $brandFormHtml]);
        //-----------------------------------------------------------------------------
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
           
            $this->BrandManager->deleteBrandByName($_GET['brand']);
            echo '<script>window.location.href = "/admin";</script>';
        }
        
    }
}
