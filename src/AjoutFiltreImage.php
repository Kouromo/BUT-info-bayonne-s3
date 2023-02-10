<?php
    function AjoutFiltreImage($difficulty)
    {
    switch ($difficulty)
      {
        case 'easy':
          imagefilter($_SESSION['image_t'], IMG_FILTER_SMOOTH, $_SESSION['distortion_level']);
          break;
        case 'medium':
          imagefilter($_SESSION['image_t'], IMG_FILTER_SMOOTH, $_SESSION['distortion_level']);
          break;
        case 'hard':
          imagefilter($_SESSION['image_t'], IMG_FILTER_SMOOTH, $_SESSION['distortion_level']);
          imagefilter($_SESSION['image_t'], IMG_FILTER_COLORIZE, rand(-255, 255), rand(-255, 255), rand(-255, 255));
          break;
      }
    }
?>