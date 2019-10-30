<?php #
/**
 * Plugin name: Log de erros
 * LogErro
 * 
 * @package Digimag
 * @author André Keher
 * @copyright 2014
 * @version 1.0
 */
class LogErro
{
    private $_arquivoLog;

    /**
     * LogErro::LogErro()
     * Construtor da classe
     */
    function __construct()
    {
        @ini_set('display_errors', 0);
        @ini_set('error_reporting', E_ALL);
        @ini_set('log_errors', 1);
        $this->_arquivoLog = dirname(__FILE__) . '/logs.log';
        @ini_set('error_log', $this->_arquivoLog);

        add_action('admin_menu', array($this, 'Menu'));
    }

    /**
     * LogErro::Menu()
     * Cria o menu no admin do Wordpress
     */
    function Menu()
    {
        //Verificar qual o usuário logado
        $usuario = wp_get_current_user();
        if (!strcmp('admin', $usuario->user_login))
        {
            //Adicionar o menu 'Logs PHP'
            add_menu_page('Log Erro', 'Log Erro', 'manage_options', 'log-erro', array($this, 'Pagina'));
        }
    }

    /**
     * LogErro::Pagina()
     * Cria a página para visualizar os logs 
     */
    function Pagina()
    {
        // Número máximo de erros a serem exibidos
        $numeroErros = 100;
        // Número máximo de caracteres para cada erro
        $limiteCaracteres = 500;
        $permissao = current_user_can('manage_options');
        // Limpar o arquivo de log?
        if ($permissao && isset($_GET['logs_php']) && $_GET['logs_php'] == 'clear')
        {
            $handle = fopen($this->_arquivoLog, 'w');
            fclose($handle);
            echo ("<script type='text/javascript'>alert('Sucesso ao limpar arquivo de log!');</script>");
        }
        // Ler o arquivo de log
        if (file_exists($this->_arquivoLog))
        {
            $erros = file($this->_arquivoLog);
            $erros = array_reverse($erros);
            if ($erros)
            {
                echo '<p>' . count($erros) . ' erro (s).';
                if ($permissao)
                    echo ' [ <b><a href="' . get_bloginfo("url") . '/wp-admin/admin.php?page=log-erro&logs_php=clear" onclick="return confirm(\'Deseja realmente limpar o arquivo de log?\');">Limpar arquivo de log</a></b> ]';
                echo '</p>';
                echo '<div id="logs_php" style="height:250px;overflow:scroll;padding:2px;background-color:#faf9f7;border:1px solid #ccc;">';
                echo '<ol style="padding:0;margin:0;">';
                $i = 0;
                foreach ($erros as $erro)
                {
                    echo '<li style="padding:2px 4px 6px;border-bottom:1px solid #ececec;">';
                    $erroOutput = preg_replace('/\[([^\]]+)\]/', '<b>[$1]</b>', $erro, 1);
                    if (strlen($erroOutput) > $limiteCaracteres)
                    {
                        echo substr($erroOutput, 0, $limiteCaracteres) . ' [...]';
                    }
                    else
                    {
                        echo $erroOutput;
                    }
                    echo '</li>';
                    $i++;
                    if ($i > $numeroErros)
                    {
                        echo '<li style="padding:2px;border-bottom:2px solid #ccc;"><em>Exibindo ' . $numeroErros . ' erros do log.</em></li>';
                        break;
                    }
                }
                echo '</ol></div>';
            }
            else
            {
                echo '<p><strong>Parab&eacute;ns!</strong><br/>Nenhum registro de erro no log.</p>';
            }
        }
        else
        {
            echo '<p><em>N&atilde;o foi possivel acessar o arquivo de log.</em></p>';
        }
    }
}
$objLE = new LogErro();
