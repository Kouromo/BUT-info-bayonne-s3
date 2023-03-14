<?php
    // Connexion à la base de données
    include('ConnBD.php');
    $conn = ConnBD();

    // Requête pour récupérer tous les billets
    $query = "SELECT * FROM Billet;";
    $result = $conn->query($query); //$result = mysqli_query($conn, $query);
    
    // Stockage des billets dans un tableau PHP
    $billets = array();
    while ($row = $result->fetch()) {
        $billets[] = $row;
    }

    $thedate = date('Y-m-d');

    // Initialisation des tableaux pour chaque genre
    $sports = array();
    $concerts = array();
    $festivals = array();
    $theatre = array();
    $autre = array();


    // si variable de session user_id existe, on la stocke dans $id
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    }
?>

<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="main.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <script src="search_tickets.js"></script>
        <title>Tickets'Press</title>
        <script type="text/javascript">
            function refresh_part_of_page() {
                $.ajax({
                    url: 'http://lakartxela.iutbayonne.univ-pau.fr/~qrobert001/s3/avecDesFonctionPartout+Site/index.php',
                    success: function (data) {
                        $('#part_of_page_to_refresh').html(data);
                    }
                });
            }
        </script>
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

                <div>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="searchbar" onkeyup="search_ticket()" type="text"
                    name="search" placeholder="Rechercher">
                </div>
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
                        echo '<button><a href="panier.php?id=' . $id . '">panier</a></button>';
                        echo '</a>';
                    }
                    ?>
                </div>
            </section>
        </header>

        <!-- Contenu principal de la page -->
        <main>
            <!-- Formulaire pour sélectionner la date -->
            <!-- fil d'ariane -->
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
               $linkActu = "Accueil";
               $current_path .= $crumb . '/';
               if ($i == $num_breadcrumbs) {
                   $breadcrumb .= '<a class="breadcrumb-item">' .$linkActu. '</a>';
                   
               } else {
               
                   $breadcrumb .= '<a href="' . $root_domain . $current_path . '" title="' . $page_title . '" class="breadcrumb-item">' . $link . '</a> <a class="breadcrumb-separator">&lt;</a> ';
               }
               $i++;
           }
       
           $breadcrumb .= '</div></div></div>';
           return $breadcrumb;
       }
       echo breadcrumbs();
       

            ?>

        

            <form>
                <label for="date">billet disponible à partir du : </label>
                <!-- Champ pour sélectionner la date -->
                <div>
                    <input type="date" id="dateInput" name="date" value="<?php echo date('Y-m-d'); ?>"
                        min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d', strtotime('+5 year')); ?>">

                </div>
            </form>

            <script>
                document.getElementById("dateInput").addEventListener("change", function (event) {

                    var date = this.value;

                    // Envoi d'une requête AJAX pour récupérer les billets correspondants à la date entrée
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "get_posts.php?date=" + date, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
                            // Mise à jour de l'affichage des billets  
                            document.getElementById("billet").innerHTML = xhr.responseText;
                        }
                    };
                    xhr.send();
                });
            </script>

            <?php
                echo "<section id='billet'>";
                foreach ($billets as $billet) {
                    $genre = trim($billet['genre']);
                    switch ($genre) {
                        case 'sport':
                            $sports[] = $billet;
                            break;

                        case 'concert':
                            $concerts[] = $billet;
                            break;

                        case 'festival':
                            $festivals[] = $billet;
                            break;

                        case 'theatre':
                            $theatre[] = $billet;
                            break;

                        case 'autre':
                            $autre[] = $billet;
                            break;
                    }
                }

                // Affichage des billets pour chaque genre
                echo '<h2>Sports</h2>';
                echo '<div style="display: flex;">';
                foreach ($sports as $sport) {
                    echo '<article style="width: 175px; word-wrap: break-word;">';
                    echo '<a href="achat.php?id=' . $sport['id'] . '">';
                    echo '<img src="images/sport.jpg"> <br>';
                    echo '<span style="font-size: smaller;" >' . $sport['libelle'] . '</span><br>';
                    echo '</a>';
                    echo '</article>';
                }
                echo '</div>';

                echo '<h2>Concerts</h2>';
                echo '<div style="display: flex;">';
                foreach ($concerts as $concert) {
                    echo '<article style="width: 175px; word-wrap: break-word;">';
                    echo '<a href="achat.php?id=' . $concert['id'] . '">';
                    echo '<img src="images/concert.jpg"> <br>';
                    echo '<span style="font-size: smaller;" >' . $concert['libelle'] . '</span><br>';
                    echo '</a>';
                    echo '</article>';
                }

                echo '</div>';

                echo '<h2>Festivals</h2>';
                echo '<div style="display: flex;">';
                foreach ($festivals as $festival) {
                    echo '<article style="width: 175px; word-wrap: break-word;">';
                    echo '<a href="achat.php?id=' . $festival['id'] . '">';
                    echo '<img src="images/festival.jpg"> <br>';
                    echo '<span style="font-size: smaller;" >' . $festival['libelle'] . '</span><br>';
                    echo '</a>';
                    echo '</article>';
                }

                echo '</div>';

                echo '<h2>Theatre</h2>';
                echo '<div style="display: flex;">';
                foreach ($theatre as $theatres) {
                    echo '<article style="width: 175px; word-wrap: break-word;">';
                    echo '<a href="achat.php?id=' . $theatres['id'] . '">';
                    echo '<img src="images/theatre.jpg"> <br>';
                    echo '<span style="font-size: smaller;" >' . $theatres['libelle'] . '</span><br>';
                    echo '</a>';
                    echo '</article>';
                }

                echo '</div>';

                echo '<h2>Autre</h2>';
                echo '<div style="display: flex;">';
                foreach ($autre as $autres) {
                    echo '<article style="width: 175px; word-wrap: break-word;">';
                    echo '<a href="achat.php?id=' . $autres['id'] . '">';
                    echo '<img src="images/autre.jpg"> <br>';
                    echo '<span style="font-size: smaller;" >' . $autres['libelle'] . '</span><br>';
                    echo '</a>';
                    echo '</article>';
                }
                echo '</div>';

                echo "</section>";
            ?>

            <!-- Pied de page -->
            <footer>
            </footer>
    </body>
</html>