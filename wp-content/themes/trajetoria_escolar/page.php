<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package trajetoria_escolar
 */

get_header();

global $post;
$centerContent = false;
if (!has_shortcode($post->post_content, 'mapa_distorcao') && !has_shortcode($post->post_content, 'painel_distorcao') && !has_shortcode($post->post_content, 'painel_distorcao_brasil')) {
    $centerContent = true;
}
if($centerContent) {
    echo '<div class="center-content">';
} 
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part('template-parts/content', 'page');
			// If comments are open or we have at least one comment, load up the comment template.
			if (comments_open() || get_comments_number()) :
				comments_template();
			endif;
		endwhile; // End of the loop.
		?>
		</main><!-- #main -->
	</div><!-- #primary -->
<?php
if($centerContent) {
    echo '</div>';
}
get_sidebar();
get_footer();
