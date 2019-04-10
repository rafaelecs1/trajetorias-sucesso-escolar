jQuery(document).ready(function ($) {
    let interval = setInterval(function () {
        let segs = parseInt($('span.material-segundos').text());
        if (segs === 0) {
            clearInterval(interval);
            $('a.material-download')[0].click();
        } else {
            $('span.material-segundos').text(segs - 1);
        }
    }, 1000);
});
