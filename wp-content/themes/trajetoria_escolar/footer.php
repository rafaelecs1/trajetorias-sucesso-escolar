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

    <div class="center" style="display: flex; flex-wrap: wrap">

        <div class="item" style="max-width: 400px; margin-right:50px;">
            <ul class="realizacao">
                <?php
                $realizacao = array(
                    'aprendiz' => array('url' => 'https://www.cidadeescolaaprendiz.org.br', 'tipo' => 'Parceiro técnico'),
                    'cedac' => array('url' => 'http://www.comunidadeeducativa.org.br', 'tipo' => ''),
                    'avisa_la' => array('url' => 'https://avisala.org.br', 'tipo' => ''),
                    'cenpec' => array('url' => 'https://www.cenpec.org.br', 'tipo' => ''),

                );
                foreach ($realizacao as $k => $v) {
                    echo '<li><strong><span>', $v['tipo'], '</span></strong><a href="', $v['url'], '" class="realizador ', $k, '" target="_blank"><img src="', get_template_directory_uri(), '/img/logo/', $k, '.png" alt="', strtoupper($k), '" title="', strtoupper($k), '"/></a></li>';
                }
                ?>
            </ul>
        </div>
        
        <div class="item" style="max-width: 400px;">
            <ul class="realizacao">
                <?php
                $realizacao = array(
                    'netclaroembratel' => array('url' => 'https://www.institutonetclaroembratel.org.br', 'tipo' => 'Parceiro Estratégico'),
                    'itau' => array('url' => 'https://www.itausocial.org.br', 'tipo' => '')

                );
                foreach ($realizacao as $k => $v) {
                    echo '<li><strong><span>', $v['tipo'], '</span></strong><a href="', $v['url'], '" class="realizador ', $k, '" target="_blank"><img src="', get_template_directory_uri(), '/img/logo/', $k, '.png" alt="', strtoupper($k), '" title="', strtoupper($k), '"/></a></li>';
                }
                ?>
            </ul>
        </div>

        <div class="item" style="max-width: 300px;">
            <ul class="realizacao">
                <?php
                $realizacao = array(
                    'unicef' => array('url' => 'https://www.unicef.org/brazil/pt', 'tipo' => 'Iniciativa'),
                );
                foreach ($realizacao as $k => $v) {
                    echo '<li><strong><span>', $v['tipo'], '</span></strong><a href="', $v['url'], '" class="realizador ', $k, '" target="_blank"><img src="', get_template_directory_uri(), '/img/logo/', $k, '.png" alt="', strtoupper($k), '" title="', strtoupper($k), '"/></a></li>';
                }
                ?>
            </ul>
        </div>
        
        
        
    </div>

    <div class="center">
        <ul class="share">
            <?php
            global $arShare;
            foreach ($arShare as $k => $v) {
                echo '<li><a class="', $k, '" href="', $v, '" title="Compartilhe no ', ucfirst($k), '"><img src="', get_template_directory_uri(), '/img/', $k, '-share-footer.png" alt="', strtoupper($k), '"/></a></li>';
            }
            ?>
        </ul>
    </div>
    
</footer><!-- #colophon -->
</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>