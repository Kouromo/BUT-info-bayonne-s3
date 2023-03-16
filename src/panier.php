<?php

/**
 * Ajoute un article au panier
 * @file panier.php
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @brief Affiche le contenu du panier et permet de faire la transaction
 * @date 2023-02-21
 */

include('ConnBD.php');
$conn = ConnBD();

$idUtilisateur = $_SESSION['user_id'];
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
            <section id="headGauche">
                <?php
                    echo '<a href="vente.php?id=' . $idUtilisateur . '"><button>Vendre ses billets</button></a>';
                ?>
            </section>
            <section id="headDroite">
                <div>
                    <?php

                    if (isset($_GET['error'])) {
                        echo '<div id="banniereErreur">';
                        if ($_GET['error'] == 'date') {
                            echo '<p class="erreur">La date d\'expiration de la carte</p>';
                        } elseif ($_GET['error'] == 'captcha') {
                            echo '<p class="erreur">Captcha non valide</p>';
                        }
                        echo '</div>';
                    }



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
                    ?>
                </div>
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
                    
                        $breadcrumb .= '<a class="breadcrumb-item">' .$link. '</a>';
                        
                    } else {

                        if ($i == $num_breadcrumbs-1) {
                            $breadcrumb .= '<a href="' . $root_domain . $current_path . '" title="' . $page_title . '" class="breadcrumb-item">' .$linkPrecedent. '</a> <a class="breadcrumb-separator">&lt;</a> ';
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

            
            <?php
                if (empty($_SESSION['panier'])) {
                    echo "<p>Le panier est vide. Pour ajouter du contenu au panier, cliquez sur un BILLET et sélectionnez \"Ajouter au panier\".</p>";
                } else {
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
                        $result = $conn->query($query); // $result = mysqli_query($conn, $query);
                
                        // On mets les résultats proprement dans results
                        $results = $result->fetch(); // $results = mysqli_fetch_array($result, MYSQLI_BOTH);
                
                        // Utilise l'identifiant de l'article pour récupérer les informations de l'article dans le fichier XML
                        $titre = trim($results[1]);

                        $genre = trim($results[3]);

                        $prix = trim($results[2]);

                        // Affiche les informations de l'article dans un tableau
                        echo '<tr>';
                        echo '<td>' . $titre . '</td>';
                        echo '<td>' . $genre . '</td>';
                        echo '<td>' . $prix . '</td>';
                        echo '<td><a href="delete.php?id=' . $id . '"><i class="fa-solid fa-trash-alt"></i></a></td>';
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
                    echo '<input type="date" name="dateExpiration" id="dateExpiration"><br><br>';
                    echo '</section>';
                    echo '<div class="verifCaptcha">';
                    echo '<label for="codeCaptcha">Vérification :</label><br>';
                    echo '<img src="generationCaptchaUAttempt.php" alt="CodeDuCaptcha"></img><br>';
                    echo '<input id="refresh" type="button" value="Refresh Captcha" onClick="location.href=location.href"><br>';
                    echo '<input type="txt" name="codeCaptcha" placeholder="Code du Captcha" maxlength="10" required>';
                    echo '</div>';
                    echo '<input type="submit" value="Envoyer">';
                    echo '</form>';
                }
            ?>
        </main>
    </body>
</html>