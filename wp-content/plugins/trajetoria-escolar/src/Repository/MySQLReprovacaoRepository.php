<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLReprovacaoRepository extends AbstractRepository{

    public function getDataMapaBrasil($anoReferencia)
    {
        return parent::getDataMapaBrasil($anoReferencia);

    }

    public function getDataPainelBrasil($anoReferencia)
    {
        return parent::getDataPainelBrasil($anoReferencia);

    }


}