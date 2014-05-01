<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package first-edition
 */
$footer_text = get_theme_mod( 'first_edition_footer_text' );
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<?php if ( '' != $footer_text ) {
				echo $footer_text;
			} else {
				printf( __( '%1$s was made with %2$s for %3$s', 'first-edition' ), '<a href="http://designsimply.com/wordpress/theme/first-edition/" rel="designer">First Edition WordPress Theme</a>', '<a href="http://underscores.me/" rel="inspiration">Underscores.me</a>', '<a href="http://wordpress.org/" rel="generator">WordPress</a>' );
			} ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

	</div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
