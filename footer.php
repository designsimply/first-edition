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
			<?php if ( '' != $footer_text ) { echo $footer_text; } ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->

	</div><!-- #content -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
