require('../css/profile.scss');
require('../images/icons/valide.png');
const $ = require('jquery');

let newPassword;

$(document).ready(function (e) {
    $('#user_profile_password_first').keyup(function () {
        // Avoir la longueur de la chaine
        let numberChar = $(this).val().length;
        numberChar = 6 - numberChar;

        // Avoir la valeur
        newPassword = $(this).val();
        console.log(newPassword);

        let message = 'Votre nouveau mot de passe doit contenir au moins 6 caractères. Il en manque ' + numberChar ;

        $('#js-errors-counter-message').text(message);

        if (numberChar > 0 && numberChar < 6) {
            $('#js-errors-counter-box').removeClass('hidden');
        } else {
            $('#js-errors-counter-box').addClass('hidden');
        }
    });

    $('#new_user_password_first').keyup(function () {
        // Avoir la longueur de la chaine
        let numberChar = $(this).val().length;
        numberChar = 6 - numberChar;

        // Avoir la valeur
        newPassword = $(this).val();
        console.log(newPassword);

        let message = 'Votre mot de passe doit contenir au moins 6 caractères. Il en manque ' + numberChar ;

        $('#js-errors-counter-message').text(message);

        if (numberChar > 0 && numberChar < 6) {
            $('#js-errors-counter-box').removeClass('hidden');
        } else {
            $('#js-errors-counter-box').addClass('hidden');
        }
    });

    $('#user_profile_password_second').keyup(function () {
       // Avoir la valeur
       let newPasswordConfirm = $(this).val();
       console.log(newPasswordConfirm);

       if (newPasswordConfirm !== newPassword) {
           $('#js-errors-identical-box').removeClass('hidden');
           $('#js-errors-identical-image').addClass('hidden');
       } else {
           $('#js-errors-identical-box').addClass('hidden');
           $('#js-errors-identical-image').removeClass('hidden');
       }
    });

    $('#new_user_password_second').keyup(function () {
        // Avoir la valeur
        let newPasswordConfirm = $(this).val();
        console.log(newPasswordConfirm);

        if (newPasswordConfirm !== newPassword) {
            $('#js-errors-identical-box').removeClass('hidden');
            $('#js-errors-identical-image').addClass('hidden');
        } else {
            $('#js-errors-identical-box').addClass('hidden');
            $('#js-errors-identical-image').removeClass('hidden');
        }
    })
});
