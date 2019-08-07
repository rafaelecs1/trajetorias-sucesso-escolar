<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLMatriculaRepository | MySQLMatriculaRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */
namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Contract\IDistorcaoRepository;
use Unicef\TrajetoriaEscolar\Contract\IRestFull;
use Unicef\TrajetoriaEscolar\Model\Distorcao;
use Unicef\TrajetoriaEscolar\Contract\IDistorcao;

class MySQLMatriculaRepository extends AbstractRepository
{

    public function getDataMapaBrasil($anoReferencia)
    {
        return parent::getDataMapaBrasil($anoReferencia, self::NACIONAL_MATRICULA);
    }

    public function getDataMatriculaEstado($estadoId, $anoReferencia)
    {
        return parent::getDataMatriculaEstado($estadoId, $anoReferencia, self::ESTADO_MATRICULA);
    }
}
