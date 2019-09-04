
<div class="center_mapa_inicial animated fadeIn">

    <div class="mn_mapa_nacional">

        <section class="mn_container mn_flex center">

            <div class="item item_1">
                <h2 class="animated fadeInDown">Distorção idade-série no Brasil - <?php echo $this->year - 1 ?></h2>
                <div class="mn_fundamental_e_medio">

                    <div class="mn_fundamental">
                        <div class="conteudo">
                            <h3>Ensino Fundamental</h3>

                            <div class="valores">

                                <div class="item iniciais">
                                    <h4>Anos iniciais</h4>
                                    <div class="value value_fi counter"><?php echo number_format($distorcaoMapa->nacional['anos_iniciais'], 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_fi"><?php echo number_format(($distorcaoMapa->nacional['anos_iniciais'] * 100) / $distorcaoMapa->nacional['total_iniciais'], 2) ?></span>]%
                                    </div>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <div class="value value_ff counter"><?php echo number_format($distorcaoMapa->nacional['anos_finais'], 0, ',', '.'); ?></div>
                                    <div class="perc">
                                        [<span><?php echo number_format(($distorcaoMapa->nacional['anos_finais'] * 100) / $distorcaoMapa->nacional['total_finais'], 2) ?></span>]%
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="mn_medio">
                        <div class="conteudo">
                            <h3>Ensino Médio</h3>

                            <div class="valores">

                                <div class="item unico">
                                    <div class="value value_mi counter"><?php echo number_format($distorcaoMapa->nacional['medio'], 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_mi"><?php echo number_format(($distorcaoMapa->nacional['medio'] * 100) / $distorcaoMapa->nacional['total_medio'], 2) ?></span>]%
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="item item_2">

                <?php include('wp-includes/mapa_svg.php'); ?>

            </div>

            <div class="item item_3">

                <div class="mr_fundamental_e_medio">

                    <div class="item mr_fundamental">

                        <div class="conteudo">

                            <div class="cabecalho">
                                <h3>Ensino Fundamental</h3>
                            </div>

                            <div class="valores">

                                <div class="item iniciais">
                                    <div class="cabecalho">
                                        <h4>Anos iniciais</h4>
                                    </div>
                                    <ul>
                                        <li class="norte">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[6]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[6]['total'] * 100) / (int)$distorcaoMapa->regiao[6]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[3]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[3]['total'] * 100) / (int)$distorcaoMapa->regiao[3]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[9]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[9]['total'] * 100) / (int)$distorcaoMapa->regiao[9]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[12]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[12]['total'] * 100) / (int)$distorcaoMapa->regiao[12]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[0]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[0]['total'] * 100) / (int)$distorcaoMapa->regiao[0]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <ul>
                                        <li class="norte">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[7]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[7]['total'] * 100) / (int)$distorcaoMapa->regiao[7]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[4]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[4]['total'] * 100) / (int)$distorcaoMapa->regiao[4]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[10]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[10]['total'] * 100) / (int)$distorcaoMapa->regiao[10]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[13]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[13]['total'] * 100) / (int)$distorcaoMapa->regiao[13]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[1]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[1]['total'] * 100) / (int)$distorcaoMapa->regiao[1]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="item mr_medio">
                        <div class="conteudo">

                            <div class="cabecalho">
                                <h3>Ensino Médio</h3>
                            </div>

                            <div class="valores">

                                <div class="item unico">
                                    <ul>
                                        <li class="norte">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[8]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[8]['total'] * 100) / (int)$distorcaoMapa->regiao[8]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[5]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[5]['total'] * 100) / (int)$distorcaoMapa->regiao[5]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[11]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[11]['total'] * 100) / (int)$distorcaoMapa->regiao[11]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[14]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[14]['total'] * 100) / (int)$distorcaoMapa->regiao[14]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$distorcaoMapa->regiao[2]['total'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$distorcaoMapa->regiao[2]['total'] * 100) / (int)$distorcaoMapa->regiao[2]['total_geral'], 2) ?></span>%]</span>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </section>

        <div class="center" style="text-align: center;">
            <p><a id="bt_link_nacional" style="" href="/painel-brasil/<?php echo $this->year - 1 ?>">Ver dados
                    nacionais</a></p>
        </div>

    </div>

</div>
