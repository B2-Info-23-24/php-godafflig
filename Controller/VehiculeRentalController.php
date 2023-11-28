<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use ReservationManager;
use UserManager;
use VehiculeManager;

require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/VehiculeManager.php';
require_once __DIR__ . '/../Modele/ReservationsManager.php';
require_once 'Database/DatabaseManager.php';

class VehiculeRentalController
{
    private $reservationsManager;
    public $twig;
    private $databaseManager;
    private $loader;
    private $userManager;
    private $vehiculeManager;

    public function __construct()
    {

        //----------------------------logique twig -----------------------------------
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->reservationsManager = new ReservationManager($this->databaseManager->conn);
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->vehiculeManager = new VehiculeManager($this->databaseManager->conn);
        //----------------------------------------------------------------------------


        //---------------------------verification si la sessions existe---------------
        $userSessionData = $this->userManager->getUserSession();
        //----------------------------------------------------------------------------


        //---------------------------fetch vehicule id------------------------------
        // $vehicules = $this->vehiculeManager->getVehicles();
        // echo ($_GET['vehicule_id']);
        // $vm = new VehiculeManager($this->databaseManager->conn);
        // $vm->vehiculesId($_GET['vehicule_id']);
        // $vehicules = $this->vehiculeManager->vehiculesId($_GET['vehicule_id']);
        //----------------------------------------------------------------------------


        //----------------affichage des vue en fonction de la voiture --------------
        $this->twig->display('header/header.twig', ['user' => $userSessionData]);
        // $this->twig->display('booking/booking.html.twig',['vehicule' => $vehicules]);
        //-----------------------------------------------------------------------------

    }

    public function rentVehicle($userId, $vehicleId, $startDate, $endDate)
    {

        $reservationCreated = $this->reservationsManager->createReservation($userId, $vehicleId, $startDate, $endDate);

        if ($reservationCreated) {
            // La réservation a été créée avec succès
        } else {
            // Informez l'utilisateur de l'échec
        }
    }
    public function viewVehicule()
    {
        
        echo 'test1';
        // Vérification si l'ID du véhicule est présent dans l'URL
        if (isset($_GET['vehicule_id'])) {
            $vehiculeId = intval($_GET['vehicule_id']); // Conversion en entier pour plus de sécurité
            // Récupération des informations du véhicule
            $vehiculeManagerid = $this->vehiculeManager->vehiculesId($vehiculeId);
            // var_dump($vehiculeManagerid); 
            if ($vehiculeManagerid) {
                // Affichage des informations du véhicule
                // Par exemple, avec un système de template
                $this->twig->display('booking/booking.html.twig', ['vehicul' => $vehiculeManagerid]);
                echo "test2";
            } else {
                // Gestion du cas où le véhicule n'est pas trouvé
                echo "Véhicule non trouvé";
            }
        } else {
            // Gestion du cas où l'ID n'est pas fourni ou n'est pas valide
            echo "ID de véhicule non spécifié ou invalide";
        }
    }

}

// Création du contrôleur quelque part dans votre application
// $controller = new VehiculeRentalController();

// Exemple d'appel de la méthode pour louer un véhicule
// $controller->rentVehicle($userId, $vehicleId, '2023-01-01', '2023-01-10'); 
