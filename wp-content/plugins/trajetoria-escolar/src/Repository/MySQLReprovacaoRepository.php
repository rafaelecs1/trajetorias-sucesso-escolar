<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLReprovacaoRepository extends AbstractRepository{

    public function getDataBrasil($anoReferencia)
    {
        return parent::getDataBrasil($anoReferencia);

    }

}