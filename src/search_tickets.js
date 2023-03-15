/**
 * @file search_tickets.js
 * @brief Script qui permet de rechercher un ticket dans la liste des tickets
 * @author  Quentin ROBERT, Matéo ALVES, Tanguy LAURIOU, Juan David RODRIGUEZ SINCLAIR
 * @date 2023-02-19
 */

// Script qui va chercher les balise span, et qui va cacher toutes les balises artcle qui ne correspondent pas au mot clé entré dans l'input id='searchbar'
function search_ticket(){
    var input, filter, span, article, i, txtValue;
    input = document.getElementById("searchbar");
    filter = input.value.toUpperCase();
    span = document.getElementsByTagName("span");
    article = document.getElementsByTagName("article");
    for (i = 0; i < span.length; i++) {
        txtValue = span[i].textContent || span[i].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
            article[i].style.display = "block";
        } else {
            article[i].style.display = "none";
        }
    }
}