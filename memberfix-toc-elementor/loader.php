<?php
namespace TocElementor;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class for MF TOC Elementor
 */
class Toc_Elementor_Loader {

	/**
	 * @var Toc_Elementor_Loader
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	private $classes_aliases = [
		'TocElementor\Modules\PanelPostsControl\Module' => 'TocElementor\Modules\QueryControl\Module',
		'TocElementor\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'TocElementor\Modules\QueryControl\Controls\Group_Control_Posts',
		'TocElementor\Modules\PanelPostsControl\Controls\Query' => 'TocElementor\Modules\QueryControl\Controls\Query',
	];

	public $elements_data = [
		'sections' => [],
		'columns'  => [],
		'widgets'  => [],
	];

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return MFTOC_VER;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'mf-toc-elementor' ), '1.6.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'mf-toc-elementor' ), '1.6.0' );
	}

	/**
	 * @return \Elementor\Toc_Elementor_Loader
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Toc_Elementor_Loader
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	

	/**
	 * we loaded module manager + admin php from here
	 * @return [type] [description]
	 */
	private function _includes() {
		require MFTOC_PATH . 'includes/modules-manager.php';
		if ( is_admin() ) {
			if(!defined('MFTOC_CH')) {
				require MFTOC_PATH . 'includes/admin.php';

				// Load admin class for admin related content process
				new Admin();
			}
		}

	}

	/**
	 * Autoloader function for all classes files
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = MFTOC_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	/**
	 * Register all script that need for any specific widget on call basis.
	 * @return [type] [description]
	 */
	public function register_site_scripts() {

		$suffix   = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$settings = get_option( 'Toc_Elementor_api_settings' );

		wp_register_script( 'table-of-content', MFTOC_URL . 'assets/toc/js/table-of-content' . $suffix . '.js', ['jquery'], null, true );

	}



	/**
	 * Loading site related style from here.
	 * @return [type] [description]
	 */
	public function enqueue_site_styles() {

		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style( 'toc-elementor-site', MFTOC_URL . 'assets/css/toc-elementor-site' . $direction_suffix . '.css', [], MFTOC_VER );		
	}


	/**
	 * Loading site related script that needs all time such as uikit.
	 * @return [type] [description]
	 */
	public function enqueue_site_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		
		wp_enqueue_script( 'toc-elementor-site', MFTOC_URL . 'assets/js/toc-elementor-site' . $suffix . '.js', ['jquery', 'elementor-frontend'], MFTOC_VER );

		$script_config = [ 
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'toc-elementor-site' ),
			'data_table' => [
				'language'    => [
			        'lengthMenu' => sprintf(esc_html_x('Show %1s Entries', 'DataTable String', 'mf-toc-elementor'), '_MENU_' ),
			        'info'       => sprintf(esc_html_x('Showing %1s to %2s of %3s entries', 'DataTable String', 'mf-toc-elementor'), '_START_', '_END_', '_TOTAL_' ),
			        'search'     => esc_html_x('Search :', 'DataTable String', 'mf-toc-elementor'),
			        'paginate'   => [
			            'previous' => esc_html_x('Previous', 'DataTable String', 'mf-toc-elementor'),
			            'next'     => esc_html_x('Next', 'DataTable String', 'mf-toc-elementor'),
			        ],
				],
			],
			'contact_form' => [
				'sending_msg' => esc_html_x('Sending message please wait...', 'Contact Form String', 'mf-toc-elementor'),
				'captcha_nd' => esc_html_x('Invisible captcha not defined!', 'Contact Form String', 'mf-toc-elementor'),
				'captcha_nr' => esc_html_x('Could not get invisible captcha response!', 'Contact Form String', 'mf-toc-elementor'),

			],
			'elements_data' => $this->elements_data,
		];


		// localize for user login widget ajax login script
	    wp_localize_script( 'mftoc-uikit', 'Toc_Elementor_ajax_login_config', array( 
			'ajaxurl'        => admin_url( 'admin-ajax.php' ),
			'loadingmessage' => esc_html__('Sending user info, please wait...', 'mf-toc-elementor'),
	    ));

	    $script_config = apply_filters( 'Toc_Elementor/frontend/localize_settings', $script_config );

	    // TODO for editor script
		wp_localize_script( 'mftoc-uikit', 'ElementPackConfig', $script_config );

	}

	public function enqueue_editor_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'mftoc-uikit', MFTOC_URL . 'assets/js/toc-elementor-editor' . $suffix . '.js', ['backbone-marionette', 'elementor-common-modules', 'elementor-editor-modules'], MFTOC_VER, true );

		$locale_settings = [
			'i18n' => [],
			'urls' => [
				'modules' => MFTOC_MODULES_URL,
			],
		];

		$locale_settings = apply_filters( 'Toc_Elementor/editor/localize_settings', $locale_settings );

		wp_localize_script(
			'mftoc-uikit',
			'ElementPackConfig',
			$locale_settings
		);
	}

	/**
	 * Load editor editor related style from here
	 * @return [type] [description]
	 */
	public function enqueue_preview_styles() {
		$direction_suffix = is_rtl() ? '.rtl' : '';

		wp_enqueue_style('toc-elementor-preview', MFTOC_URL . 'assets/css/toc-elementor-preview' . $direction_suffix . '.css', '', MFTOC_VER );
	}


	public function enqueue_editor_styles() {
		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_enqueue_style('toc-elementor-editor', MFTOC_URL . 'assets/css/toc-elementor-editor' . $direction_suffix . '.css', '', MFTOC_VER );
	}


	/**
	 * initialize the category
	 * @return [type] [description]
	 */
	public function Toc_Elementor_init() {
		$this->_modules_manager = new Manager();

		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category( MFTOC_SLUG, [ 'title' => MFTOC_TITLE, 'icon'  => 'font' ], 1 );
		
		do_action( 'mf_toc_elementor/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'Toc_Elementor_init' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_site_scripts' ] );

		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_preview_styles' ] );
		//add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] ); //TODO

		add_action( 'elementor/frontend/after_register_styles', [ $this, 'enqueue_site_styles' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_site_scripts' ] );

		// TODO AJAX SEARCH
		// add_action('wp_ajax_Toc_Elementor_search', [ $this, 'Toc_Elementor_ajax_search' ] );
		// add_action('wp_ajax_nopriv_Toc_Elementor_search', [ $this, 'Toc_Elementor_ajax_search' ] );
		
		
	
	}

	/**
	 * Toc_Elementor_Loader constructor.
	 */
	private function __construct() {
		// Register class automatically
		spl_autoload_register( [ $this, 'autoload' ] );
		// Include some backend files
		$this->_includes();
		// Finally hooked up all things here
		$this->setup_hooks();
	}
}

if ( ! defined( 'MFTOC_TESTS' ) ) {
	// In tests we run the instance manually.
	Toc_Elementor_Loader::instance();
}

// handy fundtion for push data
function Toc_Elementor_config() {
	return Toc_Elementor_Loader::instance();
}