<?php

include('ConnBD.php');
$conn = ConnBD();

$codeCaptcha = $_POST['codeCaptcha'];
$dateExpiration = $_POST['dateExpiration'];
/*récupération des informations CB*/

if (isset($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha']) {
    if ($dateExpiration > date("Y-m-d")) {
        //header('Location: index.php');*/

        // Date du jour
        $date = date("Y-m-d");
        foreach ($_SESSION['panier'] as $article) {

            $id = $article['id'];
            // prépare la requête qui retire un à la quantité de l'article dans la base de données en PDO
            $stmt = $conn->prepare("UPDATE Billet SET quantite = quantite - 1 WHERE id = :id");
            $stmt->bindParam(':id', $id);
            // On exécute la requête
            $stmt->execute();
            $stmt->closeCursor();

            // supprime le billet en question de la Base de donnée si la quantitité est égale à 0
            /*$stmt = $conn->prepare("DELETE FROM Billet WHERE id = :id AND quantite = 0");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $stmt->closeCursor();*/

            // prépare la requête qui affiche le nombre de billet vendu dans la base de données en PDO
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Vente");
            $stmt->execute();
            // converti le contenu de $query en number
            $idVente = ($stmt->fetchColumn()) + 1;
            $stmt->closeCursor();
            
            // récupère le prix du billet en question
            $stmt = $conn->prepare("SELECT prix FROM Billet WHERE id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $prix = $stmt->fetchColumn();
            $stmt->closeCursor();
            
            //insertion dans la table Vente
            $stmt = $conn->prepare("INSERT INTO Vente (idVente, dateVente, prix, idBillet, idAcheteur) VALUES (:idVente, :dateVente, :prix, :idBillet, :idAcheteur)");
            $stmt->bindParam(':idVente', $idVente);
            $stmt->bindParam(':dateVente', $date);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':idBillet', $id);
            $stmt->bindParam(':idAcheteur', $_SESSION['user_id']);
            $stmt->execute();
            $stmt->closeCursor();
            
        }
        // On supprime le panier
        unset($_SESSION['panier']);
        header('Location: index.php');
        

    } else {
        // Si la date d'expiration est inférieure à la date actuelle, on redirige vers la page de connexion
        header('Location: panier.php?error=date');
    }
} else {
    // Si le code captcha est mauvais, on redirige vers la page de connexion
    header('Location: panier.php?error=captcha');
}
?>