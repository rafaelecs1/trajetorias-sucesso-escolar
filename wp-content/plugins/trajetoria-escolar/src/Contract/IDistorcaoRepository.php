<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IDistorcaoRepository | IDistorcaoRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use \Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use \Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Define os métodos para realizar as operações de criação, atualização e recuperação de informações sobre distorção
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IDistorcaoRepository
{
    /**
     * Salvar as informações básicas/comuns relacionadas a uma distorção
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorção $distorcao
     * @return int
     */
    function save(Distorcao $distorcao);
    
    /**
     * Retornar o ID da distorção com as informações básicas/comuns
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @return int
     */
    function getDistorcaoId(Distorcao $distorcao);
    
    /**
     * Retornar as quantidades de crianças e adolescentes em distorção idade-série
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param int $anoReferencia
     * @return int
     */
    function getTotal(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes que não estão em distorção idade-série
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param int $anoReferencia
     * @return int
     */
    function getTotalSem(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de rede
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorTipoRede(IDistorcao $origem, $anoReferencia = 0);
    
     /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de ensino
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorTipoEnsino(IDistorcao $origem, $anoReferencia = 0);
    
     /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de ano
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorTipoAno(IDistorcao $origem, $anoReferencia = 0);
    
     /**
     * Retornar as quantidades detalhadas (0, 1, 2 e 3 anos) de crianças e adolescentes com e sem distorção idade-série por ano escolar
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorAno(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por localização
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorLocalizacao(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por localização diferenciada
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorLocalizacaoDiferenciada(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por cor/raça
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorCorRaca(IDistorcao $origem, $anoReferencia = 0);
    
    /**
     * Retornar as quantidades de crianças e adolescentes com e sem distorção idade-série por gênero
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    function getPorGenero(IDistorcao $origem, $anoReferencia = 0);
}
