<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IEstadoRepository | IEstadoRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use \Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Define os métodos para realizar as operações de criação e recuperação de informações sobre estados
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IEstadoRepository
{
    /**
     * Salvar um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @return int
     */
    function save(Estado $estado);
    
    /**
     * Retornar um estado
     *
     * @param integer $id
     * @return \Unicef\TrajetoriaEscolar\Model\Estado
     */
    function get($id = 0);
    
    /**
     * Retornar uma lista de estados (ID e nome)
     *
     * @return array
     */
    function getList();
    
    /**
     * Retornar uma lista de estados com seus lmites norte, sul, leste e oeste
     *
     * @return array
     */
    function getLimites();
}
