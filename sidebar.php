<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package first-edition
 */
?>
	<div id="secondary" class="widget-area" role="complementary">
               <nav id="site-navigation" class="main-navigation" role="navigation">
                       <h2 class="menu-toggle"><?php _e( 'Menu', 'first-edition' ); ?></h2>
                       <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'first-edition' ); ?></a>

                       <?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
               </nav><!-- #site-navigation -->

		<?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>

			<!-- This space intentionally left blank. No default widgets. -->

		<?php endif; // end sidebar widget area ?>
	</div><!-- #secondary -->
