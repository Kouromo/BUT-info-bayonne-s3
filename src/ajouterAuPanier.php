<?php
    include('ConnBD.php');
    
    $conn = ConnBD();
 
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
<?php
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
}

?>