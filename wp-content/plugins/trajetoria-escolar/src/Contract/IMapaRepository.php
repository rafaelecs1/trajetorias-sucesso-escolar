<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IMapaRepository | IMapaRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Define os métodos para realizar as operações de criação, recuperação e eliminação de informações de cache de mapas de estados e seus municípios
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IMapaRepository
{
    /**
     * Retornar o mapa de um estado e seus municípios
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return string
     */
    function get(Estado $estado, $anoReferencia = 0);
    
    /**
     * Retornar o cache do mapa de um estado e seus municípios
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return string
     */
    function getCache(Estado $estado, $anoReferencia = 0);
    
    /**
     * Salvar o cache do mapa de um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @param string $mapa
     * @return void
     */
    function save(Estado $estado, $anoReferencia = 0, $mapa = '');
    
    /**
     * Apagar o cache de mapas de todos os estados
     *
     * @return void
     */
    function clear();
    
    /**
     * Retornar um resumo da situação de cache dos mapas
     *
     * @return array
     */
    function getDetails();
}
