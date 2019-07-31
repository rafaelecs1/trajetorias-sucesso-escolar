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


    public function getDataBrasil($anoReferencia)
    {
        $data = new \stdClass();
        $data->total = parent::getTotal($anoReferencia);
        $data->anos_iniciais = parent::getAnosIniciais($anoReferencia);
        $data->anos_anos_finais = parent::getAnosFinais($anoReferencia);
        $data->medio = parent::getlMedio($anoReferencia);
        $data->regiao_norte = parent::getTotalPorRegiao($anoReferencia, 'Norte');
        echo "<pre>";
        var_dump($data);exit;
        echo "</pre>";
        return $data;
    }
}
