<?php
include('ConnBD.php');
$conn = ConnBD();

$pseudo = $_POST['pseudo'];
$pseudo = strip_tags($pseudo);
$email = $_POST['email'];
$email = strip_tags($email);
$password = $_POST['password'];
$password = strip_tags($password);
$confirm_password = $_POST['confirm_password'];
$confirm_password = strip_tags($confirm_password);
$date = $_POST['DATE'];
$date = strip_tags($date);
$pays = $_POST['pays'];
$pays = strip_tags($pays);
$region = $_POST['region'];
$region = strip_tags($region);
$codePostal = $_POST['codePostal'];
$codePostal = strip_tags($codePostal);
$adresse = $_POST['adresse'];
$adresse = strip_tags($adresse);
$codeCaptcha = $_POST['codeCaptcha'];
$codeCaptcha = strip_tags($codeCaptcha);

if (empty($email) || empty($password) || empty($pseudo) || empty($date) || empty($pays) || empty($region) || empty($codePostal) || empty($adresse) || empty($codeCaptcha)) { // Si l'email ou le mot de passe est vide, on redirige vers la page de connexion
    header('Location: connexion.html');
} else {
    if (isset($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha']) {

        // requête qui affiche le nombre d'utilisateur dans la base de données en PDO
        $query = $conn->query("SELECT COUNT(*) FROM Utilisateur");

        // converti le contenu de $query en number
        $nombreUti = $query->fetchColumn();
        $idUti = $nombreUti + 1;

        // convertir $date en DATE SQL
        $date = date('Y-m-d', strtotime($date));
        //var_dump($date);

        // définir la variable suspecte à 0
        $suspecte = 0;

        // regarde s'il existe un mail ou pseudo déjà associé à un utilisateur
        $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE mail = :mail");
        // On lie les données envoyées à la requête
        $stmt->bindParam(':mail', $email);
        // On exécute la requête
        $stmt->execute();
        // On récupère les résultats de la requête
        $mailExist = $stmt->fetch();

        $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE pseudo = :pseudo");
        // On lie les données envoyées à la requête
        $stmt->bindParam(':pseudo', $pseudo);
        // On exécute la requête
        $stmt->execute();
        // On récupère les résultats de la requête
        $pseudoExist = $stmt->fetch();

        if (($mailExist == true) || ($pseudoExist == true)) {
            header('Location: inscription.html');
        } else {
            if ($password == $confirm_password) {

                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sth = $conn->prepare("INSERT INTO Utilisateur (idUti, pseudo, mail, mdp, dateNaiss, pays, region, codePostal, adresse, suspecte) 
                VALUES (:idUti, :pseudo, :mail, :mdp, :dateNaiss, :pays, :region, :codePostal, :adresse, :suspecte);");
                $sth->execute(
                array(
                ':idUti' => $idUti,
                ':pseudo' => $pseudo,
                ':mail' => $email,
                ':mdp' => $hash,
                ':dateNaiss' => $date,
                ':pays' => $pays,
                ':region' => $region,
                ':codePostal' => $codePostal,
                ':adresse' => $adresse,
                ':suspecte' => $suspecte
                )
                );
                $conn = null;
                header('Location: index.php');

            } else {
                header('Location: inscription.html');
            }
        }

    } else {
        header("Location: inscription.html");
    }
}

?>