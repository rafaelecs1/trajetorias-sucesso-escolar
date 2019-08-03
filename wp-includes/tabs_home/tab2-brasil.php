
<div class="center_mapa_inicial animated fadeIn">

    <div class="mn_mapa_nacional">

        <section class="mn_container mn_flex center">

            <div class="item item_1">
                <h2>Reprovação no Brasil - <?php echo $this->year - 1 ?></h2>
                <div class="mn_fundamental_e_medio">

                    <div class="mn_fundamental">
                        <div class="conteudo">
                            <h3>Ensino Fundamental</h3>

                            <div class="valores">

                                <div class="item iniciais">
                                    <h4>Anos iniciais</h4>
                                    <div class="value value_fi counter"><?php echo number_format($reprovacoes->anos_iniciais['qtd'], 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_fi"><?php echo number_format(($reprovacoes->anos_iniciais['qtd'] * 100) / $matriculas->anos_iniciais['qtd'], 2) ?></span>]%
                                    </div>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <div class="value value_ff counter"><?php echo number_format($reprovacoes->anos_finais['qtd'], 0, ',', '.'); ?></div>
                                    <div class="perc">
                                        [<span><?php echo number_format(($reprovacoes->anos_finais['qtd'] * 100) / $matriculas->anos_finais['qtd'], 2) ?></span>]%
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
                                    <div class="value value_mi counter"><?php echo number_format($reprovacoes->medio['qtd'], 0, ',', '.'); ?></div>
                                    <div class="perc">[<span
                                                class="perc_mi"><?php echo number_format(($reprovacoes->medio['qtd'] * 100) / $matriculas->medio['qtd'], 2) ?></span>]%
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="item item_2">

                <div class="mapa_legenda">

                    <div class="item mapa">
                        <svg version="1.1" id="svg-map" xmlns="http://www.w3.org/2000/svg"
                             xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="225px"
                             height="225px" xml:space="preserve">

        <style type="text/css">
            .st0 {
                fill: #64C6E3;
            }

            .st1 {
                fill: #018BB3;
            }

            .st2 {
                fill: #ECB615;
            }

            .st3 {
                fill: #E38524;
            }

            .st4 {
                fill: #CC3282;
            }

            .st0:hover {
                fill: #3E7A8B;
            }

            .st1:hover {
                fill: #055871;
            }

            .st2:hover {
                fill: #A3810F;
            }

            .st3:hover {
                fill: #9E5F14;
            }

            .st4:hover {
                fill: #9F2965;
            }

        </style>

                            <a xlink:href="#norte" class="region">
                                <g>
                                    <path class="st0" d="M160.1,95l-7.1-9.2l3.2-4.4l-3.5-1.1l-3.6-4.4l1-8.5l-5.4-2.8l8.5-9l4.9-13.9l-10.6-2l-4.2,6.8l-7.4,0.4l-1-0.6
l6-2.3c0,0,2.9-5,3.2-5.3s1.8-2,1.2-2.4s-5.4,1.6-5.8,1.1s-5.1-1.2-5.1-1.2l-1,9.1l-4.2-2.5l-5,0.3c0.5-2.5,1.4-6.7,1.8-6.7
c0.5,0,5.2-4.9,6-5.2s4.2-3.3,4.2-3.3l-2-2.5l-3-2.5c0,0-0.5-3.5-0.6-3.9s-2.5-4.8-2.5-4.8l-0.1-4.5L122,22.3l-6.8,0.8l-7.2,1.4
l-0.5-3.6H102l-1.1,2.6l-14.6,4.5l0,0.2C84,26.4,81.1,24,81.1,24l-3.4-1.8l-0.3-6.9l2.7-5.1l-2-3l-0.6-3.1l-2,2c0,0-3.8,2-4.3,2.2
s-4.5,0-4.5,0l-3.5,2.7l-2.4,1.5l-5-1.2c0,0-2.7-1.8-3.2-1.9l-1.2,1.4l2.8,2.8l0.2,5.9l1.2,3.9l-0.8-0.2l-2,3.6l-6.6,3.4L37.1,29
l-3.2-2.8l-1-2.1l-8,1.4l-3.5,0.1l3.5,2.8l3.9,2.8l-2,1.4l-4.6-0.2l-1.2,0.5l4.4,6.5l-0.9,2.2l0.8,6.9l-1.2,6.2l-2.1,4.1l-6.4,1.8
l-6,0.6l-3.6,5.4l-2.1,5.8l-2.1,1.9l1.4,3l-2.9,4.8C0.6,82.4,3,84.5,3,84.5V88l3.9,1.5l1.9,2.1l7.2-2.2L17,98h9l13.4-6.5l9-0.8
l-0.8,4.6l0.8,8.6l4.2,1l7.2,2l7.8,4.4l6.9,1.2L77,109v-9l-6.3-2.1L69,91.6V86l18.2-0.4l1.9-8.1l3.4,7.9l4.2,3.6l38.4,1.8l-3.4,15.9
l3.4,1.8l4.8,3.4l2.4-3.4l3.8,2.6l7-1.2l5.8-0.5l-2.2-9.5L160.1,95z"/>
                                </g>
                            </a>
                            <a xlink:href="#centro_oeste" class="region">
                                <path class="st1" d="M75.6,112.7l3-3.6l0.4-9.7l-8.2-3.1l-0.9-4.9l0.4-4.1L87,86.5l2.4-5.8l2.2,5.8l3.3,2.7l3.5,1l35,1.8l-3.2,15.1
l3.8,1.9c0,0,5.8,4.2,6,4.2s2.3-2.8,2.3-2.8l3.2,2.1l10.3-1.6v4.1l2,2.3v1.7l-2.5,0.8l-2.4,2.8l0.6,2.6l-4.2,2.2l0.7,7.4l-2.1,4.8
l-6.2,0.5l-7.8,1.1l-3.4,3.7l-1.8,2.3v2.8l-2.4,2.6l-4.2,7.7l-5.2,4.2l-3.6,2.6l-3,4.1l-1.9,0.7l-3.8,0.6l-2.7-10.1
c0,0-10.8-0.2-10.8-0.6c0-0.3-1.1-6.2-1.1-6.2l-0.8-7.2l2.5-5.6l0.6-6.8l-3.9-8.2l-11-0.5L75.6,112.7z"/>
                            </a>
                            <a xlink:href="#nordeste" class="region">
                                <path class="st2" d="M158,113v3l1.6,4.8l10.1-4.4l2.4,3.6h4.9l5.1,1.2l3.8,1c0,0,0.7,1.8,1.1,1.9s7.1,1.4,7.1,1.4v2.5l-1.9,3.2
l-2,3.4l3.8,4.4h3.1l2.2-7.2c0,0,0.9-6.6,0.8-8s-1.1-8.2-1.1-9.2s0.4-4.3,0.5-4.9s0.2-1.7,1.2-1.7s0.2,2.4,2.2,1.1s2.1-2.1,2.5-2.5
s0.6-2.6,0.6-2.6V102l7.8-9.1l5.8-5.5l1.4-5.2V75l-1.6-6.4l-0.9-3l-8.8-1.8l-9.8-6.5l-5.8-3.4H186h-3l-5.5-2H171l-4.6,1.4l0.6-4V47
l-7.4-4.4l-3.4,9.4l-5.5,8.1l-4.3,3.9l5.3,2.8l-1.1,9.6l2.8,2.6l3.8,0.5l0.9,3.6l-3,2.9l6.6,8.2l-3.6,5.9l1.1,4.1l0.8,4.9L158,113z"
                                />
                            </a>
                            <a xlink:href="#sudeste" class="region">
                                <path class="st3" d="M119.8,165.1l9.4,0.5c0,0,4.7,0.7,5.1,0.7s3.8,1.8,3.8,1.8v3.9l1.4,2.6l3.5,1.9c0,0,2.2,1.5,2.6,1.5
s2.2,1.5,2.2,1.5l7.5-7.2l6.1-1.6l6.5-2c0,0,0.6-1.6,1.3-1.8s5-0.6,5-0.6s2.8-0.1,3.4-0.1s5-1.5,5-1.5s3.4-2.7,3.8-3.2
s1.4-1.6,1.3-2.1s-1.2-2-0.6-2.6s3.5-5.1,3.5-5.1s1.4-4,2-4.4s4.2-4.5,4.1-5.2s-2.6-2-2.6-2H190l-2.2-5.5l2.4-5l1.4-2.3l-5.1-1.7
l-4.9-2.3l-1.5-0.6l-7-0.6h-2l-2.1-3.2l-8.8,3.4l-3.5-0.7l-1.2,5.1l-3,1.7l0.8,4.2l-1.2,2.7l0.6,3.1l-1.9,2.4l-5.1,1.2l-6.2-0.1
l-7.4,3.9l-0.8,3.2l-1.9,2.5l-2.8,4.1l-0.9,3.5l-0.4,1.4L119.8,165.1z"/>
                            </a>
                            <a xlink:href="#sul" class="region">
                                <path class="st4" d="M92.8,209.4c0.8,0,3.4-1.3,3.4-1.3l1.8,0.5l2.4,1.5l1.9,2.3c0,0-1.4,1,0,1.1s3.1,0.5,3,0s1.5,1.5,2,1.6
s4,0.6,4,0.6l1.3,3.1l4.5,2.6c0,0,1.3,0.5,1.1,1.1s0,1.7,0,1.7l-1.4,2.2l-0.6,1.9c0,0,1.3-1.3,2-1.4c0.7-0.1,6-4.4,6-4.4l-1.6-3.2
c0,0,0.1-1.2,0.5-1.9s2.2-3,2.5-3.5s1.1-1.8,1.2-2.2s0-2.1,0-2.1s1.1-0.2,1.5-0.4s0.9-0.4,1.5-0.4s1.2,0,1.2,0v2v1c0,0-0.6,1-1,1.5
s-2,1.8-2,1.8l-1.4,1.4l-1.7,1.5c0,0,0.1,1.6,0.8,1.4s0.9-0.4,1.5-0.6s1.3-0.2,1.8-0.7c0.5-0.5,1.6-1.8,1.6-1.8l2.6-3l0.9-2.5V209
c0,0,1.6-3.4,1.9-3.8s1.5-2.1,2.1-2.5s4-2.1,4.5-2.4s1.5-1.8,1.5-1.8V194l-0.6-10.1l2-1.9l1-1.9l-8.2-5l-1.9-2.2l-0.5-2.6l-0.2-1.8
l-8.5-1.5H122h-4l-4.6,4l-2.4,3v5l1.2,1.9l1.5,3.8l1.5,2.1l-0.2,3.1l-0.4,1.4l-2.2,1.1l-3.5,1.2l-3.2,1.6l-1.9,2.3l-3,2.6
c0,0-2.9,2.1-3.1,2.5s-1.1,1.6-1.6,1.9s-2.6,1.5-2.6,1.5L92.8,209.4z"/>
                            </a>

    </svg>
                    </div>

                    <div class="item legenda">
                        <ul>
                            <li class="norte">Norte</li>
                            <li class="nordeste">Nordeste</li>
                            <li class="sudeste">Sudeste</li>
                            <li class="sul">Sul</li>
                            <li class="centro_oeste">Centro-Oeste</li>
                        </ul>
                    </div>

                </div>

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
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_norte->anos_iniciais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_norte->anos_iniciais['qtd'] * 100) / (int)$matriculas->regiao_norte->anos_iniciais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_nordeste->anos_iniciais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_nordeste->anos_iniciais['qtd'] * 100) / (int)$matriculas->regiao_nordeste->anos_iniciais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sudeste->anos_iniciais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sudeste->anos_iniciais['qtd'] * 100) / (int)$matriculas->regiao_sudeste->anos_iniciais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sul->anos_iniciais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sul->anos_iniciais['qtd'] * 100) / (int)$matriculas->regiao_sul->anos_iniciais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_centro_oeste->anos_iniciais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_centro_oeste->anos_iniciais['qtd'] * 100) / (int)$matriculas->regiao_centro_oeste->anos_iniciais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="item finais">
                                    <h4>Anos finais</h4>
                                    <ul>
                                        <li class="norte">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_norte->medio['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_norte->medio['qtd'] * 100) / (int)$matriculas->regiao_norte->medio['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_nordeste->medio['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_nordeste->medio['qtd'] * 100) / (int)$matriculas->regiao_nordeste->medio['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sudeste->medio['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sudeste->medio['qtd'] * 100) / (int)$matriculas->regiao_sudeste->medio['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sul->medio['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sul->medio['qtd'] * 100) / (int)$matriculas->regiao_sul->medio['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_centro_oeste->medio['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_centro_oeste->medio['qtd'] * 100) / (int)$matriculas->regiao_centro_oeste->medio['qtd'], 2) ?></span>%]</span>
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
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_norte->anos_finais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_norte->anos_finais['qtd'] * 100) / (int)$matriculas->regiao_norte->anos_finais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="nordeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_nordeste->anos_finais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_nordeste->anos_finais['qtd'] * 100) / (int)$matriculas->regiao_nordeste->anos_finais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sudeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sudeste->anos_finais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sudeste->anos_finais['qtd'] * 100) / (int)$matriculas->regiao_sudeste->anos_finais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="sul">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_sul->anos_finais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_sul->anos_finais['qtd'] * 100) / (int)$matriculas->regiao_sul->anos_finais['qtd'], 2) ?></span>%]</span>
                                        </li>
                                        <li class="centro_oeste">
                                            <span class="number counter"><?php echo number_format((int)$reprovacoes->regiao_centro_oeste->anos_finais['qtd'], 0, ',', '.'); ?></span>
                                            <span class="perc">[<span
                                                        class="value"><?php echo number_format(((int)$reprovacoes->regiao_centro_oeste->anos_finais['qtd'] * 100) / (int)$matriculas->regiao_centro_oeste->anos_finais['qtd'], 2) ?></span>%]</span>
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
