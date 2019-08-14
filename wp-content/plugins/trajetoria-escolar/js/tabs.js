jQuery(document).ready(function ($) {
    $('ul>li.tablinks').click(function (e) {
        var TabId = $(this).attr('id');
        e.preventDefault();

        var numbertab = 1;
        var intervalo = '';
        var auto = e.cancelable;
        var ntabReg = TabId.match(/\d+/g);
        var nTab = ntabReg[0];


        console.log(this)

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
    var contador = 0;
    window.intervalo = setInterval(function () {
        // console.log(contador)
        if (contador === 0) {
            $('ul>li.tablinks>a:eq(0)').trigger('click');
        }
        if (contador === 1) {
            $('ul>li.tablinks>a:eq(1)').trigger('click');
        }

        if (contador === 2) {
            $('ul>li.tablinks>a:eq(2)').trigger('click');
        }

        if (contador > 2) {
            contador = -1;
        }
        contador++;
    }, 6000);

})

