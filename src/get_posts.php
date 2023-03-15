
<?php
include('ConnBD.php');
$conn = ConnBD();
    $date = $_GET['date'];

    // Connexion à la base de données

    // Récupération des billets correspondants à la date entrée
    /*$query = "SELECT * FROM Billet WHERE dateExp > '" . $date . "'";
    $result = mysqli_query($conn, $query);*/
    $stmt = $conn->prepare("SELECT * FROM Billet WHERE dateExp > :date");

    $stmt->execute(array(':date' => $date)); // $billets = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC /*$row = mysqli_fetch_assoc($result)*/)) {
        $billets[] = $row;
    }




    $sports = array();
    // trier les sports en PDO


    $concerts = array();
    $festivals = array();
    $theatre = array();
    $autre = array();
    
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
        echo '<a href="achat.php?id=' . $sport['id'] . '">';
        echo '<article style="width: 175px; word-wrap: break-word;">';
        echo '<img src="images/sport.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $sport['libelle'] . '</span><br>';
        echo '</article>';
        echo '</a>';
    }
    echo '</div>';

    echo '<h2>Concerts</h2>';
    echo '<div style="display: flex;">';
    foreach ($concerts as $concert) {
        echo '<a href="achat.php?id=' . $concert['id'] . '">';
        echo '<article style="width: 175px; word-wrap: break-word;">';
        echo '<img src="images/concert.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $concert['libelle'] . '</span><br>';
        echo '</article>';
        echo '</a>';
    }
    echo '</div>';

    echo '<h2>Festivals</h2>';
    echo '<div style="display: flex;">';
    foreach ($festivals as $festival) {
        echo '<a href="achat.php?id=' . $festival['id'] . '">';
        echo '<article style="width: 175px; word-wrap: break-word;">';
        echo '<img src="images/festival.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $festival['libelle'] . '</span><br>';
        echo '</article>';
        echo '</a>';
    }
    echo '</div>';

    echo '<h2>Theatre</h2>';
    echo '<div style="display: flex;">';
    foreach ($theatre as $theatres) {
        echo '<a href="achat.php?id=' . $theatres['id'] . '">';
        echo '<article style="width: 175px; word-wrap: break-word;">';
        echo '<img src="images/theatre.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $theatres['libelle'] . '</span><br>';
        echo '</article>';
        echo '</a>';
    }
    echo '</div>';

    echo '<h2>Autre</h2>';
    echo '<div style="display: flex;">';
    foreach ($autre as $autres) {
        echo '<a href="achat.php?id=' . $autres['id'] . '">';
        echo '<article style="width: 175px; word-wrap: break-word;">';
        echo '<img src="images/autre.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $autres['libelle'] . '</span><br>';
        echo '</article>';
        echo '</a>';
    }
    echo '</div>';

    echo "</section>";

    echo '<script src="search_tickets.js"></script>';

    // Fermeture de la connexion à la base de données PDO
    $conn = null; //mysqli_close($conn);
  
  

    // Renvoi de l'affichage HTML des billets
    echo $html;
?>

        
           
           