<?php
    /**
    * @file generationCaptchaUAttempt.php
    * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
    * @brief Crée un nouveau objet Captcha avec tentatives illimités et avec une difficulté maximale
    * @date 2023-02-02
    * 
    */
include ('Captcha.php');

$unCaptcha = new Captcha('hard', False);

?>