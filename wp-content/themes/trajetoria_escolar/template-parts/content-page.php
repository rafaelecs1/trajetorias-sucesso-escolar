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
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <?php if (is_front_page()) {?>
            <div class="content-select-year">
                <form name="form-year" id="form-year" action="/" method="post">
                    <label>Ano referência
                        <select class="select-year" id="select-year" name="select-year">
                            <option>Selecione</option>
                            <option value="2019" <?php if( (int)$_POST['select-year'] == 2019 OR (int)$_POST['select-year'] == 0) { echo "selected"; }?> >2018</option>
                            <option value="2018" <?php if( (int)$_POST['select-year'] == 2018) { echo "selected"; }?>>2017</option>
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

		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'trajetoria_escolar' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'trajetoria_escolar' ),
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
