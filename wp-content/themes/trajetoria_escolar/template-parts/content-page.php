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

        <div class="faixa-video">
            <div class="center">
                <a class="bot1">
                    <h3 class="entry-header-home">Trajetórias de Sucesso Escolar</h3>
                    <p class="alignjustify">A distorção idade-série acontece quando um aluno está com dois ou mais anos de atraso escolar,
                        considerando a idade esperada para cada ano. No Brasil, cada criança deve ingressar no 1º ano do
                        Ensino Fundamental aos 6 anos e concluir o 9º ano aos 14. No Ensino Médio, o ingresso deve
                        acontecer aos 15 anos e a conclusão aos 17. A taxa de distorção idade-série é calculada pelo
                        Instituto Nacional de Estudos e Pesquisas Educacionais Anísio Teixeira (INEP) com base no Censo
                        Escolar. Segundo os dados da edição 2018 do Censo, de cada 100 alunos brasileiros matriculados
                        em escolas públicas municipais e estaduais, 22 estão em situação de distorção idade-série. São
                        quase 6,5 milhões de crianças e adolescentes em atraso escolar sobretudo devido à reprovações
                        sucessivas. A estratégia <b>Trajetórias de Sucesso Escolar</b> é uma iniciativa do UNICEF, com apoio do Instituto NET Claro Embratel e outros parceiros, para enfrentar a cultura de fracasso escolar que leva
                        a essas múltiplas reprovações e, muitas vezes, à exclusão escolar.
                    </p>
                </a>
                <a class="bot12 bot1">
                    <p>
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/6P3DmtgOjfk" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </p>
                </a>
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
                            <option value="2019" <?php if ((int)$_POST['select-year'] == 2019 OR (int)$_POST['select-year'] == 0) {
                                echo "selected";
                            } ?> >2018
                            </option>
                            <option value="2018" <?php if ((int)$_POST['select-year'] == 2018) {
                                echo "selected";
                            } ?>>2017
                            </option>
                        </select>
                    </label>
                </form>
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
