<?php
    /**
    * @file verification.php
    * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
    * @brief Vérifie que le champs saisi correspond bien au champs du Captcha
    * @date 2023-02-02
    * 
    */
    session_start();
    // Vérifier la réponse soumise par l'utilisateur au captcha
    if (isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha'])
    {
        echo "c'est bon";
        // Si la réponse est correcte, réinitialiser le compteur de tentatives
        $_SESSION['attempts'] = 0;
    }
    else
    {
        echo "c'est pas bon <br>";
        // Si la réponse est incorrecte, incrémenter le compteur de tentatives
        $_SESSION['attempts']++;

        // Si l'utilisateur a échoué trois fois d'affilée, l'ajouter à la liste des utilisateurs suspects
        if ($_SESSION['attempts'] >= 3) {
            array_push($_SESSION['suspects'], $_SESSION['user_id']);
            echo "utilisateur ajouté";
        }
    }
?>