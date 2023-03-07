<?php
    include('ConnBD.php');
    $conn = ConnBD();

    $id = $_GET['id'];

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
    }
?>

<!DOCTYPE html>
<HTML>

    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="achat.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <title>Tickets'Press</title>
    </head>



    <body>
        <header>
            <button>Vendre ses billets</button>
            <i class="fa-solid fa-user"></i>
        </header>
        <?php
  function breadcrumbs($home = 'Home') {
    global $page_title;
    $breadcrumb  = '<div class="breadcrumb-container"><div class="container"><ol class="breadcrumb">';
    $root_domain = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'].'/';
    $breadcrumbs = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    $breadcrumb .= '<li><i class="fa fa-home"></i><a href="' . $root_domain . '" title="Home Page"><span>' . $home . '</span></a></li>';
    $num_breadcrumbs = count($breadcrumbs);
    $i = 1;
    foreach ($breadcrumbs as $crumb) {
        $link = ucwords(str_replace(array(".php","-","_"), array(""," "," "), $crumb));
        $root_domain .=  $crumb . '/';
        if ($i == $num_breadcrumbs) {
            $breadcrumb .= '<li><span>' . $link . '</span></li>';
        } else {
            $breadcrumb .= '<li><a href="'. $root_domain .'" title="'.$page_title.'"><span>' . $link . '</span></a></li>';
        }
        $i++;
    }
    $breadcrumb .= '</ol></div></div>';
    return $breadcrumb;
}
echo breadcrumbs();         
            

?>
        <main>
            <!-- Contenu de la page -->
            <?php
                echo "<section>";
                // id de test pour vérifier si cela fonctionne
                
                //$id = $_SESSION['id'];
                //$id = 'J23C4';
                

                // Requete pour sélectionner toute les informations sur un billet en particulier en fonction de son identifiant
                $query = "SELECT * FROM Billet WHERE id = '$id';";
                //exécute la requête en PDO
                $result = $conn->query($query); //$result = mysqli_query($conn, $query);

                // On mets les résultats proprement dans results
                // convertie la requête en PDO
                $results = $result->fetch(); //$results = mysqli_fetch_array($result, MYSQLI_BOTH);

                // On initialise une variable image qui sera le chemin d'accès à l'image correspondant à l'id
                $image = 'Images/' . trim($results[3]) . '.jpg';

                // Vérification si une variable nommée $image existe, et si c'est le cas, affiche une image
                if(isset($image)){
                    echo "<div class='image-container'>";
                    echo '<img class="image" src="'.$image.'" alt="Texte alternatif"></a>';
                    echo '</div>';
                }
                // On affiche le titre du billet
                echo "<div class='info-container'>";
                echo "<h2 class='info'>" . trim($results[1]) . '</h2>';
                
                // Requete pour sélectionner le pseudo de l'utilisateur qui a mis en vente le billet en se servant de son identifiant
                $queryUser = "SELECT pseudo FROM Utilisateur WHERE idUti = '$results[9]';";
                // exécute la requête en PDO
                $resultUser = $conn->query($queryUser); //$resultUser = mysqli_query($conn, $queryUser);

                // exécute la requête en PDO
                $resultsUser = $resultUser->fetch(); // $resultsUser = mysqli_fetch_array($resultUser, MYSQLI_BOTH);
                
                // Affichage du nom d'utilisateur qui vend le billet
                echo "<p class='info'>" . trim($resultsUser[0]) . '</p>';

                // $id2 = 'a004';

                // Requete pour sélectionner la moyenne des avis sur un utilisateur en se servant de son identifiant
                $queryAvis = "SELECT AVG(noteAvis) FROM Avis WHERE idReceveur = '$results[9]';"; // $queryAvis = "SELECT AVG(noteAvis) FROM Avis WHERE idReceveur = '$id2';";

                $resultAvis = $conn->query($queryAvis); //$resultAvis = mysqli_query($conn, $queryAvis);

                $resultsAvis = $resultAvis->fetch(); //$resultsAvis = mysqli_fetch_array($resultAvis, MYSQLI_BOTH);
                
                if (is_numeric(trim($resultsAvis[0]))) {
                    echo "<div class='icon-container'>";
                    for ($i=1; $i <= trim($resultsAvis[0]); $i++) { 
                        echo "<i class='fa-solid fa-star'></i>";
                    }

                    if (fmod(trim($resultsAvis[0]), 0.5) == 0 && intval(trim($resultsAvis[0])) != trim($resultsAvis[0])) {
                        echo "<i class='fa-solid fa-star-half-stroke'></i>";
                    }

                    for ($i=trim($resultsAvis[0]); $i < 4.5; $i++) {
                        if (trim($resultsAvis[0]) != 5) {
                            echo "<i class='fa-regular fa-star'></i>";
                        }
                    }
                    echo '</div>';
                }
                
                // echo "<p class='info'>" . trim($resultsAvis[0]) . ' étoiles</p>';

                echo "<p class='info'>" . trim($results[6]) . '</p>';
                echo '</div>';

                // Vérification si une variable nommée $image existe, et si c'est le cas, affiche une image
                /*if(isset($image)){
                    echo '<img class="image" src="'.$image.'" alt="Texte alternatif"></a>';
                }*/

                // Affichage du prix du billet
                echo "<div class='price-date-container'>";
                echo "<p>Prix</p>";
                echo "<p class='price-date'>" . trim($results[2]) . '€</p>';
                
                $thedate = trim($results[7]);

                /*echo "<input type='date' id='DATE' name='DATE'
                value='$thedate'
                min='$thedate'
                max='$thedate'></div>";*/

                $date = date("d-m-Y", strtotime($thedate));
                echo "<div class='price-date'>";
                echo "<p>Date de l'événement</p>";
                echo "<div class='date'>$date</div>";
                //echo "<div class='date'><i class='fa-solid fa-calendar-days'></i></div>";
                echo '</div>';
                
                echo '</div>';
                
                echo "</section>";

                // Bouton "Ajouter au panier"
                echo '<form action="panier.php" method="post">';
                echo '<input type="hidden" name="id" value="' . $id . '">';
                echo '<input type="submit" value="Ajouter au panier">';
                echo '</form>';
            ?>
        </main>
    </body>
</HTML>