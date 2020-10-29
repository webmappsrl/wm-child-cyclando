<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header();

wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
wp_enqueue_script('route-single-post-style-animation', get_stylesheet_directory_uri() . '/jquery/child-main.js', array('jquery'));
?>
<main id="page-content" class="l-main cyc-single-route-main-container" <?php echo (us_get_option('schema_markup')) ? ' itemprop="mainContentOfPage"' : ''; ?>>
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
		if (defined('ICL_LANGUAGE_CODE')) {
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
		if ($touroperator_id_array) {
			$touroperator_id = $touroperator_id_array[0];
			$touroperator = get_the_title($touroperator_id);
		}
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
		if ($tax_activities)
			foreach ($tax_activities as $tax_activity) {
				array_push($tax_activities_slug, $tax_activity->slug);
			}
		$tax_targets_slug = array();
		if ($tax_targets)
			foreach ($tax_targets as $tax_target) {
				array_push($tax_targets_slug, $tax_target->slug);
			}
		$tax_places_to_go_slug = array();
		if ($tax_places_to_go)
			foreach ($tax_places_to_go as $tax_place) {
				array_push($tax_places_to_go_slug, $tax_place->slug);
			}


		// Import from header
		// variable and queries
		$days = (int)get_field('vn_durata');
		$distance = get_field('distance');
		$difficulty = get_field('n7webmapp_route_difficulty');
		$difficulty = str_replace('.', ',', $difficulty);
		$nights = $days - 1;
		$target = 'who';
		$places_to_go = 'where';
		$activity = 'activity';
		$scheda_tecnica = get_field('vn_scheda_tecnica');
		$program = get_field('vn_prog');
		$touroperator_id_array = get_field('tour_operator');
		$coming_soon = get_field('not_salable');
		if ($coming_soon) {
			// get the route price and if its 9.99 set it to 9999
			$price = get_field('wm_route_price', $post_id);
			if ($price == null || $price == '9.99') {
				update_field('wm_route_price', '9999', $post_id);
			}
		}
		$popup_show_prices_class = 'popup-show-prices';
		if ($coming_soon && !return_route_targets_has_cyclando($post_id)) {
			$coming_soon_class = 'coming-soon-button';
		} elseif (return_route_targets_has_cyclando($post_id)) {
			$coming_soon_class = 'download-app-button';
			$popup_show_prices_class = '';
		}
		$has_track = get_field("n7webmap_route_related_track", $post_id);
		$headers = get_headers('https://a.webmapp.it/cyclando.com/route/{$post_id}_map_1000x1000.png', 1);
		$interactive_route_map = "https://a.webmapp.it/cyclando.com/route/{$post_id}_map_1000x1000.png";
		if ($has_track && get_option('webmapp_show_interactive_route_map') && strpos($headers['Content-Type'], 'image/') !== false) {
			$featured_map = $interactive_route_map;
		} else {
			$featured_map = '/wp-content/themes/wm-child-cyclando/images/map-logo-osm.jpg';
		}
		if (defined('ICL_LANGUAGE_CODE')) {
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
		if (have_rows('departures_periods', get_the_ID())) {
			$dates = array();
			while (have_rows('departures_periods')) : the_row();
				$sta = get_sub_field('start');
				$sto = get_sub_field('stop');
				$w_d = get_sub_field('week_days');
				$d_o_w = new DaysOfWeek($sta, $sto);
				$w_d_int = wm_weekDayToWeekNumber($w_d);
				$dates = array_merge($dates, $d_o_w->query_byDayOfWeek($w_d_int, 'none'));
			endwhile;
			$dates = array_unique($dates, SORT_REGULAR);
			foreach ($dates as $date) {
				$d = $date->format('d-m-Y');
				array_push($start_array, $d);
			}
		}

		if (have_rows('departure_dates', get_the_ID())) {
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
			if (date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date))) {
				$first_departure_date = date_i18n('d F', strtotime($date));
				break;
			}
		}

		// get the route price
		$price = get_field('wm_route_price');
		$price = (float)$price;

		// get the post promotion name and value
		$promotion_name = get_field('promotion_name', $post_id);
		$promotion_value = get_field('promotion_value', $post_id);
		if ($promotion_value) {
			$promotion_price = intval($price) - intval($promotion_value);
		}
		$home_site = home_url();
		$home_site = str_replace("https://", "", $home_site);

		$route_has_geojson = URL_exists("https://a.webmapp.it/cyclando.com/geojson/$post_id.geojson");
	?>

		<!-- Start new template -->
		<!-- Start section introduction  -->
		<section class="l-section wpb_row height_auto cyc-single-route-introduction-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-12 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper cyc-single-route-breadcrumb-wrapper">
										<div class="breadcrumb-rankmath"><?php echo do_shortcode('[rank_math_breadcrumb]'); ?></div>
									</div>
								</div>

								<div class="wpb_text_column ">
									<div class="wpb_wrapper">
										<h1 class=""><?php the_title() ?></h1>
									</div>
								</div>
								<div class="g-cols wpb_row  type_default valign_top vc_inner">
									<div class="vc_col-sm-10 wpb_column vc_column_container">
										<div class="vc_column-inner">
											<div class="wpb_wrapper">
												<div class="wpb_text_column">
													<div class="wpb_wrapper cyc-single-route-intro-info-wrapper">
														<?php
														if ($days) {
															echo "<span class=''>" .  " $days " . __('days', 'wm-child-cyclando') . " / </span>";
														}
														if ($distance) {
															echo $distance . "<span class=''> km</span>";
														}
														if ($price) {
															echo "<span class=''> / da $price €</span>";
														}
														?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="vc_col-sm-2 wpb_column vc_column_container">
										<div class="vc_column-inner">
											<div class="wpb_wrapper">
												<div class="wpb_text_column">
													<div class="wpb_wrapper">
														<p id="cyc-single-route-monarch-share-button" class="cyc-single-route-monarch-share"><i class="wm-icon-cyc-share"></i><span><?php echo __('Share', 'wm-child-cyclando'); ?></span></p>

													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>

		<!-- END section introduction END  -->
		<!-- START section Gallery and Information block START  -->
		<section class="l-section wpb_row height_small cyc-single-route-gallery-information-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper">
										<?php if ($gallery_ids) {
										echo do_shortcode('[us_image_slider ids="' . implode(',', $gallery_ids) . '" fullscreen="1" img_size="large" img_fit="cover" arrows="hide" nav="dots"]');
										} ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="vc_col-sm-3 wpb_column vc_column_container">
						<div class="vc_column-inner us_custom_25707306 cyc-single-route-info-block-column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper">
										<p><?php echo __('Your vacation in', 'wm-child-cyclando'); ?></p>
										<div class="meta-bar wm-where">
											<i class="cy-icons icon-map-marker-alt1"></i>
											<div class="meta-bar-container">
												<p class='meta-bar-txt-light'>
													<?php
													$parent_id = $tax_places_to_go[0]->parent;
													$parent = '';
													if ($parent_id) {
														$parent  = get_term($parent_id)->name;
													}
													if ($parent) {
														echo $parent;
													}
													?>
												</p>
												<p class='meta-bar-txt-bold'>
													<?php
													$places_count = 0;
													$tax_places_to_go_names = array();
													if ($tax_places_to_go) {
														foreach ($tax_places_to_go as $tax_place_to_go) {
															array_push($tax_places_to_go_names, $tax_place_to_go->name);
															$places_count++;
														}
														echo $tax_places_to_go[0]->name;
														if ($places_count > 1) {
															echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
															foreach ($tax_places_to_go_names as $name) {
																echo $name . '<br>';
															}
															// foreach (array_slice($tax_places_to_go_names,1) as $name) { echo $name; }
															echo "</span></a>";
														}
													}
													?>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END section Gallery and Information block END  -->
		<!-- START section Second menu START  -->
		<section class="l-section wpb_row height_auto cyc-single-route-second-menu-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper cyc-single-route-second-menu-wrapper">
										<div><h4><?php echo __('Plan your trip', 'wm-child-cyclando'); ?></h4></div>
										<div class="cyc-sr-sm-items">
											<?php if ($program or (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) {
												echo "<h4 id='expand-map'>" . __('Program', 'wm-child-cyclando') . "</h4>";
											} ?>
										</div>
										<div id="<?php echo $popup_show_prices_class ?>" class="cyc-sr-sm-items <?php echo $coming_soon_class ?>">
											<?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
												<h4 class="prezzo-container">
													<?php echo __('Dates & prices', 'wm-child-cyclando') ?>
												</h4>
											<?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
												<a class="download-app-link" target="_blank" href="https://info.cyclando.com/app">
													<div class="scarica-app">
														<h4 class='meta-bar-txt-light'><?php echo __('Download', 'wm-child-cyclando'); ?></h4>
													</div>
												</a>
											<?php } else { ?>
												<div class="coming-soon">
													<h4 class='meta-bar-txt-light'><?php echo __('Coming soon!', 'wm-child-cyclando'); ?></h4>
												</div>
											<?php } ?>
										</div>
										<div class="cyc-sr-sm-items">
											<?php if ($program or (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) {
												echo "<h4 id='expand-map'>" . __('Map', 'wm-child-cyclando') . "</h4>";
											} ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END section Second menu block END  -->
		<!-- START section Description START  -->
		<section class="l-section wpb_row height_small cyc-single-route-description-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper cyc-single-route-description-wrapper">
										<?php
										echo "<p class='route-excerpt'>" . get_the_excerpt() . "</p>";
										echo get_the_content();
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END section Description block END  -->
		<!-- START section Description START  -->
		<?php if ($touroperator) : ?>
		<section class="l-section wpb_row height_small cyc-single-route-tour-operator-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper cyc-single-route-tour-operator-wrapper">
										<?php
										echo "<h4> " .__('Tour operator', 'wm-child-cyclando'). "</h4>" . "<p>" . $touroperator . "</p>";
										?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php endif; ?>
		<!-- END section Description block END  -->
		<!-- START section call to action sei pronto START  -->
		<section class="l-section wpb_row height_small cyc-single-route-cta-container">
			<div class="l-section-h i-cf">
				<div class="g-cols vc_row type_default valign_top">
					<div class="vc_col-sm-9 wpb_column vc_column_container">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<div class="wpb_text_column">
									<div class="wpb_wrapper cyc-single-route-cta-wrapper">
										<div class="cyc-single-route-cta-text">
											<h2><?php echo __('Are you ready to leave for your next vacation?', 'wm-child-cyclando'); ?></h2>
											<p><?php echo __('Personalize your vacation or just ask us any question.', 'wm-child-cyclando'); ?></p>
										</div>
										<div class="cyc-single-route-cta-buttons">
											<div id="cy-contact-in-basso" class="">
												<div class="cy-btn-contact">
													<p><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
												</div>
											</div>
											<?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
											<a target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
												<div id="wm-book-quote" class="cy-btn-quote">
													<p><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
												</div>
											</a>
											<?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
											<a class="download-app-link" target="_blank" href="https://info.cyclando.com/app">
												<div class="cy-btn-quote">
													<p class=''><?php echo __('Download', 'wm-child-cyclando'); ?></p>
												</div>
											</a>
											<?php } else { ?>
											<div class="cy-btn-quote">
												<p class=''><?php echo __('Coming soon!', 'wm-child-cyclando'); ?></p>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- END section call to action sei pronto block END  -->
		<!-- END new template END-->

		<!-- import html header start -->
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
										if (strpos($iconimage, 'wm-icon-') !== false) {
											echo "<i class='$iconimage'></i>" . $tax_target->name;
										} else {
											echo "<i class='cy-icons icon-" . $iconimage . "'></i>" . $tax_target->name;
										}
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
				<div <?php if ($program || (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) {
							echo 'id="expand-map"';
						} else {
							echo '';
						} ?> class="webmapp-featured-map" style="background-image: url('<?php echo $featured_map; ?>')">
					<div class="container">
						<?php
						if ($days) {

						?>
							<div class="route-duration">
								<?php
								if ($program or (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) {
									//echo "<span id='expand-map' class='header-txt-layer-1 expand-map'><i class='cy-icons icon-expand-alt1'></i></span>";
									echo "<span id='expand-map' class='header-txt-layer-1 expand-map'>" . __('Program', 'wm-child-cyclando') . "</span>";
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
							<div id="<?php echo $popup_show_prices_class ?>" class="popup-show-prices <?php echo $coming_soon_class ?>">
								<!-- <div class="meta-bar price-from"> -->
								<?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
									<!-- <a  target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>"></a> -->
									<div class="prezzo-container">
										<!-- prezzo start-->
										<div class="prezzo">
											<?php if ($promotion_value) { ?>
												<p class='meta-bar-txt-light'><span class='old-price'><?php echo $price . ' €'; ?></span></p>
											<?php } else { ?>
												<p class='meta-bar-txt-light'><?php echo __('From', 'wm-child-cyclando'); ?></p>
											<?php } ?>
											<p class="cifra"><?php
																if ($promotion_value) {
																	echo $promotion_price . ' €';
																} else {
																	echo $price . ' €';
																}
																?>
											</p>
										</div>
									</div>
									<div class="first-departure">
										<span class='meta-bar-txt-light'><?php echo __('Next departures', 'wm-child-cyclando'); ?></span>
										<div class="first-departure-date"><?php echo $first_departure_date; ?></div>
									</div>
									<!--.prezzo  end-->
									<div class="show-price-btn"><i class="cy-icons icon-calendar-alt1"></i></div>
								<?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
									<a class="download-app-link" target="_blank" href="https://info.cyclando.com/app">
										<div class="scarica-app">
											<span class='meta-bar-txt-light'><?php echo __('Download', 'wm-child-cyclando'); ?></span>
										</div>
									</a>
								<?php } else { ?>
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
									$parent = '';
									if ($parent_id) {
										$parent  = get_term($parent_id)->name;
									}
									if ($parent) {
										echo $parent;
									}
									?>
								</p>
								<p class='meta-bar-txt-bold'>
									<?php
									$places_count = 0;
									$tax_places_to_go_names = array();
									if ($tax_places_to_go) {
										foreach ($tax_places_to_go as $tax_place_to_go) {
											array_push($tax_places_to_go_names, $tax_place_to_go->name);
											$places_count++;
										}
										echo $tax_places_to_go[0]->name;
										if ($places_count > 1) {
											echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
											foreach ($tax_places_to_go_names as $name) {
												echo $name . '<br>';
											}
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
									echo __('Difficulty', 'wm-child-cyclando')
									?>
								</p>
								<p class='meta-bar-txt-bold'>
									<?php
									echo $difficulty . " " . __('from 5', 'wm-child-cyclando');
									?>
								</p>
							</div>
						</div>
						<div class="meta-bar wm-activity">
							<i class="<?php echo $iconimage_activity; ?>"></i>
							<div class="meta-bar-container">
								<p class='meta-bar-txt-light'>
									<?php
									echo __('Activity', 'wm-child-cyclando')
									?>
								</p>
								<p class='meta-bar-txt-bold'>
									<?php
									$activity_count = 0;
									$tax_activities_names = array();
									if ($tax_activities) {
										foreach ($tax_activities as $tax_activity) {
											array_push($tax_activities_names, $tax_activity->name);
											$activity_count++;
										}
										echo $tax_activities[0]->name;
										if ($activity_count > 1) {
											echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
											foreach ($tax_activities_names as $name) {
												echo $name . '<br>';
											}
											// foreach (array_slice($tax_places_to_go_names,1) as $name) { echo $name; }
											echo "</span></a>";
										}
									}
									?>
								</p>
							</div>
						</div>
						<?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
							<div id="wm-book-quote" class="meta-bar wm-book long-txt">
								<p class='meta-bar-txt-bold'><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
								<a target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
								</a>
							</div>
						<?php } else { ?>
							<div id="cy-contact-in-alto" class="meta-bar wm-book long-txt">
								<p id="cy-contact-in-alto-text" class='meta-bar-txt-bold'><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div> <!-- END div webmapp-layer-container -->
		<?php if (current_user_can('administrator') && get_option('webmapp_show_interactive_route_map')) { ?>
			<div id="wm-wizards-container">
				<?php
				echo do_shortcode("[wmWizards conf='']");
				?>
			</div>
		<?php } ?>
		<!-- HTML modal for prices -->
		<div id="cy-prices-modal" class="cy-prices-modal">
			<div class="cy-modal-content">
				<div class="cy-modal-header">
					<div class="close-button-container"><span class="cy-close">&times;</span></div>
					<div class="vedi-prezzi">
						<h2>Vedi i prezzi</h2>
					</div>
					<div class="meta-bar wm-activity"><i class="<?php echo $iconimage_activity; ?>"></i></div>
					<div id="wm-book-quote" class="meta-bar wm-book long-txt">
						<p class='meta-bar-txt-bold'><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
						<a target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
						</a>
					</div>
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
					<div class="close-button-container"><span class="cy-close-map">&times;</span></div>
					<div class="route-program">
						<h2><?php echo __('Program', 'wm-child-cyclando'); ?></h2>
					</div>
					<?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
						<div id="wm-book-quote" class="meta-bar wm-book long-txt">
							<p class='meta-bar-txt-bold'><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
							<a target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
							</a>
						</div>
					<?php } else { ?>
						<div id="cy-contact-modal" class="meta-bar wm-book long-txt">
							<p id="cy-contact-modal-text" class='meta-bar-txt-bold'><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
						</div>
					<?php } ?>
				</div>
				<?php if ($program && !get_option('webmapp_show_interactive_route_map')) : ?>
					<div class="cy-modal-body cy-modal-body-program">
						<?php
						echo $program;
						?>
					</div>
				<?php
				elseif (!$has_track && get_option('webmapp_show_interactive_route_map')) :
				?>
					<div class="cy-modal-body cy-modal-body-program">
						<?php
						echo $program;
						?>
					</div>
				<?php

				elseif ($route_has_geojson == false) :
				?>
					<div class="cy-modal-body cy-modal-body-program">
						<?php
						echo $program;
						?>
					</div>
				<?php

				elseif (get_option('webmapp_show_interactive_route_map')) :
					echo '<div class="cy-modal-body cy-modal-body-map">';
					echo do_shortcode('[wm-embedmaps feature_color="#F18E08" color="#9AC250" route="https://a.webmapp.it/' . $home_site . '\/geojson/' . $post_id . '.geojson" height="100%" lang="it"]');
					echo '</div>';
				endif;

				?>
			</div>
		</div>
		<!-- END HTML modal for expand map program -->
		<!-- import html header END -->
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
								<?php if ($gallery_ids) {
									echo do_shortcode('[us_image_slider ids="' . implode(',', $gallery_ids) . '" fullscreen="1" img_size="large" img_fit="cover"]');
								} ?>
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
									<?php if ($program || (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) : ?>
										<div class="cy-route-body-button cy-route-body-programbutton">
											<?php
											echo "<span id='expand-map' class='header-txt-layer-1 expand-map-content'>" . __('Program', 'wm-child-cyclando') . "</span>";
											?>
										</div>
									<?php endif; ?>
									<div id="cy-contact-in-basso" class="cy-route-body-button cy-route-body-contactus expand-map-content">
										<div class="meta-bar long-txt">
											<p id="cy-contact-in-basso-text" class='meta-bar-txt-bold'><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
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
											echo "<h2>" . __('Features', 'wm-child-cyclando') . "</h2>";
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
					<div class="route-contact">
						<h2><?php echo __('Contact us', 'wm-child-cyclando'); ?></h2>
					</div>
				</div>
				<div class="cy-modal-body">
					<?php //echo do_shortcode('[contact-form-7 id="54052" title="Contact form route"]'); 
					?>
					<script>
						hbspt.forms.create({
							portalId: "6554435",
							formId: "369b0992-8548-4163-9eb9-a0029e90e1dd",
							onFormReady: function($form, ctx) {
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="activities"]').setAttribute('value', '<?php echo implode(";", $tax_activities_slug);  ?>')
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="target"]').setAttribute('value', '<?php echo implode(";", $tax_targets_slug);  ?>')
								window['hs-form-iframe-0'].contentDocument.querySelector('input[name="place_to_go"]').setAttribute('value', '<?php echo implode(";", $tax_places_to_go_slug);  ?>')
							},
							onFormSubmit: function($form) {
								window.dataLayer = window.dataLayer || [];
								window.dataLayer.push({

									'event': 'formInviato',
								});
							}
						});

						jQuery(document).ready(function() {
							setTimeout(function(){ jQuery(".cyc-single-route-main-container .rsFullscreenIcn").html("<span class='gallery-expand-desktop'>Guarda tutte le foto</span><span class='gallery-expand-mobile'><i class='fas fa-expand'></i></span>");
							console.log("foto"); }, 400);
							
							// Get DOM Elements
							const modal = document.querySelector('#cy-prices-modal');
							const modalBtn = document.querySelector('#popup-show-prices');
							const closeBtn = document.querySelector('.cy-close');
							const fixedAncor = document.querySelectorAll('.fixed-ancor-menu');
							const bodyDiv = document.querySelector('body');

							// Get contact elements
							const contactModal = document.querySelector('#cy-route-contact');
							const contactModalBtnAlto = document.querySelectorAll('#cy-contact-in-alto');
							const contactModalBtnBasso = document.querySelectorAll('#cy-contact-in-basso');
							const contactModalBtnBassoMobile = document.querySelectorAll('#cy-contact-in-basso-mobile');
							const contactModalBtn = document.querySelectorAll('#cy-contact-modal');
							const closeContactBtn = document.querySelector('.cy-close-contact');

							// Get button element inside prices modal
							// const contactInsideModal = document.querySelector('#cy-prices-modal #wm-book');

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
							if (modalBtn) {
								modalBtn.addEventListener('click', openModal);
							}
							if (closeBtn) {
								closeBtn.addEventListener('click', closeModal);
							}
							window.addEventListener('click', outsideClick);
							// fixedAncor.addEventListener('click', scrollOffset);

							// Events contactModal
							contactModalBtn.forEach((button) => {
								button.addEventListener('click', openContactModal);
							});
							contactModalBtnAlto.forEach((button) => {
								button.addEventListener('click', openContactModal);
							});
							contactModalBtnBasso.forEach((button) => {
								button.addEventListener('click', openContactModal);
							});
							contactModalBtnBassoMobile.forEach((button) => {
								button.addEventListener('click', openContactModal);
							});
							closeContactBtn.addEventListener('click', closeContactModal);
							// contactInsideModal.addEventListener('click', closeModalOpenContact);

							// Open modal prices
							function openModal() {
								modal.style.display = 'block';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "hidden";
							}
							// Open contact modal
							function openContactModal() {
								modal.style.display = 'none';
								programModal.style.display = 'none';
								contactModal.style.display = 'block';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "hidden";
							}

							// Open program modal
							function openProgramModal() {
								programModal.style.display = 'block';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "hidden";
							}

							// Close modal prices
							function closeModal() {
								modal.style.display = 'none';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "auto";
							}

							// Close contact modal
							function closeContactModal() {
								contactModal.style.display = 'none';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "auto";
							}

							// Close program modal
							function closeProgramModal() {
								programModal.style.display = 'none';
								// add over flow hidden to cody to stop scroll
								bodyDiv.style.overflow = "auto";
							}

							// Close If Outside Click
							function outsideClick(e) {
								if (e.target == modal) {
									modal.style.display = 'none';
									// add over flow hidden to cody to stop scroll
									bodyDiv.style.overflow = "auto";
								}
								if (e.target == contactModal) {
									contactModal.style.display = 'none';
									// add over flow hidden to cody to stop scroll
									bodyDiv.style.overflow = "auto";
								}
								if (e.target == programModal) {
									programModal.style.display = 'none';
									// add over flow hidden to cody to stop scroll
									bodyDiv.style.overflow = "auto";
								}
							}

							// Close prices modal and open contact modal
							function closeModalOpenContact() {
								modal.style.display = 'none';
								contactModal.style.display = 'block';
							}


							// MONARCH interaction with Share Condividi button
                
							jQuery( '.et_social_sidebar_networks' ).removeClass( 'et_social_visible_sidebar' );
							jQuery( '.et_social_sidebar_networks' ).addClass( 'et_social_hidden_sidebar' );
							jQuery( '.et_social_sidebar_networks' ).show(2000);

							jQuery( '#cyc-single-route-monarch-share-button' ).click( function(){
								jQuery( '.et_social_hide_sidebar' ).toggleClass( 'et_social_hidden_sidebar' );
								jQuery( '.et_social_sidebar_networks' ).toggleClass( 'et_social_hidden_sidebar et_social_visible_sidebar' );
							});

							
						});
						// 			jQuery(document).ready(function(){
						// 	jQuery( "a.fixed-ancor-menu" ).click(function( event ) {
						// 		event.preventDefault();
						// 		jQuery("html, body").animate({ scrollTop: jQuery(jQuery(this).attr("href")).offset().top-200 }, 500);
						// 	});
						// });
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