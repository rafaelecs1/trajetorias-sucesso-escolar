jQuery(document).ready(function ($) {

    var color = Chart.helpers.color;

    var tipos_trajetorias = [
        {id: 1, title: 'Matrículas iniciais de 6 anos no 1º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(), borderColor: window.chartColors.red },
        {id: 2, title: 'Matrículas iniciais de 10 anos no 5º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(), borderColor: window.chartColors.green },
        {id: 3, title: 'Matrículas iniciais de 14 anos no 9º ano do Ensino Fundamental', backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(), borderColor: window.chartColors.blue }
    ];

    $('#select-uf').change(function () {
        let me = $(this);
        id = parseInt($(me).val());
        if (id > 0){
            window.location.href = painel.siteUrl + 'painel-trajetorias/'+id+'/';
        }
    });

    $(document).on('change', '#select-municipio', function () {
        let me = $(this);
        id = parseInt($(me).val());
        if (id > 0){
            window.location.href = painel.siteUrl + 'painel-trajetorias/'+painel.uf+'/'+id;
        }
    });

    function getDatasetsByArrayTrajetorias(trajetoriasArray){
        var datasets = [];
        tipos_trajetorias.forEach( function(e){
            datasets.push({
                label: e.title,
                backgroundColor: e.backgroundColor,
                borderColor: e.borderColor,
                borderWidth: 1,
                data: getDataByTipoTrajetoriaId(e.id, trajetoriasArray)
            });
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

    function getLabelsTrajetoriasByArray(trajetoriasArray){
        var labels = [];
        trajetoriasArray.forEach( function(e) {
            if($.inArray(e.ano, labels) === -1) labels.push(e.ano);
        });
        return labels;
    };

    window.onload = function() {

        var datasets = getDatasetsByArrayTrajetorias(painel.trajetorias);
        var labels = getLabelsTrajetoriasByArray(painel.trajetorias);

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

        var ctx = document.getElementById('myChart').getContext('2d');
    
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
                    position: 'bottom',
                    align: 'start'
                },
                title: {
                    display: true,
                    text: 'Trajetórias de Sucesso Escolar'
                }
            }
        });

    };

});