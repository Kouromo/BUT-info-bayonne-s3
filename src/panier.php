<?php
    include('ConnBD.php');
    
    $conn = ConnBD();
    session_start();
 
    // Vérifie si la variable de session "panier" existe
    if (!isset($_SESSION['panier'])) {
        // Si elle n'existe pas, on la crée et on lui affecte une valeur vide
        $_SESSION['panier'] = array();
    }

    // Vérifie si le formulaire a été soumis en utilisant la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupère l'identifiant de l'article dans la variable POST "id"
        $id = $_POST['id'];

        // Ajoute l'identifiant de l'article à la variable de session "panier"
        $_SESSION['panier'][] = array('id' => $id);

        // Supprime les doublons dans le panier
        $_SESSION['panier'] = array_unique($_SESSION['panier'], SORT_REGULAR);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="panier.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <title>Panier</title>
    </head>
    <body>
        <header>
            <button>Vendre ses billets</button>
            <i class="fa-solid fa-user"></i>
            <input id="searchbar" onkeyup="search_ticket()" type="text"
            name="search" placeholder="Search tickets..">
        </header>

        <?php
            if (empty($_SESSION['panier'])) {
                echo "<p>Le panier est vide. Pour ajouter du contenu au panier, cliquez sur un BILLET et sélectionnez \"Ajouter au panier\".</p>";
            }
            else {
                echo '<table>';
                echo '<tr>';
                echo '<th>Titre</th>';
                echo '<th>Genre</th>';
                echo '<th>Prix</th>';
                echo '<th>Supprimer</th>';
                echo '</tr>';
                
                foreach ($_SESSION['panier'] as $article) {
                    //print_r($_SESSION['panier']);
                    
                    // Récupère l'identifiant de l'article dans la variable $id
                    $id = $article['id'];
                    

                    //unset($_SESSION['panier'][$id]);

                    // Requete pour sélectionner toute les informations sur un billet en particulier en fonction de son identifiant
                    $query = "SELECT * FROM Billet WHERE id = '$id';";
                    $result = mysqli_query($conn, $query);

                    // On mets les résultats proprement dans results
                    $results = mysqli_fetch_array($result, MYSQLI_BOTH);

                    // Utilise l'identifiant de l'article pour récupérer les informations de l'article dans le fichier XML
                    $titre = trim($results[1]);

                    $genre = trim($results[3]);

                    $prix = trim($results[2]);

                    // Affiche les informations de l'article dans un tableau
                    echo '<tr>';
                    echo '<td>' . $titre . '</td>';
                    echo '<td>' . $genre . '</td>';
                    echo '<td>' . $prix . '</td>';
                    echo '<td><a href="delete.php?id='.$id.'"><i class="fa-solid fa-trash-alt"></i></a></td>';
                    echo '</tr>';
                }

                echo '</table>';

                echo '<form action="verificationAchat.php" method="post">';
                echo '<section>';
                echo '<label for="numeric_field">Entrez votre numéro de carte :</label><br>';
                echo '<input type="text" name="numeric_field" id="numeric_field" maxlength="16"><br>';
                echo '<label for="csv">Entrez votre CSV :</label><br>';
                echo '<input type="text" name="csv" id="csv" maxlength="3"><br>';
                echo '<label for="dateExpiration">Date d\'expiration :</label><br>';
                echo '<input type="date" name="dateExpiration" id="dateExpiration"><br>';
                echo '</section>';
                echo '<input type="submit" value="Envoyer">';
                echo '</form>';

            }
        ?>
        </main>
    </body>
</html>