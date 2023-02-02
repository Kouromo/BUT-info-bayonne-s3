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
        <!-- Entête de la page -->
        <header>
        </header>

        <!-- Contenu principal de la page -->
        <main>
            <!-- Formulaire pour sélectionner la date -->
            <form>
                <label for="DATE">Date: </label>
                <!-- Champ pour sélectionner la date -->
                <div><input type="date" id="DATE" name="DATE"
                    value="<?php echo $thedate=date('Y-m-d'); ?>"
                    min="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d', strtotime('+5 year'));?>"></div>
            </form>
        </main>

        <!-- Pied de page -->
        <footer>
        </footer>
    </body>
</HTML>



<?php
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
  echo '<div style="width: 175px; word-wrap: break-word;">';
  echo '<a href="achat.php?id=' . $sport['id'] . '">';
  echo '<img src="images/sport.jpg"> <br>';
  echo '<span style="font-size: smaller;">' . $sport['libelle'] . '</span><br>';
  echo '</a>';
  echo '</div>';
}
echo '</div>';

echo '<h2>Concerts</h2>';
echo '<div style="display: flex;">';
foreach ($concerts as $concert) {
  echo '<div style="width: 175px; word-wrap: break-word;">';
  echo '<a href="achat.php?id=' . $concert['id'] . '">';
  echo '<img src="images/concert.jpg"> <br>';
  echo '<span style="font-size: smaller;">' . $concert['libelle'] . '</span><br>';
  echo '</a>';
  echo '</div>';
}
echo '</div>';

echo '<h2>Festivals</h2>';
echo '<div style="display: flex;">';
foreach ($festivals as $festival) {
  echo '<div style="width: 175px; word-wrap: break-word;">';
  echo '<a href="achat.php?id=' . $festival['id'] . '">';
  echo '<img src="images/festival.jpg"> <br>';
  echo '<span style="font-size: smaller;">' . $festival['libelle'] . '</span><br>';
  echo '</a>';
  echo '</div>';
}
echo '</div>';


?>


