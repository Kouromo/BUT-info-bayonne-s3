<?php
    session_start();
    // Vérification de la saisie du captcha dans la page de traitement du formulaire 
    if($_SESSION['captcha'] == $_POST['captcha']) {
        echo "captcha OK";
        } else {
        echo "Le captcha est incorrect, veuillez réessayer.";
    }
?>