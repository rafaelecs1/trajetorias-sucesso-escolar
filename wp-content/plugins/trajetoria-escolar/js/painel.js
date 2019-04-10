jQuery(document).ready(function ($) {
    //Interface
    $('#voltar').attr('href', painel.siteUrl + painel.voltar);

    function perc(ele)
    {
        let total = parseInt($(ele).data('total')),
            valor = parseInt($(ele).data('valor'));
        if (total <= 0) {
            total = 1;
        }
        return ' <span class="perc">(' + ((valor * 100) / total).toFixed(1).replace('.', ',') + '%)</span>';
    }
    $('div.amostra').each(function () {
        $('div.valor', this).each(function () {
            $(this).html($(this).html() + perc($(this)));
        });
    });
    $('#redes-de-ensino .perc').each(function (i, e) {
        $(e).append(' <sup class="asterisco">*</sup>');
    });
    $('#total-em-distorcao, #rede-municipal, #rede-estadual').append('<span class="legenda">* Taxa de distorção idade-serie</span>');
    $('h1:eq(1)').before('<span class="pre-h1">' + painel.especificacao + '</span>').after('<span>Perfil das crianças e adolescentes em distorção idade-série:</span>');
    $('.situacao-das-escolas').click(function (e) {
        e.preventDefault();
        let me = $(this),
            id = parseInt($(me).data('municipio')),
            rede = $(me).data('rede'),
            modal = $('[data-remodal-id=situacao-das-escolas]').remodal();
        if (id > 0) {
            $('.ver-escolas').hide();
            $(me).next().show();
            $.ajax({
                'type': 'GET',
                'url': painel.ajaxUrl,
                'data': {
                    'municipio': id,
                    'rede': rede,
                    'action': 'get_escolas'
                },
                'dataType': 'json',
                'success': function (d) {
                    if (d.length !== 0) {
                        $('#lista-escolas').html('').append('<h4>Lista de escolas</h4><h5>' + $('h1:eq(1)').text() + ' - Rede ' + rede + ':</h5><input type="search" id="filtrar-escolas" placeholder="Filtrar escolas"/><div class="lista"></div>');
                        $.each(d, function (i, o) {
                            $('#lista-escolas .lista').append('<div class="escola"><a href="' + painel.siteUrl + 'painel/escola/' + o.id + '/">' + o.nome + '</a></div>');
                        });
                        modal.open();
                        $('.ver-escolas').show();
                        $(me).next().hide();
                    }
                }
            });
        }

    });
    $(document).on('keyup', '#filtrar-escolas', function () {
        let search = $(this).val().toUpperCase();
        $('#lista-escolas .escola a').each(function (i, o) {
            if ($(this).text().toUpperCase().indexOf(search) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
    // Gráficos
    google.charts.load('current', {
        'packages': ['corechart', 'bar'],
        'language': 'pt_br'
    });
    google.charts.setOnLoadCallback(charts);
    let legendaTipoDistorcao = ['', 'Sem atraso escolar', '1 ano de atraso escolar', '2 anos de atraso escolar', '3 anos ou mais de atraso escolar'],
        legendaPorAtraso = ['Redes', 'Estudantes sem distorção idade-série', 'Estudantes em distorção idade-série'];
    for (var g in painel.graficosPorTipoAno) {
        painel.graficosPorTipoAno[g].unshift(legendaTipoDistorcao);
    }
    painel.graficoPorRedes.unshift(legendaPorAtraso);

    function charts()
    {
        let options = {
            width: '100%',
            height: 400,
            legend: {
                position: 'right',
                alignment: 'center',
                maxLines: 3,
                textStyle: {
                    fontSize: 12
                }
            },
            bar: {
                groupWidth: '90%'
            },
            vAxis: {
                format: '#,###'
            },
            isStacked: true,
            series: {
                0: {
                    color: '#c0d4e5'
                },
                1: {
                    color: '#82a9cb'
                },
                2: {
                    color: '#437eb0'
                },
                3: {
                    color: '#045396'
                },
            },
            backgroundColor: 'none',
        };
        for (var g in painel.graficosPorTipoAno) {
            let data = google.visualization.arrayToDataTable(
                painel.graficosPorTipoAno[g]
            );
            drawChart(g, data, options);
        }
        if (document.getElementById('grafico_por_redes').innerHTML === '') {
            let data = google.visualization.arrayToDataTable(
                painel.graficoPorRedes
            );
            options.series = {
                0: {
                    color: '#ffda80'
                },
                1: {
                    color: '#ffb400'
                },
            };
            drawChart('grafico_por_redes', data, options);
        }
    }

    function drawChart(id, data, options)
    {
        let chart = new google.charts.Bar(document.getElementById(id));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }
    $('section.aba:not(:eq(0))').hide();
    $('ul.abas>li:eq(0)').addClass('active');
    $('ul.abas>li>a').click(function (e) {
        e.preventDefault();
        let me = $(this),
            id = $(me).attr('href'),
            par = $(me).parent();
        vel = 'fast';
        $('section.aba').fadeOut(vel, function () {
            $('div.grafico', id).empty();
        });
        $(par).addClass('active').siblings('li').not(par).removeClass('active');
        $(id).fadeIn(vel, function () {
            charts();
        });
    });
    $(window).resize(function () {
        $('div.grafico').empty();
        charts();
    });
    $('a[href^=#]').click(function () {
        if (typeof ga == 'function') {
            let url = window.location.href;
            url = url.replace(painel.siteUrl, '/');
            url = url.replace('#', '');
            ga('send', 'pageview', url + $(this).attr('href'));
        }
    });
});