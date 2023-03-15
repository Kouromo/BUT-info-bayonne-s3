<?php
/**
 * @file vente.php
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @brief Fichier contenant le formulaire de vente
 * @details Structure du formulaire de vente
 * @date 2023-01-16
 */
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

    if ($prix > 100000) {
        echo "Le prix ne peut pas être supérieur à 100 000";
        $erreur = true;
    } else if ($prix < 0 || !is_float($prix)) {
        echo "Le prix doit être un nombre positif";
        $erreur = true;
    } else if ($quantite < 0 || !is_int($quantite)) {
        echo "La quantité doit être un nombre positif";
        $erreur = true;
    } else if ($date < date("Y-m-d")) {
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

        $sth->execute(
            array(
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
            )
        );

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
        <section id="headGauche">
            <?php
            // si je suis pas connecté, renvoie à la page connexion.php, sinon renvoie à la page de vente
            if (!isset($id)) {
                echo '<a href="connexion.php"><button>Vendre ses billets</button></a>';
            } else {
                echo '<a href="vente.php?id=' . $id . '"><button>Vendre ses billets</button></a>';
            }
            ?>
        </section>
        <section id="headDroite">
            <?php
            if (empty($_SESSION['user_id']) == true) { // Utilisateur non connecté
                echo '<a href="connexion.php">';
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
                echo '<a href="panier.php?id=' . $idUti . '"><button id="buttonPanier">Panier</button></a>';
                echo '</a>';
            }
            ?>
        </section>
    </header>
    <?php
    function breadcrumbs($homes = 'Home')
    {
        global $page_title;
        $breadcrumb = '<div class="breadcrumb-container"><div class="container"><div class="breadcrumb">';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            // Le site est accessible via HTTPS
            $root_domain = 'https://' . $_SERVER['HTTP_HOST'] . '/';
        } else {
            // Le site est accessible via HTTP ou en local
            $root_domain = 'http://' . $_SERVER['HTTP_HOST'] . '/';
        }

        $breadcrumbs = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
        $current_path = '';

        $num_breadcrumbs = count($breadcrumbs);
        $i = 1;
        foreach ($breadcrumbs as $crumb) {
            $link = ucwords(str_replace(array(".php", "-", "_"), array("", " ", " "), $crumb));
            $linkPrecedent = "Accueil";
            $current_path .= $crumb . '/';
            if ($i == $num_breadcrumbs) {

                $breadcrumb .= '<a class="breadcrumb-item">' . $link . '</a>';

            } else {

                if ($i == $num_breadcrumbs - 1) {
                    $breadcrumb .= '<a href="' . $root_domain . $current_path . '" title="' . $page_title . '" class="breadcrumb-item">' . $linkPrecedent . '</a> <a class="breadcrumb-separator">&lt;</a> ';
                }
                //ligne à décomenter en cas d'utilisation sur le web
                /* else{
                $breadcrumb .= '<a href="' . $root_domain . $current_path . '" title="' . $page_title . '" class="breadcrumb-item">' . $link . '</a> <a class="breadcrumb-separator">&lt;</a> ';
                }*/
            }
            $i++;
        }

        $breadcrumb .= '</div></div></div>';
        return $breadcrumb;
    }
    echo breadcrumbs();
    ?>
    <main>
        <?php
        echo "<form action='vente.php?id=$idUti' method='POST'>";
        echo "<h1>Vendre ses billets</h1>";
        echo '<label for="titre">Titre</label>';
        echo '<input type="text" maxlength="200" name="titre" id="titre" required>';

        echo '<label for="description">Description</label>';
        echo '<input type="text" maxlength="1000" name="description" id="description" required>';

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
        echo '<input type="text" maxlength="100" name="lieu" id="lieu" placeholder="exemple: 3 avenue Gordon-Bennett, 75016 France" required>';

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