<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLAbandonoRepository extends AbstractRepository{

    public function getDataMapaBrasil($anoReferencia)
    {
        return parent::getDataMapaBrasil($anoReferencia, self::NACIONAL_ABANDONO);
    }

}