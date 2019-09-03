<?php
/**
 * Unicef\TrajetoriaEscolar\Contract\IDistorcao | IDistorcao.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Contract;

/**
 * Define propriedades necessárias para operar a exibição de painéis
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Contract
 * @author André Keher
 * @copyright 2018
 */
interface IDistorcao
{
    /**
     * Retorna o ID
     *
     * @return int
     */
    public function getId();
    
    /**
     * Retorna o nome
     *
     * @return string
     */
    public function getNome();
}
