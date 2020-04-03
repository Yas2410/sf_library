// je m'assure que la "page" est bien chargée avant d'executer mon code
$(document).ready(function() {
//console.log('Le DOM est OK!');
    // en css tous les articles sont cachés, donc ici je n'affiche que les trois premiers
    // JQUERY
    $('.article').slice(0, 3).show();
    // JAVASCRIPT
    //document.getElementsByClassName('article').slice(0, 3).style.display('block');

    // au clic sur le bouton "voir plus"
    // afficher tous les articles
    // masquer le bouton

    // je sélectionne en jQuery mon bouton
    // j'utilise la fonction on('click') pour executer une fonction
    // quand le bouton est cliqué
    $('.button-more').on('click', function() {
        // je sélectionne tous les articles et je les affiche
        $('.article').show();
    });

    $('.button-more').on('mouseenter', function () {
        // PAS UNE BONNE PRATIQUE INTEGRER CSS DIRECTEMENT DANS LE FICHIER JS; ON CREE UNE CLASSE: exemple : .bookhover
    //$(this).css('background-color', 'green');
        $(this).addClass('bookHover');
    });

    $('.button-more').on('mouseleave', function () {
       // $(this).css('background-color', 'purple');
        $(this).removeClass('bookHover');
    });

    $('.header-newsletter').on('click', function () {
    $('.popup-newsletter').addClass('popup-newsletter-show')
    });

    $('body').on('click', function (e) {

        // e = évènement, donc ce sont toutes les informations relatives au clic que je viens de faire
        // avec e.target je regarde si l'élément cliqué n'est pas le bouton de newsletter
        // ou la popup en elle-même pour éviter de masquer ma popup si c'est le cas

        if (!$('.header-newsletter').is(e.target) && !$('.popup-newsletter').is(e.target))
        {
            //dans ce cas la, je masque ma popup
            $('.popup-newsletter').removeClass('popup-newsletter-show');
        }
    });

});

