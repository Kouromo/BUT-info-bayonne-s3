<?php

    

/**
 * @file verificationAchat.php
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @brief Vérifie que les champs saisis sont corrects
 * @date 2023-02-02
 * @detail Si les champs sont corrects, on enregistre la vente dans la base de données et on retire un à la quantité de l'article sinon on affiche un message d'erreur et on redirige vers la page d'accueil
 */
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

                // envoie des mails de confirmation de l'achat et de la vente
                $stmt = $conn->prepare("SELECT idUti, libelle FROM Billet WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->execute();
                $result = $stmt->fetch(); // Récupère la première ligne de résultat sous forme de tableau associatif
                $idUti = $result['idUti'];
                $libelle = $result['libelle'];
                $stmt->closeCursor();


                $stmt = $conn->prepare("SELECT mail FROM Utilisateur WHERE idUti = :id");
                $stmt->bindParam(':id', $idUti);
                $stmt->execute();
                $emailVendeur = $stmt->fetchColumn();
                $stmt->closeCursor();
                $toV = $emailVendeur;

                $stmt = $conn->prepare("SELECT mail FROM Utilisateur WHERE idUti = :id");
                $stmt->bindParam(':id', $_SESSION['user_id']);
                $stmt->execute();
                $emailAcheteur = $stmt->fetchColumn();
                $stmt->closeCursor();
                $toA = $emailAcheteur;
                $subjectA = "Confirmation d'achat";
                $messageA = "Bonjour,

Votre achat a bien été effectué, vous pouvez contacter le vendeur à l'email suivant afin de récupérer les informations du billet :  $emailVendeur

L'équipe Tickets'Press vous remercie pour votre confiance.

Cordialement,
L'équipe Tickets'Press";
                $headersA = "From: ticketspressofficial@gmail.com";
                mail($toA, $subjectA, $messageA, $headersA);

                
                $subjectV = "Vente de votre billet !";
                $messageV = "Bonjour,

J'ai le plaisir de vous annoncer que votre billet pour $libelle a été acheté par l'utilisateur $emailAcheteur !
Vous pouvez le contacter dès à présent pour lui donner les informations du billet.

Cordialement,
L'équipe Tickets'Press";
                $headersV = "From: ticketspressofficial@gmail.com";
                mail($toV, $subjectV, $messageV, $headersV);
            }
            // On supprime le panier
            unset($_SESSION['panier']);
            
            

            header('Location: index.php');
            
        }

        else {
            // Si la date d'expiration est inférieure à la date actuelle, on redirige vers la page de connexion
            header('Location: panier.php');
        }
    }
    else {
        // Si le code captcha est mauvais, on redirige vers la page de connexion
        header('Location: panier.php');
    }

?>