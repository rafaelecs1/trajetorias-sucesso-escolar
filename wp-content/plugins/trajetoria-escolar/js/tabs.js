jQuery(document).ready(function ($) {
    $('ul>li.tablinks').click(function (e) {
        var TabId = $(this).attr('id');
        e.preventDefault();

        console.log(this);

        var numbertab = 1;
        var intervalo = '';
        var auto = true;
        var ntabReg = TabId.match( /\d+/g );
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

            if (!auto) {
                // Show the current tab, and add an "active" class to the button that opened the tab
                document.getElementById('tab-' + nTab).style.display = "block";
                document.getElementById(TabId).classList.add('active');
                clearInterval(this.intervalo);
            } else {
                if (this.numbertab > 3) {
                    this.numbertab = 1
                }
                document.getElementById('tab-' + nTab).style.display = "block";
                document.getElementById(TabId).classList.add('active');
            }



// var intervalo = setInterval(function () {
//     openTab(this.numbertab, true)
// }, 7000);
    });
});

