<?php
    include('ConnBD.php');
    $conn = ConnBD();

    $idUti = $_SESSION['user_id'];

    // Vérifie si la variable de session "panier" existe
    if (!isset($_SESSION['panier'])) {
        // Si elle n'existe pas, on la crée et on lui affecte une valeur vide
        $_SESSION['panier'] = array();
    }

    // Vérifie si le formulaire a été soumis en utilisant la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $date = $_POST['date'];
        $quantite = $_POST['quantite'];
        // converti la quantite en int
        $quantite = intval($quantite);
        $prix = $_POST['prix'];
        // converti le prix en float
        $prix = floatval($prix);
        $genre = $_POST['genre'];
        $categorie = $_POST['categorie'];
        $lieu = $_POST['lieu'];

        if ($prix < 0 || !is_float($prix)) {
            echo "Le prix doit être un nombre positif";
            $erreur = true;
        }
        else if ($quantite < 0 || !is_int($quantite)) {
            echo "La quantité doit être un nombre positif";
            $erreur = true;
        }
        else if ($date < date("Y-m-d")) {
            echo "La date ne peut pas être antérieur à la date actuelle";
            $date = "";
        }

        if (!(isset($erreur))) {
            // Récupère le plus grand id de la base de donnée
            $query = "SELECT MAX(id) FROM Billet";
            $result = $conn->query($query);
            $results = $result->fetch();

            // incrémente de 1 la variable results
            $results[0]++;
            $id = $results[0];

            $sth = $conn->prepare("INSERT INTO Billet (id, libelle, prix, genre, categorie, quantite, description, dateExp, lieuEvenement, idUti) 
            VALUES (:id, :titre, :prix, :genre, :categorie, :quantite, :description, :date, :lieu, :idUti)");

            $sth->execute(array(
                ':id' => $id,
                ':titre' => $titre,
                ':prix' => $prix,
                ':genre' => $genre,
                ':categorie' => $categorie,
                ':quantite' => $quantite,
                ':description' => $description,
                ':date' => $date,
                ':lieu' => $lieu,
                ':idUti' => $idUti
            ));

            header('Location: index.php');
        }
    }
?>

<!DOCTYPE html>
<HTML>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="vente.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <title>Tickets'Press</title>
    </head>

    <body>
    <header>
            <section id = "headGauche">
                <?php
                    // si je suis pas connecté, renvoie à la page connexion.html, sinon renvoie à la page de vente
                    if (!isset($id)) {
                        echo '<button><a href="connexion.html">Vendre ses billets</a></button>';
                    } else {
                        echo '<button><a href="vente.php?id=' . $id . '">Vendre ses billets</a></button>';
                    }
                ?>
            </section>
            <section id="headDroite">
                <div>
                    <?php
                        if (empty($_SESSION['user_id']) == true) { // Utilisateur non connecté
                            echo '<a href="connexion.html">';
                            echo '<i class="fa-solid fa-user"></i>';
                            echo '<label for="user">Se connecter</label>';
                            echo '</a>';
                        } else { // Utilisateur connecté
                            $idUtilisateur = $_SESSION['user_id'];

                            $stmt = $conn->prepare("SELECT pseudo FROM Utilisateur WHERE idUti = :idUti;");
                            // On lie les données envoyées à la requête
                            $stmt->bindParam(':idUti', $idUtilisateur);
                            // On exécute la requête
                            $stmt->execute();
                            // On récupère les résultats de la requête
                            $pseudoUser = $stmt->fetch();

                            echo '<a href="#">';
                            echo '<i class="fa-solid fa-user"></i>';
                            echo '<label for="user">' . $pseudoUser['pseudo'] . '</label>';
                            echo '</a>';
                        }
                    ?>
                </div>
            </section>
        </header>

        <main>
            <?php
                echo "<form action='vente.php?id=$idUti' method='POST'>";
                echo '<label for="titre">Titre</label>';
                echo '<input type="text" name="titre" id="titre" required>';

                echo '<label for="description">Description</label>';
                echo '<input type="text" name="description" id="description" required>';

                echo '<label for="categorie">Catégorie</label>';
                echo '<input type="text" name="categorie" id="categorie" placeholder="exemple: RAP Français" required>';
                
                echo '<label for="date">Date</label>';
                $dayDate = date("Y-m-d");
                echo '<input type="date" name="date" id="date" min="' . $dayDate . '" required>';


                echo '<label for="quantite">Quantité</label>';
                echo '<input type="number" name="quantite" id="quantite" required>';


                echo '<label for="prix">Prix</label>';
                echo '<input type="text" name="prix" id="prix" placeholder="exemple: 50.28" required>';

                echo '<label for="lieu">Lieu</label>';
                echo '<input type="text" name="lieu" id="lieu" placeholder="exemple: 3 avenue Gordon-Bennett, 75016 France" required>';

                echo '<label for="genre">Genre</label>';
                echo '<div id="genre">';
                echo '<input type="radio" name="genre" id="genre1" value="concert" required>Concert';
                echo '<input type="radio" name="genre" id="genre2" value="theatre" required>Théâtre';
                echo '<input type="radio" name="genre" id="genre3" value="sport" required>Sport';
                echo '<input type="radio" name="genre" id="genre4" value="festival" required>Festival';
                echo '<input type="radio" name="genre" id="genre5" value="autre" required>Autre';
                echo '</div>';

                echo '<input type="submit" value="Vendre">';
                echo '</form>';
            ?>            
        </main>
    </body>
</HTML>