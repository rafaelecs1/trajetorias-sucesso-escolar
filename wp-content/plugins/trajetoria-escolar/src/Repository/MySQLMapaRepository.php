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
                    $resul[$item['ano_referencia']] = (int) $item['total'];
                }
            }
        }
        return $resul;
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
