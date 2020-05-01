jQuery(document).ready(function($) {
    $(".cyc-slider").owlCarousel({
        navigation : true,
        navigationText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
      slideSpeed : 300,
      paginationSpeed : 400,
 
      items : 1, 
      itemsDesktop : false,
      itemsDesktopSmall : false,
      itemsTablet: false,
      itemsMobile : false
        /*autoPlay : true,
        navigation: true,
        
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true*/
    });
    $(".owl-carousel-gallery").owlCarousel({
        autoPlay : true,
        navigation: true,
        navigationText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
        pagination: false,
        slideSpeed: 300,
        paginationSpeed: 400,
        singleItem:true
    });
});

var min = "-100px", // remember to set in css the same value
    max = "0px";

jQuery(function($) {
    $(".caption span").click(function() {
        if($(".caption").css("bottom") === min) $(".caption").animate({ bottom: max }); 
        else $(".caption").animate({ bottom: min }); 
    });
    $(".owl-item .item").click(function() {
        if($(".caption").css("bottom") === min) $(".caption").animate({ bottom: max }); 
        else $(".caption").animate({ bottom: min }); 
    });
    
});