<?php
/**
 * Fires after the main content, before the footer is output.
 *
 * @since 3.10
 */
do_action( 'et_after_main_content' );

if ( 'on' === et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>

			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>


		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php
					if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );

					}

					echo et_core_esc_previously( et_get_footer_credits() );
				?>

					</div>	<!-- .container -->
				</div>

				<!-- and modal popup for difficulty icon of routes -->
				<div class="fancybox-hidden" style="display: none;">
					<div id="difficulty_icon_popup">
							<div id="modaldiff" class="modal open" style="z-index: 1003; display: block; opacity: 1; transform: scaleX(1); top: 10%;">
							<div class="modal-content">
								<h3><?php echo __('Difficulty Scale' ,'wm-child-verdenatura');?></h3>
								<h4 class="txt-dark-green"><?php echo __('By bike' ,'wm-child-verdenatura');?></h4>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-1.png" width="30" height="30"><?php echo __('Flat ground; from 35 to 50 km (21 to 31 miles) per day; suitable for everyone, children included; no training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-1.5.png" width="30" height="30"><?php echo __('Flat ground with few gentle up and downs; from 40 to 55 km (24 to 34 miles) per day; suitable for everyone, children included; basic training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-2.png" width="30" height="30"><?php echo __('Mostly flat ground with some hilly stretches; from 45 to 65 km (28 to 40 miles) per day; suitable for everyone, included children from 11 years old; younger children can be carried by parents; basic to medium training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-2.5.png" width="30" height="30"><?php echo __('Partly flat, partly hilly ground; from 50 to 70 km (31 to 43 miles) per day; suitable for everyone, included children from 13 years old; medium training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-3.png" width="30" height="30"><?php echo __('Hilly ground; from 50 to 70 km (31 to 43 miles) per day; suitable for everyone over 16 years old; medium to high training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-4.png" width="30" height="30"><?php echo __('Hilly and mountain ground; from 40 to 70 km (24 to 43 miles) per day; suitable for everyone over 16 years old; high training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-5.png" width="30" height="30"><?php echo __('Mountain ground; from 50 km (31 miles) per day; suitable for everyone over 16 years old; excellent training needed' ,'wm-child-verdenatura');?></p>
								<h4 class="txt-dark-green"><?php echo __('On foot' ,'wm-child-verdenatura');?></h4>

								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-1.png" width="30" height="30"><?php echo __('Easy hiking trips with a maximum difference in height of 250 meters (820 ft); maximum of 2 hours of hiking per day; suitable for everyone, children included; no training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-1.5.png" width="30" height="30"><?php echo __('Easy hiking trips with a maximum difference in height of 350 meters (1150 ft); maximum of 3 hours of hiking per day; suitable for everyone, included children over 8 years old; basic training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-2.png" width="30" height="30"><?php echo __('Easy hiking trips with a maximum difference in height of 500 meters (1640 ft); maximum of 4 hours of hiking per day; suitable for everyone, included children over 11 years old; basic to medium training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-2.5.png" width="30" height="30"><?php echo __('Hiking trips with a maximum difference in height of 650 meters (2130 ft); maximum of 5 hours of hiking per day; suitable for everyone over 16 years old; medium training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-3.png" width="30" height="30"><?php echo __('Hiking trips with a maximum difference in height of 800 meters (2620 ft); maximum of 6 hours of hiking per day; medium to high training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-4.png" width="30" height="30"><?php echo __('Hiking trips with a maximum difference in height of 1,000 meters (3280 ft); maximum of 6 hours of hiking per day; high training needed' ,'wm-child-verdenatura');?></p>
								<p><img src="/wp-content/themes/wm-child-verdenatura/images/diff-5.png" width="30" height="30"><?php echo __('Hiking trips with a difference in height above 1,000 meters (3280 ft); maximum of 6 hours of hiking per day; excellent training needed' ,'wm-child-verdenatura');?></p>
								
							</div>
						</div>
					</div>
				</div>	

			</footer> <!-- #main-footer -->
				<div class="emp-by-wm" style="background-color: #FFF; text-align: center; color: black;">Powered by <a style="color: #7AB400;"href="http://www.webmapp.it">Webmapp</a></div>
		</div> <!-- #et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

	</div> <!-- #page-container -->

	<?php wp_footer(); ?>
</body>
</html>
