require('../css/report.scss');

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
    init: function () {
        newReport.starList = Object.keys(star.list);
        newReport.elem = document.querySelector('input[type="range"]');
        newReport.elem.addEventListener('input', newReport.rangeValue);
    },

    createImage: function (score) {
        let divImage = document.createElement('div');
        divImage.className = 'imageStar';

        if (score === '0') {
            divImage.style.backgroundColor = 'white';
        } else {
            divImage.style.backgroundImage = 'url(' + ratingsImage + ')';
            divImage.style.backgroundPosition = star.list[score];
            divImage.style.width = '100%';
            divImage.style.height = '100%';
        }

        return divImage;
    },

    rangeValue: function () {
        let newValue = newReport.elem.value;
        let target = document.querySelector('.value');
        target.innerHTML = '';

        target.appendChild(newReport.createImage(newValue));
    },

};

document.addEventListener('DOMContentLoaded', newReport.init);

