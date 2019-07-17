<section class="ficha <?php echo $tipo; ?>">
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
            $divisor = $painel['sem_distorcao'] + $painel['distorcao'];
            if ($divisor <= 0) {
                $divisor = 1;
            }
            $percDistorcao = ($painel['distorcao'] * 100) / $divisor;
            ?>
            <div class="total"><?php echo self::formatarNumero($painel['distorcao']); ?> <span class="perc">(<?php echo number_format($percDistorcao, 1, ',', '.'); ?>%)<sup
                            class="asterico">*</sup></span>
            </div>
        </section>
        <?php
        if ($tipo !== 'escola') {
            foreach ($painel['tipo_rede'] as $rede => $ensinos) {
                echo '<section id="rede-', strtolower($rede), '">';
                echo '<header><h3>', (($rede == 'Municipal') && ($tipo != "municipio")) ? 'Redes Municipais' : 'Rede '.$rede, '</h3></header>';
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
        <span class="legenda">* Taxa de distorção idade-serie 1</span>

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
                if (array_key_exists($tipoAno, $painel['anos'])) {
                    $slug = 'grafico-' . sanitize_title($label);
                    $id = str_replace('-', '_', $slug);
                    $lis .= '<li><a href="#' . $slug . '">' . $label . '</a></li>';
                    $sections .= '<section id="' . $slug . '" class="aba"><span>Número de estudantes em atraso escolar por ano</span><div id="' . $id . '" class="grafico"></div></section>';

                    foreach ($painel['anos'][$tipoAno] as $ano => $distorcoes) {
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
                echo number_format((int)$painel['total_geral'], 0, ',', '.')
                ?>
            </div>
            <hr>
            <div id="grafico_por_redes" class="grafico"></div>
            <?php
            $graficoPorRedes = array();
            foreach ($painel['tipo_rede'] as $rede => $ensinos) {
                $arAux = array();
                $arAux[] = $rede;
                $semDistorcao = $distorcao = 0;
                foreach ($ensinos as $anos) {
                    foreach ($anos as $ano) {
                        $semDistorcao += $ano['sem_distorcao'];
                        $distorcao += $ano['distorcao'];
                    }
                }
                $arAux[] = $semDistorcao;
                $arAux[] = $distorcao;
                $graficoPorRedes[] = $arAux;
            }
            ?>
        </section>
    </section>
    <section id="genero">
        <header><h2>Gênero</h2></header>
        <section class="genero">
            <?php
            foreach ($painel['genero'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            ?>
        </section>
    </section>
    <section id="cor-raca">
        <header><h2>Cor/Raça</h2></header>
        <section class="cor-raca">
            <?php
            foreach ($painel['cor_raca'] as $k => $v) {
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
            foreach ($painel['localizacao'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            ?>
        </section>
        <?php
        if (!empty($painel['localizacao_diferenciada'])) {
            echo '<section class="localizacao-diferenciada">';
            foreach ($painel['localizacao_diferenciada'] as $k => $v) {
                echo self::gerarAmostra($k, $v['distorcao'], $v['distorcao'] + $v['sem_distorcao']);
            }
            echo '</section>';
        }
        ?>
    </section>
</section>
<span class="legenda">* Taxa de distorção idade-serie</span>
<div class="remodal"
     data-remodal-id="situacao-das-escolas" <?php echo ($tipo === 'escola') ? 'style="display:none"' : ''; ?>>
    <button data-remodal-action="close" class="remodal-close"></button>
    <div id="lista-escolas">
    </div>
</div>