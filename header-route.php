	<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

	/**
	 * Template header
	 */

	$us_layout = US_Layout::instance();

	// variable and queries
	$days = (int)get_field('vn_durata');
	$distance = get_field('distance');
	$difficulty = get_field('vn_diff');
	$difficulty = str_replace('.', ',', $difficulty);
	$nights = $days - 1;
	$post_id = get_the_ID();
	$target = 'who';
	$places_to_go = 'where';
	$activity = 'activity';
	$scheda_tecnica = get_field('vn_scheda_tecnica');
	$program = get_field('vn_prog');
	$touroperator_id_array = get_field('tour_operator');
	$coming_soon = get_field('not_salable');
	if ($coming_soon) {
		$coming_soon_class = 'coming-soon-button';
	}
	$featured_map = '/wp-content/themes/wm-child-cyclando/images/map-logo-osm.jpg';
	if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		$language = ICL_LANGUAGE_CODE;
	} else {
		$language = 'it';
	}
	$first_departure_date = '';
	// get terms targets
	$tax_targets = get_the_terms($post_id, $target);
	$tax_places_to_go = get_the_terms($post_id, $places_to_go);

	$tax_activities = get_the_terms($post_id, $activity);
	$get_term_activity = get_term_by('slug', $tax_activities[0]->slug, $activity);
	$term_activity = 'term_' . $get_term_activity->term_id;
	$iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);

	//get the first departure date
	$start_array = array();
	if (have_rows('departures_periods',get_the_ID())) {
		$dates = array();
		while (have_rows('departures_periods')) : the_row();
			$sta = get_sub_field('start');
			$sto = get_sub_field('stop');
			$w_d = get_sub_field('week_days');
			$d_o_w = new DaysOfWeek( $sta , $sto );
			$w_d_int = wm_weekDayToWeekNumber($w_d);
			$dates = array_merge($dates,$d_o_w->query_byDayOfWeek( $w_d_int , 'none' ));
		endwhile;
		$dates = array_unique( $dates , SORT_REGULAR );
		foreach ($dates as $date) {
			$d = $date->format('d-m-Y');
			array_push($start_array, $d);
		}
	}
	
	if (have_rows('departure_dates',get_the_ID())) {
		$dates = get_field('departure_dates');
		foreach ($dates as $date) {
			foreach ($date as $single_date) {
				$start = str_replace("/", "-", $single_date);
				array_push($start_array, $start);
			}
		}
	}
	usort($start_array, function ($a, $b) {
		$dateTimestamp1 = strtotime($a);
		$dateTimestamp2 = strtotime($b);

		return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
	});
	foreach ($start_array as $date) {
		if ( date('d-m-Y', strtotime('+4 day')) <= $date ) {
			$first_departure_date = date_i18n('d F', strtotime($date));
			break;
		}
	}
	?>
	<!DOCTYPE HTML>
	<html class="<?php echo $us_layout->html_classes() ?>" <?php language_attributes('html') ?>>

	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<?php

		wp_head();

		// Theme Options CSS
		if (defined('US_DEV') or !us_get_option('optimize_assets', 0)) {
			?>
			<style id="us-theme-options-css">
				<?php echo us_get_theme_options_css() ?>
			</style>
		<?php
	}

	// Header CSS
	if ($us_layout->header_show != 'never') {
		?>
			<style id="us-header-css">
				<?php echo us_minify_css(us_get_template('templates/css-header')) ?>
			</style>
		<?php
	}

	// Custom CSS from Theme Options
	if (!us_get_option('optimize_assets', 0) and $us_custom_css = us_get_option('custom_css', '')) {
		?>
			<style id="us-custom-css">
				<?php echo us_minify_css($us_custom_css) ?>
			</style>
		<?php
	}

	// Custom HTML before </head>
	echo us_get_option('custom_html_head', '');

	// Helper action
	do_action('us_before_closing_head_tag');
	?>
	</head>

	<body <?php body_class('l-body ' . $us_layout->body_classes());
			if (us_get_option('schema_markup')) {
				echo ' itemscope itemtype="https://schema.org/WebPage"';
			} ?>>
		<?php
		global $us_iframe;
		if (!(isset($us_iframe) and $us_iframe) and us_get_option('preloader') != 'disabled' and defined('US_CORE_VERSION')) {
			add_action('us_before_canvas', 'us_display_preloader', 100);
			function us_display_preloader()
			{
				$preloader_type = us_get_option('preloader');
				if (!in_array($preloader_type, array_merge(us_get_preloader_numeric_types(), array('custom')))) {
					$preloader_type = 1;
				}

				if ($preloader_type == 'custom' and $preloader_image = us_get_option('preloader_image', '')) {
					$img_arr = explode('|', $preloader_image);
					$preloader_image_html = wp_get_attachment_image($img_arr[0], 'medium');
					if (empty($preloader_image_html)) {
						$preloader_image_html = us_get_img_placeholder('medium');
					}
				} else {
					$preloader_image_html = '';
				}

				?>
				<div class="l-preloader">
					<div class="l-preloader-spinner">
						<div class="g-preloader type_<?php echo $preloader_type ?>">
							<div><?php echo $preloader_image_html ?></div>
						</div>
					</div>
				</div>
			<?php
		}
	}

	do_action('us_before_canvas') ?>

		<div class="l-canvas <?php echo $us_layout->canvas_classes() ?>">
			<?php
			if ($us_layout->header_show != 'never') {

				do_action('us_before_header');

				us_load_template('templates/wm-header');
				$featured_image = get_the_post_thumbnail_url($post_id, 'large');
				?>

				<div id="webmapp-layer-container" class="webmapp-container">
					<div id='webmapp-layer-1-title' class="webmapp-featured-image">
						<div class="webmapp-featured-image-img" style="background-image: url('<?php echo $featured_image; ?>')">
							<div class="container">
								<?php
								if ($tax_targets) {
									?>
									<div class="tax-targets"><span class='tax-target header-txt-layer-1'>
											<?php
											foreach ($tax_targets as $tax_target) {
												$get_term = get_term_by('slug', $tax_target->slug, 'who');
												$term = 'term_' . $get_term->term_id;
												$iconimage = get_field('wm_taxonomy_icon', $term);
												echo "<i class='cy-icons icon-" . $iconimage . "'></i>" . $tax_target->name;
											}
											?>
										</span>
									</div>
								<?php } ?>
								<h1 class=""><?php the_title() ?></h1>
							</div>
						</div>
					</div>
					<div id='webmapp-layer-1-map' class="webmapp-map-container">
						<div class="webmapp-featured-map" style="background-image: url('<?php echo $featured_map; ?>')">
							<div class="container">
								<?php
								if ($days) {

									?>
									<div class="route-duration">
										<?php
										if ($program){
											//echo "<span id='expand-map' class='header-txt-layer-1 expand-map'><i class='cy-icons icon-expand-alt1'></i></span>";
											echo "<span id='expand-map' class='header-txt-layer-1 expand-map'>". __('Program','wm-child-cyclando')."</span>";
										}
										echo "<span class='header-txt-layer-1 dur-txt'>" .  " $days " . __('days', 'wm-child-cyclando') . " / $nights " . __('nights', 'wm-child-cyclando') . " ";
										if ($distance) {
											echo $distance . "<span style='font-weight:100;'> km</span>";
										}
										echo "</span>";
										?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div id='webmapp-layer-2' class="webmapp-featured-meta">
						<div class="webmapp-featured-meta-info" style="background-image: url('/wp-content/themes/wm-child-cyclando/images/background_menu_route_verde.png')">
							<div class="container">
								<div class="meta-bar show-prices">
									<div id="popup-show-prices" class="popup-show-prices <?php echo $coming_soon_class?>">
											<!-- <div class="meta-bar price-from"> -->
											<?php if (!$coming_soon) {?>
											<div class="prezzo-container">
												<!-- prezzo start-->
												<div class="prezzo">
													<span class='meta-bar-txt-light'><?php echo __('From', 'wm-child-cyclando'); ?></span>
													<p class="cifra <?php if ($in_promotion) { echo 'old-price'; } ?>"><?php
																	$vn_prezzo = get_field('wm_route_price');
																	$lowest_price = explode('€', $vn_prezzo);
																	if ($lowest_price) {
																		echo $lowest_price[0];
																	} else {
																		echo $vn_prezzo;
																	}
																	?>
														€ </span>
														<?php if ($in_promotion) : ?>
															<span class="new-price">
																<?php
																while (have_rows('model_promotion')) : the_row();
																	$discount = get_sub_field('wm_route_quote_model_promotion_discount');
																	if ($lowest_price) {
																		echo $lowest_price[0] - $discount;
																	} else {
																		echo $vn_prezzo - $discount;
																	}
																endwhile;
																?>
																€ </p>
													<?php endif; ?>
												</div>
											</div>
											<div class="first-departure">
												<span class='meta-bar-txt-light'><?php echo __('Next departures', 'wm-child-verdenatura'); ?></span>
												<div class="first-departure-date"><?php echo $first_departure_date; ?></div>
											</div>
											<!--.prezzo  end-->
											<div class="show-price-btn"><i class="cy-icons icon-calendar-alt1"></i></div>
											<?php } else {?>
											<div class="coming-soon">
												<span class='meta-bar-txt-light'><?php echo __('Coming soon!', 'wm-child-cyclando'); ?></span>
											</div>
											<?php } ?>
									</div>
								</div>

								<div class="meta-bar wm-where">
									<i class="cy-icons icon-map-marker-alt1"></i>
									<div class="meta-bar-container">
										<p class='meta-bar-txt-light'>
											<?php
											$parent_id = $tax_places_to_go[0]->parent;
											$parent  = get_term($parent_id)->name;
											echo $parent;
											?>
										</p>
										<p class='meta-bar-txt-bold'>
											<?php
											$places_count = 0;
											$tax_places_to_go_names = array();
											if ($tax_places_to_go){
												foreach ($tax_places_to_go as $tax_place_to_go) {
													array_push($tax_places_to_go_names, $tax_place_to_go->name );
													$places_count++;
												}
												echo $tax_places_to_go[0]->name;
												if ($places_count > 1) {
													echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
													foreach ($tax_places_to_go_names as $name) { echo $name.'<br>'; }
													// foreach (array_slice($tax_places_to_go_names,1) as $name) { echo $name; }
													echo "</span></a>";
												}
											}
											?>
										</p>
									</div>
								</div>
								<div class="meta-bar wm-difficulty">
									<i class="cy-icons icon-tachometer-slow1"></i>
									<div class="meta-bar-container">
										<p class='meta-bar-txt-light'>
											<?php
											echo __('Difficulty', 'wm-child-verdenatura')
											?>
										</p>
										<p class='meta-bar-txt-bold'>
											<?php
											echo $difficulty;
											?>
										</p>
									</div>
								</div>
								<div class="meta-bar wm-activity">
									<i class="<?php echo $iconimage_activity; ?>"></i>
									<div class="meta-bar-container">
										<p class='meta-bar-txt-light'>
											<?php
											echo __('Activity', 'wm-child-verdenatura')
											?>
										</p>
										<p class='meta-bar-txt-bold'>
											<?php
											$places_count = 0;
											$tax_activities_names = array();
											if ($tax_activities){
												foreach ($tax_activities as $tax_activity) {
													array_push($tax_activities_names, $tax_activity->name );
													$places_count++;
												}
												echo $tax_activities[0]->name;
												if ($places_count > 1) {
													echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
													foreach ($tax_activities_names as $name) { echo $name.'<br>'; }
													// foreach (array_slice($tax_places_to_go_names,1) as $name) { echo $name; }
													echo "</span></a>";
												}
											}	
											?>
										</p>
									</div>
								</div>
								<?php if(current_user_can('administrator')) { ?>
									<div id="wm-book-quote" class="meta-bar wm-book long-txt">
										<p class='meta-bar-txt-bold'><?php echo __('Make a quote', 'wm-child-verdenatura'); ?></p>
										<a  target="_blank" href="http://quote.cyclando.com/#/<?php echo $post_id.'?lang='.$language;?>">
										</a>
									</div>
								<?php } else { ?>
									<div id="wm-book" class="meta-bar wm-book">
										<p class='meta-bar-txt-bold'><?php echo __('Book now', 'wm-child-verdenatura'); ?></p>
									</div>
								<?php } ?>
							</div>
						</div>

						


					</div>
				</div> <!-- END div webmapp-layer-container -->
				<!-- HTML modal for prices -->
				<div id="cy-prices-modal" class="cy-prices-modal">
							<div class="cy-modal-content">
								<div class="cy-modal-header">
									<div class="close-button-container"><span class="cy-close">&times;</span></div>
									<div class="vedi-prezzi"><h2>Vedi i prezzi</h2></div>
									<div class="meta-bar wm-activity"><i class="<?php echo $iconimage_activity; ?>"></i></div>
									<?php if(current_user_can('administrator') ) { ?>
									<div id="wm-book-quote" class="meta-bar wm-book long-txt">
										<p class='meta-bar-txt-bold'><?php echo __('Make a quote', 'wm-child-verdenatura'); ?></p>
										<a  target="_blank" href="http://quote.cyclando.com/#/<?php echo $post_id.'?lang='.$language;?>">
										</a>
									</div>
									<?php } else { ?>
										<div id="wm-book" class="meta-bar wm-book">
											<p class='meta-bar-txt-bold'><?php echo __('Book now', 'wm-child-verdenatura'); ?></p>
										</div>
									<?php } ?>
								</div>
								<div class="cy-modal-body">
									<?php echo do_shortcode('[route_table_price]'); ?>
								</div>
								<!-- <div class="cy-modal-footer">
									<h3>Modal Footer</h3>
								</div> -->
							</div>
						</div>
						<!-- END HTML modal for prices -->

						<!-- HTML modal for expand map program -->
						<div id="cy-route-program" class="cy-prices-modal">
							<div class="cy-modal-content">
								<div class="cy-modal-header">
									<span class="cy-close-map">&times;</span>
									<h2><?php echo __('Program', 'wm-child-verdenatura'); ?></h2>
								</div>
								<div class="cy-modal-body">
								<?php if ($program) : ?>
									<div class="">
										<?php
										//echo "<h2>Programma</h2>";
										echo $program;
										?>
									</div>
								<?php endif; ?>
								</div>
							</div>
						</div>
						<!-- END HTML modal for expand map program -->
				<script>
					jQuery(document).ready(function() {
						// Get DOM Elements
						const modal = document.querySelector('#cy-prices-modal');
						const modalBtn = document.querySelector('#popup-show-prices');
						const closeBtn = document.querySelector('.cy-close');
						const fixedAncor = document.querySelectorAll('.fixed-ancor-menu');

						// Get contact elements
						const contactModal = document.querySelector('#cy-route-contact');
						const contactModalBtn = document.querySelectorAll('#wm-book');
						const closeContactBtn = document.querySelector('.cy-close-contact');

						// Get button element inside prices modal
						const contactInsideModal = document.querySelector('#cy-prices-modal #wm-book');

						// Get MAP elements
						const programModal = document.querySelector('#cy-route-program');
						const expandMapBtn = document.querySelectorAll('#expand-map');
						const closeMapBtn = document.querySelector('.cy-close-map');

						// Events expand map program Modal
						expandMapBtn.forEach((button) => {
							button.addEventListener('click', openProgramModal);
						});
						closeMapBtn.addEventListener('click', closeProgramModal);

						// Events modal prices
						modalBtn.addEventListener('click', openModal);
						closeBtn.addEventListener('click', closeModal);
						window.addEventListener('click', outsideClick);
						// fixedAncor.addEventListener('click', scrollOffset);

						// Events contactModal
						contactModalBtn.forEach((button) => {
							button.addEventListener('click', openContactModal);
						});
						closeContactBtn.addEventListener('click', closeContactModal);
						// contactInsideModal.addEventListener('click', closeModalOpenContact);

						// Open modal prices
						function openModal() {
							modal.style.display = 'block';
						}
						// Open contact modal
						function openContactModal() {
							modal.style.display = 'none';
							contactModal.style.display = 'block';
						}

						// Open program modal
						function openProgramModal() {
							programModal.style.display = 'block';
						}

						// Close modal prices
						function closeModal() {
							modal.style.display = 'none';
						}

						// Close contact modal
						function closeContactModal() {
							contactModal.style.display = 'none';
						}

						// Close program modal
						function closeProgramModal() {
							programModal.style.display = 'none';
						}

						// Close If Outside Click
						function outsideClick(e) {
							if (e.target == modal) {
								modal.style.display = 'none';
							}
							if (e.target == contactModal) {
								contactModal.style.display = 'none';
							}
							if (e.target == programModal) {
								programModal.style.display = 'none';
							}
						}

						// Close prices modal and open contact modal
						function closeModalOpenContact() {
							console.log('vai');
							modal.style.display = 'none';
							contactModal.style.display = 'block';
						}
					});
					// 			jQuery(document).ready(function(){
					// 	jQuery( "a.fixed-ancor-menu" ).click(function( event ) {
					// 		event.preventDefault();
					// 		jQuery("html, body").animate({ scrollTop: jQuery(jQuery(this).attr("href")).offset().top-200 }, 500);
					// 	});
					// });
				</script>
				<?php

				do_action('us_after_header');
			} ?>