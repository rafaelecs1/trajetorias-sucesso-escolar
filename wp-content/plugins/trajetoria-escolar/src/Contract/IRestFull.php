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
 * @copyright 2018
 */
interface IRestFull
{
    const MATRICULA = 'MySQLMatriculaRepository';
    const ABANDONO = 'MySQLAbandonoRepository';
    const REPROVACAO = 'MySQLReprovacaoRepository';

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
