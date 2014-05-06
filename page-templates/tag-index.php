<?php
/*
 * Template Name: Tag Index
 *
 * @package first-edition
 */
?>
<style>
.page-template #toc a {
        line-height: 2.5;
        padding: .5em .75em;
}
.page-template .tag-index {
	-webkit-column-count: 4;
	column-count: 4;
}
.page-template .tag-index ul,
.page-template .page-index ul {
	padding-left: 0;
	list-style-type: none;
	-webkit-column-break-inside:avoid;
	-moz-column-break-inside:avoid;
	column-break-inside:avoid;
}
.page-template .tag-index ul > li > span {
	float: right;
}
.page-template .tag-index ul > li {
	border-bottom: 1px dotted;
}
.page-template .tag-index ul > li:first-child,
.page-template .page-index ul > li:first-child {
	border-bottom: none;
	font-size: 1.5em;
	font-weight: bold;
}
</style>
<?php

get_header(); ?>

	<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main hentry" role="main">

			<h2 class="entry-title"><?php the_title(); ?></h2>

			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); the_content(); endwhile; endif; ?>

			<section class="index">
				<?php $first_edition_tags = get_terms( 'post_tag' );
				$total = count( $first_edition_tags );
				$toc = '';
				$output = '';

				if ( $total > 0 ) {
					/* Construct an alphabetically grouped list of tags */
					foreach( $first_edition_tags as $tag ) {
						$letter_groups[ strtoupper( $tag->name[0] ) ][ $tag->term_id ][0] = $tag->name;
						$letter_groups[ strtoupper( $tag->name[0] ) ][ $tag->term_id ][1] = $tag->count;
					}

					foreach ($letter_groups as $letter_group => $list ) {
						$toc .= '<a href="#' . $letter_group . '">' . $letter_group . '</a> ';
						$output .= '<ul id="' . $letter_group . '"><li>' . $letter_group . '</li>';
						foreach ($list as $id => $item ) {
							$url = esc_attr( get_tag_link( $id ) );
							$output .= '<li><a href="' . $url . '">' . $item[0] . '</a> ';
							$output .= '<span>' . intval( $item[1] ) . '</span></li>';
						}
						$output .= '</ul>';
					}

					// Only print table of contents links if there are a ton of things
					if ( $total > 250 )
						print '<div id="toc">' . $toc . '</div>';

					print '<div class="tag-index">' . $output . '</div>';
				} else {
					print '<p>Sorry, no tags were found.</p>';
				} ?>
			</section>

		</main><!-- #main -->
	</div><!-- #primary -->
	</div><!-- .container -->

<?php get_footer(); ?>
