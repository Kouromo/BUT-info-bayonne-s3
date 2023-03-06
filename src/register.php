<?php
    include('ConnBD.php');
    $conn = ConnBD();

    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $date = $_POST['DATE'];
    $pays = $_POST['pays'];
    $region = $_POST['region'];
    $codePostal = $_POST['codePostal'];
    $adresse = $_POST['adresse'];
    $codeCaptcha = $_POST['codeCaptcha'];

    if (empty($email) || empty($password) || empty($pseudo) || empty($date) || empty($pays) || empty($region) || empty($codePostal) || empty($adresse) || empty($codeCaptcha)) { // Si l'email ou le mot de passe est vide, on redirige vers la page de connexion
        header('Location: connexion.html');
    }
    else {
        // requête qui affiche le nombre d'utilisateur dans la base de données en PDO
        $query = $conn->query("SELECT COUNT(*) FROM Utilisateur");

        // converti le contenu de $query en number
        $nombreUti = $query->fetchColumn();

        // Préfixe de l'ID utilisateur
        define('ID_PREFIX', 'a00');
        $idUti = ID_PREFIX . $nombreUti;
        // $idUti = a00 + le nombre d'utilisateur dans la base de données
        //$idUti = "a00" . $nombreUti;

        // convertir $date en DATE SQL
        $date = date('Y-m-d', strtotime($date));

        // définir la variable suspecte à 0
        $suspecte = 0;
        

        if ($password == $confirm_password){
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO Utilisateur (idIti, pseudo, mail, mdp, dateNaissance, pays, region, codePostal, adresse, suspecte) VALUES (:idUti, :pseudo, :mail, :mdp, :dateNaissance, :pays, :region, :codePostal, :adresse, :suspecte)");
            $stmt->bindParam(':idUti', $idUti);
            $stmt->bindParam(':pseudo', $pseudo);
            $stmt->bindParam(':mail', $email);
            $stmt->bindParam(':mdp', $hash);
            $stmt->bindParam(':dateNaissance', $date);
            $stmt->bindParam(':pays', $pays);
            $stmt->bindParam(':region', $region);
            $stmt->bindParam(':codePostal', $codePostal);
            $stmt->bindParam(':adresse', $adresse);
            $stmt->bindParam(':suspecte', $suspecte);
            $stmt->execute();
            $conn = null;
            header('Location: index.php');
        }
        else{
            header('Location: inscription.html');
            exit();
    }
    }

?>