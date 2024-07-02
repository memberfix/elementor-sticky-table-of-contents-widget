<?php
use TocElementor\Toc_Elementor_Loader;


/**
 * Make sure elementor plugin installed or not
 * @return error message
 */
function bdthemes_elementor_not_found() {
    $class = 'notice notice-error';
    $message = __( 'Ops! Elementor Plugin Not Found! Make sure you installed and Activated correctly.', 'mf-toc-elementor' );

    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
}

function Toc_Elementor_get_menu() {
    $menus = wp_get_nav_menus();
    $items = ['0' => esc_html__( 'Select Menu', 'mf-toc-elementor' ) ];
    foreach ( $menus as $menu ) {
        $items[ $menu->slug ] = $menu->name;
    }

    return $items;
}

/**
 * default get_option() default value check
 * @param string $option settings field name
 * @param string $section the section name this field belongs to
 * @param string $default default text if it's not found
 * @return mixed
 */
function Toc_Elementor_option( $option, $section, $default = '' ) {

    $options = get_option( $section );

    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }

    return $default;
}



// BDT Transition
function Toc_Elementor_transition_options() {
    $transition_options = [
        ''                    => esc_html__('None', 'mf-toc-elementor'),
        'fade'                => esc_html__('Fade', 'mf-toc-elementor'),
        'scale-up'            => esc_html__('Scale Up', 'mf-toc-elementor'),
        'scale-down'          => esc_html__('Scale Down', 'mf-toc-elementor'),
        'slide-top'           => esc_html__('Slide Top', 'mf-toc-elementor'),
        'slide-bottom'        => esc_html__('Slide Bottom', 'mf-toc-elementor'),
        'slide-left'          => esc_html__('Slide Left', 'mf-toc-elementor'),
        'slide-right'         => esc_html__('Slide Right', 'mf-toc-elementor'),
        'slide-top-small'     => esc_html__('Slide Top Small', 'mf-toc-elementor'),
        'slide-bottom-small'  => esc_html__('Slide Bottom Small', 'mf-toc-elementor'),
        'slide-left-small'    => esc_html__('Slide Left Small', 'mf-toc-elementor'),
        'slide-right-small'   => esc_html__('Slide Right Small', 'mf-toc-elementor'),
        'slide-top-medium'    => esc_html__('Slide Top Medium', 'mf-toc-elementor'),
        'slide-bottom-medium' => esc_html__('Slide Bottom Medium', 'mf-toc-elementor'),
        'slide-left-medium'   => esc_html__('Slide Left Medium', 'mf-toc-elementor'),
        'slide-right-medium'  => esc_html__('Slide Right Medium', 'mf-toc-elementor'),
    ];

    return $transition_options;
}


// BDT Position
function Toc_Elementor_position() {
    $position_options = [
        ''              => esc_html__('Default', 'mf-toc-elementor'),
        'top-left'      => esc_html__('Top Left', 'mf-toc-elementor') ,
        'top-center'    => esc_html__('Top Center', 'mf-toc-elementor') ,
        'top-right'     => esc_html__('Top Right', 'mf-toc-elementor') ,
        'center'        => esc_html__('Center', 'mf-toc-elementor') ,
        'center-left'   => esc_html__('Center Left', 'mf-toc-elementor') ,
        'center-right'  => esc_html__('Center Right', 'mf-toc-elementor') ,
        'bottom-left'   => esc_html__('Bottom Left', 'mf-toc-elementor') ,
        'bottom-center' => esc_html__('Bottom Center', 'mf-toc-elementor') ,
        'bottom-right'  => esc_html__('Bottom Right', 'mf-toc-elementor') ,
    ];

    return $position_options;
}


// BDT Drop Position
function Toc_Elementor_drop_position() {
    $drop_position_options = [
        'bottom-left'    => esc_html__('Bottom Left', 'mf-toc-elementor'),
        'bottom-center'  => esc_html__('Bottom Center', 'mf-toc-elementor'),
        'bottom-right'   => esc_html__('Bottom Right', 'mf-toc-elementor'),
        'bottom-justify' => esc_html__('Bottom Justify', 'mf-toc-elementor'),
        'top-left'       => esc_html__('Top Left', 'mf-toc-elementor'),
        'top-center'     => esc_html__('Top Center', 'mf-toc-elementor'),
        'top-right'      => esc_html__('Top Right', 'mf-toc-elementor'),
        'top-justify'    => esc_html__('Top Justify', 'mf-toc-elementor'),
        'left-top'       => esc_html__('Left Top', 'mf-toc-elementor'),
        'left-center'    => esc_html__('Left Center', 'mf-toc-elementor'),
        'left-bottom'    => esc_html__('Left Bottom', 'mf-toc-elementor'),
        'right-top'      => esc_html__('Right Top', 'mf-toc-elementor'),
        'right-center'   => esc_html__('Right Center', 'mf-toc-elementor'),
        'right-bottom'   => esc_html__('Right Bottom', 'mf-toc-elementor'),
    ];

    return $drop_position_options;
}



// Button Size
function Toc_Elementor_heading_size() {
    $heading_sizes = [
        'h1' => esc_html__( 'H1', 'mf-toc-elementor' ),
        'h2' => esc_html__( 'H2', 'mf-toc-elementor' ),
        'h3' => esc_html__( 'H3', 'mf-toc-elementor' ),
        'h4' => esc_html__( 'H4', 'mf-toc-elementor' ),
        'h5' => esc_html__( 'H5', 'mf-toc-elementor' ),
        'h6' => esc_html__( 'H6', 'mf-toc-elementor' ),
    ];

    return $heading_sizes;
}