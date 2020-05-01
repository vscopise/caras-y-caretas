jQuery(document).ready(function ($) {
    $('.zocalo_suscripciones .close').click(function(){
        $(this).parents().find('.zocalo_suscripciones').css("display", "none");
    });
});