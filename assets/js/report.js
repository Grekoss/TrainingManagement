import '../css/report.scss';

const $ = require('jquery');

import ratingsImage from '../images/icons/ratings.png';

let star = {
    list: {
        1: '0 -144px',
        2: '0 -108px',
        3: '0 -72px',
        4: '0 -36px',
        5: '0 0px'
    }
};

let newReport = {
    elem: null,
    starList: [],
    rangeValue: 3,

    init: function () {
        newReport.starList = Object.keys(star.list);
        newReport.elem = document.querySelector('input[type="range"]');
        // On attribue la valeur de l'input
        newReport.elem.value = newReport.rangeValue;
        // On lance la fonction afin d'afficher le nombre d'étoile
        newReport.inputRangeValue();
        // Ecoute du changement de l'input
        newReport.elem.addEventListener('input', newReport.inputRangeValue);
        
        document.getElementById('moreFeel').addEventListener('click', newReport.moreFeel);
        document.getElementById('lessFeel').addEventListener('click', newReport.lessFeel);
    },

    /**
     * Fonction pour ajouter +1 au sentiment de l'utilisateur
     * @param {*} event Pour annuler le chargement de la page
     */
    moreFeel: function(event) {
        event.preventDefault();
        
        // On controle que l'input ne soit pas à 5 pour ne pas aller au dela de 5 étant la valeur maximal
        if(newReport.rangeValue < 5) {
            newReport.elem.value++;
            newReport.inputRangeValue();
        }        
    },

    /**
     * Fonction pour descendre de -1 au sentiment de l'utilisateur
     * @param {*} event Pour annuler le chargement de la page
     */
    lessFeel: function(event) {
        event.preventDefault();

        // On controle que l'inout ne soit pas à 0 pour ne pas aller en dessous de 0 étant la valeur minimal
        if(newReport.rangeValue > 0) {
            newReport.elem.value--;
            newReport.inputRangeValue();
        }
    },

    /**
     * Fonction pour afficher les étoiles sur la page
     * @param {int} score Nombre entre 0 et 5 indiquant le sentiment de l'utilisateur
     */
    createImage: function (score) {
        let divImage = document.createElement('div');
        divImage.className = 'imageStar';

        if (score === '0') {
            divImage.style.backgroundColor = 'white';
        } else {
            divImage.style.backgroundImage = 'url(' + ratingsImage + ')';
            divImage.style.backgroundPosition = star.list[score];
            divImage.style.width = '168px';
            divImage.style.height = '36px';
        }

        return divImage;
    },

    /**
     * Fonction qui permet de récupérer la valeur de l'input range et lance la fonction pour afficher les étoiles
     */
    inputRangeValue: function () {
        // Valeur de l'input range!
        newReport.rangeValue = newReport.elem.value;
        let target = document.querySelector('.value');
        target.innerHTML = '';

        target.appendChild(newReport.createImage(newReport.rangeValue));
    },

};

document.addEventListener('DOMContentLoaded', newReport.init);

