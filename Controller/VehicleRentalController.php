<?php

namespace App\Controller;

require 'vendor/autoload.php';

use Twig\Environment;
use App\Database\DatabaseManager;
use ReservationManager;
require_once __DIR__ . '/../Modele/UserManager.php'; // Chemin d'accès à UserManager
require_once __DIR__ . '/../Modele/VehiculeManager.php';
require_once __DIR__ . '/../Modele/ReservationsManager.php';

require_once 'Database/DatabaseManager.php';
class VehicleRentalController
{
    private $reservationsManager;
    protected $twig;
    private $databaseManager;
    private $loader;
    public function __construct()
    {
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->reservationsManager = new ReservationManager($this->databaseManager->conn);
    }

    public function rentVehicle($userId, $vehicleId, $startDate, $endDate)
    {
        // Utilisez la méthode createReservation pour créer une nouvelle réservation
        $reservationCreated = $this->reservationsManager->createReservation($userId, $vehicleId, $startDate, $endDate);

        if ($reservationCreated) {
            // La réservation a été créée avec succès
            // Redirigez l'utilisateur ou affichez un message de succès
        } else {
            // La réservation a échoué (peut-être que les dates ne sont pas disponibles)
            // Informez l'utilisateur de l'échec
        }
    }

    // ... autres méthodes du contrôleur
}

// Création du contrôleur quelque part dans votre application
$controller = new VehicleRentalController($conn);

// Exemple d'appel de la méthode pour louer un véhicule
$controller->rentVehicle($userId, $vehicleId, '2023-01-01', '2023-01-10');
