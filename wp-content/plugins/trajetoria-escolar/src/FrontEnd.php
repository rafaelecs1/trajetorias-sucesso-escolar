<?php
/**
 * Unicef\TrajetoriaEscolar\FrontEnd | FrontEnd.php
 * @author André Keher
 */

namespace Unicef\TrajetoriaEscolar;

use Unicef\TrajetoriaEscolar\Model\Estado;
use Unicef\TrajetoriaEscolar\Model\Municipio;

use Unicef\TrajetoriaEscolar\Repository\MySQLDistorcaoRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLEstadoRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLMunicipioRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLEscolaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLMapaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository;

/**
 * Classe que implementa os requisitos para o front-end (páginas)
 *
 * @package Unicef\TrajetoriaEscolar
 * @author André Keher
 * @version 2018
 */
class FrontEnd
{
    /**
     * * Configura callbacks para shortcodes, actions e filters para customizar as páginas do site
     *
     * @return void
     */
    public function __construct()
    {
        //Shortcodes
        add_shortcode('painel_distorcao', array($this, 'painelDistorcao'));
        add_shortcode('mapa_brasil', array($this, 'mapaBarsil'));
        add_shortcode('painel_distorcao_brasil', array($this, 'painelDistorcaoBrasil'));
        add_shortcode('mapa_distorcao', array($this, 'mapaDistorcao'));

        add_action('wp_ajax_get_cidades', array($this, 'mapaGetCidades'));
        add_action('wp_ajax_nopriv_get_cidades', array($this, 'mapaGetCidades'));

        add_action('wp_ajax_get_escolas', array($this, 'getEscolas'));
        add_action('wp_ajax_nopriv_get_escolas', array($this, 'getEscolas'));

        add_action('wp', array($this, 'atualizarTitulo'));

        //Filters
        add_filter('the_content', array($this, 'proxyParaMaterial'));
    }

    /**
     * Cria os filtros de estados e municípios, a legenda e o mapa com a API do GOOGLE MAPS
     *
     * @return string Marcação HTML
     */
    public function mapaDistorcao()
    {
        $rEstado = new MySQLEstadoRepository();
        $estados = $rEstado->getLimites();
        ob_start();
        ?>
        <section id="container-filtros-e-legenda" class="home-col">
            <div class="filtros-e-legenda">
                <section id="filtros">
                    <!--header><h3>Selecione um ano:</h3></header>
                    <select id="ano">
                        <option value="2018">2018</option>
                        <option value="2019">2019</option>
                    </select -->
                </section>
                <section id="filtros">
                    <header><h3>Selecione um estado:</h3></header>
                    <select id="estados">
                        <option value="0">--</option>
                        <?php
                        foreach ($estados as $k => $v) {
                            echo sprintf(
                                '
                                <option value="%d" data-n="%f" data-s="%f" data-l="%f" data-o="%f">%s</option>',
                                $k,
                                $v['limites']['n'],
                                $v['limites']['s'],
                                $v['limites']['l'],
                                $v['limites']['o'],
                                $v['nome']
                            );
                        }
                        ?>
                    </select>
                    <img style="display: none;" alt="Processando..." title="Processando..."
                         src="<?php echo admin_url('images/loading.gif'); ?>"/>
                    <header id="selecione-municipio" style="display: none;"><h3>Selecione um município:</h3></header>
                </section>
                <section id="legenda">
                    <h3>Distorção idade-série</h3>
                    <ul>
                        <li><span></span> De 0% a 5%</li>
                        <li><span></span> De 5% a 10%</li>
                        <li><span></span> De 10% a 20%</li>
                        <li><span></span> De 20% a 40%</li>
                        <li><span></span> De 40% a 60%</li>
                        <li><span></span> De 60% a 100%</li>
                    </ul>
                </section>
            </div>
        </section>
        <section id="container-mapa" class="home-col">
            <div id="mapa"></div>
        </section>
        <?php
        wp_enqueue_script('mapa', plugin_dir_url(dirname(__FILE__)) . 'js/mapa.js', array('jquery'), false, true);
        wp_localize_script('mapa', 'mapa', array(
            'siteUrl' => site_url('/'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'actionGetCidades' => 'get_cidades',
        ));
        wp_enqueue_script('google_maps', 'https://maps.googleapis.com/maps/api/js?key=' . GOOGLE_MAPS_API_KEY . '&callback=myMap', array(), false, true);
        wp_enqueue_script('infobox', plugin_dir_url(dirname(__FILE__)) . 'js/infobox.min.js', array('google_maps'), false, true);
        return ob_get_clean();
    }

    /**
     * Retorna uma lista de cidades em formato JSON para uma chamada AJAX contendo o ID do estado
     *
     * @return string JSON contendo cidades de um estado
     */
    public function mapaGetCidades()
    {
        $mapa = null;
        extract($_GET);
        $estado = (int)(isset($estado)) ? $estado : 0;
        if (!empty($estado)) {
            $estado = new Estado($estado);

            $rMapa = new MySQLMapaRepository();
            $mapa = $rMapa->get($estado, 2019);
        }
        header('Content-type: application/json;charset=UTF-8');
        echo $mapa;
        die();
    }

    /**
     * Retorna o mapa do Brasil com dados gerais
     *
     * @return string Marcacao HTML
     */
    public function mapaBarsil(){

        $rDistorcaoMapa = new MySQLMapaRepository();
        $distorcaoMapa = $rDistorcaoMapa->getBrasil(2018);

        ob_start();
        wp_enqueue_style('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'css/mapa-nacional.css');
        wp_enqueue_script('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'js/mapa-nacional.js', array('jquery'), false, true);

        ?>

        <div class="center_mapa_inicial">

            <div class="mn_mapa_nacional">

                <section class="mn_container mn_flex center">

                    <div class="item item_1">
                        <h2>Distorção idade-série no Brasil</h2>
                        <div class="mn_fundamental_e_medio">

                            <div class="mn_fundamental">
                                <div class="conteudo">
                                    <h3>Ensino Fundamental</h3>

                                    <div class="valores">

                                        <?php $qtd_total_nacional = $distorcaoMapa->nacional['anos_iniciais'] + $distorcaoMapa->nacional['anos_finais'] + $distorcaoMapa->nacional['medio']; ?>

                                        <div class="item iniciais">
                                            <h4>Anos iniciais</h4>
                                            <div class="value value_fi"><?php echo number_format($distorcaoMapa->nacional['anos_iniciais'], 0, ',', '.' ); ?></div>
                                            <div class="perc">[<span class="perc_fi"><?php echo number_format( ($distorcaoMapa->nacional['anos_iniciais'] * 100) / $qtd_total_nacional, 0 ) ?></span>]%</div>
                                        </div>

                                        <div class="item finais">
                                            <h4>Anos finais</h4>
                                            <div class="value value_ff"><?php echo number_format($distorcaoMapa->nacional['anos_finais'], 0, ',', '.' ); ?></div>
                                            <div class="perc">[<span><?php echo number_format( ($distorcaoMapa->nacional['anos_finais'] * 100) / $qtd_total_nacional, 0 ) ?></span>]%</div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                            <div class="mn_medio">
                                <div class="conteudo">
                                    <h3>Ensino Médio</h3>

                                    <div class="valores">

                                        <div class="item unico">
                                            <div class="value value_mi"><?php echo number_format($distorcaoMapa->nacional['medio'], 0, ',', '.' ); ?></div>
                                            <div class="perc">[<span class="perc_mi"><?php echo number_format( ($distorcaoMapa->nacional['medio'] * 100) / $qtd_total_nacional, 0 ) ?></span>]%</div>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="item item_2">

                        <div class="mapa_legenda">

                            <div class="item mapa">
                                <svg version="1.1" id="svg-map" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="225px" height="225px"xml:space="preserve">

        <style type="text/css">
            .st0{fill:#64C6E3;}
            .st1{fill:#018BB3;}
            .st2{fill:#ECB615;}
            .st3{fill:#E38524;}
            .st4{fill:#CC3282;}

            .st0:hover{fill:#3E7A8B;}
            .st1:hover{fill:#055871;}
            .st2:hover{fill:#A3810F;}
            .st3:hover{fill:#9E5F14;}
            .st4:hover{fill:#9F2965;}

        </style>

                                    <a xlink:href="#norte" class="region">
                                        <g>
                                            <path class="st0" d="M160.1,95l-7.1-9.2l3.2-4.4l-3.5-1.1l-3.6-4.4l1-8.5l-5.4-2.8l8.5-9l4.9-13.9l-10.6-2l-4.2,6.8l-7.4,0.4l-1-0.6
l6-2.3c0,0,2.9-5,3.2-5.3s1.8-2,1.2-2.4s-5.4,1.6-5.8,1.1s-5.1-1.2-5.1-1.2l-1,9.1l-4.2-2.5l-5,0.3c0.5-2.5,1.4-6.7,1.8-6.7
c0.5,0,5.2-4.9,6-5.2s4.2-3.3,4.2-3.3l-2-2.5l-3-2.5c0,0-0.5-3.5-0.6-3.9s-2.5-4.8-2.5-4.8l-0.1-4.5L122,22.3l-6.8,0.8l-7.2,1.4
l-0.5-3.6H102l-1.1,2.6l-14.6,4.5l0,0.2C84,26.4,81.1,24,81.1,24l-3.4-1.8l-0.3-6.9l2.7-5.1l-2-3l-0.6-3.1l-2,2c0,0-3.8,2-4.3,2.2
s-4.5,0-4.5,0l-3.5,2.7l-2.4,1.5l-5-1.2c0,0-2.7-1.8-3.2-1.9l-1.2,1.4l2.8,2.8l0.2,5.9l1.2,3.9l-0.8-0.2l-2,3.6l-6.6,3.4L37.1,29
l-3.2-2.8l-1-2.1l-8,1.4l-3.5,0.1l3.5,2.8l3.9,2.8l-2,1.4l-4.6-0.2l-1.2,0.5l4.4,6.5l-0.9,2.2l0.8,6.9l-1.2,6.2l-2.1,4.1l-6.4,1.8
l-6,0.6l-3.6,5.4l-2.1,5.8l-2.1,1.9l1.4,3l-2.9,4.8C0.6,82.4,3,84.5,3,84.5V88l3.9,1.5l1.9,2.1l7.2-2.2L17,98h9l13.4-6.5l9-0.8
l-0.8,4.6l0.8,8.6l4.2,1l7.2,2l7.8,4.4l6.9,1.2L77,109v-9l-6.3-2.1L69,91.6V86l18.2-0.4l1.9-8.1l3.4,7.9l4.2,3.6l38.4,1.8l-3.4,15.9
l3.4,1.8l4.8,3.4l2.4-3.4l3.8,2.6l7-1.2l5.8-0.5l-2.2-9.5L160.1,95z"/>
                                        </g>
                                    </a>
                                    <a xlink:href="#centro_oeste" class="region">
                                        <path class="st1" d="M75.6,112.7l3-3.6l0.4-9.7l-8.2-3.1l-0.9-4.9l0.4-4.1L87,86.5l2.4-5.8l2.2,5.8l3.3,2.7l3.5,1l35,1.8l-3.2,15.1
l3.8,1.9c0,0,5.8,4.2,6,4.2s2.3-2.8,2.3-2.8l3.2,2.1l10.3-1.6v4.1l2,2.3v1.7l-2.5,0.8l-2.4,2.8l0.6,2.6l-4.2,2.2l0.7,7.4l-2.1,4.8
l-6.2,0.5l-7.8,1.1l-3.4,3.7l-1.8,2.3v2.8l-2.4,2.6l-4.2,7.7l-5.2,4.2l-3.6,2.6l-3,4.1l-1.9,0.7l-3.8,0.6l-2.7-10.1
c0,0-10.8-0.2-10.8-0.6c0-0.3-1.1-6.2-1.1-6.2l-0.8-7.2l2.5-5.6l0.6-6.8l-3.9-8.2l-11-0.5L75.6,112.7z"/>
                                    </a>
                                    <a xlink:href="#nordeste" class="region">
                                        <path class="st2" d="M158,113v3l1.6,4.8l10.1-4.4l2.4,3.6h4.9l5.1,1.2l3.8,1c0,0,0.7,1.8,1.1,1.9s7.1,1.4,7.1,1.4v2.5l-1.9,3.2
l-2,3.4l3.8,4.4h3.1l2.2-7.2c0,0,0.9-6.6,0.8-8s-1.1-8.2-1.1-9.2s0.4-4.3,0.5-4.9s0.2-1.7,1.2-1.7s0.2,2.4,2.2,1.1s2.1-2.1,2.5-2.5
s0.6-2.6,0.6-2.6V102l7.8-9.1l5.8-5.5l1.4-5.2V75l-1.6-6.4l-0.9-3l-8.8-1.8l-9.8-6.5l-5.8-3.4H186h-3l-5.5-2H171l-4.6,1.4l0.6-4V47
l-7.4-4.4l-3.4,9.4l-5.5,8.1l-4.3,3.9l5.3,2.8l-1.1,9.6l2.8,2.6l3.8,0.5l0.9,3.6l-3,2.9l6.6,8.2l-3.6,5.9l1.1,4.1l0.8,4.9L158,113z"
                                        />
                                    </a>
                                    <a xlink:href="#sudeste" class="region">
                                        <path class="st3" d="M119.8,165.1l9.4,0.5c0,0,4.7,0.7,5.1,0.7s3.8,1.8,3.8,1.8v3.9l1.4,2.6l3.5,1.9c0,0,2.2,1.5,2.6,1.5
s2.2,1.5,2.2,1.5l7.5-7.2l6.1-1.6l6.5-2c0,0,0.6-1.6,1.3-1.8s5-0.6,5-0.6s2.8-0.1,3.4-0.1s5-1.5,5-1.5s3.4-2.7,3.8-3.2
s1.4-1.6,1.3-2.1s-1.2-2-0.6-2.6s3.5-5.1,3.5-5.1s1.4-4,2-4.4s4.2-4.5,4.1-5.2s-2.6-2-2.6-2H190l-2.2-5.5l2.4-5l1.4-2.3l-5.1-1.7
l-4.9-2.3l-1.5-0.6l-7-0.6h-2l-2.1-3.2l-8.8,3.4l-3.5-0.7l-1.2,5.1l-3,1.7l0.8,4.2l-1.2,2.7l0.6,3.1l-1.9,2.4l-5.1,1.2l-6.2-0.1
l-7.4,3.9l-0.8,3.2l-1.9,2.5l-2.8,4.1l-0.9,3.5l-0.4,1.4L119.8,165.1z"/>
                                    </a>
                                    <a xlink:href="#sul" class="region">
                                        <path class="st4" d="M92.8,209.4c0.8,0,3.4-1.3,3.4-1.3l1.8,0.5l2.4,1.5l1.9,2.3c0,0-1.4,1,0,1.1s3.1,0.5,3,0s1.5,1.5,2,1.6
s4,0.6,4,0.6l1.3,3.1l4.5,2.6c0,0,1.3,0.5,1.1,1.1s0,1.7,0,1.7l-1.4,2.2l-0.6,1.9c0,0,1.3-1.3,2-1.4c0.7-0.1,6-4.4,6-4.4l-1.6-3.2
c0,0,0.1-1.2,0.5-1.9s2.2-3,2.5-3.5s1.1-1.8,1.2-2.2s0-2.1,0-2.1s1.1-0.2,1.5-0.4s0.9-0.4,1.5-0.4s1.2,0,1.2,0v2v1c0,0-0.6,1-1,1.5
s-2,1.8-2,1.8l-1.4,1.4l-1.7,1.5c0,0,0.1,1.6,0.8,1.4s0.9-0.4,1.5-0.6s1.3-0.2,1.8-0.7c0.5-0.5,1.6-1.8,1.6-1.8l2.6-3l0.9-2.5V209
c0,0,1.6-3.4,1.9-3.8s1.5-2.1,2.1-2.5s4-2.1,4.5-2.4s1.5-1.8,1.5-1.8V194l-0.6-10.1l2-1.9l1-1.9l-8.2-5l-1.9-2.2l-0.5-2.6l-0.2-1.8
l-8.5-1.5H122h-4l-4.6,4l-2.4,3v5l1.2,1.9l1.5,3.8l1.5,2.1l-0.2,3.1l-0.4,1.4l-2.2,1.1l-3.5,1.2l-3.2,1.6l-1.9,2.3l-3,2.6
c0,0-2.9,2.1-3.1,2.5s-1.1,1.6-1.6,1.9s-2.6,1.5-2.6,1.5L92.8,209.4z"/>
                                    </a>

    </svg>
                            </div>

                            <div class="item legenda">
                                <ul>
                                    <li class="norte">Norte</li>
                                    <li class="nordeste">Nordeste</li>
                                    <li class="sudeste">Sudeste</li>
                                    <li class="sul">Sul</li>
                                    <li class="centro_oeste">Centro-Oeste</li>
                                </ul>
                            </div>

                        </div>

                    </div>

                    <div class="item item_3">

                        <div class="mr_fundamental_e_medio">

                            <div class="item mr_fundamental">

                                <div class="conteudo">

                                    <div class="cabecalho">
                                        <h3>Ensino Fundamental</h3>
                                    </div>

                                    <div class="valores">

                                        <div class="item iniciais">
                                            <div class="cabecalho">
                                                <h4>Anos iniciais</h4>
                                            </div>
                                            <ul>
                                                <li class="norte">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[6]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="nordeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[3]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="sudeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[9]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span></li>
                                                <li class="sul">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[12]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span></li>
                                                <li class="centro_oeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[0]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span></li>
                                            </ul>
                                        </div>

                                        <div class="item finais">
                                            <h4>Anos finais</h4>
                                            <ul>
                                                <li class="norte">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[7]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="nordeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[4]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="sudeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[10]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="sul">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[13]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="centro_oeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[1]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="item mr_medio">
                                <div class="conteudo">

                                    <div class="cabecalho">
                                        <h3>Ensino Médio</h3>
                                    </div>

                                    <div class="valores">

                                        <div class="item unico">
                                            <ul>
                                                <li class="norte">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[8]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="nordeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[5]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="sudeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[11]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="sul">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[14]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                                <li class="centro_oeste">
                                                    <span class="number"><?php echo number_format((int)$distorcaoMapa->regiao[2]['total'], 0, ',', '.' ); ?></span>
                                                    <span class="perc">[<span class="value">X</span>%]</span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>

                </section>

                <div class="center" style="text-align: center;">
                    <p><a id="bt_link_nacional" style="" href="/painel-brasil">Ver dados nacionais</a></p>
                </div>

            </div>

        </div>

        <?php

        return ob_get_clean();
    }

    /**
     * Coordena e exibe as informações para os painéis
     *
     * @return string
     */
    public function painelDistorcaoBrasil()
    {
        global $wp_query;
        $id = (int)(isset($wp_query->query_vars['painel_id'])) ? $wp_query->query_vars['painel_id'] : 0;
        $tipo = (isset($wp_query->query_vars['painel_tipo'])) ? $wp_query->query_vars['painel_tipo'] : '';
        $origem = $painel = null;
        $rDistorcaoPainel = new MySQLPainelRepository();
        $distorcao = $rDistorcaoPainel->getBrasil(2019);
        ob_start();
        ?>

        <section class="ficha municipio">
            <section id="redes-de-ensino">
                <header>
                    <h2>Redes de Ensino</h2>
                </header>
                <section id="total-em-distorcao">
                    <header>
                        <h3>
                            Número total de estudantes
                            <?php
                            if ($tipo !== 'escola') {
                                echo 'das redes municipal e estadual ';
                            }
                            ?>
                            em distorção idade-série
                            <?php
                            if ($tipo === 'estado') {
                                echo 'no estado';
                            } elseif ($tipo === 'municipio') {
                                echo 'no município';
                            } else {
                                echo 'na escola';
                            }
                            ?>:
                        </h3>
                    </header>
                    <?php
                    $divisor = $distorcao['sem_distorcao'] + $distorcao['distorcao'];
                    if ($divisor <= 0) {
                        $divisor = 1;
                    }
                    $percDistorcao = ($distorcao['distorcao'] * 100) / $divisor;
                    ?>
                    <div class="total"><?php echo self::formatarNumero($distorcao['distorcao']); ?> <span
                                class="perc">(<?php echo number_format($percDistorcao, 1, ',', '.'); ?>%)</span></div>
                </section>
                <?php
                if (true) {
                    foreach ($distorcao['tipo_rede'] as $rede => $ensinos) {
                        echo '<section id="rede-', strtolower($rede), '">';
                        echo '<header><h3>Rede ', $rede, '</h3></header>';
                        foreach ($ensinos as $ensino => $anos) {
                            foreach ($anos as $ano => $v) {
                                echo self::gerarAmostra('Ensino ' . $ensino . '<span class="bold">' . (($ensino !== 'Médio') ? '<br/><span class="bold">Anos ' . $ano . '</span>' : '') . '</span>', $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
                            }
                        }
                        if ($tipo === 'municipio') {
                            echo '<a class="situacao-das-escolas" data-municipio="', $id, '" data-rede="', sanitize_title($rede), '" href="#situacao-das-escolas-rede-', sanitize_title($rede), '">Situação das escolas</a>';
                            echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                        }
                        echo '</section>';
                    }
                }
                ?>
                <section id="graficos-por-tipo-ensino">
                    <?php
                    $tiposAno = array(
                        'Iniciais' => 'Anos Iniciais - Ensino Fundamental',
                        'Finais' => 'Anos Finais - Ensino Fundamental',
                        'Todos' => 'Ensino Médio',
                    );
                    $graficosPorTipoAno = array();
                    $lis = $sections = '';
                    foreach ($tiposAno as $tipoAno => $label) {
                        if (array_key_exists($tipoAno, $distorcao['anos'])) {
                            $slug = 'grafico-' . sanitize_title($label);
                            $id = str_replace('-', '_', $slug);
                            $lis .= '<li><a href="#' . $slug . '">' . $label . '</a></li>';
                            $sections .= '<section id="' . $slug . '" class="aba"><span>Número de estudantes em atraso escolar por ano</span><div id="' . $id . '" class="grafico"></div></section>';

                            foreach ($distorcao['anos'][$tipoAno] as $ano => $distorcoes) {
                                $arAux = array();
                                $arAux[] = $ano . '° ano';
                                foreach ($distorcoes as $dist) {
                                    $arAux[] = $dist;
                                }
                                $graficosPorTipoAno[$id][] = $arAux;
                            }
                        }
                    }
                    if (!empty($lis)) {
                        echo '<ul class="abas">';
                        echo $lis;
                        echo '</ul>';
                        echo $sections;
                    }
                    ?>
                </section>
                <section id="grafico-por-redes">
                    <header><h2>Total de Matrículas</h2></header>
                    <div id="grafico_por_redes" class="grafico"></div>
                    <?php
                    $graficoPorRedes = array();
                    foreach ($distorcao['tipo_rede'] as $rede => $ensinos) {
                        $arAux = array();
                        $arAux[] = $rede;
                        $semDistorcao = $distorcaoValor = 0;
                        foreach ($ensinos as $anos) {
                            foreach ($anos as $ano) {
                                $semDistorcao += $ano['sem_distorcao'];
                                $distorcaoValor += $ano['distorcao'];
                            }
                        }
                        $arAux[] = $semDistorcao;
                        $arAux[] = $distorcaoValor;
                        $graficoPorRedes[] = $arAux;
                    }
                    ?>
                </section>
            </section>
            <section id="genero">
                <header><h2>Gênero</h2></header>
                <section class="genero">
                    <?php
                    foreach ($distorcao['genero'] as $k => $v) {
                        echo self::gerarAmostra($k, $v['distorcao'], $distorcao['total_geral']);
                    }
                    ?>
                </section>
            </section>
            <section id="cor-raca">
                <header><h2>Cor/Raça</h2></header>
                <section class="cor-raca">
                    <?php
                    foreach ($distorcao['cor_raca'] as $k => $v) {
                        echo self::gerarAmostra($k, $v['distorcao'], $distorcao['total_geral']);
                    }
                    ?>
                </section>
            </section>
            <section id="localizacao">
                <header><h2>Localização</h2></header>
                <section class="localizacao">
                    <?php
                    foreach ($distorcao['localizacao'] as $k => $v) {
                        echo self::gerarAmostra($k, $v['distorcao'], $distorcao['total_geral']);
                    }
                    ?>
                </section>
                <?php
                if (!empty($distorcao['localizacao_diferenciada'])) {
                    echo '<section class="localizacao-diferenciada">';
                    foreach ($distorcao['localizacao_diferenciada'] as $k => $v) {
                        echo self::gerarAmostra($k, $v['distorcao'], $distorcao['total_geral']);
                    }
                    echo '</section>';
                }
                ?>
            </section>
        </section>

        <div class="remodal"
             data-remodal-id="situacao-das-escolas" <?php echo ($tipo === 'escola') ? 'style="display:none"' : ''; ?>>
            <button data-remodal-action="close" class="remodal-close"></button>
            <div id="lista-escolas">
            </div>
        </div>

        <?php
        if ($tipo !== 'escola') {
            wp_enqueue_style('remodal', plugin_dir_url(dirname(__FILE__)) . 'css/remodal.css');
            wp_enqueue_style('remodal_theme', plugin_dir_url(dirname(__FILE__)) . 'css/remodal-default-theme.css', array('remodal'));
            wp_enqueue_script('remodal', plugin_dir_url(dirname(__FILE__)) . 'js/remodal.js', array('jquery'), false, true);
        }

        wp_enqueue_script('painel', plugin_dir_url(dirname(__FILE__)) . 'js/painelGeral.js', array('jquery'), false, true);
        $voltar = $especificacao = null;
        if ($tipo === 'estado') {
            $voltar = '#' . $origem->getId();
            $especificacao = 'Estado:';
        } elseif ($tipo === 'municipio') {
            $voltar = '#' . $origem->getEstado()->getId();
            $especificacao = 'Município:';
        } elseif ($tipo === 'escola') {
            $voltar = 'painel/municipio/' . $origem->getMunicipio()->getId() . '/';
            $especificacao = $origem->getMunicipio()->getNome() . ' - Rede ' . $origem->getDependencia();
        }
        wp_localize_script('painel', 'painel', array(
            'siteUrl' => site_url('/'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'voltar' => $voltar,
            'especificacao' => $especificacao,
            'graficosPorTipoAno' => $graficosPorTipoAno,
            'graficoPorRedes' => $graficoPorRedes,
        ));
        wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js', null, false, true);
        return ob_get_clean();
    }

    /**
     * Coordena e exibe as informações para os painéis
     *
     * @return string
     */
    public function painelDistorcao()
    {
        global $wp_query;
        $id = (int)(isset($wp_query->query_vars['painel_id'])) ? $wp_query->query_vars['painel_id'] : 0;
        $tipo = (isset($wp_query->query_vars['painel_tipo'])) ? $wp_query->query_vars['painel_tipo'] : '';
        $origem = $painel = null;

        if (!empty($id) && in_array($tipo, array('estado', 'municipio', 'escola'))) {
            if ($tipo === 'estado') {
                $rEst = new MySQLEstadoRepository();
                $origem = $rEst->get($id);
            } elseif ($tipo === 'municipio') {
                $rMun = new MySQLMunicipioRepository();
                $origem = $rMun->get($id);
            } elseif ($tipo === 'escola') {
                $rEsc = new MySQLEscolaRepository();
                $origem = $rEsc->get($id);
            }
            if (empty($origem)) {
                return false;
            }
            $rPainel = new MySQLPainelRepository();
            $painel = $rPainel->get($origem, 2018);
            ob_start();
            ?>
            <section class="ficha <?php echo $tipo; ?>">
                <section id="redes-de-ensino">
                    <header>
                        <h2>Redes de Ensino</h2>
                    </header>
                    <section id="total-em-distorcao">
                        <header>
                            <h3>
                                Número total de estudantes
                                <?php
                                if ($tipo !== 'escola') {
                                    echo 'das redes municipal e estadual ';
                                }
                                ?>
                                em distorção idade-série
                                <?php
                                if ($tipo === 'estado') {
                                    echo 'no estado';
                                } elseif ($tipo === 'municipio') {
                                    echo 'no município';
                                } else {
                                    echo 'na escola';
                                }
                                ?>:
                            </h3>
                        </header>
                        <?php
                        $divisor = $painel['sem_distorcao'] + $painel['distorcao'];
                        if ($divisor <= 0) {
                            $divisor = 1;
                        }
                        $percDistorcao = ($painel['distorcao'] * 100) / $divisor;
                        ?>
                        <div class="total"><?php echo self::formatarNumero($painel['distorcao']); ?> <span class="perc">(<?php echo number_format($percDistorcao, 1, ',', '.'); ?>%)</span>
                        </div>
                    </section>
                    <?php
                    if ($tipo !== 'escola') {
                        foreach ($painel['tipo_rede'] as $rede => $ensinos) {
                            echo '<section id="rede-', strtolower($rede), '">';
                            echo '<header><h3>Rede ', $rede, '</h3></header>';
                            foreach ($ensinos as $ensino => $anos) {
                                foreach ($anos as $ano => $v) {
                                    echo self::gerarAmostra('Ensino ' . $ensino . '<span class="bold">' . (($ensino !== 'Médio') ? '<br/><span class="bold">Anos ' . $ano . '</span>' : '') . '</span>', $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
                                }
                            }
                            if ($tipo === 'municipio') {
                                echo '<a class="situacao-das-escolas" data-municipio="', $id, '" data-rede="', sanitize_title($rede), '" href="#situacao-das-escolas-rede-', sanitize_title($rede), '">Situação das escolas</a>';
                                echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                            }
                            echo '</section>';
                        }
                    }
                    ?>
                    <section id="graficos-por-tipo-ensino">
                        <?php
                        $tiposAno = array(
                            'Iniciais' => 'Anos Iniciais - Ensino Fundamental',
                            'Finais' => 'Anos Finais - Ensino Fundamental',
                            'Todos' => 'Ensino Médio',
                        );
                        $graficosPorTipoAno = array();
                        $lis = $sections = '';
                        foreach ($tiposAno as $tipoAno => $label) {
                            if (array_key_exists($tipoAno, $painel['anos'])) {
                                $slug = 'grafico-' . sanitize_title($label);
                                $id = str_replace('-', '_', $slug);
                                $lis .= '<li><a href="#' . $slug . '">' . $label . '</a></li>';
                                $sections .= '<section id="' . $slug . '" class="aba"><span>Número de estudantes em atraso escolar por ano</span><div id="' . $id . '" class="grafico"></div></section>';

                                foreach ($painel['anos'][$tipoAno] as $ano => $distorcoes) {
                                    $arAux = array();
                                    $arAux[] = $ano . '° ano';
                                    foreach ($distorcoes as $dist) {
                                        $arAux[] = $dist;
                                    }
                                    $graficosPorTipoAno[$id][] = $arAux;
                                }
                            }
                        }
                        if (!empty($lis)) {
                            echo '<ul class="abas">';
                            echo $lis;
                            echo '</ul>';
                            echo $sections;
                        }
                        ?>
                    </section>
                    <section id="grafico-por-redes">
                        <header><h2>Total de Matrículas</h2></header>
                        <div id="grafico_por_redes" class="grafico"></div>
                        <?php
                        $graficoPorRedes = array();
                        foreach ($painel['tipo_rede'] as $rede => $ensinos) {
                            $arAux = array();
                            $arAux[] = $rede;
                            $semDistorcao = $distorcao = 0;
                            foreach ($ensinos as $anos) {
                                foreach ($anos as $ano) {
                                    $semDistorcao += $ano['sem_distorcao'];
                                    $distorcao += $ano['distorcao'];
                                }
                            }
                            $arAux[] = $semDistorcao;
                            $arAux[] = $distorcao;
                            $graficoPorRedes[] = $arAux;
                        }
                        ?>
                    </section>
                </section>
                <section id="genero">
                    <header><h2>Gênero</h2></header>
                    <section class="genero">
                        <?php
                        foreach ($painel['genero'] as $k => $v) {
                            echo self::gerarAmostra($k, $v['distorcao'], $painel['distorcao']);
                        }
                        ?>
                    </section>
                </section>
                <section id="cor-raca">
                    <header><h2>Cor/Raça</h2></header>
                    <section class="cor-raca">
                        <?php
                        foreach ($painel['cor_raca'] as $k => $v) {
                            echo self::gerarAmostra($k, $v['distorcao'], $painel['distorcao']);
                        }
                        ?>
                    </section>
                </section>
                <section id="localizacao">
                    <header><h2>Localização</h2></header>
                    <section class="localizacao">
                        <?php
                        foreach ($painel['localizacao'] as $k => $v) {
                            echo self::gerarAmostra($k, $v['distorcao'], $painel['distorcao']);
                        }
                        ?>
                    </section>
                    <?php
                    if (!empty($painel['localizacao_diferenciada'])) {
                        echo '<section class="localizacao-diferenciada">';
                        foreach ($painel['localizacao_diferenciada'] as $k => $v) {
                            echo self::gerarAmostra($k, $v['distorcao'], $painel['distorcao']);
                        }
                        echo '</section>';
                    }
                    ?>
                </section>
            </section>
            <div class="remodal"
                 data-remodal-id="situacao-das-escolas" <?php echo ($tipo === 'escola') ? 'style="display:none"' : ''; ?>>
                <button data-remodal-action="close" class="remodal-close"></button>
                <div id="lista-escolas">
                </div>
            </div>
            <?php
            if ($tipo !== 'escola') {
                wp_enqueue_style('remodal', plugin_dir_url(dirname(__FILE__)) . 'css/remodal.css');
                wp_enqueue_style('remodal_theme', plugin_dir_url(dirname(__FILE__)) . 'css/remodal-default-theme.css', array('remodal'));
                wp_enqueue_script('remodal', plugin_dir_url(dirname(__FILE__)) . 'js/remodal.js', array('jquery'), false, true);
            }

            wp_enqueue_script('painel', plugin_dir_url(dirname(__FILE__)) . 'js/painel.js', array('jquery'), false, true);
            $voltar = $especificacao = null;
            if ($tipo === 'estado') {
                $voltar = '#' . $origem->getId();
                $especificacao = 'Estado:';
            } elseif ($tipo === 'municipio') {
                $voltar = '#' . $origem->getEstado()->getId();
                $especificacao = 'Município:';
            } elseif ($tipo === 'escola') {
                $voltar = 'painel/municipio/' . $origem->getMunicipio()->getId() . '/';
                $especificacao = $origem->getMunicipio()->getNome() . ' - Rede ' . $origem->getDependencia();
            }
            wp_localize_script('painel', 'painel', array(
                'siteUrl' => site_url('/'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'voltar' => $voltar,
                'especificacao' => $especificacao,
                'graficosPorTipoAno' => $graficosPorTipoAno,
                'graficoPorRedes' => $graficoPorRedes,
            ));
            wp_enqueue_script('google_charts', 'https://www.gstatic.com/charts/loader.js', null, false, true);
            return ob_get_clean();
        }
    }

    /**
     * Retorna uma lista de escolas em formato JSON para uma chamada AJAX contendo o ID do município
     *
     * @return string JSON contendo escolas de uma determinada rede (municipal ou estadual) em um município
     */
    public function getEscolas()
    {
        $escolas = array();
        extract($_GET);
        $municipio = (int)(isset($municipio)) ? $municipio : 0;
        $rede = strip_tags((isset($rede)) ? $rede : '');
        if (!empty($municipio) && !empty($rede)) {
            $municipio = new Municipio($municipio);
            $rEscolas = new MySQLEscolaRepository();
            if ($rede === 'municipal') {
                $escolas = $rEscolas->getListMunicipais($municipio);
            }
            if ($rede === 'estadual') {
                $escolas = $rEscolas->getListEstaduais($municipio);
            }
        }
        header('Content-type: application/json;charset=UTF-8');
        echo json_encode($escolas);
        die();
    }

    /**
     * Cria marcação HTML para uma amostra
     *
     * @param string $termo Texto que especifica a amostra
     * @param integer $valor Número que representa a quantidade da amostra
     * @param integer $total Quantidade total em que a amostra foi extraída
     * @return string
     */
    private static function gerarAmostra($termo = '', $valor = 0, $total = 0)
    {
        return '<div class="amostra"><div>' . $termo . '</div><div class="valor" data-valor="' . $valor . '" data-total="' . $total . '">' . number_format((int)$valor, 0, ',', '.') . '</div></div>';
    }

    /**
     * Formata a exibição de um número decimal
     *
     * @param integer $numero O número decimal a ser formatado
     * @param integer $decimais A quantidade de casa decimais a serem usadas na formatação
     * @return float
     */
    private static function formatarNumero($numero = 0, $decimais = 0)
    {
        return number_format($numero, $decimais, ',', '.');
    }

    /**
     * FrontEnd::proxyParaMaterial()
     *
     * @param string $content Marcação HTML original gerada pelo Wordpress
     * @return string
     */
    public function proxyParaMaterial($content)
    {
        if (is_singular('material')) {
            global $post;
            $meta = get_post_meta($post->ID);
            $downloads = 1;
            if (isset($meta['_downloads'][0])) {
                $downloads = $meta['_downloads'][0] + 1;
            }
            update_post_meta($post->ID, '_downloads', $downloads);
            ob_start();
            ?>
            <div>
                O download será iniciado em <span class="material-segundos">5</span> segundos.
                Caso isso não ocorra, por favor clique <a href="<?php echo $meta['_url'][0]; ?>"
                                                          download="<?php echo basename($meta['_url'][0]); ?>"
                                                          class="material-download">aqui</a>.
            </div>
            <?php
            wp_enqueue_script('material', plugin_dir_url(dirname(__FILE__)) . 'js/material.js', array('jquery'), false, true);
            $content = ob_get_clean();
        }
        return $content;
    }

    /**
     * Atualiza o elemento title do site quando exibindo um painel
     *
     * @return void
     */
    public function atualizarTitulo()
    {
        global $wp_query, $post, $wpdb;

        $id = (int)(isset($wp_query->query_vars['painel_id'])) ? $wp_query->query_vars['painel_id'] : 0;
        $tipo = (isset($wp_query->query_vars['painel_tipo'])) ? $wp_query->query_vars['painel_tipo'] : '';

        if (!empty($id) && in_array($tipo, array('estado', 'municipio', 'escola'))) {
            $sql = sprintf('SELECT nome FROM te_%ss WHERE id = %d', $tipo, $id);
            $nome = $wpdb->get_var($sql);
            if (!empty($nome)) {
                //Remove a reescrita do título pelo plugin Yoast SEO para os painéis
                if (class_exists('\WPSEO_Frontend')) {
                    $wpSeoFront = \WPSEO_Frontend::get_instance();
                    remove_filter('pre_get_document_title', array($wpSeoFront, 'title'), 15);
                    remove_filter('wp_title', array($wpSeoFront, 'title'), 15);
                }
                $post->post_title = $id . ' - ' . $nome;
            }
        }
    }
}
