<?php
    include ('InterpreterDifficulte.php');
    include ('CreerImageDeRetour.php');
    include ('CreationCaptcha.php');
    include ('ConnBD.php');

    session_start();

    function GenerateCaptcha($difficulty)
    {
        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        InterpreterDifficulte($difficulty);

        // On créer une image qui sera retournée avec une couleur de texte et une couleur de background
        CreerImageDeRetour();


        // Générer un texte aléatoire à utiliser comme captcha en utilisant la fonction str_shuffle()
        // et en limitant la longueur du texte en fonction du niveau de difficulté
        $_SESSION['captcha'] = substr(str_shuffle('0123456789'), 0, $_SESSION['text_length']);

        // Créer le captcha
        CreationCaptcha($difficulty);

        // Afficher l
        header("Content-type: image/png");
        imagepng($_SESSION['image']);
        imagedestroy($_SESSION['image']);
        
    }
    // Connexion à la base de donnée
    ConnBD();

    // Définir une variable pour enregistrer le nombre de tentatives de chaque utilisateur
    $_SESSION['attempts'] = 0;

    // Définir une variable pour enregistrer la liste des utilisateurs suspects
    $_SESSION['suspects'] = array();

    // Générer un captcha en utilisant la fonction generate_captcha()
    GenerateCaptcha('hard');
?>