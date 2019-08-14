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
        return ' <span class="perc">(' + ((valor * 100) / total).toFixed(2).replace('.', ',') + '%)<sup class="astericos">*</sup></span>';
    }

    $('div.amostra').each(function () {
        $('div.valor', this).each(function () {
            $(this).html($(this).html() + perc($(this)));
        });
    });

    $(document).on('click', '.situacao-das-escolas', function (e)
    {
        e.preventDefault();
        let me = $(this),
            id = parseInt($(me).data('municipio')),
            rede = $(me).data('rede'),
            modal = $('[data-remodal-id=situacao-das-escolas]').remodal();

        console.log($(me).data('municipio'));

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
                            $('#lista-escolas .lista').append('<div class="escola"><a href="' + painel.siteUrl + 'painel/escola/' + o.id + '/' + (parseInt(painel.year)-1) + '/">' + o.nome + '</a></div>');
                        });
                        modal.open();
                        $('.ver-escolas').show();
                        $(me).next().hide();
                    }
                }
            });
        }


    });

    $(document).on('keyup', '#filtrar-escolas', function ()
    {
        let search = $(this).val().toUpperCase();
        $('#lista-escolas .escola a').each(function (i, o) {
            if ($(this).text().toUpperCase().indexOf(search) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    function iniciaGraficosDistorcao()
    {

        google.charts.load('current', {
            'packages': ['corechart', 'bar'],
            'language': 'pt_br'
        });

        google.charts.setOnLoadCallback(charts);

        let legendaTipoDistorcao = ['', 'Sem atraso escolar', '1 ano de atraso escolar', '2 anos de atraso escolar', '3 anos ou mais de atraso escolar'],
            legendaPorAtraso = ['Redes', 'Estudantes sem distorção idade-série', 'Estudantes em distorção idade-série'];

        for (var g in painel.graficosDistorcaoPorTipoAno) {
            painel.graficosDistorcaoPorTipoAno[g].unshift(legendaTipoDistorcao);
        }

        painel.graficoDistorcaoPorRedes.unshift(legendaPorAtraso);

        function charts() {
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
            for (var g in painel.graficosDistorcaoPorTipoAno) {
                let data = google.visualization.arrayToDataTable(
                    painel.graficosDistorcaoPorTipoAno[g]
                );
                drawChart(g, data, options);
            }
            if (document.getElementById('grafico_por_redes').innerHTML === '') {
                let data = google.visualization.arrayToDataTable(
                    painel.graficoDistorcaoPorRedes
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

        function drawChart(id, data, options) {
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
    }

    function iniciaGraficosReprovacao()
    {
        $('section.aba_reprovacao:not(:eq(0))').hide();

        $('ul.abas_reprovacoes>li:eq(0)').addClass('active');

        $('ul.abas_reprovacoes>li>a').click(function (e) {
            e.preventDefault();
            let me = $(this),
                id = $(me).attr('href'),
                par = $(me).parent();
            $('section.aba_reprovacao').fadeOut('fast', function () {
                $('div.grafico', id).empty();
            });
            $(par).addClass('active').siblings('li').not(par).removeClass('active');
            $(id).fadeIn('fast', function () {
                //charts();
            });
        });
    }

    function iniciaGraficosAbandono()
    {}

    $('a[href^=#]').click(function ()
    {
        if (typeof ga == 'function') {
            let url = window.location.href;
            url = url.replace(painel.siteUrl, '/');
            url = url.replace('#', '');
            ga('send', 'pageview', url + $(this).attr('href'));
        }
    });

    //Action for select year of panel
    $('#select-year').change(function ()
    {
        var value_link = $(this).val();
        window.location.href = value_link;
    });

    iniciaGraficosDistorcao();
    iniciaGraficosReprovacao();
    iniciaGraficosAbandono();

});