<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package trajetoria_escolar
 */

?>
	</div><!-- #content -->
    <?php
    if (is_front_page()) {
    ?>
    <section id="faixa-bottom">
        <div class="center">
            <p>Tem uma experiência interessante em seu município ou estado para compartilhar? </p>
            <a href="<?php echo site_url('/fale-conosco/'); ?>">Fale conosco</a>
        </div>
    </section>
    <?php
    }
    ?>
	<footer id="colophon" class="site-footer">
		<div class="center">
            <ul class="realizacao">
                <?php
                $realizacao = array(
                    'unicef' => array('url' => 'https://www.unicef.org/brazil/pt/', 'tipo' => 'Iniciativa'),
                    'samsung' => array('url' => 'https://www.samsung.com/br/', 'tipo' => 'Parcerias Estratégicas'),
                    'netclaroembratel' => array('url' => 'https://www.institutonetclaroembratel.org.br/', 'tipo' => ' '),
                    'aprendiz' => array('url' => 'http://www.cidadeescolaaprendiz.org.br/', 'tipo' => 'Parceiro técnico'),
                    );
                foreach($realizacao as $k => $v)
                {
                    echo '<li><span>', $v['tipo'], '</span><a href="', $v['url'], '" class="realizador ', $k, '" target="_blank"><img src="', get_template_directory_uri(), '/img/logo/', $k, '.png" alt="', strtoupper($k), '" title="', strtoupper($k), '"/></a></li>';
                }
                ?>
            </ul>
            <ul class="share">
                <?php
                global $arShare;
                foreach($arShare as $k =>$v)
                {
                    echo '<li><a class="', $k,'" href="', $v, '" title="Compartilhe no ', ucfirst($k), '"><img src="', get_template_directory_uri(), '/img/', $k, '-share-footer.png" alt="', strtoupper($k), '"/></a></li>';
                }
                ?>
            </ul>
        </div>
	</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>