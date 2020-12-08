jQuery(document).ready(function ($) {

    $('ul>li.tablinks').click(function (e) {
        var TabId = $(this).attr('id');
        e.preventDefault();

        var numbertab = 1;
        var intervalo = '';
        var auto = e.cancelable;
        var ntabReg = TabId.match(/\d+/g);
        var nTab = ntabReg[0];

        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");

        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        if (auto) {
            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById('tab-' + nTab).style.display = "block";
            document.getElementById(TabId).classList.add('active');
            clearInterval(window.intervalo);
        } else {
            if (this.numbertab > 3) {
                this.numbertab = 1
            }
            document.getElementById('tab-' + nTab).style.display = "block";
            document.getElementById(TabId).classList.add('active');
        }
        
    });

});

jQuery(document).ready(function ($) {

    $('.type_region').click( function(e){

        //houve click e não trigger
        if ( e.originalEvent !== undefined ){
            clearInterval(window.intervalo);
        }
        
        e.preventDefault();
        var idRegion = $(this).attr('id');

        // Todos os elementos com class="type_region" -> seletores das regioes
        typeRegions = document.getElementsByClassName("type_region");
        for (i = 0; i < typeRegions.length; i++) {
            typeRegions[i].className = typeRegions[i].className.replace(" active", "");
        }
        document.getElementById(idRegion).classList.add('active'); // deixa o link ativo com a seta >

        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Todos os elementos com class="regiao_mapas" -> grandes slides de regioes
        slideRegions = document.getElementsByClassName("regiao_mapas");
        for (i = 0; i < slideRegions.length; i++) {
            slideRegions[i].style.display = "none";
        }
        document.getElementsByClassName(idRegion)[0].style.display = "block"; //exibe o slide
        document.querySelectorAll('.'+idRegion+' > .tabcontent')[0].style.display = "block"; //ativa primeira div das regioes (geografica/ territorio)
        document.querySelectorAll('.'+idRegion+' > .abas > .tablinks')[0].classList.add('active'); //ativa primeira aba da div das regioes (geografica/ territorio)

    });

})


jQuery(document).ready(function ($) {

    //fecha o slide de territorios
    document.getElementsByClassName('regiao_territorial')[0].style.display = "none";

    var contador = 0;

    window.intervalo = setInterval(function () {
        
        if (contador === 0) {
            $('#regiao_geografica').trigger('click');
            $('ul>li.tablinks>a:eq(0)').trigger('click');
        }

        if (contador === 1) {
            $('ul>li.tablinks>a:eq(1)').trigger('click');
        }

        if (contador === 2) {
            $('ul>li.tablinks>a:eq(2)').trigger('click');
        }

        if (contador === 3) {
            $('#regiao_territorial').trigger('click');
            $('ul>li.tablinks>a:eq(3)').trigger('click');
        }

        if (contador === 4) {
            $('ul>li.tablinks>a:eq(4)').trigger('click');
        }

        if (contador === 5) {
            $('ul>li.tablinks>a:eq(5)').trigger('click');
        }

        if (contador > 5) {
            contador = -1;
        }
        
        contador++;

    }, 6000);

});

