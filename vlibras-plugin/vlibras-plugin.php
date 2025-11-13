<?php
/**
 * Plugin Name: VLibras - Unofficial (Delayed Init)
 * Plugin URI:  https://github.com/danielangelone/vlibras
 * Description: Plugin não oficial para integrar o VLibras ao WordPress com inicialização atrasada para evitar erros de carregamento.
 * Version:     1.2
 * Author:      Daniel Angelone
 * Author URI:  https://github.com/danielangelone
 * License:     GPL2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enfileira apenas o script do VLibras (sem inicializá-lo ainda)
 */
function vlibras_delayed_enqueue_script() {
    if ( is_admin() ) {
        return;
    }

    wp_enqueue_script(
        'vlibras-widget-core',
        'https://vlibras.gov.br/app/vlibras-plugin.js',
        array(),
        null,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'vlibras_delayed_enqueue_script' );

/**
 * Injeta o HTML do widget + script de inicialização com delay
 */
function vlibras_delayed_init_script() {
    if ( is_admin() ) {
        return;
    }

    echo '
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <script>
    (function() {
        function initVLibras() {
            if (typeof window.VLibras !== "undefined" && typeof window.VLibras.Widget === "function") {
                try {
                    new window.VLibras.Widget("https://vlibras.gov.br/app");
                    console.log("[VLibras] Inicializado.");
                } catch (e) {
                    console.error("[VLibras] Erro ao inicializar:", e);
                }
            } else {
                console.warn("[VLibras] window.VLibras não está disponível. Tentando novamente em 1s...");
                setTimeout(initVLibras, 1000); // retry
            }
        }

        // Aguarda window.onload (todos recursos carregados) + 4 segundos extras
        window.addEventListener("load", function() {
            setTimeout(initVLibras, 4000);
        });
    })();
    </script>
    ';
}
add_action( 'wp_footer', 'vlibras_delayed_init_script' );
