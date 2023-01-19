<?php
    
    /**
    * @file ConnBD.php
    * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
    * @brief Prodédure qui permet de se connecter à la base de données
    * @date 2023-01-16
    * 
    */


    /**
    * Procédure qui permet la connexion à la base de donnée sur lakartxela
    * @return void
    */
    function ConnBD()
    {
        // Définition d'une variable de test
        $mail = "LisaLabelleGosse@gmail.com";

        // Définition de variables pour se connecter à la base de donnée
        $servername = 'lakartxela.iutbayonne.univ-pau.fr';
        $username = 'tlauriou_bd';
        $password = 'tlauriou_bd';
        $bdd = 'tlauriou_bd';
        
        // On établit la connexion
        $conn = mysqli_connect($servername,$username,$password,$bdd);

        $query  = "SELECT idUti FROM Utilisateur WHERE mail = $mail;";
        // Définir une variable pour enregistrer le user_id utilisateur
        $_SESSION['user_id'] = mysqli_query($conn, $query);
        return $conn;
    }
?>