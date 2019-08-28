/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');

// Add Font Awesome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.min');

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

// importation des images
import imgUser from '../images/backgrounds/user.jpg';
import imgQuestion from '../images/backgrounds/question.jpg';
import imgReport from '../images/backgrounds/report.jpg';

let app = {
    init: function() {
        //Connaitre la route pour savoir qu'elle image afficher
        let arrayRoute = window.location.href.split('/');
        app.backgroundImageShow(arrayRoute[3]);
    },

    backgroundImageShow: function(route) {
        // On récupére l'élément pour la modification du background
        let elmt = document.getElementsByClassName('content-more');

        switch (route) {
            case 'user' :
                elmt[0].style.backgroundImage = 'url(' + imgUser + ')';
                break;

            case 'question' :
                elmt[0].style.backgroundImage = 'url(' + imgQuestion + ')';
                break;

            case 'report' :
                elmt[0].style.backgroundImage = 'url(' + imgReport + ')';
                break;

            default:
                console.log('Une erreur vient de se produire');
        };

    }
}
document.addEventListener('DOMContentLoaded', app.init);
