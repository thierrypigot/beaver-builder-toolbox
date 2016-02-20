<?php
/**
 * Plugin Name: Beaver Builder - Toolbox
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Add cool things to Beaver Builder.
 * Version: 1.2
 * Author: Thierry Pigot
 * Author URI: http://www.thierry-pigot.fr
 * Source : https://github.com/brentjett/bb-experiments
 */

define('TP_BB_TOOLBOX_DIR', plugin_dir_path(__FILE__));
define('TP_BB_TOOLBOX_URL', plugins_url('/', __FILE__));

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', 'tp_bb_load_textdomain_toolbox' );
function tp_bb_load_textdomain_toolbox()
{
    load_plugin_textdomain( 'bb-toolbox', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_filter( 'fl_builder_upgrade_url', 'tp_bb_toolbox_upgrade_link' );
function tp_bb_toolbox_upgrade_link() { 
    return 'https://www.wpbeaverbuilder.com/?fla=315'; 
}

/*
 * Load Toolbox modules
 */
add_action('init', 'tp_bb_load_module_toolbox');
function tp_bb_load_module_toolbox() {
    if( class_exists('FLBuilder') ) {
        require_once 'bb-page-settings/bb-page-settings.php';
    }
}
