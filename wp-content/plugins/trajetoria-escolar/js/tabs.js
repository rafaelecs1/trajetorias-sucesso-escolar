var numbertab = 1;
var intervalo = '';

function openTab(numbertab, auto) {
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
        document.getElementById('tab-' + numbertab).style.display = "block";
        document.getElementById('tab-link-' + numbertab).classList.add('active');
        clearInterval(this.intervalo);
    } else {
        if (this.numbertab > 3) {
            this.numbertab = 1
        }
        var ntab = this.numbertab++;
        document.getElementById('tab-' + ntab).style.display = "block";
        document.getElementById('tab-link-' + ntab).classList.add('active');
    }

}

var intervalo = setInterval(function () {
    openTab(this.numbertab, true)
}, 70000);
