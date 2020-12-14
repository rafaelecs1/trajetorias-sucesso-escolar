<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLTrajetoriaRepository | MySQLTrajetoriaRepository.php
 *
 * @author Manoel Souza
 * @copyright 2020
 */

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;

/**
 * Realiza as operações de banco de dados MySQL para o cache de painéis
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Repository
 * @author Manoel Souza
 * @copyright 2020
 * @implements \Unicef\TrajetoriaEscolar\Contract\IPanelRepository
 */
class MySQLTrajetoriaRepository 
{

    /**
     * Objeto responsável pelas operações de banco de dados
     */
    private $db;

    /**
     * Construtor da classe
     *
     * @return void
     */
    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

    
}
