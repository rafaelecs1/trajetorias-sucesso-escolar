<?php

/**
 * Unicef\TrajetoriaEscolar\Model\Abandono\Abandono | Abandono.php
 *
 * @author Manoel Filho
 */
namespace Unicef\TrajetoriaEscolar\Model\Abandono;

use Unicef\TrajetoriaEscolar\Model\Escola;

/**
 * Classe abstrata que define as informações de um Abandono
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model\Abandono
 * @author Manoel Filho
 */
abstract class Abandono
{
    private $id;
    private $te_escolas_id;
    private $genero_id;
    private $cor_raca_id;
    private $ano_referencia;
    private $ano1;
    private $ano2;
    private $ano3;
    private $ano4;
    private $ano5;
    private $ano6;
    private $ano7;
    private $ano8;
    private $ano9;
    private $ano10;
    private $ano11;
    private $ano12;
    private $ano13;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTeEscolasId()
    {
        return $this->te_escolas_id;
    }

    /**
     * @param mixed $te_escolas_id
     */
    public function setTeEscolasId($te_escolas_id)
    {
        $this->te_escolas_id = $te_escolas_id;
    }

    /**
     * @return mixed
     */
    public function getGeneroId()
    {
        return $this->genero_id;
    }

    /**
     * @param mixed $genero_id
     */
    public function setGeneroId($genero_id)
    {
        $this->genero_id = $genero_id;
    }

    /**
     * @return mixed
     */
    public function getCorRacaId()
    {
        return $this->cor_raca_id;
    }

    /**
     * @param mixed $cor_raca_id
     */
    public function setCorRacaId($cor_raca_id)
    {
        $this->cor_raca_id = $cor_raca_id;
    }

    /**
     * @return mixed
     */
    public function getAnoReferencia()
    {
        return $this->ano_referencia;
    }

    /**
     * @param mixed $ano_referencia
     */
    public function setAnoReferencia($ano_referencia)
    {
        $this->ano_referencia = $ano_referencia;
    }

    /**
     * @return mixed
     */
    public function getAno1()
    {
        return $this->ano1;
    }

    /**
     * @param mixed $ano1
     */
    public function setAno1($ano1)
    {
        $this->ano1 = $ano1;
    }

    /**
     * @return mixed
     */
    public function getAno2()
    {
        return $this->ano2;
    }

    /**
     * @param mixed $ano2
     */
    public function setAno2($ano2)
    {
        $this->ano2 = $ano2;
    }

    /**
     * @return mixed
     */
    public function getAno3()
    {
        return $this->ano3;
    }

    /**
     * @param mixed $ano3
     */
    public function setAno3($ano3)
    {
        $this->ano3 = $ano3;
    }

    /**
     * @return mixed
     */
    public function getAno4()
    {
        return $this->ano4;
    }

    /**
     * @param mixed $ano4
     */
    public function setAno4($ano4)
    {
        $this->ano4 = $ano4;
    }

    /**
     * @return mixed
     */
    public function getAno5()
    {
        return $this->ano5;
    }

    /**
     * @param mixed $ano5
     */
    public function setAno5($ano5)
    {
        $this->ano5 = $ano5;
    }

    /**
     * @return mixed
     */
    public function getAno6()
    {
        return $this->ano6;
    }

    /**
     * @param mixed $ano6
     */
    public function setAno6($ano6)
    {
        $this->ano6 = $ano6;
    }

    /**
     * @return mixed
     */
    public function getAno7()
    {
        return $this->ano7;
    }

    /**
     * @param mixed $ano7
     */
    public function setAno7($ano7)
    {
        $this->ano7 = $ano7;
    }

    /**
     * @return mixed
     */
    public function getAno8()
    {
        return $this->ano8;
    }

    /**
     * @param mixed $ano8
     */
    public function setAno8($ano8)
    {
        $this->ano8 = $ano8;
    }

    /**
     * @return mixed
     */
    public function getAno9()
    {
        return $this->ano9;
    }

    /**
     * @param mixed $ano9
     */
    public function setAno9($ano9)
    {
        $this->ano9 = $ano9;
    }

    /**
     * @return mixed
     */
    public function getAno10()
    {
        return $this->ano10;
    }

    /**
     * @param mixed $ano10
     */
    public function setAno10($ano10)
    {
        $this->ano10 = $ano10;
    }

    /**
     * @return mixed
     */
    public function getAno11()
    {
        return $this->ano11;
    }

    /**
     * @param mixed $ano11
     */
    public function setAno11($ano11)
    {
        $this->ano11 = $ano11;
    }

    /**
     * @return mixed
     */
    public function getAno12()
    {
        return $this->ano12;
    }

    /**
     * @param mixed $ano12
     */
    public function setAno12($ano12)
    {
        $this->ano12 = $ano12;
    }

    /**
     * @return mixed
     */
    public function getAno13()
    {
        return $this->ano13;
    }

    /**
     * @param mixed $ano13
     */
    public function setAno13($ano13)
    {
        $this->ano13 = $ano13;
    }

}