<?php  
    session_start();
    // Génération d'une image aléatoire avec des lettres et des chiffres
    $image = imagecreate(200, 50);
    $fond = imagecolorallocate($image, 200, 200, 200);

    // Choix aléatoire des lettres et des chiffres à afficher
    $lettres = "0123456789";
    $chaine = "";
    for($i=0;$i<5;$i++) {
    $chaine .= $lettres[rand(0, strlen($lettres)-1)];
    }

    // Stockage de la chaine générée en session pour vérification ultérieure
    $_SESSION['captcha'] = $chaine;

    // Ecriture des lettres sur l'image
    $couleur = imagecolorallocate($image, 0, 0, 0);
    imagettftext($image, 20, -10, 20, 30, $couleur, "font.ttf", $chaine);

    // Envoi de l'image au navigateur
    header("Content-type: image/png");
    imagepng($image);
    imagedestroy($image);

    
    

?>

<!-- Formulaire avec champ pour saisie du captcha -->

<form action="verification.php" method="post">
    <label for="captcha">Saisissez les caractères de l'image :</label>
    <input type="text" name="captcha" id="captcha">
    <input type="submit" value="Envoyer">
</form>

<?php
    // Vérification de la saisie du captcha dans la page de traitement du formulaire 
    if($_SESSION['captcha'] == $_POST['captcha']) {
        // Traitement du formulaire
        } else {
        echo "Le captcha est incorrect, veuillez réessayer.";
    }
?>