<?php
/**
 * Unicef\TrajetoriaEscolar\Model\Estado | Estado.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;

/**
 * Representa os dados de um estado
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IDistorcao
 */
class Estado implements IDistorcao
{
    /**
     * ID do estado
     */
    private $id;
    
    /**
     * Nome do estado
     */
    private $nome;
    
    /**
     * Limites do estado ao norte, sul, leste e oeste
     */
    private $limites;
    
    /**
     * Região do estado
     */
    private $regiao;
    /**
     * @var tipo
     */
    private $tipo;

    /**
     * Construtor da classe
     *
     * @param int $id
     * @param string $nome
     * @param string $limites
     * @param string $regiao
     * @param
     * @return void
     */

    public function __construct($id = 0, $nome = '', $limites = '', $regiao = '')
    {
        $this->setId($id);
        $this->setNome($nome);
        $this->setLimites($limites);
        $this->setRegiao($regiao);
        $this->tipo = 'estado';
    }

    /**
     * Retorna o ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Seta o ID
     *
     * @param int $id
     * @return void
     */
    public function setId($id = 0)
    {
        $this->id = (int)$id;
    }

    /**
     * Retorna o nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Seta o nome
     *
     * @param string $nome
     * @return void
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }
    
    /**
     * Retorna uma string no formato JSON com as coordenadas limite para norte, sul, leste e oeste
     *
     * @return string JSON
     */
    public function getLimites()
    {
        return $this->limites;
    }

    /**
     * Seta através de uma string no formato JSON as coordenadas limite para norte, sul, leste e oeste
     *
     * @param string JSON $limites
     * @return void
     */
    public function setLimites($limites)
    {
        $this->limites = $limites;
    }

    /**
     * Retorna a região
     *
     * Valores retornados:
     * * Centro-Oeste;
     * * Nordeste;
     * * Norte;
     * * Sudeste;
     * * Sul.
     *
     * @return string
     */
    public function getRegiao()
    {
        return $this->regiao;
    }

    /**
     * Seta a região
     *
     * Valores esperados:
     * * Centro-Oeste;
     * * Nordeste;
     * * Norte;
     * * Sudeste;
     * * Sul.
     *
     * @param string $regiao
     * @return void
     */
    public function setRegiao($regiao)
    {
        $this->regiao = $regiao;
    }
}
