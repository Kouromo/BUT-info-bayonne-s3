<?php
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Définition de variables pour se connecter à la base de donnée
    $servername = 'lakartxela.iutbayonne.univ-pau.fr';
    $username = 'tlauriou_bd';
    $password = 'tlauriou_bd';
    $bdd = 'tlauriou_bd';
    
    // On établit la connexion en PDO pour sécurer la connexion
    $conn = new PDO("mysql:host=$servername;dbname=$bdd", $username, $password);

    // Vérifie l'email existe dans la base de donnée
    $query  = "SELECT mdp FROM Utilisateur WHERE mail = $email;";

    if ($conn->query($query) == $password) {
        // Connexion réussie
        echo "Connexion réussie";
    } else {
        echo "Email ou mot de passe incorrect";
    }

?>