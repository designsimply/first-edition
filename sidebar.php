<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package first-edition
 */
?>
	<div id="secondary" class="widget-area" role="complementary">
		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

			<!-- This space intentionally left blank. No default widgets. -->

		<?php endif; // end sidebar widget area ?>
	</div><!-- #secondary -->
