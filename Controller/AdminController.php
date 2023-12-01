<?php

namespace App\Controller;

require 'vendor/autoload.php';
require_once 'Database/DatabaseManager.php';
require_once __DIR__ . '/../Modele/Manager.php'; // Chemin d'accès à UserManager


use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use UserManager;
use App\Database\DatabaseManager;
use Manager;

class AdminController
{
    protected $twig;
    private $loader;
    private $userManager;
    private $databaseManager;
    private $Manager;


    public function __construct()
    {
        //----------------------------logique twig -----------------------------------
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');
        $this->twig = new Environment($this->loader);
        $this->databaseManager = new DatabaseManager();
        $this->userManager = new UserManager($this->databaseManager->conn);
        $this->Manager = new Manager($this->databaseManager->conn);
        // Obtenez les données de l'utilisateur connecté, si elles existent
        //----------------------------------------------------------------------------
        //---------------------------verification si la sessions existe---------------
        $userSessionData = $this->userManager->getUserSession();
        //----------------------------------------------------------------------------

        //---------------------------display-----------------------------------------
        $brandFormHtml = $this->Manager->displayBrandForm();
        $colorsFormHtml = $this->Manager->displaycolorForm();
        $NbOfSeatFormHtml = $this->Manager->displaynbofsaetForm();
        $usersFormHtml = $this->Manager->displayusersForm();
        $carsFormhtml = $this->Manager->displaycarForm();
        $seats = $this->Manager->getSeats(); // Récupère les sièges disponibles de la BDD
        $colors = $this->Manager->getColors(); // Récupère les couleurs disponibles de la BDD
        $brands = $this->Manager->getBrands(); // Récupère les marques disponibles de la BDD
        // $reviews = $this->Manager->getReviews(); // Récupère les marques disponibles de la BDD

        //----------------------------------------------------------------------------

        //----------------affichage des vue en fonction de l'utilisateur --------------
        $this->twig->display('header/header.twig', ['user' => $userSessionData]);
        $this->twig->display('admin/admin.html.twig', [
            'user' => $userSessionData, 'brandForm' => $brandFormHtml, 'nbofseatForm' => $NbOfSeatFormHtml, 'colorsForm' => $colorsFormHtml, 'userForm' => $usersFormHtml,
            'carsForm' => $carsFormhtml, 'seats' => $seats,
            'colors' => $colors,
            'brands' => $brands,
        ]);
        //-----------------------------------------------------------------------------
        //----------------check user isadmin or not  ------------------------------------
        $userSessionData = $this->userManager->getUserSession();
        if (!$userSessionData || $userSessionData['isAdmin'] != 1) {
            echo '<script>window.location.href = "/home";</script>'; // Rediriger vers la page de connexion ou d'accueil
            exit(); // Important pour arrêter l'exécution du script
        }
        //-----------------------------------------------------------------------------

    }

    public function deletebrand()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            echo '<script>window.location.href = "/admin";</script>';
            $this->Manager->deleteRecord('brand', 'text', $_GET['brand']);
        }
    }

    public function createbrand()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->Manager->createRecord('brand', 'text', $_POST['brandName']);
            echo ($_POST['brandName']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }

    public function updatebrand()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->Manager->updateRecord('brand', 'text', $_POST['brandName'], 'text', $_GET['brand']);

            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function deletenbofseat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->Manager->deleteRecord('nbOfseat', 'nb_of_seat_int', $_GET['nbOfseat']);

            // $this->BrandManager->deletenbofseat($_GET['brand']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function createnbofseat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->Manager->createRecord('nbOfseat', 'nb_of_seat_int', $_POST['nbOfseat']);
                echo '<script>window.location.href = "/admin";</script>';
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "Erreur : " . $e->getMessage();
                    echo '<script>window.location.href = "/admin";</script>';
                } else {
                    // Gérer les autres types d'erreurs ici
                    echo "valeur ajouter";
                    echo '<script>window.location.href = "/admin";</script>';
                }
            }
        }
    }
    public function updatenbofseat()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->Manager->updateRecord('nbOfseat', 'nb_of_seat_int', $_POST['nbOfseat'], 'nb_of_seat_int', $_GET['nbOfseat']);

            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function deletecolor()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->Manager->deleteRecord('color', 'text', $_GET['color']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function createcolor()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $this->Manager->createRecord('color', 'text', $_POST['color']);
                echo '<script>window.location.href = "/admin";</script>';
            } catch (\Exception $e) {
                if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                    echo "Erreur : " . $e->getMessage();
                    echo '<script>window.location.href = "/admin";</script>';
                } else {
                    // Gérer les autres types d'erreurs ici
                    echo "valeur ajouter";
                    echo '<script>window.location.href = "/admin";</script>';
                }
            }
        }
    }
    public function updatecolor()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->Manager->updateRecord('color', 'text', $_POST['color'], 'text', $_GET['color']);

            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function deletuser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->Manager->deleteRecord('users', 'id', $_GET['id']);
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
    public function createuser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : null;
            $phone_number = isset($_POST['phoneNumber']) ? $_POST['phoneNumber'] : null;
            $last_name = isset($_POST['lastName']) ? $_POST['lastName'] : null;
            $first_name = isset($_POST['firstName']) ? $_POST['firstName'] : null;
            $password = isset($_POST['passwordUser']) ? $_POST['passwordUser'] : null;
            // Utilisation de userManager pour insérer l'utilisateur
            $this->userManager->insererUtilisateur($last_name, $first_name, $email, $password, $phone_number);
        }
        echo '<script>window.location.href = "/admin";</script>';
    }
    public function updateuser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_GET['id']; // Assurez-vous de valider et nettoyer cette valeur
            $userData = [
                'lastName' => $_POST['lastName'],
                'firstName' => $_POST['firstName'],
                'email' => $_POST['email'],
                'phoneNumber' => $_POST['phoneNumber'],
                'passwordUser' => $_POST['passwordUser'],
                'isAdmin' => isset($_POST['isAdmin']) ? 1 : 0
            ];

            // Mise à jour des informations de l'utilisateur
            $updated = $this->Manager->updateUser($userId, $userData);

            // Redirection ou traitement en fonction du résultat de la mise à jour
            if ($updated) {
                echo '<script>window.location.href = "/admin";</script>';
            } else {
                echo "Aucune mise à jour n'a été effectuée.";
            }
        }
    }
    // public function deletecars()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //         $this->Manager->deleteRecord('vehicule', 'id', $_GET['id']);
    //         echo '<script>window.location.href = "/admin";</script>';
    //     }
    // }
    public function createVehicle()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Récupérer les données du formulaire
                $color_id = isset($_POST['color_id']) ? $_POST['color_id'] : null;
                $nbOfseat_id = isset($_POST['nbOfseat_id']) ? $_POST['nbOfseat_id'] : null;
                $review = isset($_POST['review']) ? $_POST['review'] : null;
                $priceDay = isset($_POST['priceDay']) ? $_POST['priceDay'] : null;
                $brand_id = isset($_POST['brandName']) ? $_POST['brandName'] : null;
                $nbofstar = isset($_POST['nbofstar']) ? $_POST['nbofstar'] : null;
                echo 'colorid : ' . $color_id . '<br>';
                echo 'nbofseatid : ' . $nbOfseat_id . '<br>';
                echo 'brandid : '  .  $brand_id . '<br>';

                $missingIds = [];

                if (!$this->Manager->recordExists('color', $color_id)) {
                    $missingIds[] = "color";
                }
                if (!$this->Manager->recordExists('nbOfseat', $nbOfseat_id)) {
                    $missingIds[] = "nbOfseat";
                }
                if (!$this->Manager->recordExists('brand', $brand_id)) {
                    $missingIds[] = "brand";
                }
                if (!empty($missingIds)) {
                    echo "ID non trouvé pour : " . implode(', ', $missingIds) . ".";
                    return; // Sortir de la fonction si un ou plusieurs ID ne sont pas valides
                }
                // Insérer le nouveau véhicule
                $this->Manager->createVehicule($nbOfseat_id, $review, $color_id, $priceDay, $brand_id, $nbofstar);

                // Redirection en cas de succès
                echo '<script>window.location.href = "/admin";</script>';
            } catch (\Exception $e) {
                // Gérer l'erreur
                echo "Erreur lors de la création du véhicule : " . $e->getMessage();
            }
        } else {
            // Redirection si la méthode n'est pas POST
            echo '<script>window.location.href = "/admin";</script>';
        }
    }
}
