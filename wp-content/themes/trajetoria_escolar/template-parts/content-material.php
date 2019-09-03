<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <section class="left">
        <header class="entry-header">
    		<?php the_title('<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
    	</header>
        <?php if (is_archive()) : ?>
            <p class="entry-excerpt"><?php echo get_the_excerpt(); ?></p>
            <a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark" class="button">Download</a>
        <?php else: ?>
            <div class="entry-content"><?php the_content(); ?></div>
        <?php endif; ?>
    </section>
    <section class="right">
        <?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
    </section>
</article><!-- #post-<?php the_ID(); ?> -->