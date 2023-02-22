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

    // Parcours des billets et classement selon leur genre

?>

<!DOCTYPE html>
<HTML>
    <head>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="main.css" />
        <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
        <script src="search_tickets.js"></script>
        <title>Tickets'Press</title>
    </head>
    
    <script type="text/javascript">
        function refresh_part_of_page(){
            $.ajax({
                url: 'http://lakartxela.iutbayonne.univ-pau.fr/~qrobert001/s3/avecDesFonctionPartout+Site/index.php',
                success: function(data){
                    $('#part_of_page_to_refresh').html(data);
                }
            });
        }
    </script>

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
                    <?php
                     if (isset($_SESSION['user_id']) == false)
                     {
                        echo '<a href="connexion.html">';
                        echo '<i class="fa-solid fa-user"></i>';
                        echo '<label for="user">Se connecter</label>';
                        echo '</a>';
                     }
                     else
                     {
                        $idUtilisateur = $_SESSION['user_id'];
                        $query  = "SELECT pseudo FROM Utilisateur WHERE idUti = $idUtilisateur;";

                        echo '<a href="#">';
                        echo '<i class="fa-solid fa-user"></i>';
                        echo '<label for="user">Mon compte</label>';
                        echo '</a>';
                     }
                    ?>
                    
                </div>
            </section>
        </header>

        <!-- Contenu principal de la page -->
        <main>
            <!-- Formulaire pour sélectionner la date -->
            <form>

    <label for="date">Date: </label>
    <!-- Champ pour sélectionner la date -->
    <div>
        <input type="date" id="dateInput" name="date" value="<?php echo date('Y-m-d');?>"    min="<?php echo date('Y-m-d');?>" max="<?php echo date('Y-m-d', strtotime('+5 year'));?>">
        <button id="refreshButton">Rafraîchir</button>
    </div>
</form>

<script>
  document.getElementById("refreshButton").addEventListener("click", function(event) {
    event.preventDefault(); // Empêche la soumission du formulaire
    var date = document.getElementById("dateInput").value;

    // Envoi d'une requête AJAX pour récupérer les billets correspondants à la date entrée
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "get_posts.php?date=" + date, true);
    xhr.onreadystatechange = function() {
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
    }
}


// Affichage des billets pour chaque genre
echo '<h2>Sports</h2>';
echo '<div style="display: flex;">';
foreach ($sports as $sport) {
    if (strtotime($sport['dateExp']) >= strtotime($thedate)) {
        echo '<article id="leBillet" style="width: 175px; word-wrap: break-word;">';
        echo '<a href="achat.php?id=' . $sport['id'] . '">';
        echo '<img src="images/sport.jpg"> <br>';
        echo '<span id="libelle" style="font-size: smaller;">' . $sport['libelle'] . '</span><br>';
        echo '</a>';
        echo '</article>';
    }
}
echo '</div>';

echo '<h2>Concerts</h2>';
echo '<div style="display: flex;">';
foreach ($concerts as $concert) {
    if (strtotime($concert['dateExp']) >= strtotime($thedate)) {
        echo '<article id="leBillet" style="width: 175px; word-wrap: break-word;">';
        echo '<a href="achat.php?id=' . $concert['id'] . '">';
        echo '<img src="images/concert.jpg"> <br>';
        echo '<span id="libelle" style="font-size: smaller;">' . $concert['libelle'] . '</span><br>';
        echo '</a>';
        echo '</article>';
    }
}

echo '</div>';

echo '<h2>Festivals</h2>';
echo '<div style="display: flex;">';
foreach ($festivals as $festival) {
    if (strtotime($festival['dateExp']) >= strtotime($thedate)) {
        echo '<article id="leBillet" style="width: 175px; word-wrap: break-word;">';
        echo '<a href="achat.php?id=' . $festival['id'] . '">';
        echo '<img src="images/festival.jpg"> <br>';
        echo '<span id="libelle" style="font-size: smaller;">' . $festival['libelle'] . '</span><br>';
        echo '</a>';
        echo '</article>';
    }
}
echo '</div>';

echo "</section>";

?>

</main>



<!-- Pied de page -->
<footer>
</footer>      
    </body>
</HTML>
