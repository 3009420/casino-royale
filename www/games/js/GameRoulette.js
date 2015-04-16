var deckbox = '.game#roulette div.btn';


$(document).ready(function () {
    $(document).on('click', deckbox, function () {
        $('#modal-bet').modal();
    });

    $('#modal-bet').on('shown.bs.modal', function () {
        $('#modal-bet #bet-amount').focus();
    });

    $(document).on('click', '#bet-confirm', function () {
        betVal = $('#modal-bet #bet-amount').val();
    });


});