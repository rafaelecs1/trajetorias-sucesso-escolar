<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IDistorcao | IDistorcao.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

/**
 * Define propriedades necessárias para operar a exibição de dados
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author Sandy Santos
 * @copyright 2019
 */
interface IRestFull
{
    /**
     * Nome dos objetos instanciados
     */
    const MATRICULA = 'MySQLMatriculaRepository';
    const ABANDONO = 'MySQLAbandonoRepository';
    const REPROVACAO = 'MySQLReprovacaoRepository';
    /**
     * Nome do cache
     */
    const NACIONAL = 'Nacional';
    const NACIONAL_MATRICULA = 'NacionalMatricula';
    const NACIONAL_ABANDONO = 'NacionalAbandono';
    const NACIONAL_REPROVACAO = 'NacionalReprovacao';

    const ESTADO = 'Estado';
    const ESTADO_MATRICULA = 'EstadoMatricula';
    const ESTADO_ABANDONO = 'EstadoAbandono';
    const ESTADO_REPROVACAO = 'EstadoReprovacao';

    const MUNICIPIO = 'Municipio';
    const MUNICIPIO_MATRICULA = 'MunicipioMatricula';
    const MUNICIPIO_ABANDONO = 'MunicipioAbandono';
    const MUNICIPIO_REPROVACAO = 'MunicipioReprovacao';

    const ESCOLA = 'Escola';
    const ESCOLA_MATRICULA = 'EscolaMatricula';
    const ESCOLA_ABANDONO = 'EscolaAbandono';
    const ESCOLA_REPROVACAO = 'EscolaReprovacao';



    /**
     * @param $param
     * @return mixed
     */
    public function get($param);

    /**
     * @param $data
     * @return mixed
     */
    public function getById($id);

    /**
     * @param $data
     * @return mixed
     */
    public function create($data);

    /**
     * @param $id
     * @return mixed
     */
    public function update($id, $data);

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id);
}
