<?php
include('ConnBD.php');
$conn = ConnBD();
  $date = $_GET['date'];

  // Connexion à la base de données

  // Récupération des billets correspondants à la date entrée
  $query = "SELECT * FROM Billet WHERE dateExp > '" . $date . "'";
  $result = mysqli_query($conn, $query);
  $billets = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $billets[] = $row;
    }
    $sports = array();
$concerts = array();
$festivals = array();
  // Construction de l'affichage HTML des billets
  $html = "";

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

echo "</section>";
  

  // Fermeture de la connexion à la base de données
  mysqli_close($conn);

  // Renvoi de l'affichage HTML des billets
  echo $html;
?>

        
           
           