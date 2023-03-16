<?php
/**
 * Page d'inscription
 * @file inscription.php
 * @brief Ce programme permet à un utilisateur de s'inscrire sur le site
 * @details Ce programme permet à un utilisateur de s'inscrire sur le site et affiche une erreur si l'inscription n'a pas pu être effectuée
 * @author Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @date 2023-02-21
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include('ConnBD.php');
  $conn = ConnBD();

  if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['DATE']) && !empty($_POST['pays']) && !empty($_POST['region']) && !empty($_POST['codePostal']) && !empty($_POST['adresse'])) {
    $pseudo = strip_tags($_POST['pseudo']);
    $email = strip_tags($_POST['email']);
    $password = strip_tags($_POST['password']);
    $confirm_password = strip_tags($_POST['confirm_password']);
    $date = strip_tags($_POST['DATE']);
    $pays = strip_tags($_POST['pays']);
    $region = strip_tags($_POST['region']);
    $codePostal = strip_tags($_POST['codePostal']);
    $adresse = strip_tags($_POST['adresse']);
    $codeCaptcha = strip_tags($_POST['codeCaptcha']);

    if (!empty($_POST['codeCaptcha']) && $_POST['codeCaptcha'] == $_SESSION['captcha']) {
      // regarde s'il existe un mail ou pseudo déjà associé à un utilisateur
      $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE mail = :mail");
      // On lie les données envoyées à la requête
      $stmt->bindParam(':mail', $email);
      // On exécute la requête
      $stmt->execute();
      // On récupère les résultats de la requête
      $mailExist = $stmt->fetch();

      $stmt = $conn->prepare("SELECT * FROM Utilisateur WHERE pseudo = :pseudo");
      // On lie les données envoyées à la requête
      $stmt->bindParam(':pseudo', $pseudo);
      // On exécute la requête
      $stmt->execute();
      // On récupère les résultats de la requête
      $pseudoExist = $stmt->fetch();

      if ($mailExist == false && $pseudoExist == false) {
        // On vérifie que les mots de passe sont identiques
        if ($password == $confirm_password) {
          // On crypte le mot de passe
          $password = password_hash($password, PASSWORD_DEFAULT);
          // requête qui affiche le nombre d'utilisateur dans la base de données en PDO
          $query = $conn->query("SELECT COUNT(*) FROM Utilisateur");

          // converti le contenu de $query en number
          $nombreUti = $query->fetchColumn();
          $idUti = $nombreUti + 1;

          // convertir $date en DATE SQL
          $date = date('Y-m-d', strtotime($date));
          //var_dump($date);

          // définir la variable suspecte à 0
          $suspecte = 0;

          // On prépare la requête d'insertion
          $stmt = $conn->prepare("INSERT INTO Utilisateur (idUti, pseudo, mail, mdp, dateNaiss, pays, region, codePostal, adresse, suspecte) VALUES (:idUti, :pseudo, :mail, :mdp, :dateNaiss, :pays, :region, :codePostal, :adresse, :suspecte)");
          // On lie les données envoyées à la requête
          $stmt->bindParam(':idUti', $idUti);
          $stmt->bindParam(':pseudo', $pseudo);
          $stmt->bindParam(':mail', $email);
          $stmt->bindParam(':mdp', $password);
          $stmt->bindParam(':dateNaiss', $date);
          $stmt->bindParam(':pays', $pays);
          $stmt->bindParam(':region', $region);
          $stmt->bindParam(':codePostal', $codePostal);
          $stmt->bindParam(':adresse', $adresse);
          $stmt->bindParam(':suspecte', $suspecte);
          // On exécute la requête
          $stmt->execute();
          $conn = null;

          $_SESSION['user_id'] = $idUti;

          header('Location: index.php?success=inscription'); // Inscription réussie
        } else {
          header('Location: inscription.php?error=password'); // Mots de passe différents
        }
      } elseif ($mailExist == true) {
        header('Location: inscription.php?error=mailExist'); // Mail déjà existant
      } elseif ($pseudoExist == true) {
        header('Location: inscription.php?error=pseudoExist'); // Pseudo déjà existant
      }
    } else {
      header('Location: inscription.php?error=captcha'); // Captcha non valide
    }
  } else {
    header('Location: inscription.php?error=empty'); // Formulaire incomplet
  }
}

?>
<HTML>

<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="inscription.css" />
  <script src="https://kit.fontawesome.com/7c2235d2ba.js" crossorigin="anonymous"></script>
  <title>Page de login ou de sign in</title>
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
    <!-- Formulaire d'inscription -->
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <h2>Inscription</h2>
      <hr>
      <?php
      if (isset($_GET['error'])) {
        echo '<div id="banniereErreur">';
        if ($_GET['error'] == 'empty') {
          echo '<p class="erreur">Veuillez remplir tous les champs</p>';
        } elseif ($_GET['error'] == 'password') {
          echo '<p class="erreur">Les mots de passe ne sont pas identiques</p>';
        } elseif ($_GET['error'] == 'mailExist') {
          echo '<p class="erreur">Mail déjà existant</p>';
        } elseif ($_GET['error'] == 'captcha') {
          echo '<p class="erreur">Captcha non valide</p>';
        } elseif ($_GET['error'] == 'pseudoExist') {
          echo '<p class="erreur">Pseudo déjà existant</p>';
        }
        echo '</div>';
      }

      ?>
      <label for="pseudo">Pseudo * : </label>
      <input type="text" name="pseudo" placeholder="Votre pseudo" required>
      <label for="email">Email * : </label>
      <input type="email" name="email" placeholder="Votre adresse email" required>
      <label for="password">Mot de passe * : </label>
      <input type="password" name="password" placeholder="Votre mot de passe" required>
      <input type="password" name="confirm_password" placeholder="Confirmer votre mot de passe" required>
      <label for="DATE">Date de naissance * : </label>
      <input type="date" id="DATE" name="DATE" onclick="refresh_part_of_page()" required>
      <label for="pays">Pays * : </label>
      <input type="text" name="pays" placeholder="Votre pays" required>
      <label for="region">Région * : </label>
      <input type="text" name="region" placeholder="Votre région" required>
      <label for="codePostal">Code Postal * : </label>
      <input type="text" name="codePostal" placeholder="Votre code postal" required>
      <label for="adresse">Adresse * : </label>
      <input type="text" name="adresse" placeholder="Votre adresse" required>
      <section id="VerifCaptcha">
        <label for="codeCaptcha">Vérification * :</label>
        <img src="generationCaptchaUAttempt.php" alt="CodeDuCaptcha"></img>
        <input type="button" id="refresh" value="Refresh Captcha" onClick="location.href=location.href">
        <input type="txt" name="codeCaptcha" placeholder="Code du Captcha" required>
      </section>
      <input type="submit" value="S'inscrire">
      <a href="connexion.php">Déjà un compte ?</a>
    </form>
  </main>
</body>


</HTML>