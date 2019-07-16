<?php

/**
 * Unicef\TrajetoriaEscolar\Model\Matricula\Matricula | Matricula.php
 *
 * @author Manoel Filho
 */
namespace Unicef\TrajetoriaEscolar\Model\Matricula;

use Unicef\TrajetoriaEscolar\Model\Escola;

/**
 * Classe abstrata que define as informações de uma Matrícula
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model\Matricula
 * @author Manoel Filho
 */
abstract class Matricula
{

    /**
     * id da matrícula
     */
    private $id;

    /**
     * id da matrícula com informações
     */
    private $matriculaId;

    /**
     * Escola a qual a matrícula pertence
     */
    private $escola;

    /**
     * Ano de referência da matrícula
     */
    private $anoReferencia;

    /**
     * Tipo de ensino da matrícula
     * * Fundamental
     * * Médio
     */
    private $tipoEnsino;

    /**
     * Tipo de ano da matrícula:
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
    public function getMatriculaId()
    {
        return $this->matriculaId;
    }

    /**
     * @param mixed $matriculaId
     */
    public function setMatriculaId($matriculaId)
    {
        $this->matriculaId = $matriculaId;
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