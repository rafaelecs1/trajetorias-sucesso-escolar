<section class="ficha animated fadeIn <?php echo $tipo; ?>">

    <section id="redes-de-ensino">

        <header>
            <h2 class="mt-0">Redes de Ensino - <?php echo $this->year - 1; ?></h2>
        </header>

        <section id="total-em-distorcao">
            <header>
                <h3>
                    Número total de estudantes
                    <?php
                    if ($tipo !== 'escola') {
                        echo 'das redes municipal e estadual ';
                    }
                    ?>
                    com reprovação
                    <?php
                    if ($tipo === 'estado') {
                        echo 'no estado';
                    } elseif ($tipo === 'municipio') {
                        echo 'no município';
                    } else {
                        echo 'na escola';
                    }
                    ?>:
                </h3>
            </header>

            <?php
                $divisor = $matriculas->total;
                if ($divisor <= 0) { $divisor = 1;}
                $percReprovacao = ($reprovacoes->total * 100) / $divisor;
            ?>
            <div class="total"><?php echo self::formatarNumero($reprovacoes->total); ?>
                <span class="perc">(<?php echo number_format($percReprovacao, 1, ',', '.'); ?>%)
                    <sup class="arterico">*</sup>
                </span>
            </div>

        </section>

        <?php if($tipo !== 'escola') {

            foreach ($reprovacoes as $key => $item) {
                if ($key == 'municipal' || $key == 'estadual') {
                    echo '<section id="rede-', strtolower($key), '">';

                    echo '<header><h3>Redes ', ($key == 'municipal') ? 'Municipais' : 'Estaduais', '</h3></header>';

                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Iniciais </span>', $item->anos_iniciais, $matriculas->$key->anos_iniciais);
                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Finais </span>', $item->anos_finais, $matriculas->$key->anos_finais);
                    echo self::gerarAmostra('<span class=""></span><br/><span class="bold">Ensino Médio </span>', $item->medio, $matriculas->$key->medio);

                    if ($tipo === 'municipio') {
                        echo '<a class="situacao-das-escolas" data-municipio="', $idMunicipio, '" data-rede="', sanitize_title($key), '" href="#situacao-das-escolas-rede-', sanitize_title($key), '">Situação das escolas</a>';
                        echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                    }

                    echo '</section>';
                }
            }

        ?>


         <span class="legenda">* Taxa de reprovação</span>

        <?php } ?>

        <section id="graficos-por-tipo-ensino">

            <?php

                $anosReprovacoes = $reprovacoes->anos;
                $arrayAnos = json_decode(json_encode($anosReprovacoes), True);

                $tiposAnosReprovacoes = array(
                    'Iniciais' => 'Anos Iniciais - Ensino Fundamental',
                    'Finais' => 'Anos Finais - Ensino Fundamental',
                    'Medio' => 'Ensino Médio',
                );


                $graficosReprovacaoPorTipoAno = array();
                $lisReprovacoes = $sectionsReprovacoes = '';

                foreach ($tiposAnosReprovacoes as $tipoAno => $label) {


                    if ( array_key_exists( $tipoAno, $arrayAnos['anos'] ) ) {

                        $slugReprovacao = 'grafico-reprovacao-' . sanitize_title($label);
                        $idReprovacao = str_replace('-', '_', $slugReprovacao);

                        $lisReprovacoes .= '<li><a href="#' . $slugReprovacao . '">' . $label . '</a></li>';
                        $sectionsReprovacoes .= '<section id="' . $slugReprovacao . '" class="aba_reprovacao"><span>Número de estudantes reprovados por ano </span><div id="'.$idReprovacao.'" class="grafico-reprovacao"></div></section>';

                        foreach ( $arrayAnos['anos'][$tipoAno] as $ano => $anoReprovacoes ) {

                            switch ($ano){
                                case 10:
                                    $serie = 1;
                                    break;
                                case 11:
                                    $serie = 2;
                                    break;
                                case 12:
                                    $serie = 3;
                                    break;
                                case 13:
                                    $serie = 4;
                                    break;
                                default:
                                    $serie = $ano;
                            }

                            $arAux = array();
                            $arAux[] = $serie . '° ano';
                            foreach ($anoReprovacoes as $dist) {
                                $arAux[] = $dist;
                            }
                            $graficosReprovacaoPorTipoAno[$idReprovacao][] = $arAux;
                        }

                    }

                }

                if (!empty($lisReprovacoes)) {
                    echo '<ul class="abas_reprovacoes">';
                    echo $lisReprovacoes;
                    echo '</ul>';
                    echo $sectionsReprovacoes;
                }

            ?>

        </section>

        <section id="grafico-por-redes">
            <header><h2>Total de reprovações</h2></header>
            <div class="valor">
                <?php echo number_format((int)$reprovacoes->total, 0, ',', '.') ?>
            </div>
            <hr>
            <div id="grafico_por_redes_reprovacao" class="grafico"></div>

            <?php
                $graficoReprovacaoPorRedes = array();
                $graficoReprovacaoPorRedes[0] = ['Municipal', (int)$matriculas->municipal->total, (int)$reprovacoes->municipal->total, (int)$abandonos->municipal->total];
                $graficoReprovacaoPorRedes[1] = ['Estadual', (int)$matriculas->estadual->total, (int)$reprovacoes->estadual->total, (int)$abandonos->estadual->total];
            ?>

        </section>

    </section>

    <section id="genero">
        <header><h2>Gênero</h2></header>
        <section class="genero">

            <?php
                if($reprovacoes->genero->masculino != null) {
                    echo self::gerarAmostra('Masculino', $reprovacoes->genero->masculino, $matriculas->genero->masculino);
                }
            ?>

            <?php
            if($reprovacoes->genero->feminino != null) {
                echo self::gerarAmostra('Feminino', $reprovacoes->genero->feminino, $matriculas->genero->feminino);
            }
            ?>


        </section>
    </section>

    <section id="cor-raca">
        <header><h2>Cor/Raça</h2></header>
        <section class="cor-raca">
            <?php

                if($reprovacoes->cor_raca->nao_declarada != null) {
                    echo self::gerarAmostra('Não declarada', $reprovacoes->cor_raca->nao_declarada, $matriculas->cor_raca->nao_declarada);
                }

                if($reprovacoes->cor_raca->branca != null) {
                    echo self::gerarAmostra('Branca', $reprovacoes->cor_raca->branca, $matriculas->cor_raca->branca);
                }

                if($reprovacoes->cor_raca->preta != null) {
                    echo self::gerarAmostra('Preta', $reprovacoes->cor_raca->preta, $matriculas->cor_raca->preta);
                }

                if($reprovacoes->cor_raca->parda != null) {
                    echo self::gerarAmostra('Parda', $reprovacoes->cor_raca->parda, $matriculas->cor_raca->parda);
                }

                if($reprovacoes->cor_raca->amarela != null) {
                    echo self::gerarAmostra('Amarela', $reprovacoes->cor_raca->amarela, $matriculas->cor_raca->amarela);
                }

                if($reprovacoes->cor_raca->indigena != null) {
                    echo self::gerarAmostra('Indígena', $reprovacoes->cor_raca->indigena, $matriculas->cor_raca->indigena);
                }
            ?>
        </section>
    </section>

    <span class="legenda">* Taxa de reprovação</span>

    <section id="localizacao">
        <header><h2>Localização</h2></header>

        <section class="localizacao">
            <?php
                if($reprovacoes->localizacao->rural != null) {
                    echo self::gerarAmostra('Rural', $reprovacoes->localizacao->rural, $matriculas->localizacao->rural);
                }

                if($reprovacoes->localizacao->urbana != null) {
                    echo self::gerarAmostra('Urbana', $reprovacoes->localizacao->urbana, $matriculas->localizacao->urbana);
                }
            ?>
        </section>

        <section class="localizacao-diferenciada">
            <?php

                if($reprovacoes->localizacao_diferenciada->area_de_assentamento != null) {
                    echo self::gerarAmostra('Área de assentamento', $reprovacoes->localizacao_diferenciada->area_de_assentamento, $matriculas->localizacao_diferenciada->area_de_assentamento);
                }

                if($reprovacoes->localizacao_diferenciada->area_remanecente_quilombola != null) {
                    echo self::gerarAmostra('Área remanescente de quilombo', $reprovacoes->localizacao_diferenciada->area_remanecente_quilombola, $matriculas->localizacao_diferenciada->area_remanecente_quilombola);
                }

                if($reprovacoes->localizacao_diferenciada->terra_inidigena != null) {
                    echo self::gerarAmostra('Terra indígena', $reprovacoes->localizacao_diferenciada->terra_inidigena, $matriculas->localizacao_diferenciada->terra_inidigena);
                }

                if($reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel != null) {
                    echo self::gerarAmostra('Unidade de uso sustentável', $reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel);
                }

                if($reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo != null) {
                    echo self::gerarAmostra('Unidade de uso sustentável em área remanescente de quilombo', $reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo);
                }

                if($reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena != null) {
                    echo self::gerarAmostra('Unidade de uso sustentável em terra indígena', $reprovacoes->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena);
                }
            ?>
        </section>
    </section>
    <span class="legenda">* Taxa de reprovação</span>

    <?php if($reprovacoes->deficiencia != null) { ?>
        <section id="deficiencia">
            <header><h2>Deficiência</h2></header>
            <section class="deficiencia">
                <?php
                    echo self::gerarAmostra('com deficiência', intval($reprovacoes->deficiencia->com), $matriculas->deficiencia->com);
                    echo self::gerarAmostra('sem deficiência', intval($reprovacoes->deficiencia->sem), $matriculas->deficiencia->sem);
                ?>
            </section>
        </section>
        <span class="legenda">* Taxa de reprovação</span>
    <?php } ?>


</section>
