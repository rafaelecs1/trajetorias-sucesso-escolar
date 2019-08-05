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
    private $default_year = 2019;
    private $years = [2018, 2019];

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
        $matriculas = $matriculasObj->getDataBrasil($this->year);

        $abandonosObj = new MySQLAbandonoRepository();
        $abandonos = $abandonosObj->getDataBrasil($this->year);

        $reprovacoesObj = new MySQLReprovacaoRepository();
        $reprovacoes = $abandonosObj->getDataBrasil($this->year);

        ob_start();
        wp_enqueue_style('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'css/mapa-nacional.css');
        wp_enqueue_style('animate', plugin_dir_url(dirname(__FILE__)) . 'css/animate.css');
        wp_enqueue_script('mapa-nacional', plugin_dir_url(dirname(__FILE__)) . 'js/mapa-nacional.js', array('jquery'), false, true);
        wp_enqueue_script('waypoints', plugin_dir_url(dirname(__FILE__)) . 'js/waypoints.js', array('jquery'), false, true);
        wp_enqueue_script('counterup', plugin_dir_url(dirname(__FILE__)) . 'js/counterup.js', array('jquery'), false, true);
        wp_enqueue_script('counter', plugin_dir_url(dirname(__FILE__)) . 'js/counter.js', array('jquery'), false, true);
        wp_enqueue_script('tabs', plugin_dir_url(dirname(__FILE__)) . 'js/tabs.js', array('jquery'), false, true);

        ?>

        <section id="slider-tabs">
            <ul class="abas" >
                <li id="tab-link-1" class="tablinks active" onclick="openTab(1)"><a href="javascript:void(0);">Distorção idade-série </a></li>
                <li id="tab-link-2" class="tablinks" onclick="openTab(2)"><a  href="javascript:void(0);">Reprovação</a></li>
                <li id="tab-link-3" class="tablinks" onclick="openTab(3)"><a href="javascript:void(0);">Abandono</a></li>
            </ul>
            <section id="tab-1" class="aba-home tabcontent active" style="display: block;">

                <?php

                include_once 'wp-includes/tabs_home/tab1-brasil.php'

                ?>

            </section>
            <section id="tab-2" class="aba-home tabcontent" style="display: none;">
                <?php

                include_once 'wp-includes/tabs_home/tab2-brasil.php'

                ?>
            </section>
            <section id="tab-3" class="aba-home tabcontent" style="display: none;">
                <?php

                include_once 'wp-includes/tabs_home/tab3-brasil.php'

                ?>
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
        $distorcao = $rDistorcaoPainel->getBrasil($this->year);
        ob_start();

        ?>
        <div class="content-select-year-painel">
            <form name="form-year" id="form-year" method="post">
                <label>Ano referência
                    <select class="select-year" id="select-year" name="select-year">
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2018/"; ?>" <?php if ((int)$this->year == 2019) {
                            echo "selected";
                        } ?> >2018
                        </option>
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2017/"; ?>" <?php if ((int)$this->year == 2018) {
                            echo "selected";
                        } ?> >2017
                        </option>
                    </select>
                </label>
            </form>
        </div>

        <section id="slider-tabs">
            <ul class="abas" >
                <li id="tab-link-1" class="tablinks active" onclick="openTab(1)"><a href="javascript:void(0);">Distorção Idade-série</a></li>
                <li id="tab-link-2" class="tablinks" onclick="openTab(2)"><a  href="javascript:void(0);">Reprovação</a></li>
                <li id="tab-link-3" class="tablinks" onclick="openTab(3)"><a href="javascript:void(0);">Abandono</a></li>
            </ul>
            <section id="tab-1" class="aba-panel tabcontent active" style="display: block;">

                <?php

                include_once 'wp-includes/tabs_panels/tab1-panel-nacional.php'

                ?>

            </section>
            <section id="tab-2" class="aba-panel tabcontent" style="display: none;">
                <?php

                include_once 'wp-includes/tabs_panels/tab2-panel-nacional.php'

                ?>
            </section>
            <section id="tab-3" class="aba-panel tabcontent" style="display: none;">
                <?php

                include_once 'wp-includes/tabs_panels/tab3-panel-nacional.php'

                ?>
            </section>
        </section>


        <?php
        if ($tipo !== 'escola') {
            wp_enqueue_style('remodal', plugin_dir_url(dirname(__FILE__)) . 'css/remodal.css');
//            wp_enqueue_style('animate', plugin_dir_url(dirname(__FILE__)) . 'css/animate.css');
            wp_enqueue_style('remodal_theme', plugin_dir_url(dirname(__FILE__)) . 'css/remodal-default-theme.css', array('remodal'));
            wp_enqueue_script('remodal', plugin_dir_url(dirname(__FILE__)) . 'js/remodal.js', array('jquery'), false, true);

        }

        wp_enqueue_script('painel', plugin_dir_url(dirname(__FILE__)) . 'js/painelGeral.js', array('jquery'), false, true);
        $voltar = $especificacao = null;

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

        //A URL aparece com um ano de atraso
        $this->year = (isset($wp_query->query_vars['painel_ano'])) ? (int)$wp_query->query_vars['painel_ano'] + 1 : $this->default_year;
        $this->year = in_array($this->year, $this->years) ? $this->year : $this->default_year;

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
            $painel = $rPainel->get($origem, $this->year);
            ob_start();
            ?>
                <?php if ($tipo === "estado") { ?>
            <div class="content-select-year-painel">
                <form name="form-year" id="form-year" method="post">
                    <label>Ano referência
                        <select class="select-year" id="select-year" name="select-year">
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                echo "selected";
                            } ?> >2018
                            </option>
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/estado/" . substr($_SERVER['REQUEST_URI'], 15, 2) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                echo "selected";
                            } ?> >2017
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
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                echo "selected";
                            } ?> >2018
                            </option>
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/municipio/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                echo "selected";
                            } ?> >2017
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
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2018"; ?>" <?php if ((int)$this->year == 2019) {
                                echo "selected";
                            } ?> >2018
                            </option>
                            <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel/escola/" . $this->getNumberMunicipioOrSchool($_SERVER['REQUEST_URI']) . "/2017"; ?>" <?php if ((int)$this->year == 2018) {
                                echo "selected";
                            } ?> >2017
                            </option>
                        </select>
                    </label>
                </form>
            </div>
        <?php } ?>
                  <section id="slider-tabs">
            <ul class="abas" >
                <li id="tab-link-1" class="tablinks active" onclick="openTab(1)"><a href="javascript:void(0);">Distorção Idade Série</a></li>
                <li id="tab-link-2" class="tablinks" onclick="openTab(2)"><a  href="javascript:void(0);">Reprovação</a></li>
                <li id="tab-link-3" class="tablinks" onclick="openTab(3)"><a href="javascript:void(0);">Abandono</a></li>
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
                'graficosPorTipoAno' => $graficosPorTipoAno,
                'graficoPorRedes' => $graficoPorRedes,
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

    //Function to return the number of municipio where painel is type cidade
    public function getNumberMunicipioOrSchool($text)
    {
        preg_match('/[0-9]+/', $text, $m);
        return isset($m[0]) ? $m[0] : false;
    }
}
