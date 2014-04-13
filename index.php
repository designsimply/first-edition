<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package first-edition
 */

get_header(); ?>

	<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php switch ( true ) :
					case ( is_page() ) :
						get_template_part( 'content', 'page' );
						break;
					case ( is_single() ) :
						get_template_part( 'content', 'single' );
						break;
					default :
						get_template_part( 'content', get_post_format() );
						break;
				endswitch;?>

				<?php if ( is_single() || is_page() || is_attachment ) {
					first_edition_post_nav();

					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				} ?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

	<?php get_sidebar(); ?>
	</div><!-- .container -->

<?php get_footer(); ?>
