<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package trajetoria_escolar
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if (is_front_page()) : ?>

        <div class="faixa_frases">

            <div class="center">

                <h2 class="title">
                    O enfrentamento da cultura do fracasso escolar é urgente porque...
                </h2>

                <!-- <div class="owl-carousel"> -->
                <div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                Cultura do fracasso escolar afeta milhões de estudantes e desigualdade se agrava na pandemia, alertam UNICEF e Instituto Claro <br/>

                                <span> Estudo mostra que reprovação, abandono escolar e distorção idade-série já impactavam os estudantes mais vulneráveis, antes da pandemia. Com a chegada da Covid-19, os desafios são ainda maiores.</span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + Mais de 912 mil crianças e adolescentes deixaram a escola apenas em 2018.

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + Os estudantes negros e pardos deixam a escola quase duas vezes mais que os brancos e representam 48,4% do total de reprovados.

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + Os indígenas têm as maiores taxas de distorção idade-série e abandono. Mais de 41% dos estudantes estão em atraso escolar e mais de 15 mil deixaram a escola em 2018.

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + São 383 mil crianças e adolescentes com deficiência em distorção idade-série, o equivalente a quase 49% das matrículas.

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + Norte e Nordeste são as regiões com as maiores taxas de distorção idade-série e abandono escolar do país. Essas regiões concentram 54,8% das crianças com dois ou mais anos de atraso

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="itens">

                        <div class="text item">

                            <div>

                                <div class="frases">

                                    + O Ensino Médio perdeu 459 mil estudantes apenas em 2018, a maioria abandona a escola no primeiro ano. É também a etapa de ensino que mais reprova no país.

                                </div>

                            </div>

                        </div>

                    </div> -->

                </div>

                <!-- <div class="navigation-owl">
                    <label data="0">&emsp;</label>
                    <label data="1">&emsp;</label>
                    <label data="2">&emsp;</label>
                    <label data="3">&emsp;</label>
                    <label data="4">&emsp;</label>
                    <label data="5">&emsp;</label>
                    <label data="6">&emsp;</label>
                </div> -->

                <div class="font">
                    <!-- <span><a href="LINK" target="_blank" style="color: white; size: 13px;">Saiba mais</a> </span></br> -->
                    <span>Estudo desenvolvido em parceria com CENPEC Educação</span>
                </div>

            </div>

        </div>

        <div class="faixa_video">

            <div class="center content_video">

                    <div class="item text">
                        <h3 class="entry-header-home">Sobre a estratégia</h3>

                        <p class="alignjustify">A estratégia <b>Trajetórias de Sucesso Escolar</b> é uma iniciativa do UNICEF, do
                            Instituto Claro e outros parceiros para o enfrentamento da cultura de fracasso escolar no
                            Brasil. O site disponibiliza indicadores de fluxo escolar nacionais, estaduais, municipais e por
                            escola retirados do Censo Escolar. O objetivo é facilitar um diagnóstico amplo sobre a distorção
                            idade-série no país – quando um estudante está com dois ou mais anos de  atraso escolar – e
                            oferecer um conjunto de recomendações para o desenvolvimento de políticas educacionais que
                            promovam o acesso, permanência e aprendizagem desses estudantes. Além das taxas de distorção e
                            índices de abandono e reprovação, o site disponibiliza recortes por gênero, raça e localidade
                            que mostram as relações entre o atraso escolar e as desigualdades brasileiras. 
                        </p>

                    </div>

                    <div class="item video">

                        <iframe src="https://www.youtube.com/embed/7MuJKuRBIxY"
                                frameborder="0"
                                color="white"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>

                        </iframe>

                    </div>

            </div>

        </div>

    <?php endif; ?>


    <header class="entry-header">
        
        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

        <?php if (is_front_page()) { ?>

            <div class="content-select-year">
                <form name="form-year" id="form-year" action="/" method="post">
                    <label>Ano referência
                        <select class="select-year" id="select-year" name="select-year">
                            <option>Selecione</option>
                            <option value="2020" <?php if ((int)$_POST['select-year'] == 2020 OR (int)$_POST['select-year'] == 0) {
                                echo "selected";
                            } ?> >2019
                            </option>
                            <option value="2019" <?php if ((int)$_POST['select-year'] == 2019) {
                                echo "selected";
                            } ?> >2018
                            </option>
                            <option value="2018" <?php if ((int)$_POST['select-year'] == 2018) {
                                echo "selected";
                            } ?>>2017
                            </option>
                            <option value="2017" <?php if ((int)$_POST['select-year'] == 2017) {
                                echo "selected";
                            } ?>>2016
                            </option>
                            <option value="2016" <?php if ((int)$_POST['select-year'] == 2016) {
                                echo "selected";
                            } ?>>2015
                            </option>
                        </select>
                    </label>
                </form>
            </div>

            <div div="regions_selector">
                <p class="entry-header-description" style="margin-top: 25px;">
                    <a href="#" id="regiao_geografica" class="type_region active">Regiões geográficas</a> | <a href="#" id="regiao_territorial" class="type_region">Territórios</a>
                </p>
            </div>

        <?php } ?>
        
    </header><!-- .entry-header -->

    <?php trajetoria_escolar_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        the_content();

        wp_link_pages(array(
            'before' => '<div class="page-links">' . esc_html__('Pages:', 'trajetoria_escolar'),
            'after' => '</div>',
        ));
        ?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link()) : ?>
        <footer class="entry-footer">
            <?php
            edit_post_link(
                sprintf(
                    wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                        __('Edit <span class="screen-reader-text">%s</span>', 'trajetoria_escolar'),
                        array(
                            'span' => array(
                                'class' => array(),
                            ),
                        )
                    ),
                    get_the_title()
                ),
                '<span class="edit-link">',
                '</span>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
    
</article><!-- #post-<?php the_ID(); ?> -->