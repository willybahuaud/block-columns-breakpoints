<?php

/**
 * Plugin name: Columns breakpoints
 * Description: Permet de régler finement els points de rupture des blocs de type colonnes
 * Author: Willy Bahuaud
 * Author URI: https://wabeo.fr
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require 'render-styles.php';

function w_register_columns_breakpoints_scripts() {
    wp_register_script(
        'columns-breakpoints',
        plugin_dir_url( __FILE__ ) . 'build/index.js',
        [ 'wp-blocks', 'wp-dom', 'wp-dom-ready', 'wp-edit-post' ],
        filemtime(  plugin_dir_path( __FILE__ ) . 'build/index.js' )
    );
    wp_enqueue_script( 'columns-breakpoints' );
}
add_action( 'enqueue_block_editor_assets', 'w_register_columns_breakpoints_scripts' );
