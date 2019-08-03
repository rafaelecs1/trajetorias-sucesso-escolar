<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLAbandonoRepository extends AbstractRepository{

    public function getDataBrasil($anoReferencia)
    {
        $data = new \stdClass();
        $data->total = parent::getTotal($anoReferencia);
        $data->anos_iniciais = parent::getAnosIniciais($anoReferencia);
        $data->anos_anos_finais = parent::getAnosFinais($anoReferencia);
        $data->medio = parent::getlMedio($anoReferencia);
        $data->regiao_norte = parent::getTotalPorRegiao($anoReferencia, 'Norte');
        $data->regiao_sul = parent::getTotalPorRegiao($anoReferencia, 'Sul');
        $data->regiao_centro_oeste = parent::getTotalPorRegiao($anoReferencia, 'Centro-Oeste');
        $data->regiao_sudeste = parent::getTotalPorRegiao($anoReferencia, 'Sudeste');
        return $data;
    }

}