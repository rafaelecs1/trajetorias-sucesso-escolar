<?php
/**
 * Unicef\TrajetoriaEscolar\Model\Distorcao | Distorcao.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Model\Escola;

/**
 * Classe abstrata que define as informações básica de uma distorção
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 */
abstract class Distorcao
{
    /**
     * ID da distorção
     */
    private $id;
    
    /**
     * ID da distorção com as informações básicas
     */
    private $distorcaoId;
    
    /**
     * Escola que a distorção se refere
     */
    private $escola;
    
    /**
     * Ano de referência da distorção
     */
    private $anoReferencia;
    
    /**
     * Tipo de ensino da distorção:
     * * Fundamental;
     * * Médio.
     */
    private $tipoEnsino;
    
    /**
     * Tipo de ano da distorção:
     * * Iniciais;
     * * Finais;
     * * Todos.
     */
    private $tipoAno;

    /**
     * Construtor que configura o tipo de ensino automaticamente com base no tipo de ano
     *
     * @return void
     */
    public function __construct()
    {
        $this->setTipoEnsino('Fundamental');
        if ($this->getTipoAno() === 'Todos') {
            $this->setTipoEnsino('Médio');
        }
    }

    /**
     * Retorna o ID da distorção
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Seta o ID da distorção
     *
     * @param int $id
     * @return void
     */
    public function setId($id = 0)
    {
        $this->id = (int)$id;
    }
    
    /**
     * Retorna o ID da distorção com as informações básicas
     *
     * @return int
     */
    public function getDistorcaoId()
    {
        return $this->distorcaoId;
    }

    /**
     * Seta o ID da distorção com as informações básicas
     *
     * @param mixed $distorcaoId
     * @return void
     */
    public function setDistorcaoId($distorcaoId)
    {
        $this->distorcaoId = (int)$distorcaoId;
    }

    /**
     * Retorna a escola associada a distorção
     *
     * @return \Unicef\TrajetoriaEscolar\Model\Escola
     */
    public function getEscola()
    {
        return $this->escola;
    }
        
    /**
     * Seta uma escola para a distorção
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @return void
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * Retorna o ano de referência da distorção
     *
     * @return int
     */
    public function getAnoReferencia()
    {
        return $this->anoReferencia;
    }

    /**
     * Seta o ano de referência da distorção
     *
     * @param int $anoReferencia
     * @return void
     */
    public function setAnoReferencia($anoReferencia)
    {
        $this->anoReferencia = (int)$anoReferencia;
    }

    /**
     * Retorna o tipo de ensino da distorção
     *
     * Valores retornados:
     * * Fundamental;
     * * Médio.
     * @return string
     */
    public function getTipoEnsino()
    {
        return $this->tipoEnsino;
    }

    /**
     * Seta o tipo de ensino da distorção
     *
     * Valores esperados:
     * * Fundamental;
     * * Médio.
     *
     * @param string $tipoEnsino
     * @return void
     */
    public function setTipoEnsino($tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
    }

    /**
     * Retorna o tipo de ano
     *
     * Valores retornandos:
     * * Iniciais;
     * * Finais;
     * * Todos.
     *
     * @return string
     */
    public function getTipoAno()
    {
        return $this->tipoAno;
    }

    /**
     * Seta o tipo de ano
     *
     * Valores esperados:
     * * Iniciais;
     * * Finais;
     * * Todos.
     *
     * @param string $tipoAno
     * @return void
     */
    public function setTipoAno($tipoAno)
    {
        $this->tipoAno = $tipoAno;
    }
}
