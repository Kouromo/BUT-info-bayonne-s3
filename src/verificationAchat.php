<?php

include('ConnBD.php');
$conn = ConnBD();

$codeCaptcha = $_POST['codeCaptcha'];
$dateExpiration = $_POST['dateExpiration'];
/*récupération des informations CB*/

if (isset($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha']) {
    if ($dateExpiration > date("Y-m-d")) {
        header('Location: index.php');
        foreach ($_SESSION['panier'] as $article) {
            $id = $article['id'];
            // prépare la requête qui retire un à la quantité de l'article dans la base de données en PDO
            $stmt = $conn->prepare("UPDATE Billet SET quantite = quantite - 1 WHERE id = :id");
            $stmt->bindParam(':id', $id);
            // On exécute la requête
            $stmt->execute();

            $stmt = $conn->prepare("SELECT * FROM Billet WHERE id = :id AND quantite = 0");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            // si la requête retourne un résultat, on supprime l'article de la base de données
            if ($stmt->rowCount() > 0) {
                $stmt = $conn->prepare("DELETE FROM Billet WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
            }
            // On supprime le panier
            unset($_SESSION['panier']);
            header('Location: index.php');
            
        }

    } else {
        // Si la date d'expiration est inférieure à la date actuelle, on redirige vers la page de connexion
        header('Location: panier.php');
    }
} else {
    // Si le code captcha est mauvais, on redirige vers la page de connexion
    header('Location: panier.php');
}
?>