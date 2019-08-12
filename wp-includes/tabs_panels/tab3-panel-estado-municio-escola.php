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
                    que abandonaram a escola
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
                $percReprovacao = ($abandonos->total * 100) / $divisor;
            ?>
            <div class="total"><?php echo self::formatarNumero($matriculas->total); ?>
                <span class="perc">(<?php echo number_format($percReprovacao, 1, ',', '.'); ?>%)
                    <sup class="arterico">*</sup>
                </span>
            </div>

        </section>

        <?php if($tipo !== 'escola') {

            foreach ($abandonos as $key => $item) {
                if ($key == 'municipal' || $key == 'estadual') {

                    echo '<section id="rede-', strtolower($key), '">';

                    echo '<header><h3>Redes ', ($key == 'municipal') ? 'Municipais' : 'Estaduais', '</h3></header>';

                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Iniciais </span>', $item->anos_iniciais, $matriculas->$key->anos_iniciais);
                    echo self::gerarAmostra('<span class="">Ensino Fundamental</span> <br/><span class="bold">Anos Finais </span>', $item->anos_finais, $matriculas->$key->anos_finais);
                    echo self::gerarAmostra('<span class=""></span><br/><span class="bold">Anos Médio </span>', $item->medio, $matriculas->$key->medio);

                    if ($tipo === 'municipio') {
                        echo '<a class="situacao-das-escolas" data-municipio="', $idMunicipio, '" data-rede="', sanitize_title($key), '" href="#situacao-das-escolas-rede-', sanitize_title($key), '">Situação das escolas</a>';
                        echo '<img style="display: none;" alt="Processando..." title="Processando..." src="', admin_url('images/loading.gif'), '"/>';
                    }

                    echo '</section>';

                }
            }

        ?>

        <span class="legenda">* Taxa de abandono</span>

        <?php } ?>

        <!-- antigo php para loop das redes municipais e estaduais -->

        <!-- antigo php os graficos por tipo de ensino -->

        <!-- antigo php os graficos por redes -->

    </section>

    <section id="genero">
        <header><h2>Gênero</h2></header>
        <section class="genero">

            <?php
            if($abandonos->genero->masculino != null) {
                echo self::gerarAmostra('Masculino', $abandonos->genero->masculino, $matriculas->genero->masculino);
            }
            ?>

            <?php
            if($abandonos->genero->feminino != null) {
                echo self::gerarAmostra('Feminino', $abandonos->genero->feminino, $matriculas->genero->feminino);
            }
            ?>


        </section>
    </section>


    <section id="cor-raca">
        <header><h2>Cor/Raça</h2></header>
        <section class="cor-raca">
            <?php

            if($abandonos->cor_raca->nao_declarada != null) {
                echo self::gerarAmostra('Não declarada', $abandonos->cor_raca->nao_declarada, $matriculas->cor_raca->nao_declarada);
            }

            if($abandonos->cor_raca->branca != null) {
                echo self::gerarAmostra('Branca', $abandonos->cor_raca->branca, $matriculas->cor_raca->branca);
            }

            if($abandonos->cor_raca->preta != null) {
                echo self::gerarAmostra('Preta', $abandonos->cor_raca->preta, $matriculas->cor_raca->preta);
            }

            if($abandonos->cor_raca->parda != null) {
                echo self::gerarAmostra('Parda', $abandonos->cor_raca->parda, $matriculas->cor_raca->parda);
            }

            if($abandonos->cor_raca->amarela != null) {
                echo self::gerarAmostra('Amarela', $abandonos->cor_raca->amarela, $matriculas->cor_raca->amarela);
            }

            if($abandonos->cor_raca->indigena != null) {
                echo self::gerarAmostra('Indígena', $abandonos->cor_raca->indigena, $matriculas->cor_raca->indigena);
            }
            ?>
        </section>
    </section>

    <span class="legenda">* Taxa de abandono</span>

    <section id="localizacao">
        <header><h2>Localização</h2></header>

        <section class="localizacao">
            <?php
            if($abandonos->localizacao->rural != null) {
                echo self::gerarAmostra('Rural', $abandonos->localizacao->rural, $matriculas->localizacao->rural);
            }

            if($abandonos->localizacao->urbana != null) {
                echo self::gerarAmostra('Urbana', $abandonos->localizacao->urbana, $matriculas->localizacao->urbana);
            }
            ?>
        </section>

        <section class="localizacao-diferenciada">
            <?php
            if($abandonos->localizacao_diferenciada->area_de_assentamento != null) {
                echo self::gerarAmostra('Áre de assentamento', $abandonos->localizacao_diferenciada->area_de_assentamento, $matriculas->localizacao_diferenciada->area_de_assentamento);
            }

            if($abandonos->localizacao_diferenciada->area_remanecente_quilombola != null) {
                echo self::gerarAmostra('Área remanescente de quilombo', $abandonos->localizacao_diferenciada->area_remanecente_quilombola, $matriculas->localizacao_diferenciada->area_remanecente_quilombola);
            }

            if($abandonos->localizacao_diferenciada->terra_inidigena != null) {
                echo self::gerarAmostra('Terra indígena', $abandonos->localizacao_diferenciada->terra_inidigena, $matriculas->localizacao_diferenciada->terra_inidigena);
            }

            if($abandonos->localizacao_diferenciada->unidade_uso_sustentavel != null) {
                echo self::gerarAmostra('Unidade de uso sustentável', $abandonos->localizacao_diferenciada->unidade_uso_sustentavel, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel);
            }

            if($abandonos->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo != null) {
                echo self::gerarAmostra('Unidade de uso sustentável em área remanescente de quilombo', $abandonos->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel_em_area_remancente_de_quilombo);
            }

            if($abandonos->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena != null) {
                echo self::gerarAmostra('Unidade de uso sustentável em terra indígena', $abandonos->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena, $matriculas->localizacao_diferenciada->unidade_uso_sustentavel_em_terra_indigena);
            }
            ?>
        </section>

    </section>

    <span class="legenda">* Taxa de abandono</span>
</section>
