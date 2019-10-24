//NAVEGACAO FRASES
var Owl = {

    init: function() {
        Owl.carousel();
    },

    carousel: function() {
        var owl;
        jQuery(document).ready(function() {

            owl = jQuery(".owl-carousel").owlCarousel({
                loop:true,
                autoWidth:false,
                autoHeight: false,
                autoplay:true,
                autoplayTimeout:10000,
                items: 1,
                center: true,
                dots: true,
                dotsContainer: '.navigation-owl',
            });

            jQuery('.navigation-owl').on('click', 'label', function(e) {
                owl.trigger('to.owl.carousel', [jQuery(this).index(), 500]);
            });

        });
    }
};

jQuery( document ).ready(function() {

    //MENU NOVA ABA
    jQuery("#menu-item-139 a").attr("target","_blank");

    //NAVEGACAO FRASES
    Owl.init();

});

