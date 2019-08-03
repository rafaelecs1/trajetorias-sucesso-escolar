<?php
/**
 * Unicef\TrajetoriaEscolar\Repository\MySQLPainelRepository | MySQLPainelRepository.php
 *
 * @author André Keher
 * @copyright 2018
 */

namespace Unicef\TrajetoriaEscolar\Repository;

trait SayWorld {
    public function sayHello() {
        $data = new \stdClass();
        $data->total = parent::getTotal($anoReferencia);
        $data->anos_iniciais = parent::getAnosIniciais($anoReferencia);
        $data->anos_finais = parent::getAnosFinais($anoReferencia);
        $data->medio = parent::getlMedio($anoReferencia);

        $data->regiao_norte = new \stdClass();
        $data->regiao_norte->total = parent::getTotalPorRegiao($anoReferencia, 'Norte');
        $data->regiao_norte->anos_iniciais = parent::getTotalPorRegiao($anoReferencia, 'Norte', 'iniciais');
        $data->regiao_norte->anos_finais = parent::getTotalPorRegiao($anoReferencia, 'Norte', 'finais');
        $data->regiao_norte->medio = parent::getTotalPorRegiao($anoReferencia, 'Norte', 'medio');

        $data->regiao_nordeste = new \stdClass();
        $data->regiao_nordeste->total = parent::getTotalPorRegiao($anoReferencia, 'Nordeste');
        $data->regiao_nordeste->anos_iniciais = parent::getTotalPorRegiao($anoReferencia, 'Nordeste', 'iniciais');
        $data->regiao_nordeste->anos_finais = parent::getTotalPorRegiao($anoReferencia, 'Nordeste', 'finais');

        $data->regiao_sul = new \stdClass();
        $data->regiao_sul->total = parent::getTotalPorRegiao($anoReferencia, 'Sul');
        $data->regiao_sul->anos_iniciais = parent::getTotalPorRegiao($anoReferencia, 'Sul', 'iniciais');
        $data->regiao_sul->anos_finais = parent::getTotalPorRegiao($anoReferencia, 'Sul', 'finais');

        $data->regiao_centro_oeste = new \stdClass();
        $data->regiao_centro_oeste->total = parent::getTotalPorRegiao($anoReferencia, 'Centro-Oeste');
        $data->regiao_centro_oeste->anos_iniciais = parent::getTotalPorRegiao($anoReferencia, 'Centro-Oeste', 'iniciais');
        $data->regiao_centro_oeste->anos_finais = parent::getTotalPorRegiao($anoReferencia, 'Centro-Oeste', 'finais');

        $data->regiao_sudeste = new \stdClass();
        $data->regiao_sudeste->total = parent::getTotalPorRegiao($anoReferencia, 'Sudeste');
        $data->regiao_sudeste->anos_iniciais = parent::getTotalPorRegiao($anoReferencia, 'Sudeste', 'iniciais');
        $data->regiao_sudeste->anos_finais = parent::getTotalPorRegiao($anoReferencia, 'Sudeste', 'finais');

        return $data;
    }
}