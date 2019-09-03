<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IPainelRepository | IPainelRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use Unicef\TrajetoriaEscolar\Repository\MySQLDistorcaoRepository;

/**
 * Define os métodos para realizar as operações de criação, recuperação e eliminação de informações de cache de painéis
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IPainelRepository
{
    /**
     * Retornar um painel
     *
     * Os painéis retornados podem ser de estado, município ou escola
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function get(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar o cache de um painel
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return string
     */
    function getCache(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Salvar o cache de um painel
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @param array $painel
     * @return int
     */
    function save(IDistorcao $origem, $anoReferencia = 0, $painel = array());
    
    /**
     * Apagar o cache de todos os painéis
     *
     * @return void
     */
    function clear();
    
    /**
     * Retornar um resumo da situação de cache dos painéis
     *
     * @return array
     */
    function getDetails();
}
