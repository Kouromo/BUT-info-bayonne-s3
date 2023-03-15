<?php
    session_start();
    // Vérifie si le formulaire a été soumis en utilisant la méthode POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupère l'identifiant de l'article dans la variable POST "id"
        $id = $_POST['id'];

        // Ajoute l'identifiant de l'article à la variable de session "panier"
        $_SESSION['panier'][] = array('id' => $id);

        // Supprime les doublons dans le panier
        $_SESSION['panier'] = array_unique($_SESSION['panier'], SORT_REGULAR);
    }
    header('Location: index.php');
?>