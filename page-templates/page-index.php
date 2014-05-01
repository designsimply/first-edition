<?php
/*
 * Template Name: Page Index
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
			<?php $first_edition_pages = get_pages('sort_column=post_title&hierarchical=0' );
			$total = count( $first_edition_pages );
			$url = site_url();
			$toc = '';
			$output = '';

			if ( $total > 0 ) {
				/* Construct an alphabetically grouped page list */
				foreach( $first_edition_pages as $page )
					$letter_groups[ $page->post_title[0] ][ $page->ID ] = $page->post_title;

				foreach ($letter_groups as $letter_group => $list ) {
					$toc .= '<a href="#' . $letter_group . '">' . $letter_group . '</a> ';
					$output .= '<ul id="' . $letter_group . '"><li>' . $letter_group . '</li>';
					foreach ($list as $id => $title ) {
						$url = attribute_escape( get_page_link( $id ) );
						$output .= '<li><a href="' . $url . '">' . $title . '</a></li>';
					}
					$output .= '</ul>';
				}

				// Only print table of contents links if there are a ton of things
				if ( $total > 50 )
					print '<div id="toc">' . $toc . '</div>';

				print '<div class="page-index">' . $output . '</div>';

			} else {
				 print '<p>Sorry, no pages were found.</p>';
			} ?>
		</section>

		</main><!-- #main -->
	</div><!-- #primary -->
	</div><!-- .container -->

<?php get_footer(); ?>
