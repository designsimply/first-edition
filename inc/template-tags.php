<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package first-edition
 */

if ( ! function_exists( 'first_edition_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 */
function first_edition_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Posts navigation', 'first-edition' ); ?></h2>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'first-edition' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'first-edition' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'first_edition_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 */
function first_edition_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation" role="navigation">
		<h2 class="screen-reader-text"><?php _e( 'Post navigation', 'first-edition' ); ?></h2>
		<div class="nav-links">
			<?php
				previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'first-edition' ) );
				next_post_link(     '<div class="nav-next">%link</div>',     _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link',     'first-edition' ) );
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'first_edition_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 */
function first_edition_posted_on() {
	$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	printf( __( '<span class="byline">By %1$s</span> <span class="sep">//</span> <span class="posted-on">%2$s</span>', 'first-edition' ),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s">%2$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_html( get_the_author() )
		),
		sprintf( '<a href="%1$s" rel="bookmark">%2$s</a>',
			esc_url( get_permalink() ),
			$time_string
		)
	);
}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function first_edition_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'first_edition_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'first_edition_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so first_edition_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so first_edition_categorized_blog should return false.
		return false;
	}
}

if ( ! function_exists( 'first_edition_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since first-edition 1.0
 */
function first_edition_comment( $comment, $args, $depth ) {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
                case 'pingback' :
                case 'trackback' :
        ?>
        <li class="post pingback">
                <p><?php _e( 'Mentioned at ', 'first-edition' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'edit', 'photo-addict' ), '{', '}' ); ?></p>
        <?php
                        break;
                default :
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

                <a href="<?php echo esc_url( get_comment_link() ); ?>"><?php echo get_avatar( $comment, 16, '', get_comment_date() . ' at ' . get_comment_time() ); ?></a>
                <cite class="fn"><?php comment_author_link(); ?></cite>:

                <?php if ( $comment->comment_approved == '0' ) { ?>
                        <?php _e( 'Your comment will be reviewed soon.', 'first-edition' ) ?>
                <?php } ?>

                <?php comment_text(); ?>

                <?php edit_comment_link( __( 'edit', 'first-edition' ), '{', '}' ); ?>
                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => 'reply' ) ) ); ?> 

        <?php
                        break;
        endswitch;
}
endif; // end check for first_edition_comment()

/**
 * Flush out the transients used in first_edition_categorized_blog.
 */
function first_edition_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'first_edition_categories' );
}
add_action( 'edit_category', 'first_edition_category_transient_flusher' );
add_action( 'save_post',     'first_edition_category_transient_flusher' );
