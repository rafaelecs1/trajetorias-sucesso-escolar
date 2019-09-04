<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IService | IService.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

/**
 * Define métodos que serão disponibilizados aos modelos de dados
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IService
{
    /**
     * Validar os dados do modelo
     */
    public function validate();
}
