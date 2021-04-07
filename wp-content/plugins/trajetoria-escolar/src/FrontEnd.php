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
use Unicef\TrajetoriaEscolar\Repository\MySQLAbandonoRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLMatriculaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLMunicipioRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLEscolaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLMapaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository;use Unicef\TrajetoriaEscolar\Repository\MySQLReprovacaoRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLTrajetoriaRepository;

/**
 * Classe que implementa os requisitos para o front-end (páginas)
 *
 * @package Unicef\TrajetoriaEscolar
 * @author André Keher
 * @version 2018
 */
class FrontEnd
{

    private $year = null;
    private $default_year = 2020;
    private $years = [2016, 2017, 2018, 2019, 2020];

    /**
     * * Configura callbacks para shortcodes, actions e filters para customizar as páginas do site
     *
     * @return void
     */
    public function __construct()
    {
        $this->year = (isset($_POST['select-year'])) ? (int)$_POST['select-year'] : $this->default_year;
        $this->year = in_array($this->year, $this->years) ? $this->year : $this->default_year;

        //Shortcodes
        add_shortcode('painel_distorcao', array($this, 'painelDistorcao'));
        add_shortcode('mapa_brasil', array($this, 'mapaBrasil'));
        add_shortcode('painel_distorcao_brasil', array($this, 'painelDistorcaoBrasil'));
        add_shortcode('mapa_distorcao', array($this, 'mapaDistorcao'));

        add_shortcode('painel_trajetorias', array($this, 'painelTrajetorias'));

        add_action('wp_ajax_get_cidades', array($this, 'mapaGetCidades'));
        add_action('wp_ajax_nopriv_get_cidades', array($this, 'mapaGetCidades'));

        add_action('wp_ajax_get_abandonos', array($this, 'getAbandonos'));
        add_action('wp_ajax_nopriv_get_abandonos', array($this, 'getAbandonos'));

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

        <header class="entry-header">
            <h1 class="entry-title">Painel Distorção idade-série, reprovação e abandono </h1> <p class="entry-header-description">Brasil, estados, municípios e escolas</p>
        </header>
        <section id="container-filtros-e-legenda" class="home-col">
            <div class="filtros-e-legenda">
           <a class="map-button btn-paniel-nacional" style="" href="/painel-brasil/<?php echo $this->year - 1 ?>">Ver dados nacionais</a>
           <!--p class="color-white">Para ver os dados de estado, município ou escola selecione os filtros abaixo:</p -->
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
            'year' => $this->year - 1
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
            $mapa = $rMapa->get($estado, $this->year);
        }
        header('Content-type: application/json;charset=UTF-8');
        echo $mapa;
        die();
    }

    /*
     * Retorna um json com dados de abandono e matrículas do ano default
     */
    public function getAbandonos()
    {

        extract($_GET);
        $regiao = (int)(isset($regiao)) ? $regiao : 0;
        $estado = (int)(isset($estado)) ? $estado : 0;
        $municipio = (int)(isset($municipio)) ? $municipio : 0;

        $matriculasObj = new MySQLMatriculaRepository();
        $abandonosObj = new MySQLAbandonoRepository();

        $matriculas = new \stdClass();
        $abandonos = new \stdClass();


        if (!empty($regiao)) {
            
        }

        if (!empty($estado)) {
            $matriculas = $matriculasObj->getDataMatriculaEstado($estado, $this->year);
            $abandonos = $abandonosObj->getDataAbandonoEstado($estado, $this->year);
        }

        if (!empty($municipio)) {
            $matriculas = $matriculasObj->getDataMatriculaMunicipio($municipio, $this->year);
            $abandonos = $abandonosObj->getDataAbandonoMunicipio($municipio, $this->year);
        }

        if( empty($regiao) && !empty($estado) && !empty($municipio) ){
            $matriculas = $matriculasObj->getDataMapaBrasil($this->year);
            $abandonos = $abandonosObj->getDataPainelBrasil($this->year);
        }

        $json = array('matriculas' => $matriculas, 'abandonos' => $abandonos);
        $json_data = json_encode($json);
        
        header('Content-type: application/json;charset=UTF-8');
        echo $json_data;
        die();
    
    }

    /**
     * Retorna o mapa do Brasil com dados gerais
     *
     * @return string Marcacao HTML
     */
    public function mapaBrasil()
    {

        $rDistorcaoMapa = new MySQLMapaRepository();
        $distorcaoMapa = $rDistorcaoMapa->getBrasil($this->year);

        $matriculasObj = new MySQLMatriculaRepository();
        $matriculas = $matriculasObj->getDataMapaBrasil($this->year);

        $abandonosObj = new MySQLAbandonoRepository();
        $abandonos = $abandonosObj->getDataMapaBrasil($this->year);

        $reprovacoesObj = new MySQLReprovacaoRepository();
        $reprovacoes = $reprovacoesObj->getDataMapaBrasil($this->year);

        ob_start();
        wp_enqueue_style('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'css/mapa-nacional.css');
        wp_enqueue_style('animate', plugin_dir_url(dirname(__FILE__)) . 'css/animate.css');
        wp_enqueue_script('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'js/mapa-nacional.js', array('jquery'), false, true);
        wp_enqueue_script('waypoints', plugin_dir_url(dirname(__FILE__)) . 'js/waypoints.js', array('jquery'), false, true);
        wp_enqueue_script('counterup', plugin_dir_url(dirname(__FILE__)) . 'js/counterup.js', array('jquery'), false, true);
        wp_enqueue_script('counter', plugin_dir_url(dirname(__FILE__)) . 'js/counter.js', array('jquery'), false, true);
        wp_enqueue_script('tabs', plugin_dir_url(dirname(__FILE__)) . 'js/tabs.js', array('jquery'), false, true);

        wp_enqueue_script('trajetoria_escolar-modifications', plugin_dir_url(dirname(__FILE__)) . 'js/modifications.js', array('jquery'), false, true);
        wp_enqueue_script('trajetoria_escolar-owlcarousel', plugin_dir_url(dirname(__FILE__)) . 'css/owlcarousel/owl.carousel.min.js', array('jquery'), false, true);
        wp_enqueue_style('carousel', plugin_dir_url(dirname(__FILE__)) . 'css/owlcarousel/owl.carousel.min.css');

        ?>

        <section id="slider-tabs" class="regiao_geografica regiao_mapas">
            
            <ul class="abas" >
                <li id="tab-link-1" class="tablinks active"><a href="#distorcao-idade-serie">Distorção idade-série </a></li>
                <li id="tab-link-2" class="tablinks"><a  href="#reprovacao">Reprovação</a></li>
                <li id="tab-link-3" class="tablinks"><a href="#abandono">Abandono</a></li>
            </ul>

            <section id="tab-1" class="aba-home tabcontent active" style="display: block;">
                <?php include_once 'wp-includes/tabs_home/tab1-brasil.php'; ?>
            </section>

            <section id="tab-2" class="aba-home tabcontent" style="display: none;">
                <?php include_once 'wp-includes/tabs_home/tab2-brasil.php'; ?>
            </section>

            <section id="tab-3" class="aba-home tabcontent" style="display: none;">
                <?php include_once 'wp-includes/tabs_home/tab3-brasil.php'; ?>
            </section>
            
        </section>

        <section id="slider-tabs" class="regiao_territorial regiao_mapas">
            
            <ul class="abas" >
                <li id="tab-link-4" class="tablinks"><a href="#distorcao-idade-serie">Distorção idade-série </a></li>
                <li id="tab-link-5" class="tablinks"><a  href="#reprovacao">Reprovação</a></li>
                <li id="tab-link-6" class="tablinks"><a href="#abandono">Abandono</a></li>
            </ul>

            <section id="tab-4" class="aba-home tabcontent" style="display: block;">
                <?php include_once 'wp-includes/tabs_home/tab4-brasil.php'; ?>
            </section>

            <section id="tab-5" class="aba-home tabcontent" style="display: none;">
                <?php include_once 'wp-includes/tabs_home/tab5-brasil.php'; ?>
            </section>

            <section id="tab-6" class="aba-home tabcontent" style="display: none;">
                <?php include_once 'wp-includes/tabs_home/tab6-brasil.php'; ?>
            </section>
            
        </section>

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

        //A URL aparece com um ano de atraso
        $this->year = (isset($wp_query->query_vars['painel_ano'])) ? (int)$wp_query->query_vars['painel_ano'] + 1 : $this->default_year;
        $this->year = in_array($this->year, $this->years) ? $this->year : $this->default_year;

        $rDistorcaoPainel = new MySQLPainelRepository();
        $painel = $rDistorcaoPainel->getBrasil($this->year);

        $matriculasObj = new MySQLMatriculaRepository();
        $matriculas = $matriculasObj->getDataPainelBrasil($this->year);

        $abandonosObj = new MySQLAbandonoRepository();
        $abandonos = $abandonosObj->getDataPainelBrasil($this->year);

        $reprovacoesObj = new MySQLReprovacaoRepository();
        $reprovacoes = $reprovacoesObj->getDataPainelBrasil($this->year);

        ob_start();

        ?>
        <div class="content-select-year-painel">
            <form name="form-year" id="form-year" method="post">
                <label>Ano referência
                    <select class="select-year" id="select-year" name="select-year">
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2019/"; ?>" <?php if ((int)$this->year == 2020) {
                            echo "selected";
                        } ?> >2019
                        </option>
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2018/"; ?>" <?php if ((int)$this->year == 2019) {
                            echo "selected";
                        } ?> >2018
                        </option>
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2017/"; ?>" <?php if ((int)$this->year == 2018) {
                            echo "selected";
                        } ?> >2017
                        </option>
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2016/"; ?>" <?php if ((int)$this->year == 2017) {
                            echo "selected";
                        } ?> >2016
                        </option>
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2015/"; ?>" <?php if ((int)$this->year == 2016) {
                            echo "selected";
                        } ?> >2015
                        </option>
                    </select>
                </label>
            </form>
        </div>

        <section id="slider-tabs">
            <ul class="abas-paineis" >
                    <li id="tab-link-1" class="tablinks active"><a href="#distorcao-idade-serie">Distorção Idade-série</a></li>
                    <li id="tab-link-2" class="tablinks"><a  href="#reprovacao">Reprovação</a></li>
                    <li id="tab-link-3" class="tablinks"><a href="#abandono">Abandono</a></li>
                    <li id="tab-link-4" class=""><a href="/painel-trajetorias/<?php echo $this->year-1; ?>">Trajetórias</a></li>
            </ul>
            <section id="tab-1" class="aba-panel tabcontent active" style="display: block;">
                <?php include 'wp-includes/tabs_panels/tab1-panel-estado-municio-escola.php'; ?>
            </section>
            <section id="tab-2" class="aba-panel tabcontent" style="display: none;">
                <?php  include 'wp-includes/tabs_panels/tab2-panel-estado-municio-escola.php'; ?>
            </section>
            <section id="tab-3" class="aba-panel tabcontent" style="display: none;">
                <?php include 'wp-includes/tabs_panels/tab3-panel-estado-municio-escola.php'; ?>
            </section>
        </section>


        <?php
            if ($tipo !== 'escola') {
                wp_enqueue_style('remodal', plugin_dir_url(dirname(__FILE__)) . 'css/remodal.css');
                wp_enqueue_style('animate', plugin_dir_url(dirname(__FILE__)) . 'css/animate.css');
                wp_enqueue_style('remodal_theme', plugin_dir_url(dirname(__FILE__)) . 'css/remodal-default-theme.css', array('remodal'));
                wp_enqueue_script('remodal', plugin_dir_url(dirname(__FILE__)) . 'js/remodal.js', array('jquery'), false, true);
            }

            wp_enqueue_script('painel', plugin_dir_url(dirname(__FILE__)) . 'js/painel.js', array('jquery'), false, true);
            wp_enqueue_script('tabs', plugin_dir_url(dirname(__FILE__)) . 'js/tabs.js', array('jquery'), false, true);

            $voltar = $especificacao = null;

            wp_localize_script('painel', 'painel', array(
                'siteUrl' => site_url('/'),
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'voltar' => $voltar,
                'especificacao' => $especificacao,

                'graficosDistorcaoPorTipoAno' => $graficosPorTipoAno,
                'graficosDistorcaoPorTipoIdade' => $graficosPorTipoIdade,
                'graficoDistorcaoPorRedes' => $graficoPorRedes,

                'graficosReprovacaoPorTipoAno' => $graficosReprovacaoPorTipoAno,
                'graficoReprovacaoPorRedes' => $graficoReprovacaoPorRedes,

                'graficosAbandonoPorTipoAno' => $graficosAbandonoPorTipoAno,
                'graficoAbandonoPorRedes' => $graficoAbandonoPorRedes,

                'year' => $this->year
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

        //A URL aparece com um ano de atraso
        $this->year = (isset($wp_query->query_vars['painel_ano'])) ? (int)$wp_query->query_vars['painel_ano'] + 1 : $this->default_year;
        $this->year = in_array($this->year, $this->years) ? $this->year : $this->default_year;

        $origem = $painel = null;

        if (!empty($id) && in_array($tipo, array('estado', 'municipio', 'escola'))) {
            
            if ($tipo === 'estado') {

                $rEst = new MySQLEstadoRepository();
                $origem = $rEst->get($id);

                $matriculasObj = new MySQLMatriculaRepository();
                $matriculas = $matriculasObj->getDataMatriculaEstado($id, $this->year);

                $reprovacoesObj = new MySQLReprovacaoRepository();
                $reprovacoes = $reprovacoesObj->getDataReprovacaoEstado($id, $this->year);

                $abandonosObj = new MySQLAbandonoRepository();
                $abandonos = $abandonosObj->getDataAbandonoEstado($id, $this->year);

                $rPainel = new MySQLPainelRepository();
                $painel = $rPainel->get($origem, $this->year, "EstadoDistorcao", $origem->getId(), null);


            } elseif ($tipo === 'municipio') {

                $rMun = new MySQLMunicipioRepository();
                $origem = $rMun->get($id);

                $matriculasObj = new MySQLMatriculaRepository();
                $matriculas = $matriculasObj->getDataMatriculaMunicipio($id, $this->year);

                $reprovacoesObj = new MySQLReprovacaoRepository();
                $reprovacoes = $reprovacoesObj->getDataReprovacaoMunicipio($id, $this->year);

                $abandonosObj = new MySQLAbandonoRepository();
                $abandonos = $abandonosObj->getDataAbandonoMunicipio($id, $this->year);

                $idMunicipio = $id;

                $rPainel = new MySQLPainelRepository();
                $painel = $rPainel->get($origem, $this->year, "MunicipioDistorcao", null, $origem->getId());

            } elseif ($tipo === 'escola') {

                $rEsc = new MySQLEscolaRepository();
                $origem = $rEsc->get($id);

                $matriculasObj = new MySQLMatriculaRepository();
                $matriculas = $matriculasObj->getDataMatriculaEscola($id, $this->year);

                $reprovacoesObj = new MySQLReprovacaoRepository();
                $reprovacoes = $reprovacoesObj->getDataReprovacaoEscola($id, $this->year);

                $abandonosObj = new MySQLAbandonoRepository();
                $abandonos = $abandonosObj->getDataAbandonoEscola($id, $this->year);

                $rPainel = new MySQLPainelRepository();
                $painel = $rPainel->get($origem, $this->year, null, null, null);

            }

            if (empty($origem)) {
                return false;
            }

            ob_start();

            ?>

            <?php if ($tipo === "estado") { ?>
                <div class="content-select-year-painel">
                    <form name="form-year" id="form-year" method="post">
                        <label>Ano referência
                            <select class="select-year" id="select-year" name="select-year">
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2019"; ?>" <?php if ((int)$this->year == 2020) {
                                    echo "selected";
                                } ?> >2019
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                    echo "selected";
                                } ?> >2018
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                    echo "selected";
                                } ?> >2017
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2016"; ?>" <?php if ((int)$this->year == 2017) {
                                    echo "selected";
                                } ?> >2016
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2015"; ?>" <?php if ((int)$this->year == 2016) {
                                    echo "selected";
                                } ?> >2015
                                </option>
                            </select>
                        </label>
                    </form>
                </div>
            <?php } ?>

            <?php if ($tipo === "municipio") { ?>
                <div class="content-select-year-painel">
                    <form name="form-year" id="form-year" method="post">
                        <label>Ano referência
                            <select class="select-year" id="select-year" name="select-year">
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2019"; ?>" <?php if ((int)$this->year == 2020) {
                                    echo "selected";
                                } ?> >2019
                                </option>
                                    <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                    echo "selected";
                                } ?> >2018
                                </option>
                                    <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                    echo "selected";
                                } ?> >2017
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2016"; ?>" <?php if ((int)$this->year == 2017) {
                                    echo "selected";
                                } ?> >2016
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2015"; ?>" <?php if ((int)$this->year == 2016) {
                                    echo "selected";
                                } ?> >2015
                                </option>
                            </select>
                        </label>
                    </form>
                </div>
            <?php } ?>

            <?php if ($tipo === "escola") { ?>
                <div class="content-select-year-painel">
                    <form name="form-year" id="form-year" method="post">
                        <label>Ano referência
                            <select class="select-year" id="select-year" name="select-year">
                                 <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2019"; ?>" <?php if ((int)$this->year == 2020) {
                                    echo "selected";
                                } ?> >2019
                                </option>
                                     <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                    echo "selected";
                                } ?> >2018
                                </option>
                                     <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                    echo "selected";
                                } ?> >2017
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2016"; ?>" <?php if ((int)$this->year == 2017) {
                                    echo "selected";
                                } ?> >2016
                                </option>
                                <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2015"; ?>" <?php if ((int)$this->year == 2016) {
                                    echo "selected";
                                } ?> >2015
                                </option>
                            </select>
                        </label>
                    </form>
                </div>
            <?php } ?>

            <section id="slider-tabs">

                <ul class="abas-paineis" >

                        <li id="tab-link-1" class="tablinks active"><a href="#distorcao-idade-serie">Distorção Idade-série</a></li>
                        <li id="tab-link-2" class="tablinks"><a  href="#reprovacao">Reprovação</a></li>
                        <li id="tab-link-3" class="tablinks"><a href="#abandono">Abandono</a></li>
                        
                        <?php if ($tipo === "estado") { ?>
                            <li id="tab-link-4" class=""><a href="/painel-trajetorias/<?php echo $origem->getId(); ?>/<?php echo $this->year-1; ?>">Trajetórias</a></li>
                        <?php } ?>
                        
                        <?php if ($tipo === "municipio") { ?>
                            <li id="tab-link-4" class=""><a href="/painel-trajetorias/<?php echo $origem->getEstado()->getId()."/". $origem->getId() ?>/<?php echo $this->year-1; ?>">Trajetórias</a></li>
                        <?php } ?>

                </ul>

                <section id="tab-1" class="aba-panel tabcontent active" style="display: block;">
                    <?php
                        include_once 'wp-includes/tabs_panels/tab1-panel-estado-municio-escola.php'
                    ?>
                </section>

                <section id="tab-2" class="aba-panel tabcontent" style="display: none;">
                    <?php
                        include_once 'wp-includes/tabs_panels/tab2-panel-estado-municio-escola.php'
                    ?>
                </section>

                <section id="tab-3" class="aba-panel tabcontent" style="display: none;">
                    <?php
                        include_once 'wp-includes/tabs_panels/tab3-panel-estado-municio-escola.php'
                    ?>
                </section>

                <div class="remodal" data-remodal-id="situacao-das-escolas" <?php echo ($tipo === 'escola') ? 'style="display:none"' : ''; ?>>
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

                wp_enqueue_script('tabs', plugin_dir_url(dirname(__FILE__)) . 'js/tabs.js', array('jquery'), false, true);
                wp_enqueue_script('painel', plugin_dir_url(dirname(__FILE__)) . 'js/painel.js', array('jquery'), false, true);

                $voltar = $especificacao = null;
                if ($tipo === 'estado') {
                    $voltar = '#' . $origem->getId();
                    $especificacao = 'Estado:';
                } elseif ($tipo === 'municipio') {
                    $voltar = '#' . $origem->getEstado()->getId();
                    $especificacao = 'Município:';
                } elseif ($tipo === 'escola') {
                    $voltar = 'painel/municipio/' . $origem->getMunicipio()->getId() . '/'. (int)($this->year-1);
                    $especificacao = $origem->getMunicipio()->getNome() . ' - Rede ' . $origem->getDependencia();
                }

                wp_localize_script('painel', 'painel', array(
                    'siteUrl' => site_url('/'),
                    'ajaxUrl' => admin_url('admin-ajax.php'),
                    'voltar' => $voltar,
                    'especificacao' => $especificacao,

                    'graficosDistorcaoPorTipoAno' => $graficosPorTipoAno,
                    'graficosDistorcaoPorTipoIdade' => $graficosPorTipoIdade,
                    'graficoDistorcaoPorRedes' => $graficoPorRedes,

                    'graficosReprovacaoPorTipoAno' => $graficosReprovacaoPorTipoAno,
                    'graficoReprovacaoPorRedes' => $graficoReprovacaoPorRedes,

                    'graficosAbandonoPorTipoAno' => $graficosAbandonoPorTipoAno,
                    'graficoAbandonoPorRedes' => $graficoAbandonoPorRedes,

                    'year' => $this->year
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
        return '<div class="amostra"><div style="text-transform: capitalize;">' . $termo . '</div><div class="valor" data-valor="' . $valor . '" data-total="' . $total . '">' . number_format((int)$valor, 0, ',', '.') . '</div></div>';
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

        //para fornecer titulo para painel trajetorias
        if( strpos( $_SERVER['REQUEST_URI'], 'painel-trajetorias' ) ){

            $uf = (isset($wp_query->query_vars['painel_uf'])) ? (int)$wp_query->query_vars['painel_uf'] : null;
            $municipio = (isset($wp_query->query_vars['painel_municipio'])) ? (int)$wp_query->query_vars['painel_municipio'] : null;
            $ano = (isset($wp_query->query_vars['painel_ano'])) ? (int)$wp_query->query_vars['painel_ano'] : null;

            if( $uf == null AND $municipio == null){
                $post->post_title = "Painel Brasil";
            }

            if( $uf != null AND $municipio == null){
                $rEstado = new MySQLEstadoRepository();
                $estado = $rEstado->get($uf);
                $post->post_title = $estado->getId()." - ".$estado->getNome();
            }

            if( $uf != null AND $municipio != null){
                $rMunicipio = new MySQLMunicipioRepository();
                $municipio = $rMunicipio->get($municipio);
                $post->post_title = $municipio->getId()." - ".$municipio->getNome();
            }

        }
            
    }

    //Function to return the number of municipio where painel is type cidade
    public function getNumberMunicipioOrSchool($text)
    {
        preg_match('/[0-9]+/', $text, $m);
        return isset($m[0]) ? $m[0] : false;
    }

    //retorna o painel trajetorias
    public function painelTrajetorias(){

        global $wp_query, $post;

        $rTrajetoria = new MySQLTrajetoriaRepository();
        $rEstado = new MySQLEstadoRepository();

        //$estados = $rEstado->getLimites();

        $uf = (isset($wp_query->query_vars['painel_uf'])) ? (int)$wp_query->query_vars['painel_uf'] : null;
        $municipio = (isset($wp_query->query_vars['painel_municipio'])) ? (int)$wp_query->query_vars['painel_municipio'] : null;
        $ano = (isset($wp_query->query_vars['painel_ano'])) ? (int)$wp_query->query_vars['painel_ano'] : null;

        if( $uf == null AND $municipio == null){
            $trajetorias = $rTrajetoria->getTrajetoriasNacional();
            $link = "/painel-brasil/".$ano;
        }

        if( $uf != null AND $municipio == null){
            $trajetorias = $rTrajetoria->getTrajetoriasPorUF($uf);
            $link = "/painel/estado/".$uf."/".$ano;
        }

        if( $uf != null AND $municipio != null){
            $trajetorias = $rTrajetoria->getTrajetoriasPorCidadeId($municipio);
            $link = "/painel/municipio/".$municipio."/".$ano;
        }

        ob_start();

        ?>



        <section id="slider-tabs" style="margin-top: 300px;">

            <ul class="abas-paineis" >
                <li id="tab-link-1" class="tablinks"><a href="<?php echo $link; ?>"><</a></li>
                <li id="tab-link-2" class="tablinks active"><a  href="#">Trajetórias</a></li>
            </ul>
            <section id="tab-1" class="aba-panel tabcontent active" style="display: block;">

                <div class="ficha animated fadeIn" style="margin-top: 20px;">

                    <section id="redes-de-ensino">

                        <header>
                            <h2 class="mt-0">Painel Trajetórias</h2>
                        </header>
                        <section id="rede-trajetorias">
                            
                            <div id="painel_trajetorias">

                                <p>
                                Esse painel apresenta simulações de trajetórias educacionais entre 2015 a 2019 para três segmentos: Matriculas de 6 anos no 1º ano do Ensino Fundamental em 2015, Matrículas de 10 anos no 5º ano do Ensino Fundamental em 2015 e Matriculas de 14 anos no 9º ano do Ensino Fundamental em 2016. Para cada um desses segmentos acompanhou-se as matriculas nos anos seguintes segundo a idade. Por exemplo, para o primeiro segmento acompanhou-se as matriculas de 7 anos no 2º ano do Ensino Fundamental em 2016 e assim sucessivamente até as matriculas de 10 anos no 5º ano do Ensino Fundamental. O mesmo procedimento foi adotado para os outros segmentos. Dessa forma é possível, para a região analisada, acompanhar o processo de sucesso e de exclusão da escola a cada ano, indicando os casos mais críticos e que necessitam de ações visando garantir o direito de todos e todas à educação.
                                </p>

                                <!-- <div id="seletores">
                                
                                    <div id="uf_selector" class="item_seletores">
                                        <label>Estado</label>
                                        <select class="select" name="select-uf" id="select-uf">
                                            <option value="0">Nacional</option>
                                            <?php
                                                foreach ($estados as $k => $v) {
                                                    if ($k == $uf){
                                                        echo sprintf(
                                                            '<option value="%d" data-n="%f" data-s="%f" data-l="%f" data-o="%f" selected>%s</option>',
                                                            $k,
                                                            $v['limites']['n'],
                                                            $v['limites']['s'],
                                                            $v['limites']['l'],
                                                            $v['limites']['o'],
                                                            $v['nome']
                                                        );
                                                    }else{
                                                        echo sprintf(
                                                            '<option value="%d" data-n="%f" data-s="%f" data-l="%f" data-o="%f">%s</option>',
                                                            $k,
                                                            $v['limites']['n'],
                                                            $v['limites']['s'],
                                                            $v['limites']['l'],
                                                            $v['limites']['o'],
                                                            $v['nome']
                                                        );
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                
                                    <div class="item_seletores">
                                        <img style="display: none; margin-top: 15px; margin-left: 15px;" alt="Processando..." title="Processando..." src="<?php echo admin_url('images/loading.gif'); ?>"/>
                                    </div>

                                    <div id="municipio_selector" class="item_seletores">
                                        <label>Município</label>
                                        <select class="select" name="select-municipio" id="select-municipio">
                                            <option>Selecione o estado</option>
                                        </select>
                                    </div>

                                </div> -->

                                <canvas id="trajetoria1" height="80vh"></canvas> <br/><br/>
                                <canvas id="trajetoria2" height="80vh"></canvas> <br/><br/>
                                <canvas id="trajetoria3" height="80vh"></canvas>

                            </div>

                        </section>

                    </section>

                </div>

            </section>
            
        </section>

        <?php

        wp_enqueue_script('painel_trajetorias_utils', plugin_dir_url(dirname(__FILE__)) . 'js/painel_trajetorias_utils.js', array('jquery'), false, true);
        wp_enqueue_script('painel_trajetorias', plugin_dir_url(dirname(__FILE__)) . 'js/painel_trajetorias.js', array('jquery'), false, true);
        wp_localize_script('painel_trajetorias', 'painel', array(
            'siteUrl' => site_url('/'),
            'actionGetCidades' => 'get_cidades',
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'uf' => $uf,
            'municipio' => $municipio,
            'trajetorias' => $trajetorias,
            'link' => $link
        ));

        wp_enqueue_script('charts_js', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js', null, false, true);
        
        return ob_get_clean();
    }
}
