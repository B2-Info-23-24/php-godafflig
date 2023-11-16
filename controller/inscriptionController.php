<?php

require 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class inscriptionController
{
    protected $twig;
    private $loader;
    public function __construct()
    {
        
        $this->loader = new FilesystemLoader(__DIR__ . '/../vue/template');

       
        $this->twig = new Environment($this->loader);
    }
    // Fonction pour afficher la page
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Le formulaire a été soumis, traiter les données
            $username = $_POST['username'];
            $email = $_POST['email'];
            $phone_number = $_POST['phoneNumber'];
            $last_name = $_POST['lastName'];
            $first_name = $_POST['firstName'];
            $password = $_POST['password'];
            // $is_admin = $_POST['isAdmin'];
            // Inclure et appeler la fonction pour insérer l'utilisateur
            include __DIR__ . '/../modele/insertUser.php';
            insererUtilisateur($username, $email,$phone_number,$last_name,$first_name,$password);
           
            // utilisateurExiste($username,$email);
            // injection de la template pour le hader et l'inscription une fois la requete valider avec succes
            $this->twig->display('header/header.twig');
            $this->twig->display('inscription/inscritpion.twig');
           
            exit;
        } else {
            
            $this->twig->display('header/header.twig');
            $this->twig->display('inscription/inscritpion.twig');
            echo 'erreur';
        }
    }
}
