<?php
    include ('AjoutFiltreImage.php');
    function CreationCaptcha($difficulty)
    {
        // Initialisation de 2 variables pour l'angle d'inclinaison
        $min_angle = -10;
        $max_angle = 10;

        $posX = 5;
        $posY = 30;

        // Boucle jusqu'à la fin de la chaine text
        for ($i=0; $i < $_SESSION['text_length']; $i++)
        { 
            $_SESSION['image_t'] = imagecreate($_SESSION['width'], $_SESSION['height']);
            $white = imagecolorallocate($_SESSION['image_t'], 255, 255, 255);
            $black = imagecolorallocate($_SESSION['image_t'], 0, 0, 0);

            // Écrire le texte sur l'image en utilisant la fonction imagettftext()
            imagettftext($_SESSION['image_t'], $_SESSION['width'], rand($min_angle, $max_angle), $posX, $posY, $black, 'font.ttf', $_SESSION['captcha'][$i]);

            // Ajouter des filtres supplémentaires en fonction du niveau de difficulté
            AjoutFiltreImage($difficulty);

            // Ajouter des pixels sur l'images
            for ($j=0; $j < $_SESSION['height']; $j++)
            { 
                $rand_color = imagecolorallocate($_SESSION['image_t'], rand(0, 255), rand(0, 255), rand(0, 255));
                imagesetpixel($_SESSION['image_t'], rand(0, $_SESSION['width']), rand(0, $_SESSION['height']), $rand_color);
            }

            // On ajoute cette image sur l'image finale
            imagecopymerge($_SESSION['image'], $_SESSION['image_t'], 3+($_SESSION['width']*$i), 0, 0, 3, $_SESSION['width'], $_SESSION['height'], 75);
        }
        imagedestroy($_SESSION['image_t']);
    }
?>