<section class="ficha municipio">
    <section id="redes-de-ensino">
        <div class="content-select-year-painel">
            <form name="form-year" id="form-year" method="post">
                <label>Ano referência
                    <select class="select-year" id="select-year" name="select-year">
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2018/"; ?>" <?php if ((int)$this->year == 2019) {
                            echo "selected";
                        } ?> >2018
                        </option>
                        <option value="<?php echo "http://" . $_SERVER[HTTP_HOST] . "/painel-brasil/2017/"; ?>" <?php if ((int)$this->year == 2018) {
                            echo "selected";
                        } ?> >2017
                        </option>
                    </select>
                </label>
            </form>
        </div>
        <header>
            <h2>Redes de Ensino - <?php echo $this->year - 1; ?></h2>
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
                    em distorção idade-série
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
            $divisor = $distorcao['sem_distorcao'] + $distorcao['distorcao'];
            if ($divisor <= 0) {
                $divisor = 1;
            }
            $percDistorcao = ($distorcao['distorcao'] * 100) / $divisor;
            ?>
            <div class="total"><?php echo self::formatarNumero($distorcao['distorcao']); ?> <span
                        class="perc">(<?php echo number_format($percDistorcao, 1, ',', '.'); ?>%)<sup
                            class="arterico">*</sup></span></div>
        </section>

        <?php
        if (true) {
            foreach ($distorcao['tipo_rede'] as $rede => $ensinos) {
                echo '<section id="rede-', strtolower($rede), '">';
                echo '<header><h3>Redes ', ($rede == 'Municipal') ? 'Municipais' : 'Estaduais', '</h3></header>';
                foreach ($ensinos as $ensino => $anos) {
                    foreach ($anos as $ano => $v) {
                        echo self::gerarAmostra((($ensino === 'Médio') ? '<span class="bold">Ensino ' . $ensino . '</span>' : 'Ensino ' . $ensino) . '<span class="bold">' . (($ensino !== 'Médio') ? '<br/><span class="bold">Anos ' . $ano . '</span>' : '') . '</span>', $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
                    }
                }
                if ($tipo === 'municipio') {
                    echo '<a class="situacao-das-escolas" data-municipio="', $id, '" data-rede="', sanitize_title($rede), '" href="#situacao-das-escolas-rede-', sanitize_title($rede), '">Situação das escolas</a>';
                    echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                }
                echo '</section>';
            }
        }
        ?>
        <span class="legenda">* Taxa de distorção idade-serie</span>
        <section id="graficos-por-tipo-ensino">
            <?php
            $tiposAno = array(
                'Iniciais' => 'Anos Iniciais - Ensino Fundamental',
                'Finais' => 'Anos Finais - Ensino Fundamental',
                'Todos' => 'Ensino Médio',
            );
            $graficosPorTipoAno = array();
            $lis = $sections = '';
            foreach ($tiposAno as $tipoAno => $label) {
                if (array_key_exists($tipoAno, $distorcao['anos'])) {
                    $slug = 'grafico-' . sanitize_title($label);
                    $id = str_replace('-', '_', $slug);
                    $lis .= '<li><a href="#' . $slug . '">' . $label . '</a></li>';
                    $sections .= '<section id="' . $slug . '" class="aba"><span>Número de estudantes em atraso escolar por ano</span><div id="' . $id . '" class="grafico"></div></section>';

                    foreach ($distorcao['anos'][$tipoAno] as $ano => $distorcoes) {
                        $arAux = array();
                        $arAux[] = $ano . '° ano';
                        foreach ($distorcoes as $dist) {
                            $arAux[] = $dist;
                        }
                        $graficosPorTipoAno[$id][] = $arAux;
                    }
                }
            }
            if (!empty($lis)) {
                echo '<ul class="abas">';
                echo $lis;
                echo '</ul>';
                echo $sections;
            }
            ?>
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
            <?php
            $graficoPorRedes = array();
            foreach ($distorcao['tipo_rede'] as $rede => $ensinos) {
                $arAux = array();
                $arAux[] = $rede;
                $semDistorcao = $distorcaoValor = 0;
                foreach ($ensinos as $anos) {
                    foreach ($anos as $ano) {
                        $semDistorcao += $ano['sem_distorcao'];
                        $distorcaoValor += $ano['distorcao'];
                    }
                }
                $arAux[] = $semDistorcao;
                $arAux[] = $distorcaoValor;
                $graficoPorRedes[] = $arAux;
            }
            ?>
        </section>
    </section>
    <section id="genero">
        <header><h2>Gênero</h2></header>
        <section class="genero">
            <?php
            foreach ($distorcao['genero'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            ?>
        </section>
    </section>
    <section id="cor-raca">
        <header><h2>Cor/Raça</h2></header>
        <section class="cor-raca">
            <?php
            foreach ($distorcao['cor_raca'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            ?>
        </section>
    </section>
    <span class="legenda">* Taxa de distorção idade-serie</span>
    <section id="localizacao">
        <header><h2>Localização</h2></header>
        <section class="localizacao">
            <?php
            foreach ($distorcao['localizacao'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            ?>
        </section>
        <?php
        if (!empty($distorcao['localizacao_diferenciada'])) {
            echo '<section class="localizacao-diferenciada">';
            foreach ($distorcao['localizacao_diferenciada'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            echo '</section>';
        }
        ?>
    </section>
</section>
<span class="legenda">* Taxa de distorção idade-serie</span>