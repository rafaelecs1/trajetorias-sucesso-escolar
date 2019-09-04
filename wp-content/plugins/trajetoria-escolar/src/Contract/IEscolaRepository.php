<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IEscolaRepository | IEscolaRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

use \Unicef\TrajetoriaEscolar\Model\Escola;
use \Unicef\TrajetoriaEscolar\Model\Municipio;

/**
 * Define os métodos para realizar as operações de criação e recuperação de informações sobre escolas
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IEscolaRepository
{
    /**
     * Salvar uma escola
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @return int
     */
    function save(Escola $escola);

    /**
     * Retornar uma escola
     *
     * @param integer $id
     * @return \Unicef\TrajetoriaEscolar\Model\Escola
     */
    function get($id = 0);

    /**
     * Retornar uma lista de escolas (ID e nome)
     *
     * @return array
     */
    function getList();

    /**
     * Retornar uma lista de escolas municipais (ID e nome) de um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return array
     */
    function getListMunicipais(Municipio $municipio);
    
    /**
     * Retornar uma lista de escolas estaduais (ID e nome) de um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return array
     */
    function getListEstaduais(Municipio $municipio);
}
