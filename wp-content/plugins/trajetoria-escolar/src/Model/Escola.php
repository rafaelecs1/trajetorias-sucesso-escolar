<?php
/**
 * Unicef\TrajetoriaEscolar\Model\Escola | Escola.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use \Unicef\TrajetoriaEscolar\Model\Municipio;

/**
 * Representa os dados de uma escola
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IDistorcao
 */
class Escola implements IDistorcao
{
    /**
     * ID da escola
     */
    private $id;
    
    /**
     * Nome da escola
     */
    private $nome;
    
    /**
     * O município que a escola pertence
     */
    private $municipio;
    
    /**
     * Localização da escola:
     * * Urbana;
     * * Rural.
     */
    private $localizacao;
    
    /**
     * Dependência da escola:
     * * Municipal;
     * * Estadual.
     */
    private $dependencia;
    
    /**
     * Localização diferenciada da escola:
     * * Área de assentamento;
     * * Área remanescente de quilombos;
     * * Não se aplica;
     * * Terra indígena;
     * * Unidade de uso sustentável;
     * * Unidade de uso sustentável em área remanescente de quilombos;
     * * Unidade de uso sustentável em terra indígena.
     */
    private $localizacaoDiferenciada;

    /**
     * Construtor da classe
     *
     * @param int $id
     * @param string $nome
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @param string $localizacao
     * @param string $dependencia
     * @param string $localizacaoDiferenciada
     * @return void
     */
    public function __construct($id = 0, $nome = '', Municipio $municipio = null, $localizacao = '', $dependencia = '', $localizacaoDiferenciada = 'Não se aplica')
    {
        $this->setId($id);
        $this->setNome($nome);
        $this->setMunicipio($municipio);
        $this->setLocalizacao($localizacao);
        $this->setDependencia($dependencia);
        $this->setLocalizacaoDiferenciada($localizacaoDiferenciada);
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
    public function setId($id)
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
     * Retorna o município
     *
     * @return \Unicef\TrajetoriaEscolar\Model\Municipio
     */
    public function getMunicipio()
    {
        return $this->municipio;
    }

    /**
     * Seta o município
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return void
     */
    public function setMunicipio(Municipio $municipio = null)
    {
        $this->municipio = $municipio;
    }

    /**
     * Retorna a localização
     *
     * Valores retornados:
     * * Urbana;
     * * Rural.
     *
     * @return string
     */
    public function getLocalizacao()
    {
        return $this->localizacao;
    }
    
    /**
     * Seta a localização
     *
     * Valores esperados:
     * * Urbana;
     * * Rural.
     *
     * @param string $localizacao
     * @return string
     */
    public function setLocalizacao($localizacao)
    {
        $this->localizacao = $localizacao;
    }

    /**
     * Retorna a dependência
     *
     * Valores retornados:
     * * Municipal;
     * * Estadual.
     *
     * @return string
     */
    public function getDependencia()
    {
        return $this->dependencia;
    }

    /**
     * Seta a dependência
     *
     * Valores esperados:
     * * Municipal;
     * * Estadual.
     *
     * @param string $dependencia
     * @return void
     */
    public function setDependencia($dependencia)
    {
        $this->dependencia = $dependencia;
    }

    /**
     * Retorna a localização diferenciada
     *
     * @return string
     */
    public function getLocalizacaoDiferenciada()
    {
        return $this->localizacaoDiferenciada;
    }

    /**
     * Seta a localização diferenciada
     *
     * @param string $localizacaoDiferenciada
     * @return void
     */
    public function setLocalizacaoDiferenciada($localizacaoDiferenciada)
    {
        $this->localizacaoDiferenciada = $localizacaoDiferenciada;
    }
}
