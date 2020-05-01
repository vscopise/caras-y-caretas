jQuery(document).ready(function($) {
    $(".mh-slider").owlCarousel({
        autoPlay : true,
        navigation: true,
        navigationText: ['<i class="fa fa-chevron-left"></i>','<i class="fa fa-chevron-right"></i>'],
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true
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