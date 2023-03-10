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

            
            $stmt = $conn->prepare("DELETE FROM Billet WHERE id = :id AND quantite = 0");
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            // Insère le billet dans la table vente
            //$date = date("Y-m-d");

            // récupérer le nombre de billet vendu
            //$query = $conn->query("SELECT COUNT(*) FROM Vente");
            // prépare la requête qui affiche le nombre de billet vendu dans la base de données en PDO
            /*$query = $conn->prepare("SELECT COUNT(*) FROM Vente");
            $query->execute();

            // converti le contenu de $query en number
            
            $idVente = ($query->fetchColumn()) + 1;
            var_dump($idVente);

            //insertion dans la table Vente
            $stmt = $conn->prepare("INSERT INTO Vente (idVente, dateVente, prix, idBillet, idAcheteur) VALUES (:idVente, :dateVente, :prix, :idBillet, :idAcheteur)");
            $stmt->bindParam(':idVente', $idVente);
            $stmt->bindParam(':dateVente', $date);
            $stmt->bindParam(':prix', $article['prix']);
            $stmt->bindParam(':idBillet', $id);
            $stmt->bindParam(':idAcheteur', $_SESSION['user_id']);
            $stmt->execute();*/


            // supprime le billet en question de la Base de donnée si la quantitité est égale à 0

            
        }
        // On supprime le panier
        unset($_SESSION['panier']);
        //header('Location: index.php');
        

    } else {
        // Si la date d'expiration est inférieure à la date actuelle, on redirige vers la page de connexion
        header('Location: panier.php');
    }
} else {
    // Si le code captcha est mauvais, on redirige vers la page de connexion
    header('Location: panier.php');
}
?>