<?php
/**
 * first-edition Theme Customizer
 *
 * @package first-edition
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
$protocol = is_ssl() ? 'https' : 'http';
$selected_fonts = array(
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

function first_edition_customize_register( $wp_customize ) {
	global $protocol, $selected_fonts;
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/**
	* Add textarea as a type by extending WP_Customize_Control.
	* Hopefully future-proofed in case core adds a class like this later.
	* Hat tip: http://code.tutsplus.com/articles/custom-controls-in-the-theme-customizer--wp-34556
	*/
	if ( !class_exists( 'WP_Customize_Textarea_Control' ) ) {
		class WP_Customize_Textarea_Control extends WP_Customize_Control {
			public $type = 'textarea';
			public function render_content() {
				printf(
				'<label class="customize-control-footer">
					<span class="customize-control-title">%1$s</span>
					<textarea rows="10" style="width: 100%%;" %2$s>%3$s</textarea>
				</label>',
					esc_html( $this->label ),
					$this->get_link(),
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
		'title' => __( 'Theme Options', 'first-edition' ),
		'description' => 'Use a logo that is 75 x 75 pixels or smaller.',
		'priority' => 200,
	) );

	$wp_customize->add_setting( 'first_edition_logo' );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'first_edition_logo', array(
		'label' => __( 'Logo', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'settings' => 'first_edition_logo',
		'priority' => 20,
	) ) );

	$wp_customize->add_setting( 'first_edition_footer_text', array(
		'default' => sprintf(
			__( '%1$s was made with %2$s for %3$s', 'first-edition' ),
			'<a href="http://designsimply.com/theme/first-edition/">First Edition WordPress Theme</a>',
			'<a href="http://underscores.me/">Underscores.me</a>',
			'<a href="http://wordpress.org/">WordPress</a>'
		),
		'sanitize_callback' => 'wp_kses_post',
		'sanitize_callback' => 'balanceTags',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control( $wp_customize, 'first_edition_footer_text', array(
		'label' => __( 'Footer Text', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' => 'textarea',
		'priority' => 40,
	) ) );

	$wp_customize->add_setting( 'first_edition_bell', array( 'default' => 1,) );

	$wp_customize->add_control( 'first_edition_bell', array(
		'label' => __( 'Play a carriage return bell in comments', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' =>  'checkbox',
		'priority' => 60,
	) );

	$wp_customize->add_section( 'first_edition_theme_colors', array(
		'title' => __( 'Theme Colors', 'first-edition' ),
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
		'label'   => __( 'Link Color', 'first-edition' ),
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
		'label'   => __( 'Text Color', 'first-edition' ),
		'section' => 'colors',
	) ) );

	// Enqueue all the pre-selected fonts so they display lightning fast for previews.
	foreach ( $selected_fonts as $name => $query_string )
		wp_enqueue_style ( $name, "$protocol://fonts.googleapis.com/css?family=$query_string" );

	$wp_customize->add_section( 'first_edition_fonts', array(
		'title' => __( 'Fonts', 'first-edition' ),
		'priority' => 42,
	) );

	$wp_customize->add_setting( 'first_edition_font_pair', array(
		'default' => '',
		'type' => 'option',
		'transport' => 'postMessage',
	) );

	$wp_customize->add_control( 'first_edition_font_pair', array(
		'label' => __( 'Font Pairs', 'first-edition' ),
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
 * Add customized CSS to the front end.
 */
function first_edition_customize_css() {
	global $protocol, $selected_fonts;
	$color = get_option( 'first_edition_colors' );
	$color['bg'] = get_theme_support( 'custom-background', 'default-color' );
	?>
	<style type="text/css" id="customizer-css">
		.custom-background { background-color: #<?php echo $color['bg']; ?>; }
		a { color: #<?php echo $color['link']; ?>; }
		body, a:hover, a:focus, a:active, .main-navigation ul .current_page_item > a { color: #<?php echo $color['text']; ?>; }
		.comment-form input[type="submit"]:hover {
			background: #<?php echo $color['text']; ?>;
			color: #<?php echo $color['bg']; ?>;
		}
		<?php $font = get_option( 'first_edition_font_pair' );
		if ( '' != $font ) {
		switch ( $font ) {
			case 'pair1':
				wp_enqueue_style( 'lustria', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['lustria'] );
				wp_enqueue_style( 'lato', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['lato'] );
				echo  "h1,h2,h3,h4,h5,h6 { font-family: 'Lustria', serif; }";
				echo  "body { font-family: 'Lato', 'Open Sans', sans-serif; font-weight: 300; }";
				break;

			case 'pair2':
				wp_enqueue_style( 'ubutnu', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['ubutnu'] );
				wp_enqueue_style( 'lora', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['lora'] );
				echo "h1,h2,h3,h4,h5,h6 { font-family: 'Ubuntu', serif; }";
				echo "body { font-family: 'Lora', 'Open Sans', sans-serif; }";
				break;

			case 'pair3':
				wp_enqueue_style( 'raleway', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['raleway'] );
				wp_enqueue_style( 'merriweather', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['merriweather'] );
				echo "h1,h2,h3,h4,h5,h6 { font-family: 'Raleway', serif; } ";
				echo "body { font-family: 'Merriweather', 'Open Sans', sans-serif; font-weight: 300; }";
				break;

			case 'pair4':
				wp_enqueue_style( 'roboto-slab', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['roboto-slab'] );
				wp_enqueue_style( 'roboto', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['roboto'] );
				echo "h1,h2,h3,h4,h5,h6 { font-family: 'Roboto Slab', serif; }";
				echo "body { font-family: 'Roboto', 'Open Sans', sans-serif; font-weight: 300; }";
				break;

			case 'pair5':
				wp_enqueue_style( 'quattrocento', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['quattrocento'] );
				wp_enqueue_style( 'quattrocento-sans', "$protocol://fonts.googleapis.com/css?family=".$selected_fonts['quattrocento-sans'] );
				echo "h1,h2,h3,h4,h5,h6 { font-family: 'Quattrocento', serif; }";
				echo "body { font-family: 'Quattrocento Sans', 'Open Sans', sans-serif; font-weight: 300; }";
				break;
		} } ?>
	</style>
<?php }
add_action( 'wp_head', 'first_edition_customize_css' );

// Don't load the default fonts enqueued in functions.ph if they are going to be loaded by first_edition_customize_css()
function first_edition_deregister_styles() {
	if ( '' != get_option( 'first_edition_font_pair' ) ) {
		wp_dequeue_style( 'quattrocento' );
		wp_dequeue_style( 'quattrocento-sans' );
	}
}
add_action( 'wp_print_styles', 'first_edition_deregister_styles', 99 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function first_edition_customize_preview_js() {
	wp_enqueue_script( 'first_edition_customizer', get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'first_edition_customize_preview_js' );

