<?php
/**
 * first-edition Theme Customizer
 *
 * @package first-edition
 */

/**
 * Add sections for Colors, Fonts, and Theme Options. Theme Options include custom
 * logo, footer text, and a switch for the bell. :) postMessage support is added for
 * site title, description, and colors in the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function first_edition_font_list() {
	return array(
		'lustria' => 'Lustria',
		'lato' => 'Lato:300,400,700',
		'ubuntu' => 'Ubuntu',
		'lora' => 'Lora:400,700',
		'merriweather' => 'Merriweather:400,700',
		'raleway' => 'Raleway:300,400,600',
		'roboto-slab' => 'Roboto+Slab:400,700',
		'roboto' => 'Roboto:300,400,700',
		'quattrocento' => 'Quattrocento:400,700',
		'quattrocento-sans' => 'Quattrocento+Sans:300,400,700',
	);
}

function first_edition_customize_register( $wp_customize ) {
	global $protocol;
	$selected_fonts = first_edition_font_list();
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	* Add textarea as a type by extending WP_Customize_Control.
	* Hopefully future-proofed in case core adds a class like this later.
	* Hat tip: http://code.tutsplus.com/articles/custom-controls-in-the-theme-customizer--wp-34556
	*/
	if ( ! class_exists( 'WP_Customize_Textarea_Control' ) ) {
		class WP_Customize_Textarea_Control extends WP_Customize_Control {
			public $type = 'textarea';
			public function render_content() {
				printf(
				'<label class="customize-control-footer">
					<span class="customize-control-title">%1$s</span>
					<textarea rows="10" style="width: 100%%;" %2$s>%3$s</textarea>
				</label>',
					esc_html( $this->label ),
					esc_url( $this->get_link() ),
					esc_textarea( $this->value() )
				);
			}
		}
	}
	/**
	 * Admin styling for color controls.
	 */
	class WP_Customize_Color_Style_Control extends WP_Customize_Color_Control {
		public function enqueue() {
			wp_enqueue_style( 'first-edition-customizer-controls',
				get_template_directory_uri() . '/css/customizer-controls.css'
			);
		}
	}

	$wp_customize->add_section( 'first_edition_theme_options', array(
		'title' => esc_html__( 'Theme Options', 'first-edition' ),
		'description' => esc_html__( 'Use a logo that is 75 x 75 pixels or smaller.' ),
		'priority' => 200,
	) );

	$wp_customize->add_setting( 'first_edition_logo' );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'first_edition_logo', array(
		'label' => esc_html__( 'Logo', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'settings' => 'first_edition_logo',
		'priority' => 20,
	) ) );

	$wp_customize->add_setting( 'first_edition_footer_text', array(
		'default' => sprintf(
			esc_html__( '%1$s was made with %2$s for %3$s', 'first-edition' ),
			'<a href="http://designsimply.com/wordpress/theme/first-edition/">First Edition WordPress Theme</a>',
			'<a href="http://underscores.me/">Underscores.me</a>',
			'<a href="http://wordpress.org/">WordPress</a>'
		),
		'sanitize_callback' => 'wp_filter_kses',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'first_edition_footer_text', array(
		'label' => esc_html__( 'Footer Text', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' => 'textarea',
		'priority' => 40,
	) ) );

	$wp_customize->add_setting( 'first_edition_bell', array( 'default' => 1,) );

	$wp_customize->add_control( 'first_edition_bell', array(
		'label' => esc_html__( 'Play a carriage return bell in comments', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' =>  'checkbox',
		'priority' => 60,
	) );

	$wp_customize->add_section( 'first_edition_theme_colors', array(
		'title' => esc_html__( 'Theme Colors', 'first-edition' ),
		'priority' => 201,
	) );

	// Note: background color support is added to after_setup_theme in functions.php

	$wp_customize->add_setting( 'first_edition_colors[link]', array(
		'default' => '7ca4ad',
		'type' => 'option',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Style_Control( $wp_customize, 'first_edition_colors[link]', array(
		'label'   => esc_html__( 'Link Color', 'first-edition' ),
		'section' => 'colors',
	) ) );

	$wp_customize->add_setting( 'first_edition_colors[text]', array(
		'default' => '6b4c29',
		'type' => 'option',
		'sanitize_callback' => 'sanitize_hex_color_no_hash',
		'sanitize_js_callback' => 'maybe_hash_hex_color',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'first_edition_colors[text]', array(
		'label'   => esc_html__( 'Text Color', 'first-edition' ),
		'section' => 'colors',
	) ) );

	// Enqueue all the pre-selected fonts so they display lightning fast for previews.
	foreach ( $selected_fonts as $name => $query_string )
		wp_enqueue_style ( $name, '//fonts.googleapis.com/css?family=' . esc_url( $query_string ) );

	$wp_customize->add_section( 'first_edition_fonts', array(
		'title' => esc_html__( 'Fonts', 'first-edition' ),
		'priority' => 42,
	) );

	$wp_customize->add_setting( 'first_edition_font_pair', array(
		'default' => '',
		'type' => 'option',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( 'first_edition_font_pair', array(
		'label' => esc_html__( 'Font Pairs', 'first-edition' ),
		'section' => 'first_edition_fonts',
		'settings' => 'first_edition_font_pair',
		'type' => 'radio',
		'choices' => array(
			'pair1' => __( 'Lustria &amp; Lato', 'first-edition' ),
			'pair2' => __( 'Ubuntu &amp; Lora', 'first-edition' ),
			'pair3' => __( 'Raleway &amp; Merriweather', 'first-edition' ),
			'pair4' => __( 'Roboto Slab &amp; Roboto', 'first-edition' ),
			'pair5' => __( 'Quattrocento &amp; Quattrocento Sans', 'first-edition' ),
			'' => __( 'Default', 'first-edition' ), // fallback to style.css
		),
	) );
}
add_action( 'customize_register', 'first_edition_customize_register' );

/**
 * Don't load default fonts enqueued in functions.php if they are going to be loaded by first_edition_customize_css()
 */
function first_edition_deregister_styles() {
	if ( '' != get_option( 'first_edition_font_pair' ) ) {
		wp_dequeue_style( 'quattrocento' );
		wp_dequeue_style( 'quattrocento-sans' );
	}
}
add_action( 'wp_print_styles', 'first_edition_deregister_styles', 99 );

/**
 * Add customized CSS to the front end.
 */
function first_edition_customize_css() {
	$selected_fonts = first_edition_font_list();
	$color = get_option( 'first_edition_colors' );
	$bgcolor = get_theme_mod( 'background_color' );
	$customized_css = '.custom-background { background-color: #' . esc_attr( $bgcolor ) . '; }'
		. "\n" . 'a { color: #' . $color['link'] . '; }'
		. "\n" . 'body, a:hover, a:focus, a:active, .main-navigation ul .current_page_item > a { color: #' . esc_attr( $color['text'] ) . '; }'
		. "\n" . '.comment-form input[type="submit"]:hover { background: #' . $color['text'] . '; color: #' . esc_attr( $bgcolor) . '; }';
	$font = get_option( 'first_edition_font_pair' );
	if ( '' != $font ) {
	switch ( $font ) {
		case 'pair1':
			wp_enqueue_style( 'lustria', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['lustria'] ) );
			wp_enqueue_style( 'lato', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['lato'] ) );
			$customized_css .= "\nh1,h2,h3,h4,h5,h6 { font-family: 'Lustria', serif; }";
			$customized_css .= "\nbody { font-family: 'Lato', 'Open Sans', sans-serif; font-weight: 300; }";
			break;

		case 'pair2':
			wp_enqueue_style( 'ubutnu', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['ubutnu'] ) );
			wp_enqueue_style( 'lora', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['lora'] ) );
			$customized_css .= "\nh1,h2,h3,h4,h5,h6 { font-family: 'Ubuntu', serif; }";
			$customized_css .= "\nbody { font-family: 'Lora', 'Open Sans', sans-serif; }";
			break;

		case 'pair3':
			wp_enqueue_style( 'raleway', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['raleway'] ) );
			wp_enqueue_style( 'merriweather', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['merriweather'] ) );
			$customized_css .= "\nh1,h2,h3,h4,h5,h6 { font-family: 'Raleway', serif; } ";
			$customized_css .= "\nbody { font-family: 'Merriweather', 'Open Sans', sans-serif; font-weight: 300; }";
			break;

		case 'pair4':
			wp_enqueue_style( 'roboto-slab', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['roboto-slab'] ) );
			wp_enqueue_style( 'roboto', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['roboto'] ) );
			$customized_css .= "\nh1,h2,h3,h4,h5,h6 { font-family: 'Roboto Slab', serif; }";
			$customized_css .= "\nbody { font-family: 'Roboto', 'Open Sans', sans-serif; font-weight: 300; }";
			break;

		case 'pair5':
			wp_enqueue_style( 'quattrocento', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['quattrocento'] ) );
			wp_enqueue_style( 'quattrocento-sans', "//fonts.googleapis.com/css?family=" . esc_url( $selected_fonts['quattrocento-sans'] ) );
			$customized_css .= "\nh1,h2,h3,h4,h5,h6 { font-family: 'Quattrocento', serif; }";
			$customized_css .= "\nbody { font-family: 'Quattrocento Sans', 'Open Sans', sans-serif; font-weight: 300; }";
			break;
	} }
	wp_add_inline_style( 'first-edition-style', $customized_css );
}
add_action( 'wp_enqueue_scripts', 'first_edition_customize_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function first_edition_customize_preview_js() {
	wp_enqueue_script( 'first_edition_customizer', get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'first_edition_customize_preview_js' );

