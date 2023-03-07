<?php

    session_start();

    $email = $_POST['email'];
    $password = $_POST['password'];

    
    if (empty($email) || empty($password)) { // Si l'email ou le mot de passe est vide, on redirige vers la page de connexion
        header('Location: connexion.html');
    }

    // Définition de variables pour se connecter à la base de donnée
    $servername = 'lakartxela.iutbayonne.univ-pau.fr';
    $username = 'tlauriou_bd';
    $passwordBD = 'tlauriou_bd';
    $bdd = 'tlauriou_bd';
    
    // On établit la connexion en PDO pour sécurer la connexion
    $conn = new PDO("mysql:host=$servername;dbname=$bdd", $username, $passwordBD);

    // On prépare la requête pour vérifier l'existence de l'utilisateur dans la base de données
    $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE mail = :mail");
    // On lie les données envoyées à la requête
    $stmt->bindParam(':mail', $email);
    // On exécute la requête
    $stmt->execute();
    // On récupère les résultats de la requête
    $user = $stmt->fetch();

    // Affiche le contenu de la variable $user
    //var_dump($user);

    // Affiche le contenu de $user['mdp']
    //var_dump($user['mdp']);

    $hash = password_hash($user['mdp'], PASSWORD_DEFAULT);

    
    

    if (isset($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha'])
    {
        // Si l'utilisateur n'existe pas, on redirige vers la page de connexion
        if (!$user) {
            header('Location: connexion.html'); // Utilisateur inexistant
        } 
        else 
        {
            // On vérifie que le mot de passe envoyé correspond au mot de passe stocké dans la base de données
            if (password_verify($password, $hash)) {
                $_SESSION['user_id'] = $user['idUti'];
                $conn = null;
                header('Location: index.php');
                exit;
            } 
            else {
                $conn = null;
                header('Location: connexion.html'); // Mot de passe incorrect
            }
        }
    }   
    else 
    {   
        $conn = null;
        header('Location: connexion.html'); // Captcha non valide
    }

?>