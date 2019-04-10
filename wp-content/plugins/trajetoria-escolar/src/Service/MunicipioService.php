<?php
/**
 * Unicef\TrajetoriaEscolar\Service\EstadoService | EstadoService.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Service;

use \Unicef\TrajetoriaEscolar\Contract\IService;
use \Unicef\TrajetoriaEscolar\Model\Municipio;

/**
 * Disponibiliza serviços para municípios
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Service
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IService
 */
class MunicipioService implements IService
{
    /**
     * Município a utilizar os serviços
     */
    private $municipio;
    
    /**
     * Construtor da classe que inicializa o município a utilizar os serviços
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Municipio $municipio
     * @return void
     */
    public function __construct(Municipio $municipio)
    {
        $this->municipio = $municipio;
    }
    
    /**
     * Valida as informações de um município
     *
     * @return void
     */
    public function validate()
    {
        if (!is_int($this->municipio->getId()) || $this->municipio->getId() <= 0) {
            throw new \UnexpectedValueException('Informe o código do município!');
        }
        if (trim($this->municipio->getNome()) === '') {
            throw new \UnexpectedValueException('Informe o nome do município!');
        }
        if (!is_int($this->municipio->getEstado()->getId())) {
            throw new \UnexpectedValueException('Informe o estado para o município!');
        }
    }
}
