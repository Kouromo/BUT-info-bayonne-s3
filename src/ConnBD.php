<?php
    function ConnBD()
    {
        // Définition d'une variable de test
        $mail = "LisaLabelleGosse@gmail.com";

        // Définition de variables pour se connecter à la base de donnée
        $servername = 'lakartxela';
        $username = 'tlauriou_bd';
        $password = 'tlauriou_bd';
        
        // On établit la connexion
        $conn = new mysqli($servername, $username, $password);
        
        // On vérifie la connexion
        if($conn->connect_error){
            die('Erreur : ' .$conn->connect_error);
        }

        // Définir une variable pour enregistrer le user_id utilisateur
        $_SESSION['user_id'] = $conn->query("SELECT idUti FROM Utilisateur WHERE mail = $mail;");
    }
?>