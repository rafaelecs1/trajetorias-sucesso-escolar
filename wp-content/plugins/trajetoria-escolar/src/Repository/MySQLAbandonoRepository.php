<?php

namespace Unicef\TrajetoriaEscolar\Repository;

use Unicef\TrajetoriaEscolar\Repository\AbstractRepository;

class MySQLAbandonoRepository extends AbstractRepository{

    public function getDataMapaBrasil($anoReferencia)
    {
        return parent::getDataMapaBrasil($anoReferencia, self::NACIONAL_ABANDONO);
    }

    public function getDataPainelBrasil($anoReferencia)
    {
        return parent::getDataPainelBrasil($anoReferencia, self::NACIONAL_ABANDONO);
    }

    public function getDataAbandonoEstado($estadoId, $anoReferencia)
    {

        $mapa = $this->getCacheBrasil($estadoId, $anoReferencia, self::ESTADO_ABANDONO);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = new \stdClass();
        $data->total = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, null, $estadoId, null, null);
        $data->anos_iniciais = $this->getTotalAnosIniciaisEstadoMunicipioEscola($anoReferencia,  null, null, $estadoId, null, null);
        $data->anos_finais = $this->getTotalAnosFinaisEstadoMunicipioEscola($anoReferencia,  null, null, $estadoId, null, null);
        $data->medio = $this->getTotalAnosMedioEstadoMunicipioEscola($anoReferencia,  null, null, $estadoId, null, null);

        $data->municipal = new \stdClass();
        $data->municipal->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', null, $estadoId, null, null);
        $data->municipal->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'iniciais', $estadoId, null, null);
        $data->municipal->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'finais', $estadoId, null, null);
        $data->municipal->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'medio', $estadoId, null, null);

        $data->estadual = new \stdClass();
        $data->estadual->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', null, $estadoId, null, null);
        $data->estadual->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'iniciais', $estadoId, null, null);
        $data->estadual->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'finais', $estadoId, null, null);
        $data->estadual->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'medio', $estadoId, null, null);

        $data->anos = new \stdClass();
        $data->anos->anos_iniciais = new \stdClass();
        $data->anos->anos_finais = new \stdClass();
        $data->anos->medio = new \stdClass();

        $data->localizacao = new \stdClass();
        $data->localizacao->rural = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Rural', null, $estadoId, null, null);
        $data->localizacao->urbano = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Urbana', null, $estadoId, null, null);

        $data->localizacao_diferenciada = new \stdClass();
        $data->localizacao_diferenciada->area_de_assentamento = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área de assentamento', null, $estadoId, null, null);
        $data->localizacao_diferenciada->area_remanecente_quilombola = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área remanescente de quilombos', null, $estadoId, null, null);
        $data->localizacao_diferenciada->terra_inidigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Terra indígena', null, $estadoId, null, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável', null, $estadoId, null, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em área remanescente de quilombos', null, $estadoId, null, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em terra indígena', null, $estadoId, null, null);

        $data->cor_raca = new \stdClass();
        $data->cor_raca->nao_declarada = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  1, null, $estadoId, null, null);
        $data->cor_raca->branca = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  2, null, $estadoId, null, null);
        $data->cor_raca->preta = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  3, null, $estadoId, null, null);
        $data->cor_raca->parda = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  4, null, $estadoId, null, null);
        $data->cor_raca->amarela = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  5, null, $estadoId, null, null);
        $data->cor_raca->indigena = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  6, null, $estadoId, null, null);

        $data->genero = new \stdClass();
        $data->genero->masculino = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 1, $estadoId, null, null);
        $data->genero->feminismo = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 2, $estadoId, null, null);

        $this->saveBrasil($estadoId, self::ESTADO_ABANDONO, $anoReferencia, $data);

        return $data;

    }

    public function getDataAbandonoMunicipio($municipioId, $anoReferencia)
    {

        $mapa = $this->getCacheBrasil($municipioId, $anoReferencia, self::MUNICIPIO_ABANDONO);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = new \stdClass();
        $data->total = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, null, null, $municipioId, null);
        $data->anos_iniciais = $this->getTotalAnosIniciaisEstadoMunicipioEscola($anoReferencia,  null, null, null, $municipioId, null);
        $data->anos_finais = $this->getTotalAnosFinaisEstadoMunicipioEscola($anoReferencia,  null, null, null, $municipioId, null);
        $data->medio = $this->getTotalAnosMedioEstadoMunicipioEscola($anoReferencia,  null, null, null, $municipioId, null);

        $data->municipal = new \stdClass();
        $data->municipal->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', null, null, $municipioId, null);
        $data->municipal->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'iniciais', null, $municipioId, null);
        $data->municipal->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'finais', null, $municipioId, null);
        $data->municipal->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'medio', null, $municipioId, null);

        $data->estadual = new \stdClass();
        $data->estadual->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', null, null, $municipioId, null);
        $data->estadual->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'iniciais', null, $municipioId, null);
        $data->estadual->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'finais', null, $municipioId, null);
        $data->estadual->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'medio', null, $municipioId, null);

        $data->anos = new \stdClass();
        $data->anos->anos_iniciais = new \stdClass();
        $data->anos->anos_finais = new \stdClass();
        $data->anos->medio = new \stdClass();

        $data->localizacao = new \stdClass();
        $data->localizacao->rural = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Rural', null, null, $municipioId, null);
        $data->localizacao->urbano = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Urbana', null, null, $municipioId, null);

        $data->localizacao_diferenciada = new \stdClass();
        $data->localizacao_diferenciada->area_de_assentamento = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área de assentamento', null, null, $municipioId, null);
        $data->localizacao_diferenciada->area_remanecente_quilombola = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área remanescente de quilombos', null, null, $municipioId, null);
        $data->localizacao_diferenciada->terra_inidigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Terra indígena', null, null, $municipioId, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável', null, null, $municipioId, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em área remanescente de quilombos', null, null, $municipioId, null);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em terra indígena', null, null, $municipioId, null);

        $data->cor_raca = new \stdClass();
        $data->cor_raca->nao_declarada = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  1, null, null, $municipioId, null);
        $data->cor_raca->branca = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  2, null, null, $municipioId, null);
        $data->cor_raca->preta = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  3, null, null, $municipioId, null);
        $data->cor_raca->parda = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  4, null, null, $municipioId, null);
        $data->cor_raca->amarela = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  5, null, null, $municipioId, null);
        $data->cor_raca->indigena = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  6, null, null, $municipioId, null);

        $data->genero = new \stdClass();
        $data->genero->masculino = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 1, null, $municipioId, null);
        $data->genero->feminismo = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 2, null, $municipioId, null);

        $this->saveBrasil($municipioId, self::MUNICIPIO_ABANDONO, $anoReferencia, $data);

        return $data;

    }

    public function getDataAbandonoEscola($escolaId, $anoReferencia)
    {

        $mapa = $this->getCacheBrasil($escolaId, $anoReferencia, self::ESCOLA_ABANDONO);

        if (!empty($mapa)) {
            return json_decode($mapa);
        }

        $data = new \stdClass();
        $data->total = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, null, null, null, $escolaId);
        $data->anos_iniciais = $this->getTotalAnosIniciaisEstadoMunicipioEscola($anoReferencia,  null, null, null, null, $escolaId);
        $data->anos_finais = $this->getTotalAnosFinaisEstadoMunicipioEscola($anoReferencia,  null, null, null, null, $escolaId);
        $data->medio = $this->getTotalAnosMedioEstadoMunicipioEscola($anoReferencia,  null, null, null, null, $escolaId);

        $data->municipal = new \stdClass();
        $data->municipal->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', null, null, null, $escolaId);
        $data->municipal->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'iniciais', null, null, $escolaId);
        $data->municipal->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'finais', null, null, $escolaId);
        $data->municipal->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Municipal', 'medio', null, null, $escolaId);

        $data->estadual = new \stdClass();
        $data->estadual->total = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', null, null, null, $escolaId);
        $data->estadual->anos_iniciais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'iniciais', null, null, $escolaId);
        $data->estadual->anos_finais = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'finais', null, null, $escolaId);
        $data->estadual->medio = $this->getTotalDependenciaEstadoMunicipioEscola($anoReferencia, 'Estadual', 'medio', null, null, $escolaId);

        $data->anos = new \stdClass();
        $data->anos->anos_iniciais = new \stdClass();
        $data->anos->anos_finais = new \stdClass();
        $data->anos->medio = new \stdClass();

        $data->localizacao = new \stdClass();
        $data->localizacao->rural = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Rural', null, null, null, $escolaId);
        $data->localizacao->urbano = $this->getTotalLocalizacaoEstadoMunicipioEscola($anoReferencia, 'Urbana', null, null, null, $escolaId);

        $data->localizacao_diferenciada = new \stdClass();
        $data->localizacao_diferenciada->area_de_assentamento = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área de assentamento', null, null, null, $escolaId);
        $data->localizacao_diferenciada->area_remanecente_quilombola = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Área remanescente de quilombos', null, null, null, $escolaId);
        $data->localizacao_diferenciada->terra_inidigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Terra indígena', null, null, null, $escolaId);
        $data->localizacao_diferenciada->unidade_uso_sustentavel = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável', null, null, null, $escolaId);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em área remanescente de quilombos', null, null, null, $escolaId);
        $data->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena = $this->getTotalLocalizacaoDiferenciadaEstadoMunicipioEscola($anoReferencia, 'Unidade de uso sustentável em terra indígena', null, null, null, $escolaId);

        $data->cor_raca = new \stdClass();
        $data->cor_raca->nao_declarada = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  1, null, null, null, $escolaId);
        $data->cor_raca->branca = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  2, null, null, null, $escolaId);
        $data->cor_raca->preta = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  3, null, null, null, $escolaId);
        $data->cor_raca->parda = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  4, null, null, null, $escolaId);
        $data->cor_raca->amarela = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  5, null, null, null, $escolaId);
        $data->cor_raca->indigena = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  6, null, null, null, $escolaId);

        $data->genero = new \stdClass();
        $data->genero->masculino = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 1, null, null, $escolaId);
        $data->genero->feminismo = $this->getTotalMatriculasEstadoMunicipioEscola($anoReferencia,  null, 2, null, null, $escolaId);

        $this->saveBrasil($escolaId, self::ESCOLA_ABANDONO, $anoReferencia, $data);

        return $data;

    }

}