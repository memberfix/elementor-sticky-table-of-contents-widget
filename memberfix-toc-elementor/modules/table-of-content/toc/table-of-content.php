<?php
namespace TocElementor\Modules\TableOfContent\Toc;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Table_Of_Content extends Widget_Base {
	public function get_name() {
		return 'mftoc-table-of-content';
	}

	public function get_title() {
		return MFTOC . esc_html__( 'Table of Contents', 'mf-toc-elementor' );
	}

	public function get_icon() {
		return 'fa fa-th-list';
	}

	public function get_categories() {
		return [ 'toc-elementor' ];
	}

	public function get_keywords() {
		return [ 'table', 'content', 'index' ];
	}

	public function get_script_depends() {
		return [ 'jquery-ui-widget', 'table-of-content' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content_table_of_content',
			[
				'label' => esc_html__( 'Table of Contents', 'mf-toc-elementor' ),
			]
		);

		$this->add_control(
			'fixed_position',
			[
				'label'    => __( 'Position', 'mf-toc-elementor' ),
				'type'     => Controls_Manager::SELECT,
				'default'  => 'top-left',
				'options'  => [
					'top-left'     => esc_html__( 'Top-Left', 'mf-toc-elementor' ),
					'top-right'    => esc_html__( 'Top-Right', 'mf-toc-elementor' ),
					'bottom-left'  => esc_html__( 'Bottom-Left', 'mf-toc-elementor' ),
					'bottom-right' => esc_html__( 'Bottom-Right', 'mf-toc-elementor' ),
				]
			]
		);

		$this->add_control(
			'selectors',
			[
				'label'    => __( 'Index Tags', 'mf-toc-elementor' ),
				'description'    => __( 'If you want to ignore a specific heading, just add <b>ignored-heading</b> class in CSS Classes input field under Advanced tab of the heading.', 'mf-toc-elementor' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => ['h2', 'h3'],
				'options'  => Toc_Elementor_heading_size(),
			]
		);

		$this->add_responsive_control(
			'fixed_index_horizontal_offset',
			[
				'label'     => __( 'Horizontal Offset', 'mf-toc-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'separator' => 'before',
				'default'   => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -400,
						'step' => 2,
						'max'  => 400,
					],
				]
			]
		);

		$this->add_responsive_control(
			'fixed_index_vertical_offset',
			[
				'label'   => __( 'Vertical Offset', 'mf-toc-elementor' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 0,
				],
				'tablet_default' => [
					'size' => 0,
				],
				'mobile_default' => [
					'size' => 0,
				],
				'range' => [
					'px' => [
						'min'  => -400,
						'step' => 2,
						'max'  => 400,
					],
				],
				'selectors' => [
					'(desktop){{WRAPPER}} .mftoc-card-secondary' => 'transform: translate({{fixed_index_horizontal_offset.SIZE}}px, {{SIZE}}px);',
					'(tablet){{WRAPPER}} .mftoc-card-secondary'  => 'transform: translate({{fixed_index_horizontal_offset_tablet.SIZE}}px, {{SIZE}}px);',
					'(mobile){{WRAPPER}} .mftoc-card-secondary'  => 'transform: translate({{fixed_index_horizontal_offset_mobile.SIZE}}px, {{SIZE}}px);',
				]
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label'      => esc_html__( 'Width', 'mf-toc-elementor' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'vw' ],
				'range'      => [
					'px' => [
						'min' => 240,
						'max' => 1200,
					],
					'vw' => [
						'min' => 10,
						'max' => 100,
					]
				],
				'selectors' => [
					'#mftoc-toc-{{ID}} .mftoc-offcanvas-bar' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .mftoc-card-secondary'    => 'width: {{SIZE}}{{UNIT}};',
				]
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'section_additional_table_of_content',
			[
				'label' => esc_html__( 'Additional Settings', 'mf-toc-elementor' ),
			]
		);

		$this->add_control(
			'context',
			[
				'label'       => __( 'Index Area (any class/id selector)', 'mf-toc-elementor' ),
				'description'       => __( 'All headings inside this class/id selector will be added to TOC.', 'mf-toc-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '.elementor',
				'placeholder' => '.elementor / #container',
			]
		);

		$this->add_control(
			'auto_collapse',
			[
				'label'     => esc_html__( 'Auto Collapse Sub Index', 'mf-toc-elementor' ),
				'separator' => 'before',
				'type'      => Controls_Manager::SWITCHER,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_offcanvas',
			[
				'label' => esc_html__( 'Options', 'mf-toc-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'index_background',
			[
				'label'     => __( 'Background', 'mf-toc-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#mftoc-toc-{{ID}} .mftoc-offcanvas-bar' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .mftoc-card-secondary'    => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => __( 'Title Color', 'mf-toc-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#mftoc-toc-{{ID}} .mftoc-offcanvas-bar .mftoc-nav li a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mftoc-card-secondary .mftoc-nav li a'    => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'title_active_color',
			[
				'label'     => __( 'Active Title Color', 'mf-toc-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'#mftoc-toc-{{ID}} .mftoc-offcanvas-bar .mftoc-nav > li.mftoc-active > a' => 'color: {{VALUE}};',
					'{{WRAPPER}} .mftoc-card-secondary .mftoc-nav > li.mftoc-active > a'    => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'separator_line_color',
			[
				'label'     => __( 'Separator Line Color', 'mf-toc-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.mftoc-table-of-content .mftoc-nav li a' => 'border-bottom-color: {{VALUE}};', '.mftoc-table-of-content .mftoc-nav li.mftoc-active > a' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'index_typography',
				'selector' => '#mftoc-toc-{{ID}} .mftoc-offcanvas-bar .mftoc-nav > li > a, {{WRAPPER}} .mftoc-card-secondary .mftoc-nav > li > a',
			]
		);

		$this->end_controls_section();
	}	

	protected function render() {
		$settings = $this->get_settings();
		$this->mf_toc_elem();
	
	}

	private function mf_toc_elem() {
		$settings    = $this->get_settings();

		?>
		<div class="table-of-content-layout-fixed mftoc-position-<?php echo esc_attr( $settings['fixed_position'] ); ?>">
			<div class="mftoc-card mftoc-card-secondary mftoc-card-body">
				<?php $this->table_of_content(); ?>
			</div>
		</div>
		<?php
	}


	private function table_of_content() {
		$settings    = $this->get_settings();

		$this->add_render_attribute(
			[
				'table-of-content' => [
					'data-settings' => [
						wp_json_encode(array_filter([
							"context"        => $settings["context"],
							"selectors"      => implode(",", $settings["selectors"]),
							"ignoreSelector" => ".ignored-heading [class*='-heading-title']",
							"showAndHide"    => $settings["auto_collapse"] ? true : false,						
				        ]))
					]
				]
			]
		);

		?>
		<div class="mftoc-table-of-content" <?php echo $this->get_render_attribute_string( 'table-of-content' ); ?>></div>
		<?php
	}
}
