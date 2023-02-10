<?php
    function CreerImageDeRetour()
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
?>