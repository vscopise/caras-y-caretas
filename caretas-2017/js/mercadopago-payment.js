/* global MercadoPagoObject */

jQuery(document).ready(function($) {
    $('.monto').bind( 'click', function(){
        $('.monto').removeClass('selected');
        $(this).addClass('selected');
        var value = $(this).data('value');
        $('#monto').val(value);
    });
});