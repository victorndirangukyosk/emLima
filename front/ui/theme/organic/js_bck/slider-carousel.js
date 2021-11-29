$(document).on('ready', function() {

    $(".product-slider").owlCarousel({

        navigation: true, // Show next and prev buttons
        slideSpeed: 2000,
        paginationSpeed: 400,
        singleItem: true,
        pagination: false,
        autoPlay: true,
        navigationText: ["<i class='fa fa-angle-left '></i>", "<i class='fa fa-angle-right'></i>"]
         
        

        // "singleItem:true" is a shortcut for:
        // items : 1, 
        // itemsDesktop : false,
        // itemsDesktopSmall : false,
        // itemsTablet: false,
        // itemsMobile : false

    });

});



 




