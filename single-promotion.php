<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header();

?>
<main id="page-content" class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
	<?php
	do_action( 'us_before_page' );

	if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {

		// Titlebar, if it is enabled in Theme Options
		us_load_template( 'templates/titlebar' );

		// START wrapper for Sidebar
		us_load_template( 'templates/sidebar', array( 'place' => 'before' ) );
	}

	while ( have_posts() ) {
		the_post();

		$content_area_id = us_get_page_area_id( 'content' );

		if ( $content_area_id != '' AND get_post_status( $content_area_id ) != FALSE ) {
			us_load_template( 'templates/content' );
		} else {
			$the_content = apply_filters( 'the_content', get_the_content() );

			// The page may be paginated itself via <!--nextpage--> tags
			$pagination = us_wp_link_pages();

			// If content has no sections, we'll create them manually
			$has_own_sections = ( strpos( $the_content, ' class="l-section' ) !== FALSE );
			if ( ! ( function_exists( 'vc_is_page_editable' ) AND vc_is_page_editable() ) AND ( ! $has_own_sections OR get_post_type() == 'tribe_events' ) ) {
				$the_content = '<section class="l-section"><div class="l-section-h i-cf">' . $the_content . $pagination . '</div></section>';
			} elseif ( ! empty( $pagination ) ) {
				$the_content .= '<section class="l-section"><div class="l-section-h i-cf">' . $pagination . '</div></section>';
			}

			echo $the_content;
			
			// display the routes in promotion with anypost
            $post_id = get_the_ID();
			$route_list = get_field('routes',$post_id);
            $routes_count = count($route_list);
            $routes_rows = $routes_count/3;
			?>
			<section class="l-section">
				<div class="l-section-h i-cf">
					<?php
					echo do_shortcode('[webmapp_anypost post_type="route" template="cy_route" post_ids="'.implode(",",$route_list).'" posts_count="'.$routes_count.'" rows="'.ceil($routes_rows).'" posts_per_page="'.$routes_count.'" ]');
					?> 
				</div>
			</section> 
			<?php
			// Post comments
			if ( comments_open() OR get_comments_number() != '0' ) {

				$show_comments = TRUE;
				// Check comments option of Events Calendar plugin
				if ( function_exists( 'tribe_get_option' ) AND get_post_type() == 'tribe_events' ) {
					$show_comments = tribe_get_option( 'showComments' );
				}

				if ( $show_comments ) {
					?>
					<section class="l-section for_comments">
					<div class="l-section-h i-cf"><?php
						wp_enqueue_script( 'comment-reply' );
						comments_template();
						?></div>
					</section><?php
				}
			}
		}
	}

	if ( us_get_option( 'enable_sidebar_titlebar', 0 ) ) {
		// AFTER wrapper for Sidebar
		us_load_template( 'templates/sidebar', array( 'place' => 'after' ) );
	}

	do_action( 'us_after_page' );
	?>
</main>

<?php get_footer() ?>
