import '../css/startQuiz.scss';

import $ from 'jquery';

$('#container-questions').scrollspy({ target: '#listQuestions' });

$('[data-spy="scroll"]').each(function () {
    let $spy = $(this).scrollspy('refresh')
});
