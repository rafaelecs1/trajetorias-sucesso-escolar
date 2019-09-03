<?php
/**
 * Unicef\TrajetoriaEscolar\Model\DistorcaoAno | DistorcaoAno.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Representa os dados de uma distorção idade-série por ano escolar
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @extends \Unicef\TrajetoriaEscolar\Model\Distorcao
 */
class DistorcaoAno extends Distorcao
{
    /**
     * Ano escolar
     */
    private $ano;
    
    /**
     * Quantidade de crianças e adolescentes sem distorção
     */
    private $semDistorcao;
    
    /**
     * Quantidade de crianças e adolescentes com 1 ano de distorção
     */
    private $distorcao1;
    
    /**
     * Quantidade de crianças e adolescentes com 2 anos de distorção
     */
    private $distorcao2;
    
    /**
     * Quantidade de crianças e adolescentes com 3 anos de distorção
     */
    private $distorcao3;

    /**
     * Construtor da classe que recebe as informações básicas para uma distorção por ano
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @param int $anoReferencia
     * @param string $tipoAno
     * @param int $ano
     * @param int $semDistorcao
     * @param int $distocao1
     * @param int $distorcao2
     * @param int $distorcao3
     * @return void
     */
    public function __construct(Escola $escola, $anoReferencia = 0, $tipoAno = '', $ano = 0, $semDistorcao = 0, $distocao1 = 0, $distorcao2 = 0, $distorcao3 = 0)
    {
        $this->setEscola($escola);
        $this->setAnoReferencia($anoReferencia);
        $this->setTipoAno($tipoAno);
        $this->setAno($ano);
        $this->setSemDistorcao($semDistorcao);
        $this->setDistorcao1($distocao1);
        $this->setDistorcao2($distorcao2);
        $this->setDistorcao3($distorcao3);

        parent::__construct();
    }

    /**
     * Retorna o ano escolar
     *
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * Seta o ano escolar
     *
     * @param int $ano
     * @return void
     */
    public function setAno($ano)
    {
        $this->ano = (int)$ano;
    }

    /**
     * Retorna a quantidade de alunos sem distorção
     *
     * @return int
     */
    public function getSemDistorcao()
    {
        return $this->semDistorcao;
    }

    /**
     * Seta a quantidade de alunos sem distorção
     *
     * @param int $semDistorcao
     * @return void
     */
    public function setSemDistorcao($semDistorcao)
    {
        $this->semDistorcao = (int)$semDistorcao;
    }

    /**
     * Retorna a quantidade de alunos com um ano de distorção
     *
     * @return int
     */
    public function getDistorcao1()
    {
        return $this->distorcao1;
    }

    /**
     * Seta a quantidade de alunos com um ano de distorção
     *
     * @param int $distorcao1
     * @return void
     */
    public function setDistorcao1($distorcao1)
    {
        $this->distorcao1 = (int)$distorcao1;
    }

    /**
     * Retorna a quantidade de alunos com dois anos de distorção
     *
     * @return int
     */
    public function getDistorcao2()
    {
        return $this->distorcao2;
    }

    /**
     * Seta a quantidade de alunos com dois anos de distorção
     *
     * @param int $distorcao2
     * @return void
     */
    public function setDistorcao2($distorcao2)
    {
        $this->distorcao2 = (int)$distorcao2;
    }

    /**
     * Retorna a quantidade de alunos com três anos de distorção
     *
     * @return int
     */
    public function getDistorcao3()
    {
        return $this->distorcao3;
    }

    /**
     * Seta a quantidade de alunos com três anos de distorção
     *
     * @param int $distorcao3
     * @return void
     */
    public function setDistorcao3($distorcao3)
    {
        $this->distorcao3 = (int)$distorcao3;
    }
}
