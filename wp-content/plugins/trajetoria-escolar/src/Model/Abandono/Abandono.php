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

    /**
     * id do abandono
     */
    private $id;

    /**
     * id do abandono com informações
     */
    private $abandonoId;

    /**
     * Escola a qual o abandono pertence
     */
    private $escola;

    /**
     * Ano de referência do abandono
     */
    private $anoReferencia;

    /**
     * Tipo de ensino do abandono
     * * Fundamental
     * * Médio
     */
    private $tipoEnsino;

    /**
     * Tipo de ano do abandono:
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
    public function getAbandonoId()
    {
        return $this->abandonoId;
    }

    /**
     * @param mixed $abandonoId
     */
    public function setAbandonoId($abandonoId)
    {
        $this->abandonoId = $abandonoId;
    }

    /**
     * @return \Unicef\TrajetoriaEscolar\Model\Escola
     */
    public function getEscola()
    {
        return $this->escola;
    }


    /**
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @return void
     */
    public function setEscola(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * @return mixed
     */
    public function getAnoReferencia()
    {
        return $this->anoReferencia;
    }

    /**
     * @param mixed $anoReferencia
     */
    public function setAnoReferencia($anoReferencia)
    {
        $this->anoReferencia = $anoReferencia;
    }

    /**
     * @return mixed
     */
    public function getTipoEnsino()
    {
        return $this->tipoEnsino;
    }

    /**
     * @param mixed $tipoEnsino
     */
    public function setTipoEnsino($tipoEnsino)
    {
        $this->tipoEnsino = $tipoEnsino;
    }

    /**
     * @return mixed
     */
    public function getTipoAno()
    {
        return $this->tipoAno;
    }

    /**
     * @param mixed $tipoAno
     */
    public function setTipoAno($tipoAno)
    {
        $this->tipoAno = $tipoAno;
    }

}