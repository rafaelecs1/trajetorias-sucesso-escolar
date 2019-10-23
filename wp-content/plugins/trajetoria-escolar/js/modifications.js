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
                autoplayTimeout:5000,
                items: 1,
                center: true,
                dots: true,
                dotsContainer: '.navigation-owl',
            });

            jQuery('.navigation-owl').on('click', 'label', function(e) {
                owl.trigger('to.owl.carousel', [jQuery(this).index(), 300]);
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

