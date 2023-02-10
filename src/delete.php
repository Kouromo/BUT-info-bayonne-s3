<?php
    session_start();
    
    if(isset($_GET['id'])) {
        $id = $_GET['id'];
    
        // Vérifiez que la variable de session panier existe
        if(isset($_SESSION['panier'])) {
            // Récupérez les articles du panier
            $articles = $_SESSION['panier'];
    
            // Bouclez sur les articles et supprimez l'article correspondant à l'identifiant
            foreach($articles as $index => $article) {
                if($article['id'] == $id) {
                    unset($articles[$index]);
                    break;
                }
            }
            
            // Mettre à jour la variable de session panier
            $_SESSION['panier'] = $articles;
        }
    }
    
    // Rediriger l'utilisateur vers la page panier.php
    header("Location: panier.php");
    exit;
?>
