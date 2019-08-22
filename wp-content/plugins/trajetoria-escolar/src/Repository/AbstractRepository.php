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

    protected function getTotalMapa($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {

            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';

            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];

        }
        if (!empty($corRacaId)) {
            $sql .= $this->tableName . ' where ano_referencia = %d AND cor_raca_id = %d';
            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];
        }

        if (!empty($generoId)) {
            $sql .= $this->tableName . ' where ano_referencia = %d AND genero_id = %d';
            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];
        }
    }

    protected function getTotalPainel($anoReferencia = null, $dependencia = null, $tipoAno = null, $localizacao = null, $localizacao_diferenciada = null)
    {
        if ($tipoAno == null) {
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
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL AND te.dependencia = %s';

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $dependencia), ARRAY_A);
        return $response['qtd'];
    }

    protected function getTotalPainelLocalizacao($anoReferencia = null, $tipo = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id                                                                                   
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL AND te.localizacao = %s';

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $tipo), ARRAY_A);
        return $response['qtd'];
    }

    protected function getTotalPainelLocalizacaoDiferenciada($anoReferencia = null, $tipo = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id                                                                                   
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL AND te.localizacao_diferenciada = %s';

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $tipo), ARRAY_A);
        return $response['qtd'];
    }

    protected function getTotalPainelCorRaca($anoReferencia = null, $corRacaId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id                                                                                   
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.genero_id IS NULL AND ' . $this->tableName . '.cor_raca_id = %d';

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $corRacaId), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalPainelGenero($anoReferencia = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id                                                                                   
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id = %d';
        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $generoId), ARRAY_A);
        return $response['qtd'];
    }

    protected function getTotalPainelDeficiente($anoReferencia = null, $deficienciaId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName . ' join te_escolas te on te.id = ' . $this->tableName . '.escolas_id                                                                                   
                                      where ' . $this->tableName . '.ano_referencia = %d AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL AND ' . $this->tableName . '.deficiencia_id = %d';
        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $deficienciaId), ARRAY_A);
        return $response['qtd'];
    }

    protected function getTotalPorRegiao($anoReferencia = null, $regiao = null, $tipoAno = null)
    {
        if ($tipoAno == null) {
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

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $regiao), ARRAY_A);
        return $response['qtd'];
    }


    protected function getAnosIniciais($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];
        }
    }

    protected function getAnosFinais($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ';


        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];
        }

    }

    protected function getlMedio($anoReferencia = null, $corRacaId = null, $generoId = null)
    {
        $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ';

        if (empty($corRacaId) && empty($generoId)) {
            $sql .= $this->tableName . ' where cor_raca_id IS NULL AND genero_id IS NULL AND ano_referencia = %d';
            $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);
            return $response['qtd'];
        }

    }

    protected function getTotalAnosIniciaisPorAno()
    {

    }

    protected function getTotalAnosFinaisPorAno()
    {

    }

    protected function getTotalMedioPorAno()
    {

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


    protected function getDataMapaBrasil($anoReferencia, $tipo)
    {

        $mapa = $this->getCacheBrasil(2, $anoReferencia, $tipo);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = new \stdClass();
        $data->total = $this->getTotalMapa($anoReferencia);
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

        $this->saveBrasil(2, $tipo, $anoReferencia, $data);

        return $data;

    }

    protected function getDataPainelBrasil($anoReferencia, $tipo)
    {

        $mapa = $this->getCacheBrasil(1, $anoReferencia, $tipo);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = new \stdClass();
        $data->total = $this->getTotalMapa($anoReferencia);
        $data->anos_iniciais = $this->getAnosIniciais($anoReferencia);
        $data->anos_finais = $this->getAnosFinais($anoReferencia);
        $data->medio = $this->getlMedio($anoReferencia);

        $data->municipal = new \stdClass();
        $data->municipal->total = $this->getTotalPainel($anoReferencia, 'Municipal');
        $data->municipal->anos_iniciais = $this->getTotalPainel($anoReferencia, 'Municipal', 'iniciais');
        $data->municipal->anos_finais = $this->getTotalPainel($anoReferencia, 'Municipal', 'finais');
        $data->municipal->medio = $this->getTotalPainel($anoReferencia, 'Municipal', 'medio');

        $data->estadual = new \stdClass();
        $data->estadual->total = $this->getTotalPainel($anoReferencia, 'Estadual');
        $data->estadual->anos_iniciais = $this->getTotalPainel($anoReferencia, 'Estadual', 'iniciais');
        $data->estadual->anos_finais = $this->getTotalPainel($anoReferencia, 'Estadual', 'finais');
        $data->estadual->medio = $this->getTotalPainel($anoReferencia, 'Estadual', 'medio');

        $data->anos = $this->getArrayMatriculasReprovacoesAbandonos($anoReferencia, null, null, null);

        $data->localizacao = new \stdClass();
        $data->localizacao->rural = $this->getTotalPainelLocalizacao($anoReferencia, 'Rural');
        $data->localizacao->urbana = $this->getTotalPainelLocalizacao($anoReferencia, 'Urbana');

        $data->localizacao_diferenciada = new \stdClass();
        $data->localizacao_diferenciada->area_de_assentamento = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'Área de assentamento');
        $data->localizacao_diferenciada->area_remanecente_quilombola = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'Área remanescente de quilombos');
        $data->localizacao_diferenciada->terra_inidigena = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'Terra indígena');
        $data->localizacao_diferenciada->unidade_uso_sustentavel = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'Unidade de uso sustentável');
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'ÁUnidade de uso sustentável em área remanescente de quilombos');
        $data->localizacao_diferenciada->unidade_de_uso_sustentavel_em_terra_indigena = $this->getTotalPainelLocalizacaoDiferenciada($anoReferencia, 'Unidade de uso sustentável em terra indígena');

        $data->cor_raca = new \stdClass();
        $data->cor_raca->nao_declarada = $this->getTotalPainelCorRaca($anoReferencia, 1);
        $data->cor_raca->branca = $this->getTotalPainelCorRaca($anoReferencia, 2);
        $data->cor_raca->preta = $this->getTotalPainelCorRaca($anoReferencia, 3);
        $data->cor_raca->parda = $this->getTotalPainelCorRaca($anoReferencia, 4);
        $data->cor_raca->amarela = $this->getTotalPainelCorRaca($anoReferencia, 5);
        $data->cor_raca->indigena = $this->getTotalPainelCorRaca($anoReferencia, 6);

        $data->genero = new \stdClass();
        $data->genero->masculino = $this->getTotalPainelGenero($anoReferencia, 1);
        $data->genero->feminino = $this->getTotalPainelGenero($anoReferencia, 2);

        $data->deficiencia = new \stdClass();
        $data->deficiencia->com = $this->getTotalPainelDeficiente($anoReferencia, 1);

        $this->saveBrasil(1, $tipo, $anoReferencia, $data);

        return $data;
    }


    public function saveBrasil($origem, $tipo, $anoReferencia = 0, $painel = array())
    {
        return $this->db->query($this->db->prepare(
            'INSERT INTO te_paineis (ano_referencia, referencia_id, tipo, painel) 
                VALUES (%d, %d, "%s", "%s");',
            $anoReferencia,
            $origem,
            $tipo,
            json_encode($painel)
        ));
    }

    public function getCacheBrasil($referencia, $anoReferencia = 0, $tipo)

    {
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


    protected function getTotalMatriculasEstadoMunicipioEscola($anoReferencia = null, $corRacaId = null, $generoId = null, $estadoId = null, $municipioId = null, $escolaId = null, $deficienciaId = null)
    {
        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }

        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        $join = '';
        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= $corRacaId == null
            ? ' AND ' . $this->tableName . '.cor_raca_id IS NULL'
            : ' AND ' . $this->tableName . '.cor_raca_id =' . $corRacaId;

        $where .= $generoId == null
            ? ' AND ' . $this->tableName . '.genero_id IS NULL'
            : ' AND ' . $this->tableName . '.genero_id =' . $generoId;

        $where .= $deficienciaId == null
            ? ' AND ' . $this->tableName . '.deficiencia_id IS NULL'
            : ' AND ' . $this->tableName . '.deficiencia_id =' . $deficienciaId;

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalAnosIniciaisEstadoMunicipioEscola($anoReferencia = null, $corRacaId = null, $generoId = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {
        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }

        $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ' . $this->tableName;
        $join = '';
        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= $corRacaId == null
            ? ' AND ' . $this->tableName . '.cor_raca_id IS NULL'
            : ' AND ' . $this->tableName . '.cor_raca_id =' . $corRacaId;

        $where .= $generoId == null
            ? ' AND ' . $this->tableName . '.genero_id IS NULL'
            : ' AND ' . $this->tableName . '.genero_id =' . $generoId;

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalAnosFinaisEstadoMunicipioEscola($anoReferencia = null, $corRacaId = null, $generoId = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {
        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }

        $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ' . $this->tableName;
        $join = '';
        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= $corRacaId == null
            ? ' AND ' . $this->tableName . '.cor_raca_id IS NULL'
            : ' AND ' . $this->tableName . '.cor_raca_id =' . $corRacaId;

        $where .= $generoId == null
            ? ' AND ' . $this->tableName . '.genero_id IS NULL'
            : ' AND ' . $this->tableName . '.genero_id =' . $generoId;

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);

        return $response['qtd'];

    }

    protected function getTotalAnosMedioEstadoMunicipioEscola($anoReferencia = null, $corRacaId = null, $generoId = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {
        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }

        $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        $join = '';
        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_escolas on te_escolas.id = ' . $this->tableName . '.escolas_id INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= $corRacaId == null
            ? ' AND ' . $this->tableName . '.cor_raca_id IS NULL'
            : ' AND ' . $this->tableName . '.cor_raca_id =' . $corRacaId;

        $where .= $generoId == null
            ? ' AND ' . $this->tableName . '.genero_id IS NULL'
            : ' AND ' . $this->tableName . '.genero_id =' . $generoId;

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalDependenciaEstadoMunicipioEscola($anoReferencia = null, $dependencia = null, $tipoAno = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {

        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }
        if ($dependencia == null) {
            return false;
        }

        if ($tipoAno == null) {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'iniciais') {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'finais') {
            $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'medio') {
            $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        $join = ' INNER JOIN te_escolas on te_escolas.id =' . $this->tableName . '.escolas_id';

        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $where .= ' AND te_escolas.dependencia = %s';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= ' AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL';

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $dependencia), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia = null, $localizacao = null, $tipoAno = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {

        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }
        if ($localizacao == null) {
            return false;
        }

        if ($tipoAno == null) {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'iniciais') {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'finais') {
            $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'medio') {
            $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        $join = ' INNER JOIN te_escolas on te_escolas.id =' . $this->tableName . '.escolas_id';

        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $where .= ' AND te_escolas.localizacao = %s';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= ' AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL';

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $localizacao), ARRAY_A);

        return $response['qtd'];
    }

    protected function getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia = null, $localizacaoDiferenciada = null, $tipoAno = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {

        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }
        if ($localizacaoDiferenciada == null) {
            return false;
        }

        if ($tipoAno == null) {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5 + ano6 + ano7 + ano8 + ano9 + ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'iniciais') {
            $sql = 'SELECT SUM(ano1 + ano2 + ano3 + ano4 + ano5) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'finais') {
            $sql = 'SELECT SUM(ano6 + ano7 + ano8 + ano9) as qtd FROM ' . $this->tableName;
        }

        if ($tipoAno == 'medio') {
            $sql = 'SELECT SUM(ano10 + ano11 + ano12 + ano13) as qtd FROM ' . $this->tableName;
        }

        $join = ' INNER JOIN te_escolas on te_escolas.id =' . $this->tableName . '.escolas_id';

        $where = ' WHERE ' . $this->tableName . '.ano_referencia = %d';

        $where .= ' AND te_escolas.localizacao_diferenciada = %s';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_municipios on te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND ' . $this->tableName . '.escolas_id =' . $escolaId;

        $where .= ' AND ' . $this->tableName . '.cor_raca_id IS NULL AND ' . $this->tableName . '.genero_id IS NULL';

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia, $localizacaoDiferenciada), ARRAY_A);

        return $response['qtd'];
    }


    protected function getArrayMatriculasReprovacoesAbandonos($anoReferencia = null, $estadoId = null, $municipioId = null, $escolaId = null)
    {

        $data = new \stdClass();

        $data->anos = array();

        $data->anos['Iniciais'] = array(
            '1' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano1', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano1', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano1', 'abandonos')
                ],

            '2' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano2', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano2', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano2', 'abandonos')
                ],

            '3' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano3', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano3', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano3', 'abandonos')
                ],

            '4' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano4', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano4', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano4', 'abandonos')
                ],

            '5' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano5', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano5', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano5', 'abandonos')
                ],

        );

        $data->anos['Finais'] = array(
            '6' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano6', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano6', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano6', 'abandonos')
                ],

            '7' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano7', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano7', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano7', 'abandonos')
                ],

            '8' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano8', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano8', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano8', 'abandonos')
                ],

            '9' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano9', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano9', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano9', 'abandonos')
                ],

        );

        $data->anos['Medio'] = array(
            '10' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano10', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano10', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano10', 'abandonos')
                ],

            '11' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano11', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano11', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano11', 'abandonos')
                ],

            '12' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano12', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano12', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano12', 'abandonos')
                ],

            '13' =>
                [
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano13', 'matriculas'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano13', 'reprovacoes'),
                    $this->getTotalPorAno($anoReferencia, $estadoId, $municipioId, $escolaId, 'ano13', 'abandonos')
                ],

        );

        return $data;

    }

    private function getTotalPorAno($anoReferencia = null, $estadoId = null, $municipioId = null, $escolaId = null, $ano = null, $tipoSoma)
    {

        //TODO Colocar um redirect aqui
        //TODO Validar numeros!
        if ($anoReferencia == null) {
            return false;
        }
        if ($ano == null) {
            return false;
        }

        switch ($tipoSoma) {
            case "matriculas";
                $tabela = "tse_qtd_matriculas";
                break;

            case "abandonos";
                $tabela = "tse_qtd_abandonos";
                break;

            case "reprovacoes";
                $tabela = "tse_qtd_reprovacoes";
                break;

        }

        $sql = 'SELECT SUM(' . $ano . ') as qtd FROM ' . $tabela;

        $join = ' INNER JOIN te_escolas ON te_escolas.id = ' . $tabela . '.escolas_id';

        $where = ' WHERE ' . $tabela . '.ano_referencia = %d';

        $join .= $estadoId == null
            ? ''
            : ' INNER JOIN te_municipios ON te_municipios.id = te_escolas.municipio_id';

        $where .= $estadoId == null
            ? ''
            : ' AND te_municipios.estado_id = ' . $estadoId;

        $join .= $municipioId == null
            ? ''
            : ' INNER JOIN te_municipios ON te_municipios.id = te_escolas.municipio_id';

        $where .= $municipioId == null
            ? ''
            : ' AND te_municipios.id = ' . $municipioId;

        $where .= $escolaId == null
            ? ''
            : ' AND te_escolas.id = ' . $escolaId;

        $where .= ' AND ' . $tabela . '.cor_raca_id IS NULL AND ' . $tabela . '.genero_id IS NULL';

        $sql .= $join . $where;

        $response = $this->db->get_row($this->db->prepare($sql, $anoReferencia), ARRAY_A);

        return $response['qtd'];

    }

}
