<?php
/**
 * Plugin Name: Elementor Table of Contents Widget
 * Plugin URI: https://memberfix.rocks/elementor-toc/
 * Description: Table of Contents widget for Elementor
 * Version: 1.0.0
 * Author: MemberFix
 * Author URI: https://memberfix.rocks/
 * Text Domain: memberfix.rocks
 * Domain Path: /languages
 * License: GPL3
 * Elementor requires at least: 2.2.0
 * Elementor tested up to: 2.6.7
 */

// Constants
define( 'MFTOC_VER', '1.0.0' );
define( 'MFTOC__FILE__', __FILE__ );
define( 'MFTOC_PNAME', basename( dirname(MFTOC__FILE__)) );
define( 'MFTOC_PBNAME', plugin_basename(MFTOC__FILE__) );
define( 'MFTOC_PATH', plugin_dir_path( MFTOC__FILE__ ) );
define( 'MFTOC_MODULES_PATH', MFTOC_PATH . 'modules/' );
define( 'MFTOC_URL', plugins_url( '/', MFTOC__FILE__ ) );
define( 'MFTOC_ASSETS_URL', MFTOC_URL . 'assets/' );
define( 'MFTOC_ASSETS_PATH', MFTOC_PATH . 'assets/' );
define( 'MFTOC_MODULES_URL', MFTOC_URL . 'modules/' );

if (!defined('MFTOC')) { define( 'MFTOC', '' ); } //The prefix
if (!defined('MFTOC_CP')) { define( 'MFTOC_CP', '<span class="mftoc-widget-badge"></span>' ); } // if you have any custom style
if (!defined('MFTOC_SLUG')) { define( 'MFTOC_SLUG', 'toc-elementor' ); } // MF 
if (!defined('MFTOC_TITLE')) { define( 'MFTOC_TITLE', 'MemberFix Widgets' ); } // Section name


// Helpers and Utils
require(dirname(__FILE__).'/includes/helper.php');
require(dirname(__FILE__).'/includes/utils.php');

/**
 * Plugin load here correctly
 * Also loaded the language file from here
 */
function mf_toc_elementor_load_plugin() {
    

	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'mf_toc_elementor_fail_load' );
		return;
	}


	// MF TOC Elementor widget and assets loader
    require( MFTOC_PATH . 'loader.php' );
}
add_action( 'plugins_loaded', 'mf_toc_elementor_load_plugin' );


/**
 * Check Elementor installed and activated correctly
 */
function mf_toc_elementor_fail_load() {
	$screen = get_current_screen();
	if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
		return;
	}

	$plugin = 'elementor/elementor.php';

	if ( _is_elementor_installed() ) {
		if ( ! current_user_can( 'activate_plugins' ) ) { return; }
		$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		$admin_message = '<p>' . esc_html__( 'Ops! MF TOC Elementor not working because you need to activate the Elementor plugin first.', 'mf-toc-elementor' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'mf-toc-elementor' ) ) . '</p>';
	} else {
		if ( ! current_user_can( 'install_plugins' ) ) { return; }
		$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );
		$admin_message = '<p>' . esc_html__( 'Ops! MF TOC Elementor not working because you need to install the Elementor plugin', 'mf-toc-elementor' ) . '</p>';
		$admin_message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'mf-toc-elementor' ) ) . '</p>';
	}

	echo '<div class="error">' . $admin_message . '</div>';
}

/**
 * Check the elementor installed or not
 */
if ( ! function_exists( '_is_elementor_installed' ) ) {

    function _is_elementor_installed() {
        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        return isset( $installed_plugins[ $file_path ] );
    }
}