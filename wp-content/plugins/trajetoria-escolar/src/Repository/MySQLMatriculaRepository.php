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
        $mapa = $this->getCacheBrasil(2, self::NACIONAL_MATRICULA, $anoReferencia);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = parent::getDataMapaBrasil($anoReferencia);

        $this->saveBrasil(2, self::NACIONAL_MATRICULA, $anoReferencia, $data);

        return $data;
    }

    public function getDataPainelBrasil($anoReferencia)
    {
        $mapa = $this->getCacheBrasil(1, self::NACIONAL_MATRICULA, $anoReferencia);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = parent::getDataPainelBrasil($anoReferencia);

        $this->saveBrasil(1, self::NACIONAL_MATRICULA, $anoReferencia, $data);

        return $data;
    }
}
