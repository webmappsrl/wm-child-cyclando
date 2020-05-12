<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header('route');

wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
wp_enqueue_script('route-single-post-style-animation', get_stylesheet_directory_uri() . '/jquery/child-main.js', array('jquery'));
?>
<main id="page-content" class="l-main" <?php echo (us_get_option('schema_markup')) ? ' itemprop="mainContentOfPage"' : ''; ?>>
	<?php
	do_action('us_before_page');

	if (us_get_option('enable_sidebar_titlebar', 0)) {

		// Titlebar, if it is enabled in Theme Options
		us_load_template('templates/titlebar');

		// START wrapper for Sidebar
		us_load_template('templates/sidebar', array('place' => 'before'));
	}

	while (have_posts()) {
		the_post();

		// var 
		$post_id = get_the_ID();
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$language = ICL_LANGUAGE_CODE;
		} else {
			$language = 'it';
		}
		$how_to_arrive = get_field('vn_come_arrivare');
		$program = get_field('vn_prog');
		$scheda_tecnica = get_field('vn_scheda_tecnica');
		$organizzazione = get_field('vn_part_pr');
		$gallery_items = get_field('wm_route_gallery');
		$touroperator_id_array = get_field('tour_operator');
		$touroperator_id = $touroperator_id_array[0];
		$touroperator = get_the_title($touroperator_id);
		$gallery_ids = array();
		if ($gallery_items) {
			foreach ($gallery_items as $gallery_item) {
				array_push($gallery_ids, $gallery_item['ID']);
			}
		}
		//checks if it has promotion and creates a list of dates of promotion period
		$in_promotion_active = get_field('wm_route_in_promotion');
		$in_promotion = false;
		$promotion_dates_list = array();
		while (have_rows('model_promotion')) : the_row();
			$promotion_periods = get_sub_field('periods');
			$promotion_departure_dates = get_sub_field('departure_dates');
			foreach ($promotion_periods as $period) {
				$start_period = str_replace('/', '-', $period['start']);
				$stop_period = str_replace('/', '-', $period['stop']);
				$begin = new DateTime($start_period);
				$end = new DateTime($stop_period);
				$end = $end->modify('+1 day');

				$interval = new DateInterval('P1D');
				$daterange = new DatePeriod($begin, $interval, $end);
				foreach ($daterange as $date) {
					$promotion_single_date = $date->format("d-m-Y");
					array_push($promotion_dates_list, $promotion_single_date);
				}
			}
			foreach ($promotion_departure_dates as $date) {
				$single_date =  str_replace('/', '-', $date['date']);
				array_push($promotion_dates_list, $single_date);
			}
		endwhile;
		$current_date = date("d-m-Y");
		foreach ($promotion_dates_list as $dates_list) {
			if ($dates_list == $current_date) {
				$in_promotion = true;
			}
		}

		//  get activities 
		$target = 'who';
		$places_to_go = 'where';
		$activity = 'activity';
		$tax_activities = get_the_terms($post_id, $activity);
		$tax_targets = get_the_terms($post_id, $target);
		$tax_places_to_go = get_the_terms($post_id, $places_to_go);
		$tax_activities_slug = array();
		foreach ($tax_activities as $tax_activity) {
			array_push($tax_activities_slug, $tax_activity->slug );
		}
		$tax_targets_slug = array();
		foreach ($tax_targets as $tax_target) {
			array_push($tax_targets_slug, $tax_target->slug );
		}
		$tax_places_to_go_slug = array();
		foreach ($tax_places_to_go as $tax_place) {
			array_push($tax_places_to_go_slug, $tax_place->slug );
		}
		?>

		<section class="l-section height_auto for_sidebar at_right intro-section">
			<!--- first section -->
			<div id="introduction" class="l-section-h">
				<div class="g-cols type_default valign_top">
					<div class="vc_col-sm-9 vc_column_container l-content">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<section class="l-section">
									<div class="l-section-h i-cf">
										<?php
										echo "<p class='route-excerpt'>" . get_the_excerpt() . "</p>";
										echo get_the_content();
										?>
									</div>
								</section>
							</div>
						</div>
					</div>
					<div class="vc_col-sm-3 vc_column_container l-sidebar">
						<div class="vc_column-inner">
							<div class="wpb_wrapper sidebar-departures">
							<?php if ($gallery_ids) {echo do_shortcode('[us_image_slider ids="' . implode(',', $gallery_ids) . '" fullscreen="1" img_size="large" img_fit="cover"]'); }?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--- END first section -->
			<!--- second section  program and contact us-->
			<section id="program-button" class="l-section height_auto for_sidebar at_right">
				<div class="l-section-h">
					<div class="type_default valign_top">
						<div class="vc_col-sm-9 vc_column_container l-content">
							<div class="vc_column-inner">
								<div class="wpb_wrapper">
									<section class="l-section cy-section-route-buttons">
										<?php if ($program) : ?>
										<div class="cy-route-body-button cy-route-body-programbutton">
											<?php
											echo "<span id='expand-map' class='header-txt-layer-1 expand-map-content'>". __('Program','wm-child-cyclando')."</span>";
											?>
										</div>
										<?php endif; ?>
										<div id="wm-book" class="cy-route-body-button cy-route-body-contactus expand-map-content">
											<div class="meta-bar long-txt">
												<p class='meta-bar-txt-bold'><?php echo __('Contact us', 'wm-child-verdenatura'); ?></p>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<!--- END Second section program and contact us-->
		<?php if ($scheda_tecnica) : ?>
			<section id="caratteristiche" class="l-section height_auto for_sidebar at_right">
				<!--- Third section  Caratteristiche-->
				<div class="l-section-h">
					<div class="type_default valign_top">
						<div class="vc_col-sm-9 vc_column_container l-content">
							<div class="vc_column-inner">
								<div class="wpb_wrapper">
									<section class="l-section">
										<div class="l-section-h i-cf">
											<?php
											echo "<h2>".__('Features','wm-child-cyclando')."</h2>";
											echo $scheda_tecnica;
											?>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<!--- END third section Caratteristiche-->
		<?php endif; ?>
		<?php if ($touroperator) : ?>
			<!--- forth section  touroperator-->
			<section id="touroperator" class="l-section height_auto for_sidebar at_right">
				<div class="l-section-h">
					<div class="type_default valign_top">
						<div class="vc_col-sm-9 vc_column_container l-content">
							<div class="vc_column-inner">
								<div class="wpb_wrapper">
									<section class="l-section">
										<div class="l-section-h i-cf">
											<?php
											echo "<h2>Fatto da: $touroperator</h2>";
											?>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<!--- END forth section touroperator-->

		<!-- HTML modal for contact in route -->
		<div id="cy-route-contact" class="cy-route-contact">
			<div class="cy-modal-content">
				<div class="cy-modal-header">
					<div class="close-button-container"><span class="cy-close-contact">&times;</span></div>
					<div class="route-contact"><h2><?php echo __('Contact us', 'wm-child-verdenatura'); ?></h2></div>
				</div>
				<div class="cy-modal-body">
					<?php //echo do_shortcode('[contact-form-7 id="54052" title="Contact form route"]'); ?>
					<script>
						hbspt.forms.create({
							portalId: "6554435",
							formId: "369b0992-8548-4163-9eb9-a0029e90e1dd",
							onFormReady: function($form, ctx){
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="activities"]').setAttribute('value', '<?php echo implode(";",$tax_activities_slug);  ?>')
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="target"]').setAttribute('value', '<?php echo implode(";",$tax_targets_slug);  ?>')
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="place_to_go"]').setAttribute('value', '<?php echo implode(";",$tax_places_to_go_slug);  ?>')
							}
						});
					</script>
				</div>
			</div>
		</div>
		<!-- END HTML modal for contact in route -->
		<?php
		// Post comments
		if (comments_open() or get_comments_number() != '0') {

			$show_comments = FALSE;
			// Check comments option of Events Calendar plugin
			if (function_exists('tribe_get_option') and get_post_type() == 'tribe_events') {
				$show_comments = tribe_get_option('showComments');
			}

			if ($show_comments) {
				?>
				<section class="l-section for_comments">
					<div class="l-section-h i-cf"><?php
					wp_enqueue_script('comment-reply');
					comments_template();
					?></div>
				</section><?php
					}
				}
			}
			do_action('us_after_page');
			?>
</main>

<?php get_footer() ?>