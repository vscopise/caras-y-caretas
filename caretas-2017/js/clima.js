jQuery(document).ready(function ($) {
    var div_clima = '<div id="clima" class="clearfix"><div class="icon"></div><div class="temp"><div class="min"></div><div class="max"></div></div></div>';
    $('.main-nav').append( div_clima );
    
    (function worker() {
        $('#wheather_icon').html('');
        $.ajax({
            type: 'POST',
            url: clima_object.ajaxurl,
            data:{
                action: 'cyc_get_wheather_local_data'
            }, 
            success: function(data) {
                if (data !== '') {
                    var weather_data = $.parseJSON( data );
                    var weather_icon = weather_data['weather_icon'];
                    var weather_cond = weather_data['weather_cond'] + ', ' + weather_data['weather_temp'] + '&deg;';
                    var weather_temp_max = 'max ' + weather_data['weather_temp_max'] + '&deg;';
                    var weather_temp_min = 'min ' + weather_data['weather_temp_min'] + '&deg;';

                    var weather_icon_img = '<img src="' + weather_icon + '" width=43 height=43 title="' + weather_cond + '"/>';
                    $('#clima .icon').html(weather_icon_img);
                    $('#clima .min').html(weather_temp_min);
                    $('#clima .max').html(weather_temp_max);
                    $('#clima .min').attr('title', weather_cond.replace('&deg;','º'));
                    $('#clima .max').attr('title', weather_cond.replace('&deg;','º'));
                }
            },
            complete: function() {
              setTimeout(worker, 300000);
            }
        });
    })();
});