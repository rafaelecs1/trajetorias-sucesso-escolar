<?php
/**
 * Unicef\TrajetoriaEscolar\Model\Municipio | Municipio.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Model;

use Unicef\TrajetoriaEscolar\Contract\IDistorcao;
use Unicef\TrajetoriaEscolar\Model\Estado;

/**
 * Representa os dados de um município
 *
 * @package Unicef\TrajetoriaEscolar
 * @subpackage Model
 * @author André Keher
 * @copyright 2018
 * @implements \Unicef\TrajetoriaEscolar\Contract\IDistorcao
 */
class Municipio implements IDistorcao
{
    /**
     * ID do município
     */
    private $id;
    
    /**
     * Nome do município
     */
    private $nome;
    
    /**
     * Estado que o município pertence
     */
    private $estado;

    /**
     * Construtor da classe
     *
     * @param int $id
     * @param string $nome
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @return void
     */
    public function __construct($id = 0, $nome = '', Estado $estado = null)
    {
        $this->setId($id);
        $this->setNome($nome);
        $this->setEstado($estado);
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
     * Retorna o estado
     *
     * @return \Unicef\TrajetoriaEscolar\Model\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Seta o estado
     *
     * @param \Unicef\TrajetoriaEscolar\Model\Estado $estado
     * @return void
     */
    public function setEstado(Estado $estado = null)
    {
        $this->estado = $estado;
    }
}
