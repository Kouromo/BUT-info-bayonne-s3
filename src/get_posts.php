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

echo '<h2>Theatre</h2>';
echo '<div style="display: flex;">';
foreach ($theatre as $theatres) {
        echo '<div style="width: 175px; word-wrap: break-word;">';
        echo '<a href="achat.php?id=' . $theatres['id'] . '">';
        echo '<img src="images/theatre.jpg"> <br>';
        echo '<span style="font-size: smaller;">' . $theatres['libelle'] . '</span><br>';
        echo '</a>';
        echo '</div>';
    }
    echo '</div>';
    echo '<h2>Autre</h2>';
    echo '<div style="display: flex;">';
    foreach ($autre as $autres) {
            echo '<div style="width: 175px; word-wrap: break-word;">';
            echo '<a href="achat.php?id=' . $autres['id'] . '">';
            echo '<img src="images/autre.jpg"> <br>';
            echo '<span style="font-size: smaller;">' . $autres['libelle'] . '</span><br>';
            echo '</a>';
            echo '</div>';
        }
  
    echo '</div>';
echo "</section>";
  
  // Fermeture de la connexion à la base de données PDO
    $conn = null; //mysqli_close($conn);

  

  // Renvoi de l'affichage HTML des billets
  echo $html;
?>

        
           
           