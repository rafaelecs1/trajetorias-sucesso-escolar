<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLReprovacaoRepository extends AbstractRepository
{

    public function getDataMapaBrasil($anoReferencia)
    {
        $mapa = $this->getCacheBrasil(2, $anoReferencia);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = parent::getDataMapaBrasil($anoReferencia);

        $this->saveBrasil(2, self::NACIONAL_REPROVACAO, $anoReferencia, $data);

        return $data;
    }

    public function getDataPainelBrasil($anoReferencia)
    {
        $panel = $this->getCacheBrasil(2, $anoReferencia);

        if (!empty($panel)) {
            return json_decode($panel);
        }

        $data = parent::getDataPainelBrasil($anoReferencia);

        $this->saveBrasil(1, self::NACIONAL_REPROVACAO, $anoReferencia, $data);

        return $data;
    }


}