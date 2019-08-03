<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLAbandonoRepository extends AbstractRepository{

    public function getDataBrasil($anoReferencia)
    {

        $mapa = $this->getCacheBrasil(2, $anoReferencia);
        if (!empty($mapa)) {
            return (object)json_decode($mapa, true);
        }

        $data = new \stdClass();
        $data->total = parent::getTotal($anoReferencia);
        $data->anos_iniciais = parent::getAnosIniciais($anoReferencia);
        $data->anos_anos_finais = parent::getAnosFinais($anoReferencia);
        $data->medio = parent::getlMedio($anoReferencia);
        $data->regiao = new \stdClass();
        $data->regiao->norte = parent::getTotalPorRegiao($anoReferencia, 'Norte');
        $data->regiao->sul = parent::getTotalPorRegiao($anoReferencia, 'Sul');
        $data->regiao->centro_oeste = parent::getTotalPorRegiao($anoReferencia, 'Centro-Oeste');
        $data->regiao->sudeste = parent::getTotalPorRegiao($anoReferencia, 'Sudeste');

        $this->saveBrasil(2, $anoReferencia, $data);

        return $data;
    }

    public function saveBrasil($origem, $anoReferencia = 0, $painel = array())
    {
        $tipo = 'NacionalAbandono';
        $this->db->query($this->db->prepare(
            'INSERT INTO te_paineis (ano_referencia, referencia_id, tipo, painel) 
                VALUES (%d, %d, "%s", "%s");',
            $anoReferencia,
            $origem,
            $tipo,
            json_encode($painel)
        ));
        return json_encode($painel);
    }

    public function getCacheBrasil($referencia, $anoReferencia = 0)
    {
        $tipo = 'NacionalAbandono';
        return $this->db->get_var($this->db->prepare(
            'SELECT 
                painel 
            FROM te_paineis 
            WHERE ano_referencia = %d
            AND referencia_id = %d
            AND tipo = "%s";',
            $anoReferencia,
            $referencia,
            $tipo
        ));
    }

}