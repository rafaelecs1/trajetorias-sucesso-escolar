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
     * @param null $corRacaId : 1-Não declarada; 2-Branca; 3-Preta; 4-Parda; 5-Amarela; 6-Indígena
     * @param null $generoId : 1-Feminino; 2-Masculino
     * @return array|object|void|null
     */

    protected function getTotal($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
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

    protected function getTotalPorRegiao($anoReferencia = null, $regiao = null, $tipoAno = null)
    {
        if($tipoAno == null){
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ';
        }
        if ($tipoAno == 'iniciais') {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ';
        }
        if ($tipoAno == 'finais') {
            $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ';
        }
        if ($tipoAno == 'medio') {
            $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ';
        }


        $sql .= $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id
                                      join te_municipios tm on tm.id = te.municipio_id
                                      join te_estados tes on tes.id = tm.estado_id  
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL AND tes.regiao = %s';

        return $this->db->get_row($this->db->prepare($sql, $anoReferencia, $regiao), ARRAY_A);
    }


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

    public function getDataBrasil($anoReferencia)
    {

        $mapa = $this->getCacheBrasil(2, $anoReferencia);

        if (!empty($mapa)) {
            return (object)json_decode($mapa, true);
        }

        $data = new \stdClass();
        $data->total = $this->getTotal($anoReferencia);
        $data->anos_iniciais = $this->getAnosIniciais($anoReferencia);
        $data->anos_finais = $this->getAnosFinais($anoReferencia);
        $data->medio = $this->getlMedio($anoReferencia);

        $data->regiao_norte = new \stdClass();
        $data->regiao_norte->total = $this->getTotalPorRegiao($anoReferencia, 'Norte');
        $data->regiao_norte->anos_iniciais = $this->getTotalPorRegiao($anoReferencia, 'Norte', 'iniciais');
        $data->regiao_norte->anos_finais = $this->getTotalPorRegiao($anoReferencia, 'Norte', 'finais');
        $data->regiao_norte->medio = $this->getTotalPorRegiao($anoReferencia, 'Norte', 'medio');

        $data->regiao_nordeste = new \stdClass();
        $data->regiao_nordeste->total = $this->getTotalPorRegiao($anoReferencia, 'Nordeste');
        $data->regiao_nordeste->anos_iniciais = $this->getTotalPorRegiao($anoReferencia, 'Nordeste', 'iniciais');
        $data->regiao_nordeste->anos_finais = $this->getTotalPorRegiao($anoReferencia, 'Nordeste', 'finais');
        $data->regiao_nordeste->medio = $this->getTotalPorRegiao($anoReferencia, 'Nordeste', 'medio');

        $data->regiao_sul = new \stdClass();
        $data->regiao_sul->total = $this->getTotalPorRegiao($anoReferencia, 'Sul');
        $data->regiao_sul->anos_iniciais = $this->getTotalPorRegiao($anoReferencia, 'Sul', 'iniciais');
        $data->regiao_sul->anos_finais = $this->getTotalPorRegiao($anoReferencia, 'Sul', 'finais');
        $data->regiao_sul->medio = $this->getTotalPorRegiao($anoReferencia, 'Sul', 'medio');

        $data->regiao_centro_oeste = new \stdClass();
        $data->regiao_centro_oeste->total = $this->getTotalPorRegiao($anoReferencia, 'Centro-Oeste');
        $data->regiao_centro_oeste->anos_iniciais = $this->getTotalPorRegiao($anoReferencia, 'Centro-Oeste', 'iniciais');
        $data->regiao_centro_oeste->anos_finais = $this->getTotalPorRegiao($anoReferencia, 'Centro-Oeste', 'finais');
        $data->regiao_centro_oeste->medio = $this->getTotalPorRegiao($anoReferencia, 'Centro-Oeste', 'medio');

        $data->regiao_sudeste = new \stdClass();
        $data->regiao_sudeste->total = $this->getTotalPorRegiao($anoReferencia, 'Sudeste');
        $data->regiao_sudeste->anos_iniciais = $this->getTotalPorRegiao($anoReferencia, 'Sudeste', 'iniciais');
        $data->regiao_sudeste->anos_finais = $this->getTotalPorRegiao($anoReferencia, 'Sudeste', 'finais');
        $data->regiao_sudeste->medio = $this->getTotalPorRegiao($anoReferencia, 'Sudeste', 'medio');

        $this->saveBrasil(2, $anoReferencia, $data);

        return $data;
    }

    public function saveBrasil($origem, $anoReferencia = 0, $painel = array())
    {
        $tipo = $this->getTipoPainel($this);
        $this->db->query($this->db->prepare(
            'INSERT INTO te_paineis (ano_referencia, referencia_id, tipo, painel) 
                VALUES (%d, %d, "%s", "%s");',
            $anoReferencia,
            $origem,
            $tipo,
            json_encode($painel)
        ));
        return json_encode($painel);
    }

    public function getCacheBrasil($referencia, $anoReferencia = 0)
    {
        $tipo = $this->getTipoPainel($this);
        return $this->db->get_var($this->db->prepare(
            'SELECT 
                painel 
            FROM te_paineis 
            WHERE ano_referencia = %d
            AND referencia_id = %d
            AND tipo = "%s";',
            $anoReferencia,
            $referencia,
            $tipo
        ));
    }

    private function getTipoPainel($name){
        $nome = get_class($name);
        $estrutura = explode('\\', $nome);
        $nameClass = $estrutura[count($estrutura) - 1];
        switch ($nameClass) {
            case self::MATRICULA:
                return "NacionalMatricula";
                break;
            case self::ABANDONO:
                return "NacionalAbandono";
                break;
            case self::REPROVACAO:
                return "NacionalReprovacao";
                break;
        }
    }


}
