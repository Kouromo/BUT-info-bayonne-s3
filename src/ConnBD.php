<?php
    /**
    * @file ConnBD.php
    * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
    * @brief Prodédure qui permet de se connecter à la base de données
    * @date 2023-01-16
    */

    /**
    * Procédure qui permet la connexion à la base de donnée sur lakartxela
    * @return void
    */
    function ConnBD()
    {
        // Définition de variables pour se connecter à la base de donnée
        $servername = 'lakartxela.iutbayonne.univ-pau.fr';
        $username = 'tlauriou_bd';
        $password = 'tlauriou_bd';
        $bdd = 'tlauriou_bd';

        // On établit la connexion en PDO pour sécurer la connexion
        $conn = new PDO("mysql:host=$servername;dbname=$bdd", $username, $password);


        if (isset($_SESSION['user_id']) == false)
        {
            // si le fichier d'ou a été appelé se nomme get_posts.php
            if (!(basename($_SERVER['PHP_SELF']) == 'get_posts.php'))
            {
                session_start();  
            }
        }
        else
        {
            // Cherche l'id de l'utilisateur dans la base de donnée
            $idUtilisateur = $_SESSION['user_id'];
            $query  = "SELECT mail FROM Utilisateur WHERE idUti = $idUtilisateur;";

            // Définir une variable pour enregistrer le user_id utilisateur
            $_SESSION['mail'] = $conn->query($query);
        }
        
        return $conn;
    } 
?>