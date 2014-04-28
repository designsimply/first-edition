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
function first_edition_customize_register( $wp_customize ) {
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

	$wp_customize->add_section( 'first_edition_theme_options', array(
		'title' => __( 'Theme Options', 'first-edition' ),
		'priority' => 200,
	) );

	$wp_customize->add_setting( 'first_edition_footer_text', array(
		'default' => sprintf(
			__( '%1$s was made with %2$s for %3$s', 'first-edition' ),
			'<a href="http://designsimply.com/theme/first-edition/">First Edition WordPress Theme</a>',
			'<a href="http://underscores.me/">Underscores.me</a>',
			'<a href="http://wordpress.org/">WordPress</a>'
		),
		'capability' => 'edit_theme_options',
		'sanitize_callback' => 'wp_kses_post',
		'sanitize_callback' => 'balanceTags',
	) );

	$wp_customize->add_control( new WP_Customize_Textarea_Control($wp_customize, 'first_edition_footer_text', array(
		'label' => __( 'Footer Text', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' => 'textarea',
		'priority' => 40,
	) ) );

	$wp_customize->add_setting( 'first_edition_bell', array(
		'default' => 1,
		'capability' => 'edit_theme_options',
	) );

	$wp_customize->add_control( 'first_edition_bell', array(
		'label' => __( 'Play a carriage return bell in comments', 'first-edition' ),
		'section' => 'first_edition_theme_options',
		'type' =>  'checkbox',
		'priority' => 60,
	) );
}
add_action( 'customize_register', 'first_edition_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function first_edition_customize_preview_js() {
	wp_enqueue_script( 'first_edition_customizer', get_template_directory_uri() . '/js/customizer.js',
		array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'first_edition_customize_preview_js' );
