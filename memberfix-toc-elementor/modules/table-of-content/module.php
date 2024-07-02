<?php
namespace TocElementor\Modules\TableOfContent;

use TocElementor\Base\Toc_Elementor_Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Toc_Elementor_Module_Base {

	public function get_name() {
		return 'table-of-content';
	}

	public function get_widgets() {

		$widgets = [
			'Table_Of_Content',
		];

		return $widgets;
	}
}
