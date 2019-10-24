jQuery(document).ready(function ($) {

    //Interface
    $('#voltar').attr('href', painel.siteUrl + painel.voltar);

    function perc(ele) {
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

    $('a[href^=#]').click(function () {
        if (typeof ga == 'function') {
            let url = window.location.href;
            url = url.replace(painel.siteUrl, '/');
            url = url.replace('#', '');
            ga('send', 'pageview', url + $(this).attr('href'));
        }
    });

    $(document).on('click', '.situacao-das-escolas', function (e) {
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
                            $('#lista-escolas .lista').append('<div class="escola"><a href="' + painel.siteUrl + 'painel/escola/' + o.id + '/' + (parseInt(painel.year) - 1) + '/">' + o.nome + '</a></div>');
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

    function iniciaGraficosDistorcao() {
        let legendaTipoDistorcao = ['', 'Sem atraso escolar', '1 ano de atraso escolar', '2 anos de atraso escolar', '3 anos ou mais de atraso escolar'],
            legendaPorAtraso = ['Redes', 'Estudantes sem distorção idade-série', 'Estudantes em distorção idade-série'];

        for (var g in painel.graficosDistorcaoPorTipoAno) {
            painel.graficosDistorcaoPorTipoAno[g].unshift(legendaTipoDistorcao);
        }

        painel.graficoDistorcaoPorRedes.unshift(legendaPorAtraso);

        $('section.aba:not(:eq(0))').hide();

        $('ul.abas>li:eq(0)').addClass('active');

        $('ul.abas>li>a').click(function (e) {
            e.preventDefault();
            if (!e.isTrigger) {
                clearInterval(window.intervalo);
            }
            let me = $(this),
                id = $(me).attr('href'),
                par = $(me).parent();
            vel = 'fast';
            $('section.aba').fadeOut(vel, function () {
                $('div.grafico', id).empty();
            });
            $(par).addClass('active').siblings('li').not(par).removeClass('active');
            $(id).fadeIn(vel, function () {
                chartsDistorcao();
            });
        });

    }

    function iniciaGraficosReprovacao() {

        let legendaTipoReprovacao = ['', 'Aprovados', 'Reprovados', 'Abandono'],
            legendaReprovacaoPorRede = ['', 'Aprovados', 'Reprovados', 'Abandono', 'Situação não informada'];

        for (var h in painel.graficosReprovacaoPorTipoAno) {
            painel.graficosReprovacaoPorTipoAno[h].unshift(legendaTipoReprovacao);
        }

        painel.graficoReprovacaoPorRedes.unshift(legendaReprovacaoPorRede);

        $('section.aba_reprovacao:not(:eq(0))').hide();

        $('ul.abas_reprovacoes>li:eq(0)').addClass('active');

        $('ul.abas_reprovacoes>li>a').click(function (e) {
            e.preventDefault();
            if (!e.isTrigger) {
                clearInterval(window.intervalo);
            }
            let me = $(this),
                id = $(me).attr('href'),
                par = $(me).parent();
            $('section.aba_reprovacao').fadeOut('fast', function () {
                $('grafico-reprovacao', id).empty();
                $('grafico_por_redes_reprovacao').empty();
            });
            $(par).addClass('active').siblings('li').not(par).removeClass('active');
            $(id).fadeIn('fast', function () {
                chartsReprovacao();
            });
        });

    }

    function iniciaGraficosAbandono() {
        let legendaTipoAbandono = ['', 'Aprovados', 'Reprovados', 'Abandono'],
            legendaAbandonoPorRede = ['', 'Aprovados', 'Reprovados', 'Abandono', 'Situação não informada'];

        for (var i in painel.graficosAbandonoPorTipoAno) {
            painel.graficosAbandonoPorTipoAno[i].unshift(legendaTipoAbandono);
        }

        painel.graficoAbandonoPorRedes.unshift(legendaAbandonoPorRede);

        $('section.aba_abandono:not(:eq(0))').hide();

        $('ul.abas_abandonos>li:eq(0)').addClass('active');

        $('ul.abas_abandonos>li>a').click(function (e) {
            e.preventDefault();
            if (!e.isTrigger) {
                clearInterval(window.intervalo);
            }
            let me = $(this),
                id = $(me).attr('href'),
                par = $(me).parent();
            $('section.aba_abandono').fadeOut('fast', function () {
                $('grafico-abandono', id).empty();
                $('grafico_por_redes_abandono').empty();
            });
            $(par).addClass('active').siblings('li').not(par).removeClass('active');
            $(id).fadeIn('fast', function () {
                chartsAbandono();
            });
        });
    }

    function chartsDistorcao() {

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
        document.getElementById('grafico_por_redes').innerHTML = '';

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

    function chartsReprovacao() {
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

        for (var g in painel.graficosReprovacaoPorTipoAno) {

            let data = google.visualization.arrayToDataTable(
                painel.graficosReprovacaoPorTipoAno[g]
            );
            drawChart(g, data, options);
        }

        document.getElementById('grafico_por_redes_reprovacao').innerHTML = '';

        if (document.getElementById('grafico_por_redes_reprovacao').innerHTML === '') {

            let data = google.visualization.arrayToDataTable(
                painel.graficoReprovacaoPorRedes
            );
            options.series = {
                3: {
                    color: '#ffb400'
                },
                2: {
                    color: '#fdbd24'
                },
                1: {
                    color: '#f9c852'
                },
                0: {
                    color: '#fad67f'
                }
            };

            options.bar = {
                groupWidth: '50%'
            };

            options.height = 400;

            drawChart('grafico_por_redes_reprovacao', data, options);
        }


    }

    function chartsAbandono() {

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

        for (var g in painel.graficosAbandonoPorTipoAno) {

            let data = google.visualization.arrayToDataTable(
                painel.graficosAbandonoPorTipoAno[g]
            );
            drawChart(g, data, options);
        }

        document.getElementById('grafico_por_redes_abandono').innerHTML = '';

        if (document.getElementById('grafico_por_redes_abandono').innerHTML === '') {

            let data = google.visualization.arrayToDataTable(
                painel.graficoAbandonoPorRedes
            );
            options.series = {
                3: {
                    color: '#ffb400'
                },
                2: {
                    color: '#fdbd24'
                },
                1: {
                    color: '#f9c852'
                },
                0: {
                    color: '#fad67f'
                }
            };

            options.bar = {
                groupWidth: '50%'
            };

            options.height = 400;

            drawChart('grafico_por_redes_abandono', data, options);
        }

    }

    iniciaGraficosDistorcao();

    if(painel.year == 2019) {
        iniciaGraficosReprovacao();
        iniciaGraficosAbandono();
    }

    google.charts.load('current', {
        'packages': ['corechart', 'bar'],
        'language': 'pt_br'
    });

    function drawChart(id, data, options) {
        let chart = new google.charts.Bar(document.getElementById(id));
        chart.draw(data, google.charts.Bar.convertOptions(options));
    }

    //resize window
    $(window).resize(function () {
        $('div.grafico').empty();
        chartsDistorcao();
        chartsAbandono();
        chartsReprovacao();
    });

    //check if tab is visible. If true reload the graph
    $('.tablinks').click(function () {
            $('div.grafico').empty();
            if (this.id === 'tab-link-1') {
                chartsDistorcao();
                $('ul.abas>li>a:eq(0)').trigger('click');
            }
            if (this.id === 'tab-link-2') {
                chartsAbandono();
                $('ul.abas_reprovacoes>li>a:eq(0)').trigger('click');
            }
            if (this.id === 'tab-link-3') {
                chartsReprovacao();
                $('ul.abas_abandonos>li>a:eq(0)').trigger('click');
            }

        }
    );


    //Action for select year of panel
    $('#select-year').change(function () {
        var value_link = $(this).val();
        window.location.href = value_link;
    });

    function iniciaCharts() {
        chartsDistorcao();
    }

    google.charts.setOnLoadCallback(iniciaCharts);

});