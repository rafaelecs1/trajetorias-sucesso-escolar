<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package trajetoria_escolar
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo site_url('favicon/apple-touch-icon.png'); ?>" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo site_url('favicon/favicon-32x32.png'); ?>" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo site_url('favicon/favicon-16x16.png'); ?>" />
    <link rel="manifest" href="<?php echo site_url('favicon/site.webmanifest'); ?>" />
    <link rel="mask-icon" href="<?php echo site_url('favicon/safari-pinned-tab.svg'); ?>" color="#5bbad5" />
    <meta name="msapplication-TileColor" content="#da532c" />
    <meta name="theme-color" content="#ffffff" />
	<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-124111918-1', 'auto');
    ga('send', 'pageview');
    </script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'trajetoria_escolar' ); ?></a>
	<header id="masthead" class="site-header">
		<div class="center">
            <div class="content-branding">
                <div class="site-branding">
                    <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><img src="<?php echo get_template_directory_uri(); ?>/img/logo.png" /><span><?php bloginfo('name'); ?></span></a></h1>
        		</div><!-- .site-branding -->
                <nav id="site-navigation" class="main-navigation">
        			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Primary Menu', 'trajetoria_escolar' ); ?></button>
        			<?php
        			wp_nav_menu( array(
        				'theme_location' => 'menu-1',
        				'menu_id'        => 'primary-menu',
        			));
        			?>
        		</nav><!-- #site-navigation -->
            </div>
            <ul class="share">
                <?php
                $detect = new Mobile_Detect();
                $siteUrl = home_url('/');
                $siteTitle = get_bloginfo('blogname');
                global $arShare;
                $arShare = array(
                    'whatsapp' => (($detect->isMobile() || $detect->isTablet()) ? 'https://wa.me/?text=' : 'https://web.whatsapp.com/send?text=') . urlencode($siteUrl),
                    'twitter' => 'http://twitter.com/intent/tweet?status=' . $siteUrl . '+-+' . $siteTitle,
                    'facebook' => 'http://www.facebook.com/share.php?u=' . $siteUrl . '&title=' . $siteTitle,
                    );
                foreach($arShare as $k =>$v)
                {
                    echo '<li><a class="', $k,'" href="', $v, '" title="Compartilhe no ', ucfirst($k), '"><img src="', get_template_directory_uri(), '/img/', $k, '-share.png" /></a></li>';
                }
                ?>
            </ul>
        </div>
    </header><!-- #masthead -->
    <?php
    $faixa = null;
    if(is_front_page()) {
        $faixa = '<a class="bot1" href="' . site_url('por-que-e-fundamental-promover-trajetorias-de-sucesso-escolar-para-criancas-e-adolescentes') . '" rel="bookmark" class="button"><p>Por que é fundamental promover trajetórias de sucesso escolar para crianças e adolescentes?</p></a> <a class="bot2" href="' . site_url('download-dos-materiais') . '" rel="bookmark" class="button"><p>Recomendações para a promoção de trajetórias de sucesso escolar para crianças e adolescentes</p></a>';
    }
    if(strpos($_SERVER['REQUEST_URI'], '/painel/') !== false) {
        $faixa = '<a href="#" class="button" id="voltar">Voltar para o ' . ((strpos($_SERVER['REQUEST_URI'], '/municipio/') !== false || strpos($_SERVER['REQUEST_URI'], '/estado/') !== false) ? 'mapa' : 'município') . '</a>';
    }
    if(!empty($faixa)) {
        echo '<div class="faixa"><div class="center">', $faixa, '</div></div>';
    }
    ?>
	<div id="content" class="site-content <?php echo ((!is_home() && !is_front_page()) ? 'center' : 'home'); ?>">