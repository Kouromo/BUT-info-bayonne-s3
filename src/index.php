<?php 
// Connexion à la base de données
include('ConnBD.php');
$conn = ConnBD();
session_start();

// Requête pour récupérer tous les billets
$query = "SELECT * FROM Billet;";
$result = mysqli_query($conn, $query);

// Stockage des billets dans un tableau PHP
$billets = array();
while ($row = mysqli_fetch_assoc($result)) {
  $billets[] = $row;
}

// Initialisation des tableaux pour chaque genre
$sports = array();
$concerts = array();
$festivals = array();

// Parcours des billets et classement selon leur genre
?>
<!DOCTYPE html>
<HTML>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="main.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <title>Tickets'Press</title>
    </head>
    <body>
    <header>
            <section id = "headGauche">
                <button>Vendre ses billets</button>
                <div>
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input id="searchbar" onkeyup="search_ticket()" type="text"
                    name="search" placeholder="Rechercher">

                </div>
            </section>
            <section id = "headDroite">
                <div>
                    <i class="fa-solid fa-user"></i>
                </div>
            </section>
        </header>

        <!-- Contenu principal de la page -->
        <main>
            <!-- Formulaire pour sélectionner la date -->
            <form>
                <label for="date">Date: </label>
                <!-- Champ pour sélectionner la date -->
                <div><input type="date" id="date" name="date" onclick="refresh_part_of_page()"
                value="<?php echo $thedate=date('Y-m-d'); ?>"
                min="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d', strtotime('+5 year'));?>"></div>
            </form>
    <script type="text/javascript" src="date.js"></script>
    <?php include 'check_date.php'; ?>    
    </body>
</HTML>
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
                    }
                }


                // Affichage des billets pour chaque genre
                echo '<h2>Sports</h2>';
                echo '<div style="display: flex;">';
                foreach ($sports as $sport) {
                    if (strtotime($sport['dateExp']) >= strtotime($thedate)) {
                        echo '<div style="width: 175px; word-wrap: break-word;">';
                        echo '<a href="achat.php?id=' . $sport['id'] . '">';
                        echo '<img src="images/sport.jpg"> <br>';
                        echo '<span style="font-size: smaller;">' . $sport['libelle'] . '</span><br>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                echo '</div>';

                echo '<h2>Concerts</h2>';
                echo '<div style="display: flex;">';
                foreach ($concerts as $concert) {
                    if (strtotime($concert['dateExp']) >= strtotime($thedate)) {
                        echo '<div style="width: 175px; word-wrap: break-word;">';
                        echo '<a href="achat.php?id=' . $concert['id'] . '">';
                        echo '<img src="images/concert.jpg"> <br>';
                        echo '<span style="font-size: smaller;">' . $concert['libelle'] . '</span><br>';
                        echo '</a>';
                        echo '</div>';
                    }
                }

                echo '</div>';

                echo '<h2>Festivals</h2>';
                echo '<div style="display: flex;">';
                foreach ($festivals as $festival) {
                    if (strtotime($festival['dateExp']) >= strtotime($thedate)) {
                        echo '<div style="width: 175px; word-wrap: break-word;">';
                        echo '<a href="achat.php?id=' . $festival['id'] . '">';
                        echo '<img src="images/festival.jpg"> <br>';
                        echo '<span style="font-size: smaller;">' . $festival['libelle'] . '</span><br>';
                        echo '</a>';
                        echo '</div>';
                    }
                }
                echo '</div>';

                echo "</section>";

            ?>
        </main>

        <!-- Pied de page -->
        <footer>
            <? echo "<section id='billet'> $thedate </div>"; ?>
        </footer>
        




