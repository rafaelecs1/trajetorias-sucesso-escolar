<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository | MySQLPainelRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use Unicef\TrajetoriaEscolar\Contract\IRestFull;

abstract class AbstractRepository implements IRestFull
{
    /**
     * Objeto responsável pelas operações de banco de dados
     */
    protected $db;
    protected $tableName;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->tableName = $this::getTableName($this);
    }

    public function get($param)
    {
        $id = 11;
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id = %d';
        $resul = $this->db->get_row($this->db->prepare($sql, $id), ARRAY_A);
        return $resul;
    }

    public function getById($id)
    {
        $this->tableName = $this::getTableName($this);
        $sql = 'SELECT * FROM ' . $this->tableName . ' WHERE id = %d';
        $resul = $this->db->get_row($this->db->prepare($sql, $id), ARRAY_A);
        return $resul;
    }

    public function create($data)
    {
        // TODO: Implement create() method.
    }

    public function update($id, $data)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param null $anoReferencia
     * @param null $corRacaId: 1-Não declarada; 2-Branca; 3-Preta; 4-Parda; 5-Amarela; 6-Indígena
     * @param null $generoId: 1-Feminino; 2-Masculino
     * @return array|object|void|null
     */

    protected function getTotal($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $this->tableName = $this::getTableName($this);

        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            return $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
        }
        if (!empty($corRacaId)) {
            $sql .= $this->tableName . ' where ano_referencia = %d AND cor_raca_id = %d';
            return $this->db->get_row($this->db->prepare($sql, $anoReferencia, $corRacaId), ARRAY_A);
        }

        if (!empty($generoId)) {
            $sql .= $this->tableName . ' where ano_referencia = %d AND genero_id = %d';
            $resul = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $generoId), ARRAY_A);
        }
        // TODO SEPARA AS RACAS E GENEROS
    }

    protected function getTotalPorRegiao($anoReferencia = null, $regiao = null)
    {

        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ';

        $sql .= $this->tableName . ' join te_escolas te on te.id = '.$this->tableName.'.escolas_id
                                      join te_municipios tm on tm.id = te.municipio_id
                                      join te_estados tes on tes.id = tm.estado_id  
                                      where '.$this->tableName.'.ano_referencia = %d AND '.$this->tableName.'.cor_raca_id IS NULL AND '.$this->tableName.'.genero_id IS NULL AND tes.regiao = %s';

        return $this->db->get_row($this->db->prepare($sql, $anoReferencia, $regiao), ARRAY_A);
    }
//        if (!empty($corRacaId)) {
//            $sql .= $this->tableName . ' where ano_referencia = %d AND cor_raca_id = %d';
//            return $this->db->get_row($this->db->prepare($sql, $anoReferencia, $corRacaId), ARRAY_A);
//        }
//
//        if (!empty($generoId)) {
//            $sql .= $this->tableName . ' where ano_referencia = %d AND genero_id = %d';
//            $resul = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $generoId), ARRAY_A);
//        }


    protected function getAnosIniciais($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            return $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
        }
    }

    protected function getAnosFinais($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ';


        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            return $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
        }

    }

    protected function getlMedio($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            return $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
        }

    }

    private static function getTableName($origem)
    {
        $nome = get_class($origem);
        $estrutura = explode('\\', $nome);
        $nameClass = $estrutura[count($estrutura) - 1];
        switch ($nameClass) {
            case self::MATRICULA:
                return "tse_qtd_matriculas";
                break;
            case self::ABANDONO:
                return "tse_qtd_abandonos";
                break;
            case self::REPROVACAO:
                return "tse_qtd_reprovacoes";
                break;
        }
    }


}
