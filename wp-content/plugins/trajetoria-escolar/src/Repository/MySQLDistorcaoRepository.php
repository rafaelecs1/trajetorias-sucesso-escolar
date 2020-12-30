<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLDistorcaoRepository | MySQLDistorcaoRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IDistorcaoRepository;
use Unicef\TrajetoriaEscolar\Model\Distorcao;
use Unicef\TrajetoriaEscolar\Contract\IDistorcao;

/**
 * Realiza as operações de banco de dados MySQL para as diferentes distorções
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IDistorcaoRepository
 */
class MySQLDistorcaoRepository implements IDistorcaoRepository
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
     * Salva as informações básicas/comuns relacionadas a uma distorção
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorção $distorcao
     * @return int
     */
    public function save(Distorcao $distorcao)
    {
        $distorcaoId = $this->getDistorcaoId($distorcao);
        if (empty($distorcaoId)) {
            $this->db->query($this->db->prepare(
                'INSERT INTO te_distorcoes (escola_id, ano_referencia, tipo_ensino, tipo_ano) 
                    VALUES (%d, %d, "%s", "%s");',
                $distorcao->getEscola()->getId(),
                $distorcao->getAnoReferencia(),
                $distorcao->getTipoEnsino(),
                $distorcao->getTipoAno()
            ));
            $distorcaoId = $this->db->insert_id;
        }
        if (is_a($distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoAno')) {
            $this->saveDistorcaoAno($distorcao, $distorcaoId);
        }
        if (is_a($distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoRaca')) {
            $this->saveDistorcaoRaca($distorcao, $distorcaoId);
        }
        if (is_a($distorcao, 'Unicef\TrajetoriaEscolar\Model\DistorcaoGenero')) {
            $this->saveDistorcaoGenero($distorcao, $distorcaoId);
        }
        $distorcao->setId($this->db->insert_id);
        return $distorcao->getId();
    }
    
    /**
     * Retorna o ID da distorção com as informações básicas/comuns
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @return int
     */
    public function getDistorcaoId(Distorcao $distorcao)
    {
        return $this->db->get_var($this->db->prepare(
            'SELECT id 
                FROM te_distorcoes 
                WHERE escola_id = %d 
                AND ano_referencia = %d
                AND tipo_ensino = "%s"
                AND tipo_ano = "%s";',
            $distorcao->getEscola()->getId(),
            $distorcao->getAnoReferencia(),
            $distorcao->getTipoEnsino(),
            $distorcao->getTipoAno()
        ));
    }
    
    /**
     * Salva os detalhes de uma distorção do por ano escolar
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @param integer $distorcaoId
     * @return void
     */
    private function saveDistorcaoAno(Distorcao $distorcao, $distorcaoId = 0)
    {
        $this->db->query($this->db->prepare(
            'DELETE FROM te_distorcoes_anos 
                WHERE distorcao_id = %d 
                AND ano = %d;',
            $distorcaoId,
            $distorcao->getAno()
        ));
        $this->db->query($this->db->prepare(
            'INSERT INTO te_distorcoes_anos (distorcao_id, ano, sem_distorcao, distorcao_1, distorcao_2, distorcao_3) 
                VALUES (%d, %d, %d, %d, %d, %d);',
            $distorcaoId,
            $distorcao->getAno(),
            $distorcao->getSemDistorcao(),
            $distorcao->getDistorcao1(),
            $distorcao->getDistorcao2(),
            $distorcao->getDistorcao3()
        ));
    }
    
    /**
     * Salva os detalhes de uma distorção por cor/raça
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @param integer $distorcaoId
     * @return void
     */
    private function saveDistorcaoRaca(Distorcao $distorcao, $distorcaoId = 0)
    {
        $this->db->query($this->db->prepare(
            'DELETE FROM te_distorcoes_racas 
                WHERE distorcao_id = %d 
                AND tipo_distorcao = %d;',
            $distorcaoId,
            $distorcao->getTipoDistorcao()
        ));
        $this->db->query($this->db->prepare(
            'INSERT INTO te_distorcoes_racas (distorcao_id, tipo_distorcao, nao_declarada, branca, preta, parda, amarela, indigena) 
                VALUES (%d, %d, %d, %d, %d, %d, %d, %d);',
            $distorcaoId,
            $distorcao->getTipoDistorcao(),
            $distorcao->getNaoDeclarada(),
            $distorcao->getBranca(),
            $distorcao->getPreta(),
            $distorcao->getParda(),
            $distorcao->getAmarela(),
            $distorcao->getIndigena()
        ));
    }
    
    /**
     * Salva os detalhes de uma distorção por gênero
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Distorcao $distorcao
     * @param integer $distorcaoId
     * @return void
     */
    private function saveDistorcaoGenero(Distorcao $distorcao, $distorcaoId = 0)
    {
        $this->db->query($this->db->prepare(
            'DELETE FROM te_distorcoes_generos
                WHERE distorcao_id = %d 
                AND tipo_distorcao = %d;',
            $distorcaoId,
            $distorcao->getTipoDistorcao()
        ));
        $this->db->query($this->db->prepare(
            'INSERT INTO te_distorcoes_generos (distorcao_id, tipo_distorcao, masculino, feminino) 
                VALUES (%d, %d, %d, %d);',
            $distorcaoId,
            $distorcao->getTipoDistorcao(),
            $distorcao->getMasculino(),
            $distorcao->getFeminino()
        ));
    }
    
    /**
     * Retorna as quantidades de crianças e adolescentes em distorção idade-série
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param int $anoReferencia
     * @return int
     */
    public function getTotal(IDistorcao $origem, $anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS total
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        return (int)$this->db->get_var($sql);
    }

    public function getTotalBrasil($anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS total
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d',
            $anoReferencia
        );
        return (int)$this->db->get_var($sql);
    }
    
    /**
     * Retorna as quantidades de crianças e adolescentes que não estão em distorção idade-série
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param int $anoReferencia
     * @return int
     */
    public function getTotalSem(IDistorcao $origem, $anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS total
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        return (int)$this->db->get_var($sql);
    }
    public function getTotalSemBrasil($anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS total
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d;',
            $anoReferencia
        );
        return (int)$this->db->get_var($sql);
    }
    
    /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de rede
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorTipoRede(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.dependencia,
                dis.tipo_ensino,
                dis.tipo_ano,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) as sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND (
                dis_ano.sem_distorcao > 0
                OR dis_ano.distorcao_1 > 0
                OR dis_ano.distorcao_2 > 0
                OR dis_ano.distorcao_3 > 0
            )
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY esc.dependencia, dis.tipo_ensino, dis.tipo_ano;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['dependencia']][$item['tipo_ensino']][$item['tipo_ano']])) {
                    $resul[$item['dependencia']][$item['tipo_ensino']][$item['tipo_ano']] = array(
                        'sem_distorcao' => (int)$item['sem_distorcao'],
                        'distorcao' => (int)$item['distorcao']
                        );
                }
            }
        }
        return $resul;
    }

    public function getPorTipoRedeBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.dependencia,
                dis.tipo_ensino,
                dis.tipo_ano,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) as sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND (
                dis_ano.sem_distorcao > 0
                OR dis_ano.distorcao_1 > 0
                OR dis_ano.distorcao_2 > 0
                OR dis_ano.distorcao_3 > 0
            )
            --
            AND dis.ano_referencia = %d
            --
            GROUP BY esc.dependencia, dis.tipo_ensino, dis.tipo_ano;',
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['dependencia']][$item['tipo_ensino']][$item['tipo_ano']])) {
                    $resul[$item['dependencia']][$item['tipo_ensino']][$item['tipo_ano']] = array(
                        'sem_distorcao' => (int)$item['sem_distorcao'],
                        'distorcao' => (int)$item['distorcao']
                    );
                }
            }
        }
        return $resul;
    }
    
     /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de ensino
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorTipoEnsino(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ensino,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id 
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY dis.tipo_ensino;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['tipo_ensino']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                    );
            }
        }
        return $resul;
    }
    
     /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por tipo de ano
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorTipoAno(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ano,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao, 
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND dis.tipo_ano <> ""
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY dis.tipo_ano;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['tipo_ano']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                    );
            }
        }
        return $resul;
    }
    
     /**
     * Retorna as quantidades detalhadas (0, 1, 2 e 3 anos) de crianças e adolescentes com e sem distorção idade-série por ano escolar
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorAno(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ano,
                dis_ano.ano,
                SUM(dis_ano.sem_distorcao) AS sem_distorcao,
                SUM(dis_ano.distorcao_1) AS distorcao_1, 
                SUM(dis_ano.distorcao_2) AS distorcao_2, 
                SUM(dis_ano.distorcao_3) AS distorcao_3 
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND (
                dis_ano.sem_distorcao > 0
                OR dis_ano.distorcao_1 > 0
                OR dis_ano.distorcao_2 > 0
                OR dis_ano.distorcao_3 > 0
            )
            -- AND (dis_ano.ano <> 4 OR dis.tipo_ano <> "Todos")
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY                 
                dis.tipo_ano,
                dis_ano.ano;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['tipo_ano']][$item['ano']])) {
                    $resul[$item['tipo_ano']][$item['ano']] = array(
                        (int)$item['sem_distorcao'],
                        (int)$item['distorcao_1'],
                        (int)$item['distorcao_2'],
                        (int)$item['distorcao_3'],
                    );
                }
            }
        }
        return $resul;
    }

    public function getPorAnoBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ano,
                dis_ano.ano,
                SUM(dis_ano.sem_distorcao) AS sem_distorcao,
                SUM(dis_ano.distorcao_1) AS distorcao_1, 
                SUM(dis_ano.distorcao_2) AS distorcao_2, 
                SUM(dis_ano.distorcao_3) AS distorcao_3 
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND (
                dis_ano.sem_distorcao > 0
                OR dis_ano.distorcao_1 > 0
                OR dis_ano.distorcao_2 > 0
                OR dis_ano.distorcao_3 > 0
            )
            -- AND (dis_ano.ano <> 4 OR dis.tipo_ano <> "Todos")
            --
            AND dis.ano_referencia = %d
            --
            GROUP BY                 
                dis.tipo_ano,
                dis_ano.ano;',
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['tipo_ano']][$item['ano']])) {
                    $resul[$item['tipo_ano']][$item['ano']] = array(
                        (int)$item['sem_distorcao'],
                        (int)$item['distorcao_1'],
                        (int)$item['distorcao_2'],
                        (int)$item['distorcao_3'],
                    );
                }
            }
        }
        return $resul;
    }

    public function getPorIdade(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ano,
                dis_idade.ano,
                SUM(dis_idade._6_menos) AS 6_menos,
                SUM(dis_idade._6_anos) AS 6_anos,
                SUM(dis_idade._7_anos) AS 7_anos,
                SUM(dis_idade._8_anos) AS 8_anos,
                SUM(dis_idade._9_anos) AS 9_anos,
                SUM(dis_idade._10_anos) AS 10_anos,
                SUM(dis_idade._11_anos) AS 11_anos,
                SUM(dis_idade._12_anos) AS 12_anos,
                SUM(dis_idade._13_anos) AS 13_anos,
                SUM(dis_idade._14_anos) AS 14_anos,
                SUM(dis_idade._15_anos) AS 15_anos,
                SUM(dis_idade._16_anos) AS 16_anos,
                SUM(dis_idade._17_anos) AS 17_anos,
                SUM(dis_idade._18_anos) AS 18_anos,
                SUM(dis_idade._19_anos) AS 19_anos,
                SUM(dis_idade._15_e_mais) AS 15_mais,
                SUM(dis_idade._18_e_mais) AS 18_mais,
                SUM(dis_idade._20_e_mais) AS 20_mais,
                SUM(dis_idade._10_menos) AS 10_menos,
                SUM(dis_idade._11_menos) AS 11_menos,
                SUM(dis_idade._15_menos) AS 15_menos
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_idades dis_idade
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_idade.distorcao_id
            AND (
                dis_idade._6_menos > 0
                OR dis_idade._6_menos > 0
                OR dis_idade._6_anos > 0
                OR dis_idade._7_anos > 0
                OR dis_idade._8_anos > 0
                OR dis_idade._9_anos > 0
                OR dis_idade._10_anos > 0
                OR dis_idade._11_anos > 0
                OR dis_idade._12_anos > 0
                OR dis_idade._13_anos > 0
                OR dis_idade._14_anos > 0
                OR dis_idade._15_anos > 0
                OR dis_idade._16_anos > 0
                OR dis_idade._17_anos > 0
                OR dis_idade._18_anos > 0
                OR dis_idade._19_anos > 0
                OR dis_idade._15_e_mais > 0
                OR dis_idade._18_e_mais > 0
                OR dis_idade._20_e_mais > 0
                OR dis_idade._10_menos > 0
                OR dis_idade._11_menos > 0
                OR dis_idade._15_menos > 0
            )
            AND dis.ano_referencia = %d
            AND %s.id = %d
            GROUP BY                 
                dis.tipo_ano,
                dis_idade.ano;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );

        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['tipo_ano']][$item['ano']])) {
                    $resul[$item['tipo_ano']][$item['ano']] = array(
                        (int)$item['6_menos'],
                        (int)$item['6_anos'],
                        (int)$item['7_anos'],
                        (int)$item['8_anos'],
                        (int)$item['9_anos'],
                        (int)$item['10_anos'],
                        (int)$item['11_anos'],
                        (int)$item['12_anos'],
                        (int)$item['13_anos'],
                        (int)$item['14_anos'],
                        (int)$item['15_anos'],
                        (int)$item['16_anos'],
                        (int)$item['17_anos'],
                        (int)$item['18_anos'],
                        (int)$item['19_anos'],
                        (int)$item['15_mais'],
                        (int)$item['18_mais'],
                        (int)$item['20_mais'],
                        (int)$item['10_menos'],
                        (int)$item['11_menos'],
                        (int)$item['15_menos']
                    );
                }
            }
        }
        return $resul;
    }

    public function getPorIdadeBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                dis.tipo_ano,
                dis_idade.ano,
                SUM(dis_idade._6_menos) AS 6_menos,
                SUM(dis_idade._6_anos) AS 6_anos,
                SUM(dis_idade._7_anos) AS 7_anos,
                SUM(dis_idade._8_anos) AS 8_anos,
                SUM(dis_idade._9_anos) AS 9_anos,
                SUM(dis_idade._10_anos) AS 10_anos,
                SUM(dis_idade._11_anos) AS 11_anos,
                SUM(dis_idade._12_anos) AS 12_anos,
                SUM(dis_idade._13_anos) AS 13_anos,
                SUM(dis_idade._14_anos) AS 14_anos,
                SUM(dis_idade._15_anos) AS 15_anos,
                SUM(dis_idade._16_anos) AS 16_anos,
                SUM(dis_idade._17_anos) AS 17_anos,
                SUM(dis_idade._18_anos) AS 18_anos,
                SUM(dis_idade._19_anos) AS 19_anos,
                SUM(dis_idade._15_e_mais) AS 15_mais,
                SUM(dis_idade._18_e_mais) AS 18_mais,
                SUM(dis_idade._20_e_mais) AS 20_mais,
                SUM(dis_idade._10_menos) AS 10_menos,
                SUM(dis_idade._11_menos) AS 11_menos,
                SUM(dis_idade._15_menos) AS 15_menos
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_idades dis_idade
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_idade.distorcao_id
            AND (
                dis_idade._6_menos > 0
                OR dis_idade._6_menos > 0
                OR dis_idade._6_anos > 0
                OR dis_idade._7_anos > 0
                OR dis_idade._8_anos > 0
                OR dis_idade._9_anos > 0
                OR dis_idade._10_anos > 0
                OR dis_idade._11_anos > 0
                OR dis_idade._12_anos > 0
                OR dis_idade._13_anos > 0
                OR dis_idade._14_anos > 0
                OR dis_idade._15_anos > 0
                OR dis_idade._16_anos > 0
                OR dis_idade._17_anos > 0
                OR dis_idade._18_anos > 0
                OR dis_idade._19_anos > 0
                OR dis_idade._15_e_mais > 0
                OR dis_idade._18_e_mais > 0
                OR dis_idade._20_e_mais > 0
                OR dis_idade._10_menos > 0
                OR dis_idade._11_menos > 0
                OR dis_idade._15_menos > 0
            )
            AND dis.ano_referencia = %d
            GROUP BY                 
                dis.tipo_ano,
                dis_idade.ano;',
            $anoReferencia
        );

        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['tipo_ano']][$item['ano']])) {
                    $resul[$item['tipo_ano']][$item['ano']] = array(
                        (int)$item['6_menos'],
                        (int)$item['6_anos'],
                        (int)$item['7_anos'],
                        (int)$item['8_anos'],
                        (int)$item['9_anos'],
                        (int)$item['10_anos'],
                        (int)$item['11_anos'],
                        (int)$item['12_anos'],
                        (int)$item['13_anos'],
                        (int)$item['14_anos'],
                        (int)$item['15_anos'],
                        (int)$item['16_anos'],
                        (int)$item['17_anos'],
                        (int)$item['18_anos'],
                        (int)$item['19_anos'],
                        (int)$item['15_mais'],
                        (int)$item['18_mais'],
                        (int)$item['20_mais'],
                        (int)$item['10_menos'],
                        (int)$item['11_menos'],
                        (int)$item['15_menos']
                    );
                }
            }
        }
        return $resul;
    }


    /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por localização
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorLocalizacao(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.localizacao,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY esc.localizacao;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['localizacao']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                    );
            }
        }
        return $resul;
    }

    public function getPorLocalizacaoBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.localizacao,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d
            --
            GROUP BY esc.localizacao;',
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['localizacao']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                );
            }
        }
        return $resul;
    }


    /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por localização diferenciada
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorLocalizacaoDiferenciada(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.localizacao_diferenciada AS localizacao_dif,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND esc.localizacao_diferenciada <> "Não se aplica"
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            GROUP BY esc.localizacao_diferenciada;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['localizacao_dif']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                    );
            }
        }
        return $resul;
    }

    public function getPorLocalizacaoDiferenciadaBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                esc.localizacao_diferenciada AS localizacao_dif,
                SUM(dis_ano.sem_distorcao) + SUM(dis_ano.distorcao_1) AS sem_distorcao,
                SUM(dis_ano.distorcao_2) + SUM(dis_ano.distorcao_3) AS distorcao
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            AND esc.localizacao_diferenciada <> "Não se aplica"
            --
            AND dis.ano_referencia = %d
            --
            GROUP BY esc.localizacao_diferenciada;',
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query as $item) {
                $resul[$item['localizacao_dif']] = array(
                    'sem_distorcao' => (int)$item['sem_distorcao'],
                    'distorcao' => (int)$item['distorcao'],
                );
            }
        }
        return $resul;
    }
    
    /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por cor/raça
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorCorRaca(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
              "sem_distorcao" AS tipo_distorcao,
              SUM(dis_raca.nao_declarada) AS "Não declarada",
              SUM(dis_raca.branca) AS Branca,
              SUM(dis_raca.preta) AS Preta,
              SUM(dis_raca.parda) AS Parda,
              SUM(dis_raca.amarela) AS Amarela,
              SUM(dis_raca.indigena) AS Indígena
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_racas dis_raca
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_raca.distorcao_id
            AND dis_raca.tipo_distorcao IN (0, 1)
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            UNION
            --
            SELECT
              "distorcao",
              SUM(dis_raca.nao_declarada),
              SUM(dis_raca.branca),
              SUM(dis_raca.preta),
              SUM(dis_raca.parda),
              SUM(dis_raca.amarela),
              SUM(dis_raca.indigena)
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_racas dis_raca
            WHERE est.id =  mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_raca.distorcao_id
            AND dis_raca.tipo_distorcao IN (2, 3)
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId(),
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query[0] as $k => $v) {
                $resul[$k] = array(
                    'sem_distorcao' => $v,
                    'distorcao' =>  $query[1][$k],
                    );
            }
            array_shift($resul);
        }
        return $resul;
    }

    public function getPorCorRacaBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
              "sem_distorcao" AS tipo_distorcao,
              SUM(dis_raca.nao_declarada) AS "Não declarada",
              SUM(dis_raca.branca) AS Branca,
              SUM(dis_raca.preta) AS Preta,
              SUM(dis_raca.parda) AS Parda,
              SUM(dis_raca.amarela) AS Amarela,
              SUM(dis_raca.indigena) AS Indígena
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_racas dis_raca
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_raca.distorcao_id
            AND dis_raca.tipo_distorcao IN (0, 1)
            --
            AND dis.ano_referencia = %d
            --
            UNION
            --
            SELECT
              "distorcao",
              SUM(dis_raca.nao_declarada),
              SUM(dis_raca.branca),
              SUM(dis_raca.preta),
              SUM(dis_raca.parda),
              SUM(dis_raca.amarela),
              SUM(dis_raca.indigena)
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_racas dis_raca
            WHERE est.id =  mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_raca.distorcao_id
            AND dis_raca.tipo_distorcao IN (2, 3)
            --
            AND dis.ano_referencia = %d
            ;',
            $anoReferencia,
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query[0] as $k => $v) {
                $resul[$k] = array(
                    'sem_distorcao' => $v,
                    'distorcao' =>  $query[1][$k],
                );
            }
            array_shift($resul);
        }
        return $resul;
    }


    /**
     * Retorna as quantidades de crianças e adolescentes com e sem distorção idade-série por gênero
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function getPorGenero(IDistorcao $origem, $anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                "sem_distorcao" AS tipo_distorcao,
                SUM(dis_genero.masculino) AS Masculino,
                SUM(dis_genero.feminino) AS Feminino
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_generos dis_genero
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_genero.distorcao_id
            AND dis_genero.tipo_distorcao IN (0, 1)
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d
            --
            UNION
            --
            SELECT
                "distorcao",
                SUM(dis_genero.masculino),
                SUM(dis_genero.feminino)
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_generos dis_genero
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_genero.distorcao_id
            AND dis_genero.tipo_distorcao IN (2, 3)
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId(),
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query[0] as $k => $v) {
                $resul[$k] = array(
                    'sem_distorcao' => $v,
                    'distorcao' =>  $query[1][$k],
                    );
            }
            array_shift($resul);
        }
        return $resul;
    }

    public function getPorGeneroBrasil($anoReferencia = 0)
    {
        $resul = array();
        $sql = sprintf(
            'SELECT
                "sem_distorcao" AS tipo_distorcao,
                SUM(dis_genero.masculino) AS Masculino,
                SUM(dis_genero.feminino) AS Feminino
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_generos dis_genero
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_genero.distorcao_id
            AND dis_genero.tipo_distorcao IN (0, 1)
            --
            AND dis.ano_referencia = %d
            --
            UNION
            --
            SELECT
                "distorcao",
                SUM(dis_genero.masculino),
                SUM(dis_genero.feminino)
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_generos dis_genero
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_genero.distorcao_id
            AND dis_genero.tipo_distorcao IN (2, 3)
            --
            AND dis.ano_referencia = %d
            ;',
            $anoReferencia,
            $anoReferencia
        );
        $query = $this->db->get_results($sql, ARRAY_A);
        if (!empty($query)) {
            foreach ($query[0] as $k => $v) {
                $resul[$k] = array(
                    'sem_distorcao' => $v,
                    'distorcao' =>  $query[1][$k],
                );
            }
            array_shift($resul);
        }
        return $resul;
    }


    /**
     * @param IDistorcao $origem
     * @param int $anoReferencia
     * @return int
     * Retorna total de matriculas por estado ou municipio
     */
    public function getTotalMatriculas(IDistorcao $origem, $anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT
                SUM(dis_ano.sem_distorcao + dis_ano.distorcao_1 + dis_ano.distorcao_2 + dis_ano.distorcao_3) AS total
            FROM te_estados est,
                 te_municipios mun,
                 te_escolas esc,
                 te_distorcoes dis,
                 te_distorcoes_anos dis_ano
            WHERE est.id = mun.estado_id 
            AND mun.id = esc.municipio_id
            AND esc.id = dis.escola_id
            AND dis.id = dis_ano.distorcao_id
            --
            AND dis.ano_referencia = %d
            AND %s.id = %d;',
            $anoReferencia,
            $this->getParamAlias($origem),
            $origem->getId()
        );
        return (int)$this->db->get_var($sql);
    }
    private function getParamAlias(IDistorcao $origem)
    {
        $nome = get_class($origem);
        $estrutura = explode('\\', $nome);
        $param = $estrutura[count($estrutura) - 1];
        return strtolower(substr($param, 0, 3));
    }
}
