<?php
/**
 * Plugin name: UNICEF - Trajetória Escolar
 * Description: Permite a importação e exibição de informações sobre distorção idade-série de crianças e adolescentes no Brasil.
 * Author: André Keher
 * Author URI: http://github.com/andrekeher
 */

require 'vendor/autoload.php';
$tE = new Unicef\TrajetoriaEscolar\BackEnd();
$tE = new Unicef\TrajetoriaEscolar\FrontEnd();
