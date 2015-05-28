var deckbox = '.game#roulette div.btn';
var modal = '#modal-bet';


$(document).ready(function () {

    // Place a bet
    ////////////////////////////////////////////////////////////////////////////
    $(document).on('click', deckbox, function () {
        console.log($(this).data('name'));
        if ($(this).data('name') === undefined) {
            $(modal + ' #bet-value').html('number ' + $(this).data('value'));
        } else {
            $(modal + ' #bet-value').html($(this).data('name'));
        }

        $(modal + ' #bet-ratio').html($(this).data('ratio') + ' to 1');
        $(modal + ' #bet-ratio').data('value', $(this).data('ratio'));

        $(modal + ' #bet-value').val($(this).data('value'));

        $(modal + ' #bet-win').html(calculatePossibileWin($(modal + ' #bet-amount').val(), $(this).data('ratio')) + ' Cr');

        $(modal).modal();
    });

    $('#modal-bet').on('shown.bs.modal', function () {
        $(modal + ' #bet-amount').focus();
    });

    $(document).on('click', '#bet-confirm', function () {
        betVal = $(modal + ' #bet-amount').val();
    });

    $(document).on('keyup keypress blur change', '#bet-amount', function () {
        $(modal + ' #bet-win').html(calculatePossibileWin($(this).val(), $(modal + ' #bet-ratio').data('value')) + ' Cr');
    });


});


function calculatePossibileWin(betAmount, ratio) {
    return betAmount * ratio;
}