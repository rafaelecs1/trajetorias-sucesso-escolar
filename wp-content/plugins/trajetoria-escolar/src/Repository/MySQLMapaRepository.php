<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLMapaRepository | MySQLMapaRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IMapaRepository;
use Unicef\TrajetoriaEscolar\Model\Estado;
use Unicef\TrajetoriaEscolar\Repository\MySQLMunicipioRepository;

/**
 * Realiza as operações de banco de dados MySQL para o cache de mapas
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IMapaRepository
 */
class MySQLMapaRepository implements IMapaRepository
{
    /**
     * Objeto responsável pelas operações de banco de dados
     */
    private $db;

    /**
     * Durante a operação da classe, faz backup do valor padrão que define a precisão em serializações
     */
    private $serializePrecision = 0;

    /**
     * Construtor da classe e que também configura a precisão da serialização de seus métodos
     *
     * @return void
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
        $this->serializePrecision = ini_get('serialize_precision');
        ini_set('serialize_precision', 4);
    }

    /**
     * Retorna o mapa de um estado e seus municípios
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return string
     */
    public function get(Estado $estado, $anoReferencia = 0)
    {
        $mapa = $this->getCache($estado, $anoReferencia);
        if (!empty($mapa)) {
            return $mapa;
        }

        $rMunicipio = new MySQLMunicipioRepository();
        $mapa = $rMunicipio->getByEstado($estado, $anoReferencia);

        $mapa = json_encode($mapa);
        if ($mapa !== '[]') {
            $this->save($estado, $anoReferencia, $mapa);
        }

        return $mapa;
    }

    public function getBrasil($anoReferencia = 0)
    {
        $mapa = $this->getCacheBrasil(2, $anoReferencia);
        if (!empty($mapa)) {
            return (object)json_decode($mapa, true);
        }
        $valores = $this->getTotalBrasil($anoReferencia);


        $mapa = new \stdClass();
        $mapa->nacional->anos_iniciais = 0;
        $mapa->nacional->anos_finais = 0;
        $mapa->nacional->medio = 0;
        // Gera informações nacionais
        foreach ($valores as $valor) {
            if ($valor->tipo_ano == 'Iniciais')
                $mapa->nacional->anos_iniciais += $valor->total;
            if ($valor->tipo_ano == 'Finais')
                $mapa->nacional->anos_finais += $valor->total;
            if ($valor->tipo_ano == 'Todos')
                $mapa->nacional->medio += $valor->total;
        }
        // Gera informações regionais
        $mapa->regiao = $valores;
        $this->saveBrasil(2, $anoReferencia, (array)$mapa);
        return $mapa;
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
     * Retorna o cache do mapa de um estado e seus municípios
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @return string
     */
    public function getCache(Estado $estado, $anoReferencia = 0)
    {
        return $this->db->get_var($this->db->prepare(
            'SELECT 
                informacoes 
            FROM te_mapas 
            WHERE ano_referencia = %d
            AND estado_id = %d;',
            $anoReferencia,
            $estado->getId()
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
     * Salva o cache do mapa de um estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @param integer $anoReferencia
     * @param string $mapa
     * @return void
     */
    public function save(Estado $estado, $anoReferencia = 0, $mapa = '')
    {
        $this->db->query($this->db->prepare(
            'INSERT INTO te_mapas (ano_referencia, estado_id, informacoes) 
                VALUES (%d, %d, "%s");',
            $anoReferencia,
            $estado->getId(),
            $mapa
        ));
    }

    /**
     * Apaga o cache de mapas de todos os estados
     *
     * @return void
     */
    public function clear()
    {
        $this->db->query('DELETE FROM te_mapas;');
    }

    /**
     * Retorna um resumo da situação de cache dos mapas
     *
     * @return array
     */
    public function getDetails()
    {
        $resul = array();
        $query = $this->db->get_results(
            'SELECT
              ano_referencia,
              COUNT(*) AS total
            FROM te_mapas
            GROUP BY ano_referencia
            ORDER BY ano_referencia;',
            ARRAY_A
        );
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['ano_referencia']])) {
                    $resul[$item['ano_referencia']] = (int)$item['total'];
                }
            }
        }
        return $resul;
    }

    /**
     * Retorna um resumo da situação de cache dos mapas
     *
     * @return array
     */
    public function getDistorcaoGeral()
    {
        $resul = array();
        $query = $this->db->get_results(
            'SELECT
              ano_referencia,
              COUNT(*) AS total
            FROM te_distorcoes
           WHERE ano_referencia = 2018;',
            ARRAY_A
        );
        if (!empty($query)) {
            foreach ($query as $item) {
                if (!isset($resul[$item['ano_referencia']])) {
                    $resul[$item['ano_referencia']] = (int)$item['total'];
                }
            }
        }
        return $resul;
    }

    public function getTotalBrasil($anoReferencia = 0)
    {
        $sql = sprintf(
            'SELECT d.tipo_ano, es.regiao, SUM(da.distorcao_3 + da.distorcao_2) as total FROM te_distorcoes d
                    JOIN te_distorcoes_anos as da ON d.id = da.distorcao_id
                    JOIN te_escolas as e ON e.id = d.escola_id
                    JOIN te_municipios as m ON m.id = e.municipio_id
                    JOIN te_estados as es ON es.id = m.estado_id
                    GROUP BY es.regiao, d.tipo_ano, d.ano_referencia HAVING d.ano_referencia = 2018',
            $anoReferencia
        );
        return $this->db->get_results($sql);
    }

    /**
     * Destrutor da classe que restaura a precisão na serialização
     *
     * @return void
     */
    public function __destruct()
    {
        ini_set('serialize_precision', $this->serializePrecision);
    }
}
