<section class="ficha municipio animated fadeIn">
    <section id="redes-de-ensino">
        <header>
            <h2 class="mt-0">Perfil das crianças e adolescentes 2- <?php echo $this->year - 1; ?> </h2>
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
            if ($divisor <= 0) {
                $divisor = 1;
            }
            $percDistorcao = ($abandonos->total * 100) / $divisor;
            ?>
            <div class="total"><?php echo self::formatarNumero($matriculas->total); ?> <span
                        class="perc">(<?php echo number_format($percDistorcao, 1, ',', '.'); ?>%)<sup
                            class="arterico">*</sup></span></div>
        </section>

        <?php

        $tiposAno = array(
            'Iniciais' => 'Anos Iniciais - Ensino Fundamental',
            'Finais' => 'Anos Finais - Ensino Fundamental',
            'Todos' => 'Ensino Médio',
        );

        if (true) {
            foreach ($abandonos as $key => $item) {
                if ($key == 'municipal' || $key == 'estadual') {
                    echo '<section id="rede-', strtolower($key), '">';
                    echo '<header><h3>Redes ', ($key == 'municipal') ? 'Municipais' : 'Estaduais', '</h3></header>';
                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Iniciais </span>', $item->anos_iniciais, $matriculas->total);
                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Finais </span>', $item->anos_finais, $matriculas->total);
                    echo self::gerarAmostra('<span class=""></span><br/><span class="bold">Anos Médio </span>', $item->medio, $matriculas->total);

                    if ($tipo === 'municipio') {
                        echo '<a class="situacao-das-escolas" data-municipio="', $id, '" data-rede="', sanitize_title($rede), '" href="#situacao-das-escolas-rede-', sanitize_title($rede), '">Situação das escolas</a>';
                        echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                    }
                    echo '</section>';
                }
            }
        }
        ?>

        <span class="legenda">* Taxa de distorção idade-serie</span>
        <section id="graficos-por-tipo-ensino">
            <img src="<?php echo plugins_url('trajetoria-escolar/img/panel/porano.png') ?>">
            <!--            --><?php
            //            $graficosPorTipoAno = array();
            //            $lis = $sections = '';
            //            foreach ($tiposAno as $tipoAno => $label) {
            //                if (array_key_exists($tipoAno, $distorcao['anos'])) {
            //                    $slug = 'grafico-' . sanitize_title($label);
            //                    $id = str_replace('-', '_', $slug);
            //                    $lis .= '<li><a href="#' . $slug . '">' . $label . '</a></li>';
            //                    $sections .= '<section id="' . $slug . '" class="aba"><span>Número de estudantes em atraso escolar por ano</span><div id="' . $id . '" class="grafico"></div></section>';
            //
            //                    foreach ($distorcao['anos'][$tipoAno] as $ano => $distorcoes) {
            //                        $arAux = array();
            //                        $arAux[] = $ano . '° ano';
            //                        foreach ($distorcoes as $dist) {
            //                            $arAux[] = $dist;
            //                        }
            //                        $graficosPorTipoAno[$id][] = $arAux;
            //                    }
            //                }
            //            }
            //            if (!empty($lis)) {
            //                echo '<ul class="abas">';
            //                echo $lis;
            //                echo '</ul>';
            //                echo $sections;
            //            }
            //            ?>
        </section>
        <section id="grafico-por-redes">
            <header><h2>Total de Matrículas na Educação Básica</h2></header>
            <div class="valor">
                <?php
                echo number_format((int)$distorcao['total_geral'], 0, ',', '.')
                ?>
            </div>
            <hr>

            <div id="grafico_por_redes" class="grafico"></div>
            <img src="<?php echo plugins_url('trajetoria-escolar/img/panel/educacaobasica.png') ?>">

            <!--            --><?php
            //            $graficoPorRedes = array();
            //            foreach ($distorcao['tipo_rede'] as $rede => $ensinos) {
            //                $arAux = array();
            //                $arAux[] = $rede;
            //                $semDistorcao = $distorcaoValor = 0;
            //                foreach ($ensinos as $anos) {
            //                    foreach ($anos as $ano) {
            //                        $semDistorcao += $ano['sem_distorcao'];
            //                        $distorcaoValor += $ano['distorcao'];
            //                    }
            //                }
            //                $arAux[] = $semDistorcao;
            //                $arAux[] = $distorcaoValor;
            //                $graficoPorRedes[] = $arAux;
            //            }
            //            ?>
        </section>
    </section>
    <section id="genero">
        <header><h2>Gênero</h2></header>
        <section class="genero">
            <?php
            foreach ($abandonos->genero as $k => $v) {
                if ($k != 'total')
                    echo self::gerarAmostra($k, $v, $abandonos->total);
            }
            ?>
        </section>
    </section>
    <section id="cor-raca">
        <header><h2>Cor/Raça</h2></header>
        <section class="cor-raca">
            <?php
            $labelsCorRaca = ['Não declarada', 'Branca', 'Preta', 'Parda', 'Amarela', 'Indígena'];
            $countCorRaca = 0;
            foreach ($abandonos->cor_raca as $k => $v) {
                if ($k != 'total')
                    echo self::gerarAmostra($labelsCorRaca[$countCorRaca], $v, $abandonos->total);
                $countCorRaca++;
            }
            ?>
        </section>
    </section>
    <span class="legenda">* Taxa de distorção idade-serie</span>
    <section id="localizacao">
        <header><h2>Localização</h2></header>
        <section class="localizacao">
            <?php

            foreach ($abandonos->localizacao as $k => $v) {
                if ($k != 'total')
                    echo self::gerarAmostra($k, $v, $abandonos->total);
            }
            ?>
        </section>
        <?php
        $labelsLocalizacao = ['Área De Assentamento', 'Área Remanescente De Quilombos', 'Terra Indígena', 'Unidade De Uso Sustentável', 'Unidade De Uso Sustentável Em Área Remanescente De Quilombos', 'Unidade De Uso Sustentável Em Terra Indígena'];
        if (!empty($abandonos->localizacao_diferenciada)) {
            echo '<section class="localizacao-diferenciada">';
            $countLoc = 0;
            foreach ($abandonos->localizacao_diferenciada as $k => $v) {
                if ($k != 'total')
                    echo self::gerarAmostra($labelsLocalizacao[$countLoc], $v, $abandonos->total);
                $countLoc++;
            }
            echo '</section>';
        }
        ?>
    </section>
</section>
<span class="legenda">* Taxa de distorção idade-serie</span>