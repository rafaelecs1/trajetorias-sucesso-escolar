<?php
/**
 * Unicef\TrajetoriaEscolar\Model\DistorcaoRaca | DistorcaoRaca.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Representa os dados de uma distorção idade-série por cor/raça
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @extends \Unicef\TrajetoriaEscolar\Model\Distorcao
 */
class DistorcaoRaca extends Distorcao
{
    /**
     * O tipo de distorção = a quantidade anos em distorção idade-série
     */
    private $tipoDistorcao;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça não declarada
     */
    private $naoDeclarada;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça branca
     */
    private $branca;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça preta
     */
    private $preta;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça parda
     */
    private $parda;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça amarela
     */
    private $amarela;
    
    /**
     * Quantidade de crianças e adolescentes de cor/raça indígena
     */
    private $indigena;

    /**
     * Construtor da classe que recebe as informações básicas para uma distorção por cor/raça
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @param int $anoReferencia
     * @param string $tipoAno
     * @param int $tipoDistorcao
     * @param int $naoDeclarada
     * @param int $branca
     * @param int $preta
     * @param int $parda
     * @param int $amarela
     * @param int $indigena
     * @return void
     */
    public function __construct(Escola $escola, $anoReferencia = 0, $tipoAno = '', $tipoDistorcao = -1, $naoDeclarada = 0, $branca = 0, $preta = 0, $parda = 0, $amarela = 0, $indigena = 0)
    {
        $this->setEscola($escola);
        $this->setAnoReferencia($anoReferencia);
        $this->setTipoAno($tipoAno);
        $this->setTipoDistorcao($tipoDistorcao);
        $this->setNaoDeclarada($naoDeclarada);
        $this->setBranca($branca);
        $this->setPreta($preta);
        $this->setParda($parda);
        $this->setAmarela($amarela);
        $this->setIndigena($indigena);

        parent::__construct();
    }

    /**
     * Retorna o tipo de distorção
     *
     * Valores retornados:
     * * 0;
     * * 1;
     * * 2;
     * * 3.
     *
     * @return int
     */
    public function getTipoDistorcao()
    {
        return $this->tipoDistorcao;
    }

    /**
     * Seta o tipo de distorção
     *
     * Valores esperados:
     * * 0;
     * * 1;
     * * 2;
     * * 3.
     *
     * @param int $tipoDistorcao
     * @return void
     */
    public function setTipoDistorcao($tipoDistorcao)
    {
        $this->tipoDistorcao = $tipoDistorcao;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça não declarada
     *
     * @return int
     */
    public function getNaoDeclarada()
    {
        return $this->naoDeclarada;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça não declarada
     *
     * @param int $naoDeclarada
     * @return void
     */
    public function setNaoDeclarada($naoDeclarada)
    {
        $this->naoDeclarada = (int)$naoDeclarada;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça branca
     *
     * @return int
     */
    public function getBranca()
    {
        return $this->branca;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça branca
     *
     * @param int $branca
     * @return void
     */
    public function setBranca($branca)
    {
        $this->branca = (int)$branca;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça preta
     *
     * @return int
     */
    public function getPreta()
    {
        return $this->preta;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça preta
     *
     * @param int $preta
     * @return void
     */
    public function setPreta($preta)
    {
        $this->preta = (int)$preta;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça parda
     *
     * @return int
     */
    public function getParda()
    {
        return $this->parda;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça parda
     *
     * @param int $parda
     * @return void
     */
    public function setParda($parda)
    {
        $this->parda = (int)$parda;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça amarela
     *
     * @return int
     */
    public function getAmarela()
    {
        return $this->amarela;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça amarela
     *
     * @param int $amarela
     * @return void
     */
    public function setAmarela($amarela)
    {
        $this->amarela = (int)$amarela;
    }

    /**
     * Retorna a quantidade de distorção para a cor/raça indígena
     *
     * @return int
     */
    public function getIndigena()
    {
        return $this->indigena;
    }

    /**
     * Seta a quantidade de distorção para a cor/raça indígena
     *
     * @param int $indigena
     * @return void
     */
    public function setIndigena($indigena)
    {
        $this->indigena = (int)$indigena;
    }
}
