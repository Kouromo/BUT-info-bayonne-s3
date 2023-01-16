<?php
    function InterpreterDifficulte($difficulty)
    {
        // Définir la longueur et la quantité de distorsion en fonction du niveau de difficulté
        switch ($difficulty) {
            case 'medium':
                $_SESSION['text_length'] = 8;
                $_SESSION['distortion_level'] = 10;
                break;
            case 'hard':
                $_SESSION['text_length'] = 10;
                $_SESSION['distortion_level'] = 20;
                break;
            default:
                $_SESSION['text_length'] = 6;
                $_SESSION['distortion_level'] = 5;
        }
    }
?>