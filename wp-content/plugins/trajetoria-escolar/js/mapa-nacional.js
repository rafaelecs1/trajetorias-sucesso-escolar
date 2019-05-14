jQuery(document).ready(function ($) {
    $('.region').hover(
        function () {
            var region = this.getAttribute("xlink:href");
            region = region.replace("#", "");
            $('.' + region).css('background-color', '#eeeeee');
        },
        function () {
            var region = this.getAttribute("xlink:href");
            region = region.replace("#", "");
            $('.' + region).css('background-color', 'transparent');
        }
    );
});



