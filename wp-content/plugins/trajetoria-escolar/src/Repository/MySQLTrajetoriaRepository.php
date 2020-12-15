<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLTrajetoriaRepository | MySQLTrajetoriaRepository.php
 *
 * @author Manoel Souza
 * @copyright 2020
 */

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;

/**
 * Realiza as operações de banco de dados MySQL para o cache de painéis
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author Manoel Souza
 * @copyright 2020
 * @implements \Unicef\TrajetoriaEscolar\Contract\IPanelRepository
 */
class MySQLTrajetoriaRepository 
{

    /**
     * Objeto responsável pelas operações de banco de dados
     */
    private $db;
    private $table_name;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->table_name = "te_trajetorias";
    }

    public function getTrajetoriasNacional(){
        $resul = array();
        $sql = sprintf(
            'SELECT 
                t.ano, 
                SUM(t.matriculas) as matriculas,
                t.tipo 
            FROM 
                %s as t 
            GROUP BY 
                t.tipo, t.ano
            ORDER BY 
                t.ano;',
            $this->table_name
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        return $query;
    }


    public function getTrajetoriasPorUF($uf){
        $resul = array();
        $sql = sprintf(
            'SELECT 
                t.ano, 
                SUM(t.matriculas) as matriculas,
                t.tipo 
            FROM 
                te_trajetorias as t
            INNER JOIN 
                te_municipios as m
            ON 
                m.id = t.municipio_id
            WHERE 
                m.estado_id = %d
            GROUP BY 
                t.tipo, t.ano
            ORDER BY 
                t.ano;',
            $uf
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        return $query;
    }

    public function getTrajetoriasPorCidadeId($cidadeId){
        $resul = array();
        $sql = sprintf(
            'SELECT 
                t.ano, 
                SUM(t.matriculas) as matriculas,
                t.tipo 
            FROM 
                te_trajetorias as t
            WHERE 
                t.municipio_id = %d
            GROUP BY 
                t.tipo, t.ano
            ORDER BY 
                t.ano;',
            $cidadeId
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        return $query;
    }
    
}
