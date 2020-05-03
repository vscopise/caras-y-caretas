jQuery(document).ready(function ($) {
    var home_bottom_displayed = false;
    // Each time the user scrolls
    $(window).scroll(function () {
        var scrollHeight = $(document).height();
        var scrollPos = $(window).height() + $(window).scrollTop();
        if (((scrollHeight - 300) >= scrollPos) / scrollHeight == 0) {
            if (!home_bottom_displayed) {
                var loader = '<div class="home-bottom-loading"></div>';
                $('#home-bottom').html(loader);
                $.ajax({
                    url: theme_object.ajaxurl,
                    data: {
                        action: 'cyc_home_bottom',
                    },
                    success: function (result) {
                        $('#home-bottom').html(result);
                    }
                });
                home_bottom_displayed = true;
            }
        }
    });
});