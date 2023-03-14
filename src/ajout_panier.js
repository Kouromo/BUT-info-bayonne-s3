// Récupère l'élément HTML correspondant au bouton "panier"
let boutonPanier = document.getElementById('panier');

// Ajoute un écouteur d'événements "click" sur le bouton "panier"
boutonPanier.addEventListener('click', function() {

  // Récupère l'identifiant de l'article à ajouter au panier
  let idArticle = this.getAttribute('data-id');

  // Initialise une requête AJAX
  let xhr = new XMLHttpRequest();

  // Configure la requête AJAX
  xhr.open('POST', 'panier.php');

  // Configure les en-têtes de la requête AJAX
  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

  // Configure l'écouteur d'événements "load" pour la requête AJAX
  xhr.addEventListener('load', function() {
    // Vérifie que la requête AJAX a réussi
    if (xhr.status === 200) {
      // Récupère la réponse de la requête AJAX
      let response = xhr.responseText;

      // Affiche la réponse de la requête AJAX dans la console du navigateur
      console.log(response);
    } else {
      // Affiche une erreur dans la console du navigateur si la requête AJAX a échoué
      console.error(xhr.statusText);
    }
  });

  // Envoie la requête AJAX avec les informations du panier
  xhr.send('id=' + idArticle);

});
