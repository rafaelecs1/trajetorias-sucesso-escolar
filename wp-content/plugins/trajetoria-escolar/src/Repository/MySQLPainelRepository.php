<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository | MySQLPainelRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IPainelRepository;
use Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use Unicef\TrajetoriaEscolar\Repository\MySQLDistorcaoRepository;

/**
 * Realiza as operações de banco de dados MySQL para o cache de painéis
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IPanelRepository
 */
class MySQLPainelRepository implements IPainelRepository
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
     * Retorna um painel
     *
     * Os painéis retornados podem ser de estado, município ou escola
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return array
     */
    public function get(IDistorcao $origem, $anoReferencia = 0)
    {
        $painel = $this->getCache($origem, $anoReferencia);
        if (!empty($painel)) {
            return json_decode($painel, true);
        }
        $repository = new MySQLDistorcaoRepository();
        $painel = array(
            'distorcao' => $repository->getTotal($origem, $anoReferencia),
            'sem_distorcao' => $repository->getTotalSem($origem, $anoReferencia),
            'tipo_rede' => $repository->getPorTipoRede($origem, $anoReferencia),
            'anos' => $repository->getPorAno($origem, $anoReferencia),
            'localizacao' => $repository->getPorLocalizacao($origem, $anoReferencia),
            'localizacao_diferenciada' => $repository->getPorLocalizacaoDiferenciada($origem, $anoReferencia),
            'cor_raca' => $repository->getPorCorRaca($origem, $anoReferencia),
            'genero' => $repository->getPorGenero($origem, $anoReferencia),
            'deficiencia' => array('com'=>'1234567', 'sem'=>'2345678'),
            'total_geral' => $repository->getTotalMatriculas($origem, $anoReferencia),
        );

        $this->save($origem, $anoReferencia, $painel);

        return $painel;
    }

    public function getBrasil($anoReferencia = 0)
    {
        $painel = $this->getCacheBrasil(1, $anoReferencia);
        if (!empty($painel)) {
            return json_decode($painel, true);
        }
        $repository = new MySQLDistorcaoRepository();
        $rDistorcaoPainel = new MySQLMapaRepository();
        $totalGeral = $rDistorcaoPainel->getTotalGeral($anoReferencia);

        $matriculaRepository = new MySQLMatriculaRepository();
        $matriculasDeficiencia = array(
            'com' => $matriculaRepository->getTotalPainelDeficiente($anoReferencia, 1, "Distorcao",null,null),
            'sem' => $matriculaRepository->getTotalPainelDeficiente($anoReferencia, 0, "Distorcao",null,null)
        );

        $painel = array(
            'distorcao' => $repository->getTotalBrasil($anoReferencia),
            'regiao' => $repository->getTotalBrasil($anoReferencia),
            'sem_distorcao' => $repository->getTotalSemBrasil($anoReferencia),
            'tipo_rede' => $repository->getPorTipoRedeBrasil($anoReferencia),
            'anos' => $repository->getPorAnoBrasil($anoReferencia),
            'localizacao' => $repository->getPorLocalizacaoBrasil($anoReferencia),
            'localizacao_diferenciada' => $repository->getPorLocalizacaoDiferenciadaBrasil($anoReferencia),
            'cor_raca' => $repository->getPorCorRacaBrasil($anoReferencia),
            'genero' => $repository->getPorGeneroBrasil($anoReferencia),
            'deficiencia' => $matriculasDeficiencia,
            'total_geral' => $totalGeral
        );

        //Origem 1 = Brasil
        $this->saveBrasil(1, $anoReferencia, $painel);
        return $painel;
    }

    /**
     * Retorna o cache de um painel
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @return string
     */
    public function getCache(IDistorcao $origem, $anoReferencia = 0)
    {
        $tipo = self::getClassName($origem);
        return $this->db->get_var($this->db->prepare(
            'SELECT 
                painel 
            FROM te_paineis 
            WHERE ano_referencia = %d
            AND referencia_id = %d
            AND tipo = "%s";',
            $anoReferencia,
            $origem->getId(),
            $tipo
        ));
    }

    public function getCacheBrasil($referencia, $anoReferencia = 0)
    {
        $tipo = 'Nacional';
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

    /**
     * Salva o cache de um painel
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @param integer $anoReferencia
     * @param array $painel
     * @return int
     */
    public function save(IDistorcao $origem, $anoReferencia = 0, $painel = array())
    {
        $tipo = self::getClassName($origem);
        $this->db->query($this->db->prepare(
            'INSERT INTO te_paineis (ano_referencia, referencia_id, tipo, painel) 
                VALUES (%d, %d, "%s", "%s");',
            $anoReferencia,
            $origem->getId(),
            $tipo,
            json_encode($painel)
        ));
        return $origem->getId();
    }

    public function saveBrasil($origem, $anoReferencia = 0, $painel = array())
    {
        $tipo = 'Nacional';
        $this->db->query($this->db->prepare(
            'INSERT INTO te_paineis (ano_referencia, referencia_id, tipo, painel) 
                VALUES (%d, %d, "%s", "%s");',
            $anoReferencia,
            $origem,
            $tipo,
            json_encode($painel)
        ));
        return $origem;
    }

    /**
     * Apaga o cache de todos os painéis
     *
     * @return void
     */
    public function clear()
    {
        $this->db->query('DELETE FROM te_paineis;');
    }

    /**
     * Retorna um resumo da situação de cache dos painéis
     *
     * @return array
     */
    public function getDetails()
    {
        $resul = array();
        $query = $this->db->get_results(
            'SELECT
              ano_referencia,
              tipo,
              COUNT(*) AS total
            FROM te_paineis
            GROUP BY ano_referencia,
                     tipo
            ORDER BY ano_referencia;',
            ARRAY_A
        );
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['ano_referencia']][$item['tipo']])) {
                    $resul[$item['ano_referencia']][$item['tipo']] = (int)$item['total'];
                }
            }
        }
        return $resul;
    }

    /**
     * Retorna o nome da classe
     *
     * @param \Unicef\TrajetoriaEscolar\Contract\IDistorcao $origem
     * @return string
     */
    private static function getClassName(IDistorcao $origem)
    {
        $nome = get_class($origem);
        $estrutura = explode('\\', $nome);
        return $estrutura[count($estrutura) - 1];
    }
}
