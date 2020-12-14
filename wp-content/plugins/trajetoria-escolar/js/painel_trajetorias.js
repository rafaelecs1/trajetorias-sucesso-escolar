jQuery(document).ready(function ($) {

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

    window.onload = function() {

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

        var color = Chart.helpers.color;
    
        var barChartData = {
            labels: ['2015', '2016', '2017', '2018', '2019'],
            datasets: [
            
                {
                    label: 'Matrículas iniciais de 6 anos no 1º ano do Ensino Fundamental',
                    backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.red,
                    borderWidth: 1,
                    data: [
                        10.0,
                        10.0,
                        10.0,
                        10.0,
                        10.0
                    ]
                }, 
                
                {
                    label: 'Matrículas iniciais de 10 anos no 5º ano do Ensino Fundamental',
                    backgroundColor: color(window.chartColors.green).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.green,
                    borderWidth: 1,
                    data: [
                        10.0,
                        10.0,
                        10.0,
                        10.0,
                        10.0
                    ]
                },

                {
                    label: 'Matrículas iniciais de 14 anos no 9º ano do Ensino Fundamental',
                    backgroundColor: color(window.chartColors.blue).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.blue,
                    borderWidth: 1,
                    data: [
                        10.0,
                        10.0,
                        10.0,
                        10.0,
                        10.0
                    ]
                }
            ]
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