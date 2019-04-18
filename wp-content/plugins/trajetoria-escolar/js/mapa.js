var map,
    polygons = [],
    label,
    infoWindow;
jQuery(document).ready(function ($) {
    $('h1:eq(1)').after('<p class="entry-header-description">Nas redes públicas municipais e estaduais (Censo Escolar 2018 - INEP/MEC)</p>');
    $('#estados').change(function () {
        let me = $(this);
        id = parseInt($(me).val());

        $('#selecione-municipio').fadeOut();
        $('#cidades').remove();
        $('#link-ficha-estado').remove();

        if (id > 0) {
            $(me).attr('disabled', 'disabled').next().removeAttr('style');
            let coord = $('option:selected', me).data();
            for (var k in coord) {
                coord[k] = parseFloat(coord[k]);
            }
            var bounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(coord.s, coord.o),
                new google.maps.LatLng(coord.n, coord.l)
            );
            map.fitBounds(bounds);
            label.setMap(null);
            infoWindow.setMap(null);
            $.each(polygons, function (i, pol) {
                pol.setMap(null);
            });
            polygons = [];

            $.ajax({
                type: 'GET',
                url: mapa.ajaxUrl,
                data: {
                    'action': mapa.actionGetCidades,
                    'estado': id
                },
                dataType: 'json',
                success: function (cidades) {
                    let selCidades = '<select id="cidades"><option value="">--</option>';
                    $.each(cidades, function (k, v) {
                        selCidades += '<option value="' + k + '">' + v.nome + '</option>';
                        $.each(v.kmz, function (i, kmz) {
                            let path = [];
                            $.each(kmz, function (i, latLng) {
                                path.push({
                                    'lat': latLng[0],
                                    'lng': latLng[1]
                                });
                            });
                            let center = new google.maps.LatLngBounds(),
                                perc = ((v.distorcao * 100) / (v.sem_distorcao + v.distorcao)),
                                color = '#848484';
                            for (i = 0; i < path.length; i++) {
                                center.extend(path[i]);
                            }
                            if (perc < 5) {
                                color = '#3582A9';
                            } else if (perc < 10) {
                                color = '#7FA8AD';
                            } else if (perc < 20) {
                                color = '#CFD59C';
                            } else if (perc < 40) {
                                color = '#FEBE8A';
                            } else if (perc < 60) {
                                color = '#FD7E5E';
                            } else {
                                color = '#E73A3B'
                            }

                            let pol = new google.maps.Polygon({
                                map: map,
                                clickable: true,
                                fillColor: color,
                                fillOpacity: 0.75,
                                strokeColor: '#FFF',
                                strokeWeight: 1,
                                strokeOpacity: 1,
                                paths: path,
                                //
                                id: parseInt(k),
                                nome: v.nome,
                                semDistorcao: v.sem_distorcao,
                                distorcao: v.distorcao,
                                kmz: path,
                                center: center.getCenter()
                            });
                            google.maps.event.addListener(pol, 'click', function (e) {
                                let content = '<div class="iw">';
                                content +='<h3 class="map-nome">' + this.nome + '</h3>';
                                content += '<section class="map-section"><span class="map-total">' + this.distorcao.toLocaleString('pt-BR') + ' (' + ((pol.distorcao * 100) / (pol.distorcao + pol.semDistorcao)).toFixed(1).replace('.', ',') + '%)</span> crianças e adolescentes em distorção idade-série.</section>';
                                content += '<a href="' + mapa.siteUrl + 'painel/municipio/' + this.id + '/" class="map-button">Ver os dados</a>';
                                content += '</div>';
                                infoWindow.setContent(content);
                                infoWindow.setPosition(this.center);
                                $('#link-ficha-estado').fadeOut(100, function () {
                                    infoWindow.open(map);
                                });
                                if (typeof ga == 'function') {
                                    ga('send', 'pageview', '/#' + this.id);
                                }
                            });
                            google.maps.event.addListener(pol, 'mouseover', function (e) {
                                var labelOptions = {
                                    content: '<section>' + this.nome + '</section>',
                                    boxStyle: {
                                        textAlign: 'center',
                                        fontFamily: 'raleway-bold',
                                        fontSize: '12pt',
                                        color: '#3a6171',
                                        zIndex: 10000,
                                    },
                                    disableAutoPan: true,
                                    pixelOffset: new google.maps.Size(-25, 0),
                                    position: this.center,
                                    closeBoxURL: '',
                                    isHidden: false,
                                    pane: 'floatPane',
                                    enableEventPropagation: true
                                };
                                label.setOptions(labelOptions);
                                label.open(map);
                                pol.set('strokeColor', '#3b616e');
                                pol.set('zIndex', 2);
                            });
                            google.maps.event.addListener(pol, 'mouseout', function (e) {
                                label.setMap(null);
                                pol.set('strokeColor', '#FFF');
                                pol.set('zIndex', 1);
                            });
                            polygons.push(pol);
                        });
                    });
                    selCidades += '</select>';
                    google.maps.event.addListener(infoWindow, 'closeclick', function () {
                        $('#link-ficha-estado').fadeIn(100);
                    });
                    $(me).removeAttr('disabled').next().hide();
                    $('#selecione-municipio').fadeIn().after(selCidades);
                    window.location.hash = id;
                    if (typeof ga == 'function') {
                        let url = window.location.href;
                        url = url.replace(mapa.siteUrl, '/');
                        ga('send', 'pageview', url);
                    }
                    $('#mapa').prepend('<a id="link-ficha-estado" style="display:none;" href="' + mapa.siteUrl + '/painel/estado/' + id + '/">Ver dados do estado</a>');
                    $('#link-ficha-estado').fadeIn(100);
                }
            });
        }
    });
    $(document).on('change', '#cidades', function () {
        let id = parseInt($(this).val());
        if (id > 0) {
            window.location.href = mapa.siteUrl + 'painel/municipio/' + id + '/';
        }
    });
    label = new InfoBox();
    infoWindow = new google.maps.InfoWindow();

    if (window.location.hash !== '') {
        hash = window.location.hash.replace('#', '');
        $('#estados').val(hash).change();
        $('html, body').animate({
            scrollTop: $('#mapa').offset().top
        }, 'slow');
    }
});

function myMap()
{
    var mapProp = {
        center: new google.maps.LatLng(-15.6, -47.6),
        zoom: 4,
        mapTypeControl: false
    };
    map = new google.maps.Map(document.getElementById('mapa'), mapProp);
}