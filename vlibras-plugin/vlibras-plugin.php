<?php
/*
Plugin Name: VLibras - Unofficial
Plugin URI: https://vlibras.gov.br/
Description: Plugin não oficial para integrar o VLibras ao WordPress.
Version: 1.0
Author: Daniel Angelone
Author URI: https://github.com/danielangelone
License: GPL2
*/

// Evita acesso direto ao arquivo
if (!defined('ABSPATH')) {
    exit;
}

// Carrega o script do VLibras
function vlibras_enqueue_script() {
    wp_enqueue_script(
        'vlibras-widget',
        'https://vlibras.gov.br/app/vlibras-plugin.js',
        array(),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'vlibras_enqueue_script');

// Inicializa o widget do VLibras no footer
function iniciar_vlibras() {
    ?>
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new window.VLibras.Widget('https://vlibras.gov.br/app');
        });
    </script>
    <?php
}
add_action('wp_footer', 'iniciar_vlibras');