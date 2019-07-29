<?php

/**
 * Unicef\TrajetoriaEscolar\Model\Reprovacao\Reprovacao | Reprovacao.php
 *
 * @author Manoel Filho
 */
namespace Unicef\TrajetoriaEscolar\Model\Reprovacao;

use Unicef\TrajetoriaEscolar\Model\Escola;

/**
 * Classe abstrata que define as informações de uma Reprovacao
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model\Reprovacao
 * @author Manoel Filho
 */
abstract class Reprovacao
{

    /**
     * id da reprovacao
     */
    private $id;

    /**
     * id da reprovacao com informações
     */
    private $reprovacaoId;

    /**
     * Escola a qual a reprovacao pertence
     */
    private $escola;

    /**
     * Ano de referência da reprovacao
     */
    private $anoReferencia;

    /**
     * Tipo de ensino da reprovacao
     * * Fundamental
     * * Médio
     */
    private $tipoEnsino;

    /**
     * Tipo de ano da reprovacao:
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
    public function getReprovacaoId()
    {
        return $this->reprovacaoId;
    }

    /**
     * @param mixed $reprovacaoId
     */
    public function setReprovacaoId($reprovacaoId)
    {
        $this->reprovacaoId = $reprovacaoId;
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