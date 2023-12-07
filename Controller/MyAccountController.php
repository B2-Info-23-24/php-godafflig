<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Database\DatabaseManager;
use UserManager;
use ReservationManager;
use favorisManager;
use ReviewManager;


// Assurez-vous d'inclure UserManager ici
require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/ReservationsManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/favorisManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/ReviewManager.php'; // Chemin d'accès à UserManager
require_once 'Database/DatabaseManager.php';


class MyAccountController
{
    private $userManager;
    protected $twig;
    private $loader;
    private $databaseManager;
    private $ReservationManager;
    private $favorisManager;
    private $ReviewManager;
    public function __construct()
    {
        //-----------------------------------------------logique twig ------------------------------------------------------------
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->ReservationManager = new ReservationManager($this->databaseManager->conn);
        $this->favorisManager = new favorisManager($this->databaseManager->conn);
        $this->ReviewManager = new ReviewManager($this->databaseManager->conn);
        // Obtenez les données de l'utilisateur connecté, si elles existent
        //----------------------------------------------------------------------------
        //---------------------------verification si la sessions existe---------------
        $userSessionData = $this->userManager->getUserSession();
        $reservationuser = $this->ReservationManager->getUserReservations();

        if ($userSessionData) {
            $userId = $userSessionData['id'];
            $reservationuser = $this->ReservationManager->getUserReservations($userId);
            $userFavoris = $this->userManager->getUserFavoris($userId);

            $this->twig->display('header/header.twig', ['user' => $userSessionData]);
            $this->twig->display('Myaccount/Myaccount.twig', [
                'user' => $userSessionData,
                'reservation' => $reservationuser,
                'favoris' => $userFavoris
            ]);
        }
    }
    public function getUserSession()
    {
        if (isset($_SESSION['userSession'])) {
            return $_SESSION['userSession'];
        }
        return null;
    }

    public function addreview()
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $vehiculeId = $_POST["vehicule_id"];
            $review = $_POST["review"];
            $rating = $_POST["rating"];

            // Assurez-vous que $vehiculeId est un nombre entier
            if (is_numeric($vehiculeId)) {
                // Effectuez une mise à jour de la table vehicules
                $this->ReviewManager->updateReview($vehiculeId, $review, $rating);

                // Redirigez l'utilisateur vers une page de confirmation ou une autre page de votre choix
                // header("Location: /confirmation"); // Remplacez /confirmation par l'URL de votre choix
                exit(); // Assurez-vous de sortir du script après la redirection
            } else {
                // Gérer l'erreur : $vehiculeId n'est pas un nombre valide
                echo "ID du véhicule invalide.";
            }
        }
    }


    public function disconnect()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo '<meta http-equiv="refresh" content="0">';
            unset($_SESSION['userSession']);
            $_SESSION = array();
            session_destroy();
        }
    }



    public function ajouterFavori()
    {
        $session = $this->getUserSession();
        if ($session) {
            $userId = $session['id'];

            if (isset($_GET['vehicule_id']) && !empty($_GET['vehicule_id'])) {
                $vehiculeId = $_GET['vehicule_id'];

                // Vérification supplémentaire pour s'assurer que l'ID est numérique
                if (is_numeric($vehiculeId)) {
                    $this->favorisManager->ajouterAuxFavoris($userId, $vehiculeId);
                } else {
                    echo " : ID du véhicule invalide";
                }
            } else {
                echo " : ID du véhicule non fourni";
            }
        } else {
            echo " Gérer l'erreur : utilisateur non connecté";
        }
    }
    public function retirerFavori()
    {
        $session = $this->getUserSession();
        if ($session && isset($_GET['vehicule_id'])) {
            $userId = $session['id'];
            $vehiculeId = $_GET['vehicule_id'];

            // Appel à favorisManager pour retirer le favori
            $this->favorisManager->retirerDesFavoris($userId, $vehiculeId);

            echo 'le favoris a bien etait supprimer ';
        } else {
            echo 'le favoris na pas etait supprimer ';
        }
    }
}
