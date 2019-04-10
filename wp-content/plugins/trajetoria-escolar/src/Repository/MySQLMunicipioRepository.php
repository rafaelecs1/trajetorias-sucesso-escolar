<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLMunicipioRepository | MySQLMunicipioRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IMunicipioRepository;
use Unicef\TrajetoriaEscolar\Model\Municipio;
use Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Realiza as operações de banco de dados MySQL para os municípios
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IMunicipioRepository
 */
class MySQLMunicipioRepository implements IMunicipioRepository
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
     * Salva um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return
     */
    public function save(Municipio $municipio)
    {
        $this->db->query($this->db->prepare(
            'INSERT INTO te_municipios (id, nome, estado_id) 
                VALUES (%d, "%s", %d);',
            $municipio->getId(),
            $municipio->getNome(),
            $municipio->getEstado()->getId()
        ));
        return $municipio->getId();
    }
    
    /**
     * Retorna um munícipio
     *
     * @param integer $id
     * @return
     */
    public function get($id = 0)
    {
        $municipio = null;
        $resul = $this->db->get_row($this->db->prepare(
            'SELECT 
                mun.id,
                mun.nome,
                est.id AS estado_id,
                est.nome AS estado_nome
            FROM te_municipios mun,
                 te_estados est 
            WHERE mun.estado_id = est.id
            AND mun.id = %d',
            $id
        ), ARRAY_A);
        if (!empty($resul)) {
            $estado = new Estado($resul['estado_id'], $resul['estado_nome']);
            $municipio = new Municipio($resul['id'], $resul['nome'], $estado);
        }
        return $municipio;
    }
    
    /**
     * Retorna uma lista de municípios (ID e nome)
     *
     * @return array
     */
    public function getList()
    {
        $list = array();
        $municipios = $this->db->get_results(
            'SELECT 
                id, 
                nome 
            FROM te_municipios
            ORDER BY
                nome;',
            ARRAY_A
        );
        if (!empty($municipios)) {
            foreach ($municipios as $municipio) {
                $list[$municipio['id']] = $municipio['nome'];
            }
        }
        return $list;
    }
    
    /**
     * Retorna uma lista de municípios (ID e nome) para um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return array
     */
    public function getByEstado(Estado $estado, $anoReferencia = 0)
    {
        $list = array();
        $municipios = $this->db->get_results(
            $this->db->prepare(
                'SELECT
                  mun.id,
                  mun.nome,
                  pol.kmz,
                  SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                  SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
                FROM te_municipios mun,
                     te_poligonos pol,
                     te_escolas esc,
                     te_distorcoes dis,
                     te_distorcoes_anos dis_ano
                WHERE mun.id = pol.municipio_id
                AND mun.id = esc.municipio_id
                AND esc.id = dis.escola_id
                AND dis.id = dis_ano.distorcao_id 
                --
                AND mun.estado_id = %d
                AND dis.ano_referencia = %d
                --
                GROUP BY 
                    id
                ORDER BY
                    nome;',
                $estado->getId(),
                $anoReferencia
            ),
            ARRAY_A
        );
        if (!empty($municipios)) {
            foreach ($municipios as $municipio) {
                $list[$municipio['id']] = array(
                    'nome' => $municipio['nome'],
                    'kmz' => json_decode($municipio['kmz'], true),
                    'sem_distorcao' => (int)$municipio['sem_distorcao'],
                    'distorcao' => (int)$municipio['distorcao'],
                    );
            }
        }
        return $list;
    }
}
