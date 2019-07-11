 jQuery(document).ready(function(){

     jQuery('ul.abas li').click(function(){
         alert()
        var tab_id = jQuery(this).attr('data-tab');

         jQuery('ul.abas li').removeClass('active');
         jQuery('.tab-content').removeClass('active');

         jQuery(this).addClass('active');
         jQuery("#"+tab_id).addClass('active');
    })

})