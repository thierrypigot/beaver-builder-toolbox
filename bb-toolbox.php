<?php
/**
 * Plugin Name: Beaver Builder - Toolbox
 * Plugin URI: http://www.thierry-pigot.fr
 * Description: Add cool things to Beaver Builder.
 * Version: 1.3
 * Author: Thierry Pigot
 * Author URI: http://www.thierry-pigot.fr
 * Source : https://github.com/brentjett/bb-experiments
 */


//	Updater Class
add_action( 'init', 'github_plugin_updater_test_init' );
if( !function_exists( 'github_plugin_updater_test_init' ) )
{
	function github_plugin_updater_test_init()
	{
		include_once 'updater.php';
		define( 'WP_GITHUB_FORCE_UPDATE', true );
	}
}


add_action( 'init', 'bb_toolbox_updater' );
function bb_toolbox_updater()
{
	if ( is_admin() )
	{
		// note the use of is_admin() to double check that this is happening in the admin
		$login = 'thierrypigot/beaver-builder-toolbox';

		$config = array(
			'slug'					=> plugin_basename( __FILE__ ),
			'proper_folder_name'	=> 'beaver-builder-toolbox',
			'api_url'				=> 'https://api.github.com/repos/' . $login,
			'raw_url'				=> 'https://raw.github.com/' . $login .'/master',
			'github_url'			=> 'https://github.com/'. $login,
			'zip_url'				=> 'https://github.com/'. $login .'/archive/master.zip',
			'sslverify'				=> true,
			'requires'				=> '4.2',
			'tested'				=> '4.5.2',
			'readme'				=> 'README.md',
			'access_token'			=> '',
		);
		new WP_GitHub_Updater( $config );
	}
}


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
