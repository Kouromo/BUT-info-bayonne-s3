<?php

/**
 * 
 */


include ('creationCaptcha.php');
include ('ConnBD.php');
class Captcha{

    // Attributs
    private $difficulte;

    // Constructeurs
    public function __construct($diff = 'hard')
    {
        session_start();

        ConnBD();

        
        $this->setDifficulty($diff);

        // Définir une variable pour enregistrer le nombre de tentatives de chaque utilisateur
        $_SESSION['attempts'] = 0;

        // Définir une variable pour enregistrer la liste des utilisateurs suspects
        $_SESSION['suspects'] = array();




        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        $this->InterpreterDifficulte($diff);

        // On créer une image qui sera retournée avec une couleur de texte et une couleur de background
        $this->CreerImageDeRetour();


        // Générer un texte aléatoire à utiliser comme captcha en utilisant la fonction str_shuffle()
        // et en limitant la longueur du texte en fonction du niveau de difficulté
        $_SESSION['captcha'] = substr(str_shuffle('0123456789'), 0, $_SESSION['text_length']);

        // Créer le captcha
        CreationCaptcha($diff);

        // Afficher l
        header("Content-type: image/png");
        imagepng($_SESSION['image']);

        imagedestroy($_SESSION['image']);
    }

    // Encapsultation : Get & set
    public function getDifficulty() {
        return $this->difficulty;
    }

    private function setDifficulty($diff) {
        $this->difficulty = $diff;
    }

    // Méthodes usuelles

    // Méthodes spécifiques
    private function InterpreterDifficulte($difficulty)
    {
        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        switch ($difficulty) {
            case 'medium':
                $_SESSION['text_length'] = 8;
                $_SESSION['distortion_level'] = 10;
                break;
            case 'hard':
                $_SESSION['text_length'] = 10;
                $_SESSION['distortion_level'] = 20;
                break;
            default:
                $_SESSION['text_length'] = 6;
                $_SESSION['distortion_level'] = 5;
        }
    }

    private function CreerImageDeRetour()
    {
        // Initialisation de 2 variables pour les pixels de l'image
        $_SESSION['width'] = 20;
        $_SESSION['height'] = 50;

        // Créer une image vide avec une largeur de 200 pixels et une hauteur de 50 pixels
        $_SESSION['image'] = imagecreate($_SESSION['width']*$_SESSION['text_length'], $_SESSION['height']);

        // Allouer des couleurs pour l'image en utilisant les fonctions imagecolorallocate()
        $bg_color = imagecolorallocate($_SESSION['image'], 255, 255, 255);
        $text_color = imagecolorallocate($_SESSION['image'], 0, 0, 0);
    }

    
}

?>