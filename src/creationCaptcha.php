<?php
    session_start();
    function generate_captcha($difficulty)
    {
        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        switch ($difficulty) {
          case 'medium':
            $text_length = 8;
            $distortion_level = 10;
            break;
          case 'hard':
            $text_length = 10;
            $distortion_level = 20;
            break;
          default:
            $text_length = 6;
            $distortion_level = 5;
        }

        // Créer une image vide avec une largeur de 200 pixels et une hauteur de 50 pixels
        $image = imagecreate(20*$text_length, 50);

        // Allouer des couleurs pour l'image en utilisant les fonctions imagecolorallocate()
        $bg_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);


        // Générer un texte aléatoire à utiliser comme captcha en utilisant la fonction str_shuffle()
        // et en limitant la longueur du texte en fonction du niveau de difficulté
        $text = substr(str_shuffle('0123456789'), 0, $text_length);

        $min_angle = -10;
        $max_angle = 10;

        for ($i=0; $i < $text_length; $i++) { 
          $image_t = imagecreate(20, 50);
          $white = imagecolorallocate($image_t, 255, 255, 255);
          $black = imagecolorallocate($image_t, 0, 0, 0);
          
          // Écrire le texte sur l'image en utilisant la fonction imagettftext()
          imagettftext($image_t, 20, rand($min_angle, $max_angle), 3, 30, $black, 'font.ttf', $text[$i]);

          // Ajouter des filtres supplémentaires en fonction du niveau de difficulté
          switch ($difficulty) {
            case 'easy':
              imagefilter($image_t, IMG_FILTER_SMOOTH, $distortion_level);
              break;
            case 'medium':
              imagefilter($image_t, IMG_FILTER_SMOOTH, $distortion_level);
              break;
            case 'hard':
              imagefilter($image_t, IMG_FILTER_SMOOTH, $distortion_level);
              imagefilter($image_t, IMG_FILTER_COLORIZE, rand(-255, 255), rand(-255, 255), rand(-255, 255));
              break;
          }

          // Ajouter des pixels sur l'images
          for ($j=0; $j < 50; $j++) { 
            $rand_color = imagecolorallocate($image_t, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($image_t, rand(0, 20), rand(0, 50), $rand_color);
          }

          // On ajoute cette image sur l'image finale
          imagecopymerge($image, $image_t, 3+(20*$i), 2, 3, 3, 20, 50, 75);
        }

        
        $_SESSION['captcha'] = $text;

        // Afficher l
        header("Content-type: image/png");
        imagepng($image);
        imagedestroy($image);
        imagedestroy($image_t);
    }
    //définition d'une variable nulle a chier parce que je peux pas faire autrement pour l'instant
    $mail = "LisaLabelleGosse@gmail.com";

    $servername = 'lakartxela';
    $username = 'tlauriou_bd';
    $password = 'tlauriou_bd';
    
    //On établit la connexion
    $conn = new mysqli($servername, $username, $password);
    
    //On vérifie la connexion
    if($conn->connect_error){
        die('Erreur : ' .$conn->connect_error);
    }

    // Définir une variable pour enregistrer le user_id utilisateur
    $user_id = $conn->query("SELECT idUti FROM Utilisateur WHERE mail = $mail;");

    $_SESSION['user_id'] = $user_id;

    // Définir une variable pour enregistrer le nombre de tentatives de chaque utilisateur
    $attempts = 0;
    $_SESSION['attempts'] = $attempts;

    // Définir une variable pour enregistrer la liste des utilisateurs suspects
    $suspects = array();
    $_SESSION['suspects'] = $suspects;

    // Générer un captcha en utilisant la fonction generate_captcha()
    generate_captcha('hard');
?>