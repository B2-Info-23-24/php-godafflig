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
        $this->getUserSession();
        // print_r($_SESSION['userSession']);
    }
    public function getUserSession()
    {
        if (isset($_SESSION['userSession'])) {
            return $_SESSION['userSession'];
        }
        return null;
    }


    public function rentVehicule()
    {

        // session_start(); // Assurez-vous que la session est démarrée
        $session = $this->getUserSession(); // Cette méthode doit initialiser $_SESSION['userSession']

        if ($session) {
            $userId = $_SESSION['userSession']['id']; // Récupération de l'ID utilisateur depuis la session


            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $vehicleId = $_POST['vehiculeid']; // Validation et nettoyage recommandés
                $startDate = $_POST['startdate']; // Validation et nettoyage recommandés
                $endDate = $_POST['enddate']; // Validation et nettoyage recommandés

                // Calculer le nombre de jours
                $diff = date_diff(date_create($startDate), date_create($endDate));
                $nbDays = $diff->format("%a");
                echo "<br>" ."le nombre de jour reserve est egale a : " . $nbDays . "journée";
                // Récupérer le priceDay du véhicule
                $priceDay = $this->vehiculeManager->getVehiclePricePerDay($vehicleId); // Implémentez cette méthode selon votre logique de base de données
                echo "<br>" ." le prix pas journée est de  : " . $priceDay . "€";
                // Calculer le prix total
                $totalprice = $priceDay * ($nbDays + 1); // +1 car la journée de départ compte
                echo "<br>" ."le prix total est de : " . $totalprice  . "€";
                

                // Création de la réservation
                $reservationCreated = $this->reservationsManager->createReservation($userId, $vehicleId, $startDate, $endDate, $totalprice);
                if ($reservationCreated) {
                } else {
                    echo " la reservation n'a pas pu être faite le vehicules est indiosponible sur c'est date ";
                }
            }
        } else {
            // L'utilisateur n'est pas connecté ou l'ID utilisateur n'est pas dans la session
            // Redirigez vers la page de connexion ou gérez cette situation
        }
    }

    public function viewVehicule()
    {

        // Vérification si l'ID du véhicule est présent dans l'URL
        if (isset($_GET['vehicule_id'])) {
            $vehiculeId = intval($_GET['vehicule_id']); // Conversion en entier pour plus de sécurité
            // Récupération des informations du véhicule
            $vehiculeManagerid = $this->vehiculeManager->vehiculesId($vehiculeId);
            if ($vehiculeManagerid) {
                // Affichage des informations du véhicule
                // Par exemple, avec un système de template
                $this->twig->display('booking/booking.html.twig', ['vehicul' => $vehiculeManagerid]);
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
