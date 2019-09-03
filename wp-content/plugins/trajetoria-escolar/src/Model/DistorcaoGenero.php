<?php
/**
 * Unicef\TrajetoriaEscolar\Model\DistorcaoGenero | DistorcaoGenero.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Representa os dados de uma distorção idade-série por gênero
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @extends \Unicef\TrajetoriaEscolar\Model\Distorcao
 */
class DistorcaoGenero extends Distorcao
{
    /**
     * O tipo de distorção = a quantidade anos em distorção idade-série
     */
    private $tipoDistorcao;
    
    /**
     * Quantidade de crianças e adolescentes do gênero masculino em distorção
     */
    private $masculino;
    
    /**
     * Quantidade de crianças e adolescentes do gênero feminino em distorção
     */
    private $feminino;

    /**
     * Construtor da classe que recebe as informações básicas para uma distorção por gênero
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @param int $anoReferencia
     * @param string $tipoAno
     * @param int $tipoDistorcao
     * @param int $masculino
     * @param int $feminino
     * @return void
     */
    public function __construct(Escola $escola, $anoReferencia = 0, $tipoAno = '', $tipoDistorcao = -1, $masculino = 0, $feminino = 0)
    {
        $this->setEscola($escola);
        $this->setAnoReferencia($anoReferencia);
        $this->setTipoAno($tipoAno);
        $this->setTipoDistorcao($tipoDistorcao);
        $this->setMasculino($masculino);
        $this->setFeminino($feminino);
        
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
        $this->tipoDistorcao = (int)$tipoDistorcao;
    }

    /**
     * Retorna a quantidade de distorção para o gênero masculino
     *
     * @return int
     */
    public function getMasculino()
    {
        return $this->masculino;
    }

    /**
     * Seta a quantidade de distorção para o gênero masculino
     *
     * @param int $masculino
     * @return void
     */
    public function setMasculino($masculino)
    {
        $this->masculino = (int)$masculino;
    }

    /**
     * Retorna a quantidade de distorção para o gênero feminino
     *
     * @return int
     */
    public function getFeminino()
    {
        return $this->feminino;
    }

    /**
     * Seta a quantidade de distorção para o gênero feminino
     *
     * @param int $feminino
     * @return void
     */
    public function setFeminino($feminino)
    {
        $this->feminino = (int)$feminino;
    }
}
