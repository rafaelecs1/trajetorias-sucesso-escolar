<?php
/**
 * Unicef\TrajetoriaEscolar\Model\DistorcaoIdade | DistorcaoIdade.php
 *
 * @author Manoel Souza
 * @copyright 2020
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Model\Distorcao;

/**
 * Representa os dados de uma distorção idade-série por idade
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author Manoel Souza
 * @copyright 2020
 * @extends \Unicef\TrajetoriaEscolar\Model\Distorcao
 */
class DistorcaoIdade extends Distorcao
{

    /**
     * Ano escolar
     */
    private $ano;

    /**
    * Quantidade de crianças e adolescentes com menos de 6 anos
    */
    private $_6_menos;

    /**
    * Quantidade de crianças e adolescentes com seis anos
    */
    private $_6_anos;

    /**
    * Quantidade de crianças e adolescentes com 7 anos
    */
    private $_7_anos;

    /**
    * Quantidade de crianças e adolescentes com 8 anos
    */
    private $_8_anos;

    /**
    * Quantidade de crianças e adolescentes com 9 anos
    */
    private $_9_anos;

    /**
    * Quantidade de crianças e adolescentes com 10 anos
    */
    private $_10_anos;

    /**
    * Quantidade de crianças e adolescentes com 11 anos
    */
    private $_11_anos;

    /**
    * Quantidade de crianças e adolescentes com 12 anos
    */
    private $_12_anos;

    /**
    * Quantidade de crianças e adolescentes com 13 anos
    */
    private $_13_anos;

    /**
    * Quantidade de crianças e adolescentes com 14 anos
    */
    private $_14_anos;

    /**
    * Quantidade de crianças e adolescentes com 15 anos
    */
    private $_15_anos;

    /**
    * Quantidade de crianças e adolescentes com 16 anos
    */
    private $_16_anos;

    /**
    * Quantidade de crianças e adolescentes com 17 anos
    */
    private $_17_anos;

    /**
    * Quantidade de crianças e adolescentes com 18 anos
    */
    private $_18_anos;

    /**
    * Quantidade de crianças e adolescentes com 19 anos
    */
    private $_19_anos;

    /**
    * Quantidade de crianças e adolescentes com 15 anos ou mais
    */
    private $_15_e_mais;

    /**
    * Quantidade de crianças e adolescentes com 18 anos ou mais
    */
    private $_18_e_mais;

    /**
    * Quantidade de crianças e adolescentes com 20 anos e mais
    */
    private $_20_e_mais;

    /**
    * Quantidade de crianças e adolescentes com menos de 10 anos
    */
    private $_10_menos;

    /**
    * Quantidade de crianças e adolescentes com menos de 11 anos
    */
    private $_11_menos;

    /**
    * Quantidade de crianças e adolescentes com 15 anos
    */
    private $_15_menos;

    public function getAno(){
		return $this->ano;
	}

	public function setAno($ano){
		$this->ano = $ano;
	}

	public function get_6_menos(){
		return $this->_6_menos;
	}

	public function set_6_menos($_6_menos){
		$this->_6_menos = $_6_menos;
	}

	public function get_6_anos(){
		return $this->_6_anos;
	}

	public function set_6_anos($_6_anos){
		$this->_6_anos = $_6_anos;
	}

	public function get_7_anos(){
		return $this->_7_anos;
	}

	public function set_7_anos($_7_anos){
		$this->_7_anos = $_7_anos;
	}

	public function get_8_anos(){
		return $this->_8_anos;
	}

	public function set_8_anos($_8_anos){
		$this->_8_anos = $_8_anos;
	}

	public function get_9_anos(){
		return $this->_9_anos;
	}

	public function set_9_anos($_9_anos){
		$this->_9_anos = $_9_anos;
	}

	public function get_10_anos(){
		return $this->_10_anos;
	}

	public function set_10_anos($_10_anos){
		$this->_10_anos = $_10_anos;
	}

	public function get_11_anos(){
		return $this->_11_anos;
	}

	public function set_11_anos($_11_anos){
		$this->_11_anos = $_11_anos;
	}

	public function get_12_anos(){
		return $this->_12_anos;
	}

	public function set_12_anos($_12_anos){
		$this->_12_anos = $_12_anos;
	}

	public function get_13_anos(){
		return $this->_13_anos;
	}

	public function set_13_anos($_13_anos){
		$this->_13_anos = $_13_anos;
	}

	public function get_14_anos(){
		return $this->_14_anos;
	}

	public function set_14_anos($_14_anos){
		$this->_14_anos = $_14_anos;
	}

	public function get_15_anos(){
		return $this->_15_anos;
	}

	public function set_15_anos($_15_anos){
		$this->_15_anos = $_15_anos;
	}

	public function get_16_anos(){
		return $this->_16_anos;
	}

	public function set_16_anos($_16_anos){
		$this->_16_anos = $_16_anos;
	}

	public function get_17_anos(){
		return $this->_17_anos;
	}

	public function set_17_anos($_17_anos){
		$this->_17_anos = $_17_anos;
	}

	public function get_18_anos(){
		return $this->_18_anos;
	}

	public function set_18_anos($_18_anos){
		$this->_18_anos = $_18_anos;
	}

	public function get_19_anos(){
		return $this->_19_anos;
	}

	public function set_19_anos($_19_anos){
		$this->_19_anos = $_19_anos;
	}

	public function get_15_e_mais(){
		return $this->_15_e_mais;
	}

	public function set_15_e_mais($_15_e_mais){
		$this->_15_e_mais = $_15_e_mais;
	}

	public function get_18_e_mais(){
		return $this->_18_e_mais;
	}

	public function set_18_e_mais($_18_e_mais){
		$this->_18_e_mais = $_18_e_mais;
	}

	public function get_20_e_mais(){
		return $this->_20_e_mais;
	}

	public function set_20_e_mais($_20_e_mais){
		$this->_20_e_mais = $_20_e_mais;
    }
    
    public function get_10_menos(){
		return $this->_10_menos;
	}

	public function set_10_menos($_10_menos){
		$this->_10_menos = $_10_menos;
    }

	public function get_11_menos(){
		return $this->_11_menos;
	}

	public function set_11_menos($_11_menos){
		$this->_11_menos = $_11_menos;
	}

	public function get_15_menos(){
		return $this->_15_menos;
	}

	public function set_15_menos($_15_menos){
		$this->_15_menos = $_15_menos;
    }
    
    /**
        * Construtor da classe que recebe as informações básicas para uma distorção por idade
        *
        * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
        * @param int $anoReferencia
        * @param string $tipoAno
        * @param int $ano
        * @param int $_6_menos;
        * @param int $_6_anos;
        * @param int $_7_anos;
        * @param int $_8_anos;
        * @param int $_9_anos;
        * @param int $_10_anos;
        * @param int $_11_anos;
        * @param int $_12_anos;
        * @param int $_13_anos;
        * @param int $_14_anos;
        * @param int $_15_anos;
        * @param int $_16_anos;
        * @param int $_17_anos;
        * @param int $_18_anos;
        * @param int $_19_anos;
        * @param int $_15_e_mais;
        * @param int $_18_e_mais;
        * @param int $_20_e_mais;
        * @param int $_10_menos;
        * @param int $_11_menos;
        * @param int $_15_menos;
        * 
        * @return void
    */
    public function __construct(Escola $escola,
                                $anoReferencia = 0,
                                $tipoAno = '',
                                $ano = 0,
                                $_6_menos = 0,
                                $_6_anos = 0,
                                $_7_anos = 0,
                                $_8_anos = 0,
                                $_9_anos = 0,
                                $_10_anos = 0,
                                $_11_anos = 0,
                                $_12_anos = 0,
                                $_13_anos = 0,
                                $_14_anos = 0,
                                $_15_anos = 0,
                                $_16_anos = 0,
                                $_17_anos = 0,
                                $_18_anos = 0,
                                $_19_anos = 0,
                                $_15_e_mais = 0,
                                $_18_e_mais = 0, 
                                $_20_e_mais = 0, 
                                $_10_menos = 0,
                                $_11_menos = 0, 
                                $_15_menos)
    {
        $this->setEscola($escola);
        $this->setAnoReferencia($anoReferencia);
        $this->setTipoAno($tipoAno);
        $this->setAno($ano);
        $this->set_6_menos($_6_menos);
        $this->set_6_anos($_6_anos);
        $this->set_7_anos($_7_anos);
        $this->set_8_anos($_8_anos);
        $this->set_9_anos($_9_anos);
        $this->set_10_anos($_10_anos);
        $this->set_11_anos($_11_anos);
        $this->set_12_anos($_12_anos);
        $this->set_13_anos($_13_anos);
        $this->set_14_anos($_14_anos);
        $this->set_15_anos($_15_anos);
        $this->set_16_anos($_16_anos);
        $this->set_17_anos($_17_anos);
        $this->set_18_anos($_18_anos);
        $this->set_19_anos($_19_anos);
        $this->set_15_e_mais($_15_e_mais);
        $this->set_18_e_mais($_18_e_mais);
        $this->set_20_e_mais($_20_e_mais);
        $this->set_10_menos($_10_menos);
        $this->set_11_menos($_11_menos);
        $this->set_15_menos($_15_menos);
        parent::__construct();
    }

}
