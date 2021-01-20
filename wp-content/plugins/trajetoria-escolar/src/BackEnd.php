<?php
/**
 * Unicef\TrajetoriaEscolar\BackEnd | BackEnd.php
 * @author André Keher
 */

namespace Unicef\TrajetoriaEscolar;

use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\LexerConfig;

use Unicef\TrajetoriaEscolar\Model\Estado;
use Unicef\TrajetoriaEscolar\Service\EstadoService;
use Unicef\TrajetoriaEscolar\Repository\MySQLEstadoRepository;

use Unicef\TrajetoriaEscolar\Model\Municipio;
use Unicef\TrajetoriaEscolar\Service\MunicipioService;
use Unicef\TrajetoriaEscolar\Repository\MySQLMunicipioRepository;

use Unicef\TrajetoriaEscolar\Model\Escola;
use Unicef\TrajetoriaEscolar\Service\EscolaService;
use Unicef\TrajetoriaEscolar\Repository\MySQLEscolaRepository;

use Unicef\TrajetoriaEscolar\Model\Distorcao;
use Unicef\TrajetoriaEscolar\Model\DistorcaoAno;
use Unicef\TrajetoriaEscolar\Model\DistorcaoRaca;
use Unicef\TrajetoriaEscolar\Model\DistorcaoGenero;
use Unicef\TrajetoriaEscolar\Service\DistorcaoService;
use Unicef\TrajetoriaEscolar\Repository\MySQLDistorcaoRepository;

use Unicef\TrajetoriaEscolar\Repository\MySQLMapaRepository;
use Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository;

/**
 * Classe que implementa os requisitos para o back-end (admin)
 *
 * @package Unicef\TrajetoriaEscolar
 * @author André Keher
 * @version 2018
 */
class BackEnd
{
    //Tipos de informações que podem ser importadas
    const TIPO_ETAPAS_ENSINO = 'Etapas de ensino';
    const TIPO_COR_RACA = 'Cor/raça';
    const TIPO_GENERO = 'Gênero';

    //INFORMAÇÃO X COLUNA
    //Estado, município e escola
    const COD_ESCOLA = 0;
    const REGIAO = 1;
    const ESTADO = 2;
    const COD_MUNICIPIO = 3;
    const MUNICIPIO = 4;
    const ESCOLA = 5;
    const LOCALIZACAO = 6;
    const DEPENDENCIA = 7;
    const LOCALIZACAO_DIF = 8;
    //Etapas de ensino
    const INICIO_INFOS_ANOS = 9;
    const FIM_INFOS_ANOS = 57;
    //Cor/raça
    const INICIO_INFOS_COR = 9;
    const FIM_INFOS_COR = 75;
    //Gênero
    const INICIO_INFOS_GENERO = 9;
    const FIM_INFOS_GENERO = 31;

    /**
     * Configura callbacks para actions e filters para customizar o admin.
     * Ajusta o fuso horário para São Paulo
     *
     * @return void
     */
    public function __construct()
    {
        //Actions
        add_action('admin_menu', array($this, 'menu'));
        add_action('admin_init', array($this, 'importarCsv'), 0);
        add_action('admin_init', array($this, 'limparCaches'), 0);

        add_action('init', array($this, 'reescritaDeUrl'), 10, 0);

        add_action('init', array($this, 'init'));
        add_action('add_meta_boxes', array($this, 'adicionarMetabox'));
        add_action('save_post', array($this, 'salvarMetabox'));

        //Filter
        add_filter('manage_edit-material_columns', array($this, 'adicionarColunas'));
        add_filter('manage_material_posts_custom_column', array($this, 'obterDadosParaColunas'), 10, 3);

        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * Adiciona os itens de menu para as páginas no admin para importação e limpeza de cache
     *
     * @return void
     */
    public function menu()
    {
        if (current_user_can('manage_options')) {
            add_menu_page('Importar informações sobre distorção idade-série no Brasil', 'Importação', 'manage_options', 'importar_csv', array($this, 'formImportarCsv'), '', null, 6);
            add_menu_page('Limpar caches', 'Limpar caches', 'manage_options', 'limpar_caches', array($this, 'formLimparCaches'), '', null, 6);
        }
    }


    /**
     * Cria o formulário para importar informações sobre distorção idade-série no Brasil
     *
     * @return void
     */
    public function formImportarCsv()
    {
        wp_enqueue_style('admin', plugin_dir_url(dirname(__FILE__)) . 'css/admin.css');
        ?>
        <section>
            <header>
                <h1>Importar informações sobre distorção idade-série no Brasil</h1>
                <?php
                if (isset($_GET['resultado'])) {
                    ?>
                    <div id="message" class="updated notice notice-success is-dismissible">
                        <p>Importação realizada com sucesso! Ano Referencia: <?php echo $_GET['ano_referencia'] ?> Tipo de Dado: <?php echo $_GET['tipo_informacao'] ?></p>
                    </div>
                    <?php
                }
                ?>
            </header>
            <form method="post" enctype="multipart/form-data">
                <div class="form-item">
                    <label for="tipo_informacao">Qual o tipo de informações sobre distorção idade-série gostaria de
                        importar?</label>
                    <br/>
                    <select id="tipo_informacao" name="tipo_informacao"
                            data-validation="Por favor, informe qual o tipo de informação sobre distorção idade-série gostaria de importar.">
                        <option value="">--</option>
                        <option value="<?php echo self::TIPO_ETAPAS_ENSINO; ?>"><?php echo self::TIPO_ETAPAS_ENSINO; ?></option>
                        <option value="<?php echo self::TIPO_COR_RACA; ?>"><?php echo self::TIPO_COR_RACA; ?></option>
                        <option value="<?php echo self::TIPO_GENERO; ?>"><?php echo self::TIPO_GENERO; ?></option>
                    </select>
                </div>
                <div class="form-item">
                    <label for="ano_referencia">Qual o ano de referência das informações sobre distorção
                        idade-série?</label>
                    <br/>
                    <select id="ano_referencia" name="ano_referencia"
                            data-validation="Por favor, informe o ano de referência das informações sobre distorção idade-série.">
                        <option value="">--</option>
                        <?php
                        $anoAtual = date('Y');
                        $anos = range($anoAtual, 1970, 1);
                        foreach ($anos as $ano) {
                            echo '<option value="', $ano, '">', $ano, '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-item">
                    <label for="ano_referencia">Arquivo no formato CSV.</label>
                    <br/>
                    <input type="file" name="csv" accept=".csv" data-validation="Por favor, informe um arquivo CSV."/>
                </div>
                <fieldset>
                    <legend>Observações antes de importar:</legend>
                    <ul>
                        <li>A importação pode consumir muitos recursos do servidor. Recomenda-se realizar essa operação
                            fora do horário de pico de acessos;
                        </li>
                        <li>Certificar-se de que nenhum outro usuário está realizando publicando ou alterando
                            informações no site;
                        </li>
                        <li>Se possível, realizar um backup do banco de dados antes de realizar a importação;</li>
                        <li>Após clicar em "Importar", por favor aguarde a mensagem de confirmação da operação.</li>
                    </ul>
                </fieldset>
                <?php submit_button('Importar', 'primary', 'submit', true) ?>
            </form>
            <div id="importando" style="display: none;">
                <h3>Importando o arquivo CSV. Por favor, aguarde.</h3>
                <img src="<?php echo plugin_dir_url(dirname(__FILE__)); ?>/img/loading.gif" alt="Processando..."
                     title="Processando..."/>
            </div>
        </section>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#submit').click(function () {
                    let status = true;
                    $('form *[data-validation]').each(function () {
                        if ($(this).val() === '') {
                            $(this).addClass('error-validation');
                            alert($(this).data('validation'));
                            status = false;
                            return false;
                        }
                    });
                    let tipo = $('#tipo_informacao').val(),
                        ano = $('#ano_referencia').val();
                    if (status) {
                        if (!confirm('Atenção! Por favor, confira atentamente as opções selecionadas para a importação:\n\nTipo de informação: ' + tipo + '\nAno de referência: ' + ano + '\n\nDeseja realmente prosseguir?')) {
                            return false;
                        } else {
                            $('#wpbody-content h2, #wpbody-content div#message, #wpbody-content form').fadeOut('normal', function () {
                                $('#importando').fadeIn('fast');
                            });
                        }
                    }
                    return status;
                });
                $(document).on('click', '.error-validation', function () {
                    $(this).removeClass('error-validation');
                });
            });
        </script>
        <?php
    }

    /**
     * Retorna a quantidade de memória em bytes configurada para o PHP no servidor
     *
     * @return int
     */
    private static function getLimiteMemoria()
    {
        $memory = trim(ini_get('memory_limit'));

        $last = strtolower($memory[strlen($memory) - 1]);
        $memory = substr($memory, 0, -1);

        switch ($last) {
            case 'g':
                $memory *= 1024;
            case 'm':
                $memory *= 1024;
            case 'k':
                $memory *= 1024;
        }
        return $memory;
    }

    /**
     * Controla o processo de importação de informações sobre distorção idade-série
     *
     * @return void
     */
    public function importarCsv()
    {
        if (current_user_can('manage_options') && isset($_FILES['csv']) && !empty($_FILES['csv'])) {
            $csvSize = $_FILES['csv']['size'];
            $csvSize = $csvSize * 2;

            $memory = self::getLimiteMemoria() + $csvSize;

            ini_set('max_execution_time', 0);
            ini_set('max_input_time', 0);
            ini_set('post_max_size', $memory);
            ini_set('upload_max_filesize', $memory);
            ini_set('memory_limit', $memory);

            global $wpdb;

            $anoReferencia = (int)(isset($_POST['ano_referencia'])) ? $_POST['ano_referencia'] : '';
            $tipoInformacao = strip_tags((isset($_POST['tipo_informacao'])) ? $_POST['tipo_informacao'] : '');

            if (empty($anoReferencia)) {
                wp_die('Por favor, informe o ano de referência.');
            }
            $tipos = array(self::TIPO_ETAPAS_ENSINO, self::TIPO_COR_RACA, self::TIPO_GENERO);
            if (!in_array($tipoInformacao, $tipos)) {
                wp_die('Por favor, informe o tipo de informação que deseja importar.');
            }
            $mimes = array('application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv');
            if (!in_array($_FILES['csv']['type'], $mimes)) {
                wp_die('O formato do arquivo enviado é inválido. Por favor, informe um arquivo CSV válido.');
            }

            $config = new LexerConfig();
            $config->setDelimiter(';');
            $lexer = new Lexer($config);
            $interpreter = new Interpreter();
            $interpreter->unstrict();

            $interpreter->addObserver(function (array $columns) use (&$estados, &$municipios, &$escolas, &$anoReferencia, &$tipoInformacao) {
                foreach ($columns as $k => $v) {
                    //Verifica a codidicação do csv enviado
                    $utf = mb_detect_encoding($v, 'UTF-8, ISO-8859-1');//Adicione outras codificações se precisar verificar outros tipos. Documentação em: https://www.php.net/manual/pt_BR/function.mb-detect-encoding.php
                    // Se a codificação for diferente de UTF-8 aplica a coficação utp-8;
                    if ($utf !== 'UTF-8') {
                        $columns[$k] = trim(utf8_encode($v));
                    }
                }
                $estadoId = array_search($columns[self::ESTADO], $estados);
                $estado = new Estado($estadoId, $columns[self::ESTADO], null, $columns[self::REGIAO]);
                if (empty($estadoId)) {
                    $sEstado = new EstadoService($estado);
                    $sEstado->setCode();
                    $sEstado->setBounds();
                    $sEstado->validate();

                    $rEstado = new MySQlEstadoRepository();
                    $rEstado->save($estado);
                    $estados[$estado->getId()] = $estado->getNome();

                    $sEstado = null;
                    $rEstado = null;
                }

                $municipio = new Municipio((int)$columns[self::COD_MUNICIPIO], $columns[self::MUNICIPIO], $estado);
                if (!array_key_exists($columns[self::COD_MUNICIPIO], $municipios)) {
                    $sMunicipio = new MunicipioService($municipio);
                    $sMunicipio->validate();

                    $rMunicipio = new MySQLMunicipioRepository();
                    $rMunicipio->save($municipio);
                    $municipios[$municipio->getId()] = $municipio->getNome();

                    $sMunicipio = null;
                    $rMunicipio = null;
                }

                $escola = new Escola((int)$columns[self::COD_ESCOLA], $columns[self::ESCOLA], $municipio, $columns[self::LOCALIZACAO], $columns[self::DEPENDENCIA], $columns[self::LOCALIZACAO_DIF]);
                if (!array_key_exists($columns[self::COD_ESCOLA], $escolas)) {
                    $sEscola = new EscolaService($escola);
                    $sEscola->validate();

                    $rEscola = new MySQLEscolaRepository();
                    $rEscola->save($escola);
                    $escolas[$escola->getId()] = $escola->getNome();

                    $sEscola = null;
                    $rEscola = null;
                }

                if ($tipoInformacao === self::TIPO_ETAPAS_ENSINO) {
                    $this->importarDistorcoesAnos($columns, $escola, $anoReferencia);
                } elseif ($tipoInformacao === self::TIPO_COR_RACA) {
                    $this->importarDistorcoesCorRaca($columns, $escola, $anoReferencia);
                } elseif ($tipoInformacao === self::TIPO_GENERO) {
                    $this->importarDistorcoesGeneros($columns, $escola, $anoReferencia);
                }

                $estado = null;
                $municipio = null;
                $escola = null;
                $columns = null;
            });

            $wpdb->query('START TRANSACTION');
            try {
                $estados = (new MysqlEstadoRepository())->getList();
                $municipios = (new MySQLMunicipioRepository)->getList();
                $escolas = (new MySQLEscolaRepository)->getList();

                $lexer->parse($_FILES['csv']['tmp_name'], $interpreter);
                $wpdb->query('COMMIT');

                header('Location: ' . admin_url('admin.php?page=importar_csv&resultado=ok&ano_referencia=' . $anoReferencia . '&tipo_informacao=' . $tipoInformacao));
            } catch (\Exception $e) {
                $wpdb->query('ROLLBACK');

                $message = $e->getMessage();
                error_log($message);
                wp_die($message);
            }
        }
    }

    /**
     * Controla a importação de distorção idade-série por anos escolares
     *
     * @param array $columns Colunas do arquivo CSV
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola Escola de referência
     * @param integer $anoReferencia Ano de referência
     * @return void
     */
    private function importarDistorcoesAnos($columns = array(), Escola $escola, $anoReferencia = 0)
    {
        if (count($columns) !== 61) {
            throw new \InvalidArgumentException('A quantidade de colunas é inválida para importar as informações de etapas de ensino.');
        }
        for ($i = self::INICIO_INFOS_ANOS; $i <= self::FIM_INFOS_ANOS; $i = $i + 4) {
            $ano = DistorcaoService::getAnoByColumn($i);
            if (!empty($ano)) {
                $distorcaoAno = new DistorcaoAno(
                    $escola,
                    $anoReferencia,
                    DistorcaoService::getTipoAnoByColumn(self::INICIO_INFOS_ANOS, $i, 61, ['Iniciais', 'Finais', 'Todos']),
                    $ano,
                    $columns[$i],
                    $columns[$i + 1],
                    $columns[$i + 2],
                    $columns[$i + 3]
                );
                $sDistorcao = new DistorcaoService($distorcaoAno);
                $sDistorcao->validate();

                $rDistorcao = new MySQLDistorcaoRepository();
                $rDistorcao->save($distorcaoAno);

                $distorcaoAno = null;
                $sDistorcao = null;
                $rDistorcao = null;
            }
        }
    }

    /**
     * Controla a importação de distorção idade-série por cor/raça
     *
     * @param array $columns Colunas do arquivo CSV
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola Escola de referência
     * @param integer $anoReferencia Ano de referência
     * @return void
     */
    private function importarDistorcoesCorRaca($columns = array(), Escola $escola, $anoReferencia = 0)
    {
        if (count($columns) !== 81) {
            throw new \InvalidArgumentException('A quantidade de colunas é inválida para importar as informações de cor/raça.');
        }
        for ($i = self::INICIO_INFOS_COR; $i <= self::FIM_INFOS_COR; $i = $i + 6) {
            $distorcaoRaca = new DistorcaoRaca(
                $escola,
                $anoReferencia,
                DistorcaoService::getTipoAnoByColumn(self::INICIO_INFOS_COR, $i, 81, ['Iniciais', 'Finais', 'Todos']),
                DistorcaoService::getTipoDistorcaoByColumn(self::INICIO_INFOS_COR, $i, 81, [0, 1, 2, 3], 6),
                $columns[$i],
                $columns[$i + 1],
                $columns[$i + 2],
                $columns[$i + 3],
                $columns[$i + 4],
                $columns[$i + 5]
            );
            $sDistorcao = new DistorcaoService($distorcaoRaca);
            $sDistorcao->validate();

            $rDistorcao = new MySQLDistorcaoRepository();
            $rDistorcao->save($distorcaoRaca);

            $distorcaoRaca = null;
            $sDistorcao = null;
            $rDistorcao = null;
        }
    }

    /**
     * Controla a importação de distorção idade-série por gênero
     *
     * @param array $columns Colunas do arquivo CSV
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola Escola de referência
     * @param integer $anoReferencia Ano de referência
     * @return void
     */
    private function importarDistorcoesGeneros($columns = array(), Escola $escola, $anoReferencia = 0)
    {
        if (count($columns) !== 33) {
            throw new \InvalidArgumentException('A quantidade de colunas é inválida para importar as informações de gênero.');
        }
        for ($i = self::INICIO_INFOS_GENERO; $i <= self::FIM_INFOS_GENERO; $i = $i + 2) {
            $distorcaoGenero = new DistorcaoGenero(
                $escola,
                $anoReferencia,
                DistorcaoService::getTipoAnoByColumn(self::INICIO_INFOS_GENERO, $i, 33, ['Iniciais', 'Finais', 'Todos']),
                DistorcaoService::getTipoDistorcaoByColumn(self::INICIO_INFOS_GENERO, $i, 33, [0, 1, 2, 3], 2),
                $columns[$i],
                $columns[$i + 1]
            );
            $sDistorcao = new DistorcaoService($distorcaoGenero);
            $sDistorcao->validate();

            $rDistorcao = new MySQLDistorcaoRepository();
            $rDistorcao->save($distorcaoGenero);

            $distorcaoGenero = null;
            $sDistorcao = null;
            $rDistorcao = null;
        }
    }

    /**
     * Cria formulário para disparar as ações de limpeza dos caches
     *
     * @return void
     */
    public function formLimparCaches()
    {
        wp_enqueue_style('admin', plugin_dir_url(dirname(__FILE__)) . 'css/admin.css');
        ?>
        <section>
            <h1>Limpar caches</h1>
            <section>
                <header>
                    <h2>Limpar cache de mapas de estados</h2>
                    <?php
                    if (isset($_GET['mapas'])) {
                        ?>
                        <div id="message" class="updated notice notice-success is-dismissible">
                            <p>Limpeza realizada com sucesso!</p>
                        </div>
                        <?php
                    }
                    ?>
                </header>
                <form method="post">
                    <input type="hidden" name="limpar_cache_mapas" value="1"/>
                    <?php submit_button('Limpar', 'primary', 'submit', true) ?>
                </form>
                <?php
                $rMapas = new MySQLMapaRepository();
                $mapas = $rMapas->getDetails();
                if (!empty($mapas)) {
                    echo '<h3>Situação do cache</h3>';
                    echo '<table>';
                    foreach ($mapas as $ano => $quant) {
                        echo '<tr><td>', $ano, '</td><td>', $quant, '</td></tr>';
                    }
                    echo '</table>';
                }
                ?>
            </section>
            <section>
                <header>
                    <h2>Limpar cache de painéis de estados, municípios e escolas</h2>
                    <?php
                    if (isset($_GET['paineis'])) {
                        ?>
                        <div id="message" class="updated notice notice-success is-dismissible">
                            <p>Limpeza realizada com sucesso!</p>
                        </div>
                        <?php
                    }
                    ?>
                </header>
                <form method="post">
                    <input type="hidden" name="limpar_cache_paineis" value="1"/>
                    <?php submit_button('Limpar', 'primary', 'submit', true) ?>
                </form>
                <?php
                $rPainel = new MySQLPainelRepository();
                $paineis = $rPainel->getDetails();
                if (!empty($paineis)) {
                    echo '<h3>Situação do cache</h3>';

                    foreach ($paineis as $ano => $detalhes) {
                        echo '<h4>', $ano, '</h4>';
                        echo '<table>';
                        foreach ($detalhes as $tipo => $total) {
                            echo '<tr><td>', strtoupper($tipo), '(S)</td><td>', $total, '</td></tr>';
                        }
                        echo '</table>';
                    }
                }
                ?>
            </section>
        </section>
        <?php
    }

    /**
     * Realiza a limpeza dos caches
     *
     * @return void
     */
    public function limparCaches()
    {
        if (current_user_can('manage_options')) {
            if (isset($_POST['limpar_cache_paineis'])) {
                $rPainel = new MySQLPainelRepository();
                $rPainel->clear();
                header('Location: ' . admin_url('admin.php?page=limpar_caches&paineis=ok'));
            }
            if (isset($_POST['limpar_cache_mapas'])) {
                $rMapa = new MySQLMapaRepository();
                $rMapa->clear();
                header('Location: ' . admin_url('admin.php?page=limpar_caches&mapas=ok'));
            }
        }
    }


    /**
     * Cria a reecrita de URL (URL amigável) para os diferentes tipos de painéis
     *
     * @return void
     */
    public function reescritaDeUrl()
    {
        add_rewrite_tag('%painel_tipo%', '([^&]+)');
        add_rewrite_tag('%painel_id%', '([^&]+)');
        add_rewrite_tag('%painel_ano%', '([^&]+)');

        add_rewrite_tag('%painel_uf%', '([^&]+)');
        add_rewrite_tag('%painel_municipio%', '([^&]+)');

        add_rewrite_rule('^painel/([^/]*)/([^/]*)/([^/]*)/?', 'index.php?page_id=25&painel_tipo=$matches[1]&painel_id=$matches[2]&painel_ano=$matches[3]', 'top');
        add_rewrite_rule('^painel-brasil/([^/]*)/?', 'index.php?page_id=129&painel_ano=$matches[1]', 'top');
        
        add_rewrite_rule('^painel-trajetorias/([^/]*)/([^/]*)/([^/]*)?', 'index.php?page_id=164&painel_uf=$matches[1]&painel_municipio=$matches[2]&painel_ano=$matches[3]', 'top');
        add_rewrite_rule('^painel-trajetorias/([^/]*)/([^/]*)?', 'index.php?page_id=164&painel_uf=$matches[1]&painel_ano=$matches[2]', 'top');
        add_rewrite_rule('^painel-trajetorias/([^/]*)?', 'index.php?page_id=164&painel_ano=$matches[1]', 'top');
    }

    /**
     * Cria o tipo de post "material"
     *
     * @return void
     */
    public function init()
    {
        $domain = 'trajetoria_escolar';
        $labels = array(
            'name' => _x('Cadernos de recomendações', 'Post Type General Name', $domain),
            'singular_name' => _x('Material', 'Post Type Singular Name', $domain),
            'menu_name' => __('Materiais', $domain),
            'name_admin_bar' => __('Materiais', $domain),
            'archives' => __('Materiais', $domain),
            'attributes' => __('Atributos do material', $domain),
            'all_items' => __('Todos os materiais', $domain),
            'add_new_item' => __('Adicionar novo material', $domain),
            'add_new' => __('Adicionar novo', $domain),
            'new_item' => __('Novo material', $domain),
            'edit_item' => __('Editar material', $domain),
            'update_item' => __('Alterar material', $domain),
            'view_item' => __('Visualizar material', $domain),
            'view_items' => __('Visualizar materiais', $domain),
            'search_items' => __('Buscar material', $domain),
            'not_found' => __('Nenhum material encontrado', $domain),
            'not_found_in_trash' => __('Nenhum material encontrado na lixeira', $domain),
            'featured_image' => __('Imagem de capa do material', $domain),
            'set_featured_image' => __('Configurar imagem de capa do material', $domain),
            'remove_featured_image' => __('Remover imagem de capa do material', $domain),
            'use_featured_image' => __('Usar como imagem de capa do material', $domain),
            'insert_into_item' => __('Inserir no material', $domain),
            'uploaded_to_this_item' => __('Carregar para o material', $domain),
            'items_list' => __('Lista de materiais', $domain),
            'items_list_navigation' => __('Navegação da lista de materiais', $domain),
            'filter_items_list' => __('Lista de materiais de filtro', $domain),
        );
        $rewrite = array(
            'slug' => 'download-dos-materiais',
            'with_front' => true,
            'pages' => true,
            'feeds' => true,
        );
        $args = array(
            'label' => __('Material', $domain),
            'description' => __('Materiais', $domain),
            'labels' => $labels,
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
                'revisions',
            ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'query_var' => 'material',
            'rewrite' => $rewrite,
            'capability_type' => 'page',
        );
        register_post_type('material', $args);
    }

    /**
     * Configura a criação de uma metabox para detalhes de um material
     *
     * @return void
     */
    public function adicionarMetabox()
    {
        add_meta_box('endereco', 'Detalhes do material', array($this, 'metaboxMaterial'), 'material');
    }

    /**
     * Cria a metabox em HTML para detalhes de um material
     *
     * @param \WP_Post $post Material a ser a cadastrado
     * @return void
     */
    public function metaboxMaterial($post)
    {
        wp_enqueue_style('admin', plugin_dir_url(dirname(__FILE__)) . 'css/admin.css');
        $url = get_post_meta($post->ID, '_url', true);
        ?>
        <section>
            <label for="url">Endereço do material: </label>
            <br/>
            <input type="url" name="url" id="url" required="true" value="<?php echo $url; ?>"/>
            <i>Por favor, informe o endereço com o protocolo (ex.: http://, https://, ftp://, etc).</i>
        </section>
        <?php
    }

    /**
     * Salva as informações da metabox com detalhes de um material
     *
     * @param int $postId
     * @return void
     */
    public function salvarMetabox($postId)
    {
        if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX)) {
            return false;
        }
        $url = (isset($_POST['url'])) ? filter_var($_POST['url'], FILTER_SANITIZE_URL) : '';
        delete_post_meta($postId, '_url');
        if (!empty($url)) {
            update_post_meta($postId, '_url', $url);
        }
    }

    /**
     * Configura as colunas que serão exibidas dentro do admin na listagem de materiais
     *
     * @param array $cols Colunas configuradas por padrão pelo Wordpress
     * @return array Colunas que serão exibidas
     */
    public function adicionarColunas($cols)
    {
        unset($cols['date']);
        $cols['_url'] = __('Anexo');
        $cols['_downloads'] = __('Downloads');
        $cols['date'] = __('Date');
        return $cols;
    }

    /**
     * Exibe as informações para as novas colunas criadas no admin na listagem de materiais
     *
     * @param string $columnName
     * @return void
     */
    public function obterDadosParaColunas($columnName)
    {
        global $post, $typenow;
        if ($typenow === 'material') {
            $meta = get_post_meta($post->ID, $columnName, true);
            if ($columnName === '_url' && !empty($meta)) {
                $meta = '<a href="' . $meta . '" download="' . basename($meta) . '">Visualizar</a>';
            }
            echo $meta;
        }
    }
}
