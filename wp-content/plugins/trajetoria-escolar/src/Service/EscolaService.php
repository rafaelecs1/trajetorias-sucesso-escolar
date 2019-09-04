<?php
/**
 * Unicef\TrajetoriaEscolar\Service\EscolaService | EscolaService.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Service;

use \Unicef\TrajetoriaEscolar\Contract\IService;
use \Unicef\TrajetoriaEscolar\Model\Escola;

/**
 * Disponibiliza serviços para escolas
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Service
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IService
 */
class EscolaService implements IService
{
    /**
     * Escola a utilizar os serviços
     */
    private $escola;

    /**
     * Construtor da classe que inicializa a escola a utilizar os serviços
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Escola $escola
     * @return void
     */
    public function __construct(Escola $escola)
    {
        $this->escola = $escola;
    }

    /**
     * Valida as informações de uma escola
     *
     * @return void
     */
    public function validate()
    {
        if (!is_int($this->escola->getId()) || $this->escola->getId() <= 0) {
            throw new \UnexpectedValueException('Informe o código da escola!');
        }
        if (trim($this->escola->getNome()) === '') {
            throw new \UnexpectedValueException('Informe o nome da escola!');
        }
        if (!is_int($this->escola->getMunicipio()->getId())) {
            throw new \UnexpectedValueException('Informe o município para a escola!');
        }
        if (trim($this->escola->getLocalizacao()) === '') {
            throw new \UnexpectedValueException('Informe a localização da escola!');
        }
        if (trim($this->escola->getDependencia()) === '') {
            throw new \UnexpectedValueException('Informe a dependência da escola!');
        }
        if (trim($this->escola->getLocalizacaoDiferenciada()) === '') {
            throw new \UnexpectedValueException('Informe a localização diferenciada da escola!');
        }
    }
}
