<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLEscolaRepository | MySQLEscolaRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IEscolaRepository;
use Unicef\TrajetoriaEscolar\Model\Escola;
use Unicef\TrajetoriaEscolar\Model\Municipio;
use Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Realiza as operações de banco de dados MySQL para as escolas
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IEscolaRepository
 */
class MySQLEscolaRepository implements IEscolaRepository
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
     * Salva uma escola
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @return int
     */
    public function save(Escola $escola)
    {
        $this->db->query($this->db->prepare('INSERT INTO te_escolas (id, nome, municipio_id, localizacao, dependencia, localizacao_diferenciada) 
                VALUES (%d, "%s", %d, "%s", "%s", "%s");', $escola->getId(), $escola->
            getNome(), $escola->getMunicipio()->getId(), $escola->getLocalizacao(), $escola->
            getDependencia(), $escola->getLocalizacaoDiferenciada()));
        return $escola->getId();
    }

    /**
     * Retorna uma escola
     *
     * @param integer $id
     * @return \Unicef\TrajetoriaEscolar\Model\Escola
     */
    public function get($id = 0)
    {
        $escola = null;
        $resul = $this->db->get_row(
            $this->db->prepare('SELECT 
                esc.*,
                mun.id AS municipio_id,
                mun.nome AS municipio_nome,
                est.id AS estado_id,
                est.nome AS estado_nome
            FROM te_escolas esc,
                 te_municipios mun,
                 te_estados est 
            WHERE esc.municipio_id = mun.id 
            AND mun.estado_id = est.id
            AND esc.id = %d', $id),
            ARRAY_A
        );
        if (!empty($resul)) {
            $estado = new Estado($resul['estado_id'], $resul['estado_nome']);
            $municipio = new Municipio($resul['municipio_id'], $resul['municipio_nome'], $estado);
            $escola = new Escola($resul['id'], $resul['nome'], $municipio, $resul['localizacao'], $resul['dependencia'], $resul['localizacao_diferenciada']);
        }
        return $escola;
    }

    /**
     * Retorna uma lista de escolas (ID e nome)
     *
     * @return array
     */
    public function getList()
    {
        $list = array();
        $escolas = $this->db->get_results('SELECT id, nome FROM te_escolas', ARRAY_A);
        if (!empty($escolas)) {
            foreach ($escolas as $escola) {
                $list[$escola['id']] = $escola['nome'];
            }
        }
        return $list;
    }

    /**
     * Retorna uma lista de escolas municipais (ID e nome) de um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return array
     */
    public function getListMunicipais(Municipio $municipio)
    {
        return $this->db->get_results($this->db->prepare('SELECT
              esc.id,
              esc.nome
            FROM te_escolas esc,
                 te_municipios mun
            WHERE esc.municipio_id = mun.id
            AND esc.dependencia = "Municipal"
            AND mun.id = %d
            ORDER BY esc.nome;', $municipio->getId()), ARRAY_A);
    }
    
    /**
     * Retorna uma lista de escolas estaduais (ID e nome) de um município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return array
     */
    public function getListEstaduais(Municipio $municipio)
    {
        return $this->db->get_results($this->db->prepare('SELECT
              esc.id,
              esc.nome
            FROM te_escolas esc,
                 te_municipios mun
            WHERE esc.municipio_id = mun.id
            AND esc.dependencia = "Estadual"
            AND mun.id = %d
            ORDER BY esc.nome;', $municipio->getId()), ARRAY_A);
    }
}
