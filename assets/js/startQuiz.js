require('../css/startQuiz.scss');

const $ = require('jquery');

$('#container-questions').scrollspy({ target: '#listQuestions' });

$('[data-spy="scroll"]').each(function () {
    let $spy = $(this).scrollspy('refresh')
});

// let checkboxes = document.querySelectorAll('input[type="checkbox"]'),
//     setChecked = null;
//
// (setChecked = function(i) {
//     checkboxes[i].checked = true;
//
//     if (i < checkboxes.length - 1) {
//         setTimeout(setChecked, 180, i + 2);
//     }
// })(0);
