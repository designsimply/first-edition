<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package first-edition
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function first_edition_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'first_edition_jetpack_setup' );
