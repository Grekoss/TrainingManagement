require('../css/startQuiz.scss');

const $ = require('jquery');

$('#container-questions').scrollspy({ target: '#listQuestions' });

$('[data-spy="scroll"]').each(function () {
    let $spy = $(this).scrollspy('refresh')
});
