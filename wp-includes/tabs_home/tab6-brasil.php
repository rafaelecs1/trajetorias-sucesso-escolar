
<div class="center_mapa_inicial animated fadeIn">

    <div class="mn_mapa_nacional">

        <section class="mn_container mn_flex center">

            <div class="item item_1">
                <h2>Abandono Escolar no Brasil - <?php echo $this->year - 1 ?></h2>
                <div class="mn_fundamental_e_medio">

                    <div class="mn_fundamental">
                        <div class="conteudo">
                            <h3>Ensino Fundamental</h3>

                            <div class="valores">

                                <div class="item iniciais">
                                    <h4>Anos iniciais</h4>
                                    <div class="value value_fi counter"><?php echo number_format($abandonos->anos_iniciais, 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_fi"><?php echo number_format(($abandonos->anos_iniciais * 100) / $matriculas->anos_iniciais, 2) ?></span>]%
                                    </div>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <div class="value value_ff counter"><?php echo number_format($abandonos->anos_finais, 0, ',', '.'); ?></div>
                                    <div class="perc">
                                        [<span><?php echo number_format(($abandonos->anos_finais * 100) / $matriculas->anos_finais, 2) ?></span>]%
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
                                    <div class="value value_mi counter"><?php echo number_format($abandonos->medio, 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_mi"><?php echo number_format(($abandonos->medio * 100) / $matriculas->medio, 2) ?></span>]%
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="item item_2">
                <?php include('wp-includes/mapa_territorios_svg.php'); ?>
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
                                        
                                        <li class="amazonia">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_amazonia_legal->anos_iniciais, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_amazonia_legal->anos_iniciais * 100) / (int)$matriculas->regiao_amazonia_legal->anos_iniciais, 2) ?></span>%]</span>
                                        </li>
                                        
                                        <li class="semiarido">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_semiarido->anos_iniciais, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_semiarido->anos_iniciais * 100) / (int)$matriculas->regiao_semiarido->anos_iniciais, 2) ?></span>%]</span>
                                        </li>
                                        
                                    </ul>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <ul>
                                        
                                        <li class="amazonia">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_amazonia_legal->anos_finais, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_amazonia_legal->anos_finais * 100) / (int)$matriculas->regiao_amazonia_legal->anos_finais, 2) ?></span>%]</span>
                                        </li>

                                        <li class="semiarido">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_semiarido->anos_finais, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_semiarido->anos_finais * 100) / (int)$matriculas->regiao_semiarido->anos_finais, 2) ?></span>%]</span>
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
                                        
                                        <li class="amazonia">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_amazonia_legal->medio, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_amazonia_legal->medio * 100) / (int)$matriculas->regiao_amazonia_legal->medio, 2) ?></span>%]</span>
                                        </li>

                                        <li class="semiarido">
                                            <span class="number counter"><?php echo number_format((int)$abandonos->regiao_semiarido->medio, 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$abandonos->regiao_semiarido->medio * 100) / (int)$matriculas->regiao_semiarido->medio, 2) ?></span>%]</span>
                                        </li>
                                        
                                    </ul>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </section>

    </div>

</div>