<?php

/**
 * @file Captcha.php
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @brief Fichier contenant la classe Captcha
 * @details Structure de la classe Captcha permettant la création d'un captcha
 * @date 2023-01-16
 * 
 */

include ('creationImage.php');
include ('ConnBD.php');

class Captcha{

    // ATTRIBUTS
    /**
    * Niveau de difficulté du Captcha
    * @var string peut prendre la valeur easy, medium ou hard
    */
    private $difficulte;

    // CONSTRUCTEURS
    /**
    * Constructeur de la classe Captcha demandant en paramètre le niveau de difficulté
    * @param string $diff  Difficulté du captcha, mis par défaut sur hard
    */
    public function __construct($diff = 'hard', $attempt = True)
    {
        session_start();
        if ($attempt == True)
        {
            ConnBD();
            // Définir une variable pour enregistrer le nombre de tentatives de chaque utilisateur
            $_SESSION['attempts'] = 0;
            // Définir une variable pour enregistrer la liste des utilisateurs suspects
            $_SESSION['suspects'] = array();
        }
        
        $this->setDifficulty($diff);

        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        $this->InterpreterDifficulte($diff);

        // On créer une image qui sera retournée avec une couleur de texte et une couleur de background
        $this->creerImageDeRetour();


        // Générer un texte aléatoire à utiliser comme captcha en utilisant la fonction str_shuffle()
        // et en limitant la longueur du texte en fonction du niveau de difficulté
        $_SESSION['captcha'] = substr(str_shuffle('0123456789'), 0, $_SESSION['text_length']);

        // Créer le captcha
        creationImage($diff);

        // Afficher l'image
        header("Content-type: image/png");
        imagepng($_SESSION['image']);

        imagedestroy($_SESSION['image']);
    }

    // Encapsultation : Get & set
    /**
    * Fonction qui retourne la difficulté du Captcha, méthode get
    * @return string
    */
    public function getDifficulty() {
        return $this->difficulty;
    }

    /** Procédure qui définis la difficulté du Captcha, méthode set
    * @param $diff difficulté du Captcha à définir
    * @return void
    */
    private function setDifficulty($diff) {
        $this->difficulty = $diff;
    }

    // MÉTHODES USUELLES
    // MÉTHODES SPÉCIFIQUES
    /**
    * Procédure qui modifie la longueur du texte généré par le Captcha et le niveau de distortion en fonction de la difficulté
    * @param $diff difficulté du Captcha à définir
    * @return void
    */
    private function InterpreterDifficulte($diff)
    {
        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        switch ($diff) {
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

    /**
    * Procédure qui définis la longueur et la largeur
    * @return void
    */
    private function creerImageDeRetour()
    {
        // Initialisation de 2 variables pour les pixels de l'image
        $_SESSION['width'] = 20;
        $_SESSION['height'] = 50;

        // Créer une image vide avec une largeur de 20 pixels et une hauteur de 50 pixels
        $_SESSION['image'] = imagecreate($_SESSION['width']*$_SESSION['text_length'], $_SESSION['height']);

        // Allouer des couleurs pour l'image en utilisant les fonctions imagecolorallocate()
        $bg_color = imagecolorallocate($_SESSION['image'], 255, 255, 255);
        $text_color = imagecolorallocate($_SESSION['image'], 0, 0, 0);
    }
}
?>