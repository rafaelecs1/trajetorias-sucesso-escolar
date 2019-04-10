<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IMunicipioRepository | IMunicipioRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use \Unicef\TrajetoriaEscolar\Model\Municipio;
use \Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Define os métodos para realizar as operações de criação e recuperação de informações sobre municípios
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IMunicipioRepository
{
    /**
     * Salvar um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return
     */
    function save(Municipio $municipio);
    
    /**
     * Retornar um munícipio
     *
     * @param integer $id
     * @return
     */
    function get($id = 0);
    
    /**
     * Retornar uma lista de municípios (ID e nome)
     *
     * @return array
     */
    function getList();
    
    /**
     * Retornar uma lista de municípios (ID e nome) para um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return array
     */
    function getByEstado(Estado $estado, $anoReferencia = 0);
}
