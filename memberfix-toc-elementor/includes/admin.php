<?php
namespace TocElementor;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin {

	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );

	}

	public function enqueue_styles() {

		$suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style( 'mf-toc-elementor-admin', MFTOC_ASSETS_URL . 'css/admin' . $suffix . '.css', MFTOC_VER );

		//wp_enqueue_style( 'mf-toc-elementor-admin' );
	}






}