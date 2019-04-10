<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLEstadoRepository | MySQLEstadoRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IEstadoRepository;
use Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Realiza as operações de banco de dados MySQL para os estados
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IEstadoRepository
 */
class MySQLEstadoRepository implements IEstadoRepository
{
    /**
     * Objeto responsável pelas operações de banco de dados
     */
    private $db;
    
    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }
    
    /**
     * Salva um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @return int
     */
    public function save(Estado $estado)
    {
        $this->db->query($this->db->prepare(
            'INSERT INTO te_estados (id, nome, limites, regiao) 
                VALUES (%d, "%s", "%s", "%s");',
            $estado->getId(),
            $estado->getNome(),
            $estado->getLimites(),
            $estado->getRegiao()
        ));
        $estado->setId($this->db->insert_id);
        return $estado->getId();
    }
    
    /**
     * Retorna um estado
     *
     * @param integer $id
     * @return \Unicef\TrajetoriaEscolar\Model\Estado
     */
    public function get($id = 0)
    {
        $estado = null;
        $resul = $this->db->get_row($this->db->prepare(
            'SELECT 
                id,
                nome,
                limites,
                regiao
            FROM te_estados 
            WHERE id = %d',
            $id
        ), ARRAY_A);
        if (!empty($resul)) {
            $estado = new Estado($resul['id'], $resul['nome'], $resul['limites'], $resul['regiao']);
        }
        return $estado;
    }
    
    /**
     * Retorna uma lista de estados (ID e nome)
     *
     * @return array
     */
    public function getList()
    {
        $list = array();
        $estados = $this->db->get_results(
            'SELECT 
                id, 
                nome 
            FROM te_estados;',
            ARRAY_A
        );
        if (!empty($estados)) {
            foreach ($estados as $estado) {
                $list[$estado['id']] = $estado['nome'];
            }
        }
        return $list;
    }
    
    /**
     * Retorna uma lista de estados com seus lmites norte, sul, leste e oeste
     *
     * @return array
     */
    public function getLimites()
    {
        $list = array();
        $estados = $this->db->get_results(
            'SELECT 
                id, 
                nome, 
                limites 
            FROM te_estados
            ORDER BY nome;',
            ARRAY_A
        );
        if (!empty($estados)) {
            foreach ($estados as $estado) {
                $list[$estado['id']] = array(
                    'nome' => $estado['nome'],
                    'limites' => json_decode($estado['limites'], true)
                    );
            }
        }
        return $list;
    }
}
