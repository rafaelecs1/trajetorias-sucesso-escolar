jQuery(document).ready(function ($) {

    /*
        Configurações para uso do Chartjs ORG
        https://www.chartjs.org/docs/latest/configuration/legend.html
    */

    //Interface
    $('#voltar').attr('href', painel.link);

    var color = Chart.helpers.color;

    var tipos_trajetorias = [
        {id: 1, cssSelector: 'trajetoria1', title: 'Matrículas iniciais de 6 anos no 1º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(), borderColor: window.chartColors.red },
        {id: 2, cssSelector: 'trajetoria2', title: 'Matrículas iniciais de 10 anos no 5º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(), borderColor: window.chartColors.green },
        {id: 3, cssSelector: 'trajetoria3', title: 'Matrículas iniciais de 14 anos no 9º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(), borderColor: window.chartColors.blue }
    ];

    $('#select-uf').change(function () {
        let me = $(this);
        id = parseInt($(me).val());
        if (id > 0){
            window.location.href = painel.siteUrl + 'painel-trajetorias/'+id+'/+#primary';
        }
        if (id == 0){
            window.location.href = painel.siteUrl + 'painel-trajetorias';
        }
    });

    $(document).on('change', '#select-municipio', function () {
        let me = $(this);
        id = parseInt($(me).val());
        if (id > 0){
            window.location.href = painel.siteUrl + 'painel-trajetorias/'+painel.uf+'/'+id+'/#primary';
        }
    });

    function getDatasetsByArrayTrajetorias(trajetoriasArray, trajetoriaObj){
        var datasets = [];
        tipos_trajetorias.forEach( function(e){
            
            if(e.id === trajetoriaObj.id){
                datasets.push({
                    label: e.title,
                    backgroundColor: e.backgroundColor,
                    borderColor: e.borderColor,
                    borderWidth: 1,
                    data: getDataByTipoTrajetoriaId(e.id, trajetoriasArray)
                });
            }

        });
        return datasets;
    };

    function getDataByTipoTrajetoriaId(idTrajetoria, trajetoriasArray){
        var data = [];
        trajetoriasArray.forEach(function(e){
            if( idTrajetoria == parseInt(e.tipo) ){
                data.push( parseInt(e.matriculas) );
            }
        });
        return data;
    };

    function getLabelsTrajetoriasByArray(trajetoriasArray, trajetoriaObj){
        var labels = [];
        trajetoriasArray.forEach( function(e) {
            if (trajetoriaObj.id === parseInt(e.tipo)){
                if($.inArray(e.ano, labels) === -1) labels.push(e.ano);
            }
        });
        return labels;
    };

     //Tem UF selecionada? Carrega as cidades e coloca o seletor no local
    if( painel.uf != null ){
            
    $.ajax({
        type: 'GET',
        url: painel.ajaxUrl,
        data: {
            'action': painel.actionGetCidades,
            'estado': painel.uf
        },
        dataType: 'json',
        success: function (cidades) {
            let selCidades = '<div id="municipio_selector" class="item_seletores"><label>Município</label><select class="select" name="select-municipio" id="select-municipio"><option value="">--</option>';
            $.each(cidades, function (k, v) {
                if( painel.municipio != null && painel.municipio == k){
                    selCidades += '<option value="' + k + '" selected>' + v.nome + '</option>';
                }else{
                    selCidades += '<option value="' + k + '">' + v.nome + '</option>';
                }
            });
            selCidades += '</select></div>';
            $('#municipio_selector').fadeIn().replaceWith(selCidades);
        }
    });

    } 

    //percorrer os tres tipos de trajetorias para geracao de graficos separados
    //cada trajetoria tem um seletor css associado a div do front para incorporar o grafico
    tipos_trajetorias.forEach( function(trajetoria){

        var datasets = getDatasetsByArrayTrajetorias(painel.trajetorias, trajetoria);
        var labels = getLabelsTrajetoriasByArray(painel.trajetorias, trajetoria);

        var ctx = document.getElementById(trajetoria.cssSelector).getContext('2d');
    
        var barChartData = {
            labels: labels,
            datasets: datasets
        };
        
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                responsive: true,
                legend: {
                    display: false, //desativados, pois cada barra aparece em um gráfico
                },
                title: {
                    display: true,
                    fontSize: 20,
                    fontColor: '#045396',
                    fontFamily: 'steelfish',
                    text: trajetoria.title
                },
                
                tooltips: {
                    enabled: true
                },

                hover: {
                    animationDuration: 1
                },

                animation: {
                    //duration: 1,
                    onComplete: function () {
                        var chartInstance = this.chart,
                            ctx = chartInstance.ctx;
                            ctx.textAlign = 'center';
                            ctx.fillStyle = trajetoria.color;
                            ctx.textBaseline = 'bottom';

                        this.data.datasets.forEach(function (dataset, i) {
                            var meta = chartInstance.controller.getDatasetMeta(i);
                            meta.data.forEach(function (bar, index) {
                                var data = dataset.data[index];
                                ctx.fillText(data, bar._model.x, bar._model.y + 25);
                            });
                        });
                    }
                },

                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


    });

});