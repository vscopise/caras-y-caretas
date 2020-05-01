jQuery(function($) {
        $(window).scroll(function(){
                var distanceTop = $('.entry-content').offset().top - $(window).height();		
                if  ($(window).scrollTop() > distanceTop)
                        $('.fixedBar').animate({'right':'0px'},300);
                else 
                        $('.fixedBar').stop(true).animate({'right':'-5000px'},100);
        });
        $('.fixedBar .top a').on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                        scrollTop: 0
                }, 700);
        });
});
