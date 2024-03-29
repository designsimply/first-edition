<?php
/**
 * The template file for displaying image attachments.
 *
 * @package first-edition
 * @since first-edition 1.0
 */
get_header(); ?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<nav>
		<span class="previous"><?php previous_image_link( '%link', __( '&larr;', 'first-edition' ) ); ?></span>
		<h2 class="entry-header"><?php the_title(); ?>
			<span class="sep"> // </span>
			<?php $metadata = wp_get_attachment_metadata();
			printf( __( '<a class="post-parent" href="%1$s" title="%2$s" rel="gallery">%2$s</a>', 'first-edition' ),
				get_permalink( $post->post_parent ),
				get_the_title( $post->post_parent )
			); ?>
		</h2>
		<span class="next"><?php next_image_link( '%link', __( '&rarr;', 'first-edition' ) ); ?></span>
		</nav>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
				// Pull the size values available for the current image
				$image_sizes = array('small', 'medium', 'large');

				foreach ($image_sizes as $image_size) {
					if ( isset( $metadata['sizes'][$image_size]['width'] ) )
						$widths["$image_size"] = $metadata['sizes'][$image_size]['width'];
				}

				if ( isset( $metadata ) )
					$widths['full'] = $metadata['width'];

				// Sort and get the last value
				if ( isset( $widths ) ) {
					sort( $widths );
					$max_width = array_pop( $widths );
				}

				// Fallback to a set value if a max width cannot be found
				if ( empty( $max_width ) || $max_width > 960 )
					$max_width = 960;

				$content_width = $max_width;

				$attachment_size = apply_filters( 'first_edition_attachment_size', array( $max_width, $max_width ) ); // Filterable image size.
				$attachment_image = wp_get_attachment_image_src( $post->ID, $attachment_size );

				echo '<img src="' . $attachment_image[0] . '" width="' . $attachment_image[1] . '" height="' . $attachment_image[2] . '" />';

				// Match the #wrapper width to the image width so the rotate site title and byline "drip" nicely around the image
				echo '<style type="text/css">';
				if ( $attachment_image[1] > 0 ) {
					echo '.attachment #wrapper { width: ' . $attachment_image[1] . 'px; max-width: 100%; }';
				}
				echo '</style>';
			?>

			<?php the_content(); ?>

			<?php // If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template( '', true );
			?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'first-edition' ),'.' );
				if ( $tags_list ) :
				?>
				<span class="tags-links">
					<?php printf( __( 'Tagged %1$s', 'first-edition' ), $tags_list ); ?>
				</span>
				<?php endif; // End if $tags_list ?>

		</article><!-- #post-<?php the_ID(); ?> -->
	<?php endwhile; ?>
<?php else : ?>

	<?php get_template_part( 'no-results', 'index' ); ?>

<?php endif; ?>

<?php get_footer(); ?>
