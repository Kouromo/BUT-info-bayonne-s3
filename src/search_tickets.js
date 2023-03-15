// Script qui va chercher les balise span, et qui va cacher toutes les balises artcle qui ne correspondent pas au mot clé entré dans l'input id='searchbar'
function search_ticket(){
    var input, filter, span, article, i, txtValue;
    var compteur = 0;
    var leCounter = document.getElementById("counter");
    input = document.getElementById("searchbar");
    filter = input.value.toUpperCase();
    span = document.getElementsByTagName("span");
    article = document.getElementsByTagName("article");
    for (i = 0; i < span.length; i++) {
        txtValue = span[i].textContent || span[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            article[i].style.display = "block";
            compteur++;
            console.log(compteur);
        } else {
            article[i].style.display = "none";
        }
    }
    if (compteur == i) {
        leCounter.innerHTML = "";
    }
    else if (compteur == 0) {
        leCounter.innerHTML = "Aucun résultat";
    }
    else {
        leCounter.innerHTML = compteur + " résultats trouvés";
    }
}
