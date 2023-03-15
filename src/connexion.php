<?php

/**
 * @file connexion.php
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @brief Page de connexion à Tickets'Press
 * @details Page de connexion à Tickets'Press où doit être saisi l'adresse mail et le mot de passe de l'utilisateur
 * @date 2023-01-16
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include('ConnBD.php');
  $conn = ConnBD();
  if ((!empty($_POST['email'])) && !(empty($_POST['password']))) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha']) {
      // Si l'utilisateur n'existe pas, on redirige vers la page de connexion
      // On prépare la requête pour vérifier l'existence de l'utilisateur dans la base de données
      $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE mail = :mail");
      // On lie les données envoyées à la requête
      $stmt->bindParam(':mail', $email);
      // On exécute la requête
      $stmt->execute();
      // On récupère les résultats de la requête
      $user = $stmt->fetch();
      if (!$user) {
        $conn = null;
        header('Location: connexion.php?error=user'); // Utilisateur inexistant
      } else {
        // On vérifie que le mot de passe envoyé correspond au mot de passe stocké dans la base de données
        if (password_verify($password, $user['mdp'])) {
          $_SESSION['user_id'] = $user['idUti'];
          $conn = null;
          header('Location: index.php?success=connexion'); // Connexion réussie
        } else {
          $conn = null;
          header('Location: connexion.php?error=password'); // Mot de passe incorrect
        }
      }
    } else {
      $conn = null;
      header('Location: connexion.php?error=captcha'); // Captcha non valide
    }
  } else {
    $conn = null;
    header('Location: connexion.php?error=empty');
  }
}

?>

<HTML>

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="connexion.css" />
  <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
  <title>Page de connexion à Ticket'sPress</title>
</head>
<body>
  <header>
    <section id="headCentre">
      <!--Logo qui redirige vers l'index-->
      <a href="index.php">
        <img id="logo" src="images/logo1.png" alt="Logo de Ticket'sPress">
      </a>
    </section>
    <section id="headDroite">
      <div>
        <i class="fa-solid fa-user"></i>
      </div>
    </section>
  </header>
  <main>
    <!-- Formulaire de connexion -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <h2>Connexion</h2>
      <hr>
      <?php
      if (isset($_GET['error'])) {
        echo '<div id="banniereErreur">';
        if ($_GET['error'] == 'captcha') {
          echo '<p class="erreur">Captcha invalide</p>';
        }
        if ($_GET['error'] == 'user') {
          echo '<p class="erreur">Utilisateur inexistant</p>';
        }
        if ($_GET['error'] == 'password') {
          echo '<p class="erreur">Mot de passe incorrect</p>';
        }
        if ($_GET['error'] == 'empty') {
          echo '<p class="erreur">Veuillez remplir tous les champs</p>';
        }
        echo '</div>';
      }
      ?>
      <label for="email">Email : </label>
      <input type="email" name="email" placeholder="Adresse email" requiered>
      <label for="password">Mot de passe : </label>
      <input type="password" name="password" placeholder="Mot de passe" requiered>
      <section id="VerifCaptcha">
        <label for="codeCaptcha">Vérification :</label>
        <img src="generationCaptchaUAttempt.php" alt="CodeDuCaptcha"></img>
        <input id="refresh" type="button" value="Refresh Captcha" onClick="location.href=location.href">
        <input type="txt" name="codeCaptcha" placeholder="Code du Captcha" requiered>
      </section>
      <input type="submit" value="Se connecter">
      <a href="#">Mot de passe oublié ?</a> <br> <br>
      <a href="inscription.php">Pas encore de compte ?</a>
    </form>
  </main>
</body>


</HTML>