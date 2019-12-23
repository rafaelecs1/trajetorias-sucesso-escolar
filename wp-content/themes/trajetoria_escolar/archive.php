<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package trajetoria_escolar
 */

get_header();
?>
<div class="center-content">
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				$title = get_the_archive_title();
                $title = str_replace('Arquivo: ', '', $title);
                if(!empty($title)) {
                    echo '<h1 class="page-title">', $title, '</h1>';    
                }
				?>
			</header><!-- .page-header -->
            <section id="todos-os-cadernos"><div><a href="<?php echo content_url('uploads/2019/12/todos_materiais_23_12_2019.zip'); ?>" download="trajetoriaescolar.zip">Faça o download de todos os cadernos</a></div></section>
			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', get_post_type() );

			endwhile;

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_sidebar();
get_footer();
