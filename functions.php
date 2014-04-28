<?php
/**
 * first-edition functions and definitions
 *
 * @package first-edition
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'first_edition_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function first_edition_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on first-edition, use a find and replace
	 * to change 'first-edition' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'first-edition', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'first-edition' ),
	) );

	// Enable support for Post Formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link' ) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'first_edition_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form',
		'gallery',
	) );
}
endif; // first_edition_setup
add_action( 'after_setup_theme', 'first_edition_setup' );

/**
 * Filter previous/next links to loop back to the parent
 */
add_filter( 'previous_post_link', 'first_edition_previous_post_link', 10, 3 );
function first_edition_previous_post_link( $val, $attr, $content = null ) {
        global $post;
        $parent_link = '<a class="post-parent" href="' . get_permalink( $post->post_parent ) . '" rel="navigation"><span class="meta-nav">&larr;</span> ' . get_the_title( $post->post_parent ) . '</a>';

        if ( empty( $post->post_parent ) )
                $parent_link = '<a class="post-parent" href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name') ) . '" rel="home"><span class="meta-nav">&larr;</span> Home</a>';

        $updated_link = '<div class="nav-previous">' . $parent_link . '</div>';

        return ( empty( $val ) ) ? $updated_link : $val ;
}

add_filter( 'next_post_link', 'first_edition_next_post_link', 10, 3 );
function first_edition_next_post_link( $val, $attr, $content = null ) {
        global $post;
        $parent_link = '<a class="post-parent" href="' . get_permalink( $post->post_parent ) . '" rel="navigation">' . get_the_title( $post->post_parent ) . ' <span class="meta-nav">&rarr;</span></a>';

        if ( empty( $post->post_parent ) )
                $parent_link = '<a class="post-parent" href="' . home_url() . '" title="' . esc_attr( get_bloginfo( 'name') ) . '" rel="home">Home <span class="meta-nav">&rarr;</span></a>';

        $updated_link = '<div class="nav-next">' . $parent_link . '</div>';

        return ( empty( $val ) ) ? $updated_link : $val ;
}

add_filter( 'previous_image_link', 'first_edition_previous_image_link', 10, 3 );
function first_edition_previous_image_link( $val, $attr, $content = null ) {
        global $post;
        $parent_link = '<a class="post-parent" href="' . get_permalink( $post->post_parent ) . '" rel="navigation">&larr;</a>';
        return ( empty( $val ) ) ? $parent_link : $val ;
}

add_filter( 'next_image_link', 'first_edition_next_image_link', 10, 3 );
function first_edition_next_image_link( $val, $attr, $content = null ) {
        global $post;
        $parent_link = '<a class="post-parent" href="' . get_permalink( $post->post_parent ) . '" rel="navigation">&rarr;</a>';
        return ( empty( $val ) ) ? $parent_link : $val ;
}

/**
 * Change the default number of gallery columns to 5.
 * Props Viper007Bond for the gist—you are awesome!
 */
add_filter( 'shortcode_atts_gallery', 'first_edition_gallery_default_columns', 10, 3 );
function first_edition_gallery_default_columns( $atts, $defaults, $raw_atts ) {
        // Don't override manually-set number of columns
        if ( ! empty( $raw_atts['columns'] ) ) {
                return $atts;
        }

        $atts['columns'] = 5;

        return $atts;
}

/**
 * Change the default image size to large on attachment pages.
 */
add_filter( 'prepend_attachment', 'first_edition_prepend_attachment', 10, 3 );
function first_edition_prepend_attachment() {

        $output = '<p>' . wp_get_attachment_link( $id, 'large' ) . '</p>';

        return $output;
}

/**
 * Register widgetized area and update sidebar with default widgets.
 */
function first_edition_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'first-edition' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'first_edition_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function first_edition_scripts() {
	wp_enqueue_style( 'first-edition-style', get_stylesheet_uri() );

	wp_enqueue_script( 'first-edition-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
	wp_enqueue_script( 'first-edition-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'Quattrocento', "$protocol://fonts.googleapis.com/css?family=Quattrocento:400,700" );
	wp_enqueue_style( 'QuattrocentoSans', "$protocol://fonts.googleapis.com/css?family=Quattrocento+Sans:400,700" );
}
add_action( 'wp_enqueue_scripts', 'first_edition_scripts' );

/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
