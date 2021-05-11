<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header();

wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
wp_enqueue_script('route-single-post-style-animation', get_stylesheet_directory_uri() . '/jquery/child-main.js', array('jquery'));
?>
<main id="page-content" class="l-main cyc-single-route-main-container"
    <?php echo (us_get_option('schema_markup')) ? ' itemprop="mainContentOfPage"' : ''; ?>>
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
        $route_title = get_the_title($post_id);
        $wm_post_id = wm_get_original_post_it($post_id);
        $wm_post_id = $wm_post_id['id'];
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
        if ($days && $days == 1){
            $days_info = '<span>'.$days.' '.__('day', 'wm-child-cyclando').'</span>';
        } else {
            $days_info = '<span>'.$days.' '.__('days', 'wm-child-cyclando').'</span>';
        }
        $distance = get_field('distance');
        if ($distance) {
            $distance_info =  '<span>'.$distance.' '.__('km', 'wm-child-cyclando').'</span>';
        }
		$difficulty = get_field('n7webmapp_route_difficulty');
        $difficulty = str_replace('.', ',', $difficulty);
        $shape = get_field('shape');

		$nights = $days - 1;
		$target = 'who';
		$places_to_go = 'where';
		$activity = 'activity';
		$scheda_tecnica = get_field('vn_scheda_tecnica');
		$program = get_field('vn_prog');
		$touroperator_id_array = get_field('tour_operator');
		$coming_soon = get_field('not_salable');
		$popup_show_prices_class = 'popup-show-prices';
		if ($coming_soon && !return_route_targets_has_cyclando($post_id)) {
			$coming_soon_class = 'coming-soon-button';
		} elseif (return_route_targets_has_cyclando($post_id)) {
			$coming_soon_class = 'download-app-button';
			$popup_show_prices_class = '';
		}
		$has_track = get_field("n7webmap_route_related_track", $post_id);
        $has_track_program = false;
        if (is_array($has_track) && $has_track) {
            $has_track_program = true;
        }
		$headers = get_headers("https://a.webmapp.it/cyclando.com/route/{$post_id}_map_1000x1000.png", 1);
		$interactive_route_map = "https://a.webmapp.it/cyclando.com/route/{$post_id}_map_1000x1000.png";
		if ($has_track && get_option('webmapp_show_interactive_route_map') && strpos($headers['Content-Type'], 'image/') !== false) {
			$featured_map = $interactive_route_map;
		} else {
			$featured_map = '/wp-content/themes/wm-child-cyclando/images/map-logo-osm.jpg';
		}
		$first_departure_date = '';
		$first_departure_date_ajax = '';
		$first_departure_date_ajax_dormatdmY = '';
		// get terms targets
        $tax_targets = get_the_terms($post_id, $target);
        foreach ($tax_targets as $tax_target) {
            $get_term_target = get_term_by('slug', $tax_target->slug, $target);
            $term_target = 'term_' . $get_term_target->term_id;
            $array_target[$tax_target->name] = get_field('wm_taxonomy_icon', $term_target);
        }

        // get places to go
		$tax_places_to_go = get_the_terms($post_id, $places_to_go);
        $places_to_go_list = array();
        if ($tax_places_to_go) {
            foreach ($tax_places_to_go as $place) {
                if ($place->parent !== 0) {
                    $city = $place->name;
                    array_push($places_to_go_list,$city);
                    $nation  = get_term($place->parent)->name;
                    array_push($places_to_go_list,$nation);
                }
            }
        }
        $places_to_go_list = array_unique($places_to_go_list);

        $tax_activities = get_the_terms($post_id, $activity);
        foreach ($tax_activities as $tax_activity) {
            $get_term_activity = get_term_by('slug', $tax_activity->slug, $activity);
            $term_activity = 'term_' . $get_term_activity->term_id;
            $array_activity[$tax_activity->name] = get_field('wm_taxonomy_icon', $term_activity);
        }

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
        $start_arraydFY = array();
        $start_arrayYmd = array();
		foreach ($start_array as $date) { 
            $start_arraydFY[] = date_i18n('d F Y', strtotime($date));
            $start_arrayYmd[] = date_i18n('Y-n-d', strtotime($date));
        }
		foreach ($start_array as $date) {
			if (date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date))) {
				$first_departure_date = date_i18n('d F', strtotime($date));
				$first_departure_date_ajax = date_i18n('d F Y', strtotime($date));
				$first_departure_date_ajax_dormatdmY = date_i18n('d-m-Y', strtotime($date));
				break;
			}
		}

		// get the route price
		$price = get_field('wm_route_price');
        $price = (float)$price;
        if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) {
            if ($price) {
                $price_info = '<span>'.__('from', 'wm-child-cyclando').' '.$price.' '.__('€', 'wm-child-cyclando').'</span>';
            }
        } elseif (return_route_targets_has_cyclando($post_id)) { 

        } else { 
            $price_info = '<span>'.__('On Request', 'wm-child-cyclando').'</span>';
        }

		// get the post promotion name and value
		$promotion_name = get_field('promotion_name', $post_id);
		$promotion_value = get_field('promotion_value', $post_id);
		if ($promotion_value) {
			$promotion_price = intval($price) - intval($promotion_value);
		}
		$home_site = home_url();
        $home_site = preg_replace("/https?:\/\//", "", $home_site);
        if ($language == 'en') {
            $home_site = str_replace("/en/", "", $home_site);
        }

        $route_has_geojson = URL_exists("https://a.webmapp.it/cyclando.com/geojson/$wm_post_id.geojson");
        $featured_image = get_the_post_thumbnail_url($post_id,'us_600_0');
        if (! $featured_image) {
            $featured_image = get_the_post_thumbnail_url($post_id,'large');
            if (!$featured_image) {
                $featured_image = wp_get_attachment_image_src($gallery_ids[0],'us_600_0');
                $featured_image = $featured_image[0];
            }
        }

        // get the extra fields for extra popup 
        $has_extra = route_has_extra_category($wm_post_id);
        if ($has_extra['bike']) {
            unset($has_extra['bike']);
        }
        if ($has_extra['ebike']) {
            unset($has_extra['ebike']);
        }
        $hotel_product_items = array();
        $has_hotel_category = route_has_hotel_category($post_id,$first_departure_date_ajax_dormatdmY);
        if (count($has_hotel_category['modelseasonal']) >= 1) {
            $hotel_product_items = $has_hotel_category['modelseasonal'][array_key_first($has_hotel_category['modelseasonal'])];
        } else {
            $hotel_product_items = $has_hotel_category['model'][array_key_first($has_hotel_category['model'])];
        }
        $products_to_remove = array('adult' ,'adult-single','single-traveller');
        if ($hotel_product_items) {
            foreach ($hotel_product_items as $product => $val) {
                if (in_array($product,$products_to_remove)) {
                    unset($hotel_product_items[$product]);
                } 
                if (strpos($product,'kid') !== false) {
                    unset($hotel_product_items[$product]);
                }
            }
        }
        if (empty($hotel_product_items)) {
            $json_hotel_product_items = false;
        } else {
            $json_hotel_product_items = true;
        }
	?>

    <!-- Start new template -->
    <!-- Start section introduction and gallery -->
    <section class="l-section wpb_row height_auto cyc-single-route-introduction-container cyc-route-introduction-mobile" style="background-image:url(<?= $featured_image ?>);">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-12 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper cyc-route-mobile-introduction-wrapper">
                            <div class="cyc-route-mobile-introduction-days">
                                <p><?= ($days) ? $days_info : '' ?></p>
                                <p><?= ($distance) ? $distance_info : '' ?></p>
                            </div>
                            <div class="cyc-single-route-breadcrumb-wrapper">
                                <div class="breadcrumb-rankmath">
                                    <?php echo do_shortcode('[rank_math_breadcrumb]'); ?></div>
                            </div>
                            <div class="cyc-route-mobile-introduction-title">
                                <?php 
                                    if ($places_to_go_list) {
                                        foreach ($places_to_go_list as $count => $place) {
                                            if ($count == 0) {
                                                echo "<span class='meta-txt-strong'>$place</span>";
                                            } elseif ($count == 1) {
                                                echo "<span class='meta-txt-light'>, $place</span>";
                                            } 
                                        }
                                        if (count($places_to_go_list) >= 3) {
                                            echo "<a class='show-more-places tooltips' href='#!'> ... <span>";
                                            foreach ($places_to_go_list as $count => $name) {
                                                if ($count >= 2) {
                                                    echo $name . '<br>';
                                                }
                                            }
                                            echo "</span></a>";
                                        }
                                    }
                                ?>
                                <h1 class=""><?php the_title() ?></h1>
                            </div>

                            <div class="cyc-route-mobile-introduction-icons">
                                <p id="cyc-single-route-mobile-gallery-button" class="cyc-single-route-monarch-share cyc-single-route-gallery-btn">
                                    <i class="wm-icon-cyc_gallery"></i>
                                </p>
                                <p id="cyc-single-route-monarch-share-button" class="cyc-single-route-monarch-share">
                                    <i class="material-icons">ios_share</i>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- END section introduction andgalery END  -->
    <div class="cyc-route-taxonomy-tab-row-container">
        <!-- START section taxonomies and difficulty block START  -->
        <div class="cyc-route-mobile-taxonomy-container">
            <div class="cyc-route-taxonomy-row-wrapper">
                <div class="cyc-route-mobile-taxonomy-activity-wrapper">
                    <p class='meta-bar-txt-strong'>
                        <?php
                        echo __('Activity', 'wm-child-cyclando')
                        ?>
                    </p>
                    <?php if ($array_activity) { 
                        foreach ($array_activity as $activity => $icon) { ?>
                            <div class="meta-bar-taxonomy-container">
                                <p class='meta-bar-txt-light'>
                                    <?php
                                    echo $activity;
                                    ?>
                                </p>
                            </div>
                    <?php }
                    } ?>
                </div>
                <div class="cyc-route-mobile-taxonomy-target-wrapper">
                    <p class='meta-bar-txt-strong'>
                        <?php
                        echo __('Target', 'wm-child-cyclando')
                        ?>
                    </p>
                    <?php if ($array_target) { 
                        foreach ($array_target as $target => $icon) { ?>
                            <div class="meta-bar-taxonomy-container">
                                <p class='meta-bar-txt-light'>
                                    <?php
                                    echo $target;
                                    ?>
                                </p>
                            </div>
                    <?php }
                    } ?>
                </div>
                <div class="cyc-route-mobile-taxonomy-shape-wrapper">
                    <p class='meta-bar-txt-strong'>
                        <?php
                        echo __('Path', 'wm-child-cyclando')
                        ?>
                    </p>
                    <?php if ($shape) { ?>
                            <div class="meta-bar-taxonomy-container">
                                <p class='meta-bar-txt-light'>
                                    <?php
                                    $title_path = $array = [
                                        'daisy' => 'A margherita',
                                        'linear' => 'Lineare',
                                        'roundtrip' => 'Ad anello'
                                    ];
                                    if ($language == 'it') {
                                        echo __($title_path[$shape], "wm-child-cyclando");
                                    } else {
                                        echo __($shape, "wm-child-cyclando");
                                    }
                                    ?>
                                </p>
                            </div>
                    <?php 
                    } ?>
                </div>
                <div class="cyc-route-mobile-taxonomy-difficulty-wrapper">
                    <p class='meta-bar-txt-strong'>
                        <?php
                        echo __('Difficulty', 'wm-child-cyclando')
                        ?>
                    </p>
                    <?php if ($difficulty) { ?>
                            <div class="meta-bar-taxonomy-container">
                                <p class='meta-bar-txt-light'>
                                    <?php
                                    echo $difficulty. ' ' . __('out of 5', 'wm-child-cyclando');
                                    ?>
                                </p>
                            </div>
                    <?php
                    } ?>
                </div>
            </div>
            <div class="cyc-route-mobile-introduction-gallery">
                <div class="cyc-route-mobile-gallery-container">
                    <?php if ($gallery_ids) {
                        echo do_shortcode('[us_image_slider ids="' . implode(',', $gallery_ids) . '" fullscreen="1" img_size="large" arrows="hide" nav="dots"]');
                    } ?>
                </div>
            </div>
        </div>
        <!-- END section taxonomies and difficulty block END  -->
        
        <!-- START section Second menu Tab START  -->
        <div class="cyc-route-mobile-tab-container">
            <?php 
            echo do_shortcode('[vc_tta_tabs][vc_tta_section active="1" tab_id="1615221700207-6345d186-4e71" el_class="oc-tab-plan" title="'.__('Plan', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_plan post_id="'.$wm_post_id.'" hotel_product_items="'.$json_hotel_product_items.'" has_extra="'.$has_extra.'" first_departure="'.$first_departure_date_ajax_dormatdmY.'"][/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1615221700263-b2f1f133-a833" el_class="oc-tab-program" title="'.__('Program', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_program program="'.$program.'" has_track="'.$has_track_program.'" route_has_geojson="'.$route_has_geojson.'" home_site="'.$home_site.'" post_id="'.$wm_post_id.'" language="'.$language.'"][/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1615221704269-1b7373dd-65c0" el_class="oc-tab-includes" title="'.__('Includes', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_includes post_id="'.$wm_post_id.'"][/vc_column_text][/vc_tta_section][/vc_tta_tabs]');
            ?>
        </div>
        <!-- END section Second menu Tab END  -->
    </div>
    <!-- START section Description START  -->
    <section class="l-section wpb_row height_small cyc-single-route-description-container oc-single-route-mobile-description-container">
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
    <section class="l-section wpb_row height_small cyc-single-route-tour-operator-container oc-single-route-mobile-tour-operator-container">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-9 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div class="wpb_text_column">
                                <div class="wpb_wrapper cyc-single-route-tour-operator-wrapper">
                                    <?php
										echo "<h4><i class='wm-icon-cyc_bici'></i> " .__('Tour operator', 'wm-child-cyclando'). "</h4>" . "<p>" . $touroperator . "</p>";
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
    <section class="l-section wpb_row height_small cyc-single-route-cta-container oc-single-route-mobile-cta-container">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-9 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div class="wpb_text_column">
                                <div class="wpb_wrapper cyc-single-route-cta-wrapper">
                                    <div class="cyc-single-route-cta-text">
                                        <h2><?php echo __('Are you ready to leave for your next vacation?', 'wm-child-cyclando'); ?>
                                        </h2>
                                        <p><?php echo __('Personalize your vacation or just ask us any question.', 'wm-child-cyclando'); ?>
                                        </p>
                                    </div>
                                    <div class="cyc-single-route-cta-buttons">
                                        <div id="cy-contact-in-basso" class="">
                                            <div class="cy-btn-contact">
                                                <p><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
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
    <!-- END section call to action sei pronto block END  -->
    <!-- START section cose da sapere FAQ START  -->
    <section class="l-section wpb_row height_small cyc-single-route-faq-container oc-single-route-mobile-faq-container">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-12 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <h5 style="text-align: left" class="vc_custom_heading oc_faq_heading"><i class="wm-icon-ios7-help"></i><?php echo __('Things to know', 'wm-child-cyclando') ?></h5>
                        </div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container oc-faq-accordion-column">
					<div class="vc_column-inner oc-faq-accordion-column-inner">
                        <div class="wpb_wrapper">
                            <h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Bicycles', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="'.__('Can I bring my own bike?', 'wm-child-cyclando').'"][vc_column_text]'.__('Of course! You can enjoy each tour on your own bike or you can rent one. However, we do recommend renting a bike, because not all bikes have the same parts, so we can only guarantee the best mechanical assistance when you hire directly from us.
                            ', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="'.__('What kind of assistance will I have during the trip?
                            ', 'wm-child-cyclando').'"][vc_column_text]'.__('You will always have an emergency phone number you can call. On self-guided trips, you will need to be able to make your own minor repairs, such as replacing an inner tube in the event of a puncture, or fixing a slipped chain, but you can always count on assistance on site for more serious damage.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="'.__('How can I know if a tour matches my level of ability?', 'wm-child-cyclando').'"][vc_column_text]'.__('We rank our tours on a scale of 1 to 5, based on the length, elevation changes and complexity of the itinerary, but if you have any doubts then please feel free to contact us and we will be happy to help you find the trip that is right for you.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
							?>
						</div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container oc-faq-accordion-column">
                    <div class="vc_column-inner oc-faq-accordion-column-inner">
                        <div class="wpb_wrapper">
                            <h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Booking', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="'.__('How do I book a tour?', 'wm-child-cyclando').'"][vc_column_text]'.__('To book a tour, simply click on "quote", fill in all the fields, and pay the deposit. We will check the tour’s availability with our partners and provide confirmation within a few hours.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="'.__('Why should I book through Cyclando?', 'wm-child-cyclando').'"][vc_column_text]'.__('Because we guarantee you the lowest price, and in the event that your tour is not available or confirmed, you can always request a refund of your deposit or choose another trip from among the over 800 vacations we offer on our platform.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="'.__('Should I purchase travel insurance?', 'wm-child-cyclando').'"][vc_column_text]'.__('We offer free medical and baggage insurance for all Italian citizens on all our tours. We also recommend that you take out a cancellation policy; please <a href="https://cyclando.com/assicurazione-covid/">click on this link</a> for more information', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
							?>
						</div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container oc-faq-accordion-column">
					<div class="vc_column-inner oc-faq-accordion-column-inner">
                        <div class="wpb_wrapper">
                            <h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Hotel', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="'.__('Can I find out which hotels I\'ll be staying at in advance?', 'wm-child-cyclando').'"][vc_column_text]'.__('We take choosing hospitality facilities very seriously, and to offer you as much flexibility as possible when booking, we often have a large number of hotels to choose from. For this reason we cannot provide an exact list of hotels before booking.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="'.__('What is included in the tour price?', 'wm-child-cyclando').'"][vc_column_text]'.__('On each trip, you can click on "Dates and Prices" to see what is included, and what is not. Of course, you can always write to us or call us to find out more information or to request clarification.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="'.__('How do I arrive at/return to the starting point?', 'wm-child-cyclando').'"][vc_column_text]'.__('Round-trip travel to and from your home and the trip’s starting point is not included, but we can always help you find the best available solution.', 'wm-child-cyclando').'[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
							?>
						</div>
                    </div>
				</div>
            </div>
        </div>
    </section>
    <!-- END section cose da sapere FAQ END  -->
    <!-- END new template END-->

    
    <?php if (current_user_can('administrator') && get_option('webmapp_show_interactive_route_map')) { ?>
    <div id="wm-wizards-container">
        <?php
				echo do_shortcode("[wmWizards conf='']");
				?>
    </div>
    <?php } ?>

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
                <?php 
                    if ($language == 'it') {
                ?>
                <script>
                hbspt.forms.create({
                    portalId: "6554435",
                    formId: "369b0992-8548-4163-9eb9-a0029e90e1dd",
                    onFormReady: function($form, ctx) {
                        window['hs-form-iframe-0'].contentDocument.querySelector('input[name="activities"]')
                            .setAttribute('value', '<?php echo implode(";", $tax_activities_slug);  ?>')
                        window['hs-form-iframe-0'].contentDocument.querySelector('input[name="target"]')
                            .setAttribute('value', '<?php echo implode(";", $tax_targets_slug);  ?>')
                        window['hs-form-iframe-0'].contentDocument.querySelector(
                            'input[name="place_to_go"]').setAttribute('value',
                            '<?php echo implode(";", $tax_places_to_go_slug);  ?>')
                    },
                    onFormSubmit: function($form) {
                        window.dataLayer = window.dataLayer || [];
                        window.dataLayer.push({

                            'event': 'formInviato',
                        });
                    }
                });
                </script>
                <?php } else { ?>
                <script>
                    hbspt.forms.create({
                        portalId: "6554435",
                        formId: "bb3356c0-9f15-463a-9930-b403bc5dd680",
                        onFormReady: function($form, ctx) {
                            window['hs-form-iframe-0'].contentDocument.querySelector('input[name="activities"]')
                                .setAttribute('value', '<?php echo implode(";", $tax_activities_slug);  ?>')
                            window['hs-form-iframe-0'].contentDocument.querySelector('input[name="target"]')
                                .setAttribute('value', '<?php echo implode(";", $tax_targets_slug);  ?>')
                            window['hs-form-iframe-0'].contentDocument.querySelector(
                                'input[name="place_to_go"]').setAttribute('value',
                                '<?php echo implode(";", $tax_places_to_go_slug);  ?>')
                        },
                        onFormSubmit: function($form) {
                            window.dataLayer = window.dataLayer || [];
                            window.dataLayer.push({

                                'event': 'formInviato',
                            });
                        }
                    });
                </script>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- END HTML modal for contact in route -->
    <script>
        var post_id = <?= $post_id ?>;
        var departureArrays = <?php echo json_encode($start_array)?>;
        var start_arraydFY = <?php echo json_encode($start_arraydFY)?>;
        var start_arrayYmd = <?php echo json_encode($start_arrayYmd)?>;
        var has_extra = <?php echo json_encode($has_extra)?>;
        var hotel_product_items = <?php echo json_encode($hotel_product_items)?>;
        var first_departure_date_ajax = <?php echo json_encode($first_departure_date_ajax )?>;
        console.log('first_departure_date_ajax ' + first_departure_date_ajax);
        var route_title = <?php echo json_encode($route_title) ?>;
        var planSummarytxt = '';


        jQuery(document).ready(function() {
            calculateDepartureDate();
        });
        function ajaxUpdatePrice(){
            var savedCookie = ocmCheckCookie();
            var data = {
                'action': 'oc_ajax_route_price',
                'postid':  post_id,
                'cookies':  savedCookie,
            };
            jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                type : 'post',
                data: data,
                beforeSend: function(){
                    jQuery(".cifraajax").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
                },
                success : function( response ) {
                    jQuery(".cifraajax").html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
                },
                complete:function(response){
                    var addtocart = '';
                    obj = JSON.parse(response.responseText);
                    console.log(obj);
                    jQuery(".cifraajax").html(obj.price+'€');
                    var savedCookie = ocmCheckCookie();
                    savedCookie['price'] = obj.price;
                    savedCookie['routeName'] = route_title;
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    jQuery( ".deposit-title" ).remove();
                    jQuery( ".depositajax" ).remove();
                    delete savedCookie['deposit'];
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    if (obj.deposit) {
                        jQuery( ".oc-route-mobile-plan-price-container" ).prepend( 
                            `<div class="deposit-title"><?= __('Deposit', 'wm-child-cyclando') ?></div><div class="depositajax">`+obj.deposit+`€</div>`
                        );
                        savedCookie['deposit'] = obj.deposit;
                        Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    }
                    if (obj.depositaddtocart) {
                        addtocart = obj.depositaddtocart;
                    } else {
                        addtocart = obj.addtocart;
                    }
                    calcCategorySelectOptions(obj);
                    updatePlanSummaryTxt(savedCookie);
                    updateYourReservationSummaryTxt(savedCookie,obj);
                    jQuery( "#quotewcaddtocart" ).remove();
                    jQuery('#yourReservationPurchaseFrom').prepend('<input type="hidden" id="quotewcaddtocart" name="add-to-cart" value="'+addtocart+'" />');
                }
            });
        }
        function calcCategorySelectOptions(obj){ 
            var savedCookie = ocmCheckCookie(); 
            var options = '<option disabled="disabled"><?= __('Select a category', 'wm-child-cyclando') ?></option>';
            jQuery.each(obj.category, function(index, value) {
                var selected = '';
                if (obj.categoryname == value) {
                    selected = 'selected="selected"';
                    savedCookie['category'] = value;
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                }
                options += "<option "+selected+" value='"+ value + "'>" + value + "</option>";
            });
            jQuery(".category-select-holder select").html(options);
            updateYourReservationSummaryTxt(savedCookie);
        }

        // old function - not in use - for single room dropdown
        function calcSigleSelectOptions(){
            var options = '<option disabled="disabled"><?= __('Single', 'wm-child-cyclando') ?></option>';
                var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                
                if (parseInt(savedCookie['adults'])>0) { 
                    jQuery.each(new Array(parseInt(savedCookie['adults'])), function(index) {
                        var selected = '';
                        index = index + 1;
                        if (parseInt(savedCookie['single']) == index) {
                            selected = 'selected="selected"';
                        }
                        options += "<option "+selected+" value='"+ index + "'>" + index + "</option>";
                    });
                }
                jQuery(".single-room-select-holder select").html(options);
        }

        function updatePlanSummaryTxt(savedCookie){
            var sums = cal_sum_cookies(savedCookie);
            var planSummarytxtBikes = '';
            planSummarytxtPartecipants = sums['participants'] + ' ' + '<?= __('participants', 'wm-child-cyclando') ?>';
            if (sums['bikes']) {
                planSummarytxtBikes = ', ' + sums['bikes'] + ' ' + '<?= __('bikes', 'wm-child-cyclando') ?>';
            }
            planSummarytxtDuration = ' ' + '<?= __('for', 'wm-child-cyclando') ?>' + ' ' + '<?php echo json_encode($days)?>' + ' ' + '<?= __('days', 'wm-child-cyclando') ?>' ;

            planSummarytxt =  planSummarytxtPartecipants + planSummarytxtBikes + planSummarytxtDuration;

            jQuery(".oc-route-mobile-plan-summary").html(planSummarytxt);
        }

        function updateYourReservationSummaryTxt(savedCookie){

            var yRSummarytxtKids = '';
            yRSummarytxtadults = savedCookie['adults'] + ' ' + '<?= __('adults', 'wm-child-cyclando') ?>';
            if (savedCookie['kids']) {
                yRSummarytxtKids = ', ' + savedCookie['kids'] + ' ' + '<?= __('kids', 'wm-child-cyclando') ?>';
            }

            var yRSummarytxtRegular = '';
            var yRSummarytxtElectric = '';
            var yRSummarytxtRegularComa = '';
            if (savedCookie['regular'] || savedCookie['electric']) {
                jQuery('.oc-route-your-reservation-bikes-title').show();
                jQuery('.oc-route-your-reservation-bikes-info').show();
            } else {
                jQuery('.oc-route-your-reservation-bikes-title').hide();
                jQuery('.oc-route-your-reservation-bikes-info').hide();
            }
            if (savedCookie['regular']) {
                yRSummarytxtRegular = savedCookie['regular'] + ' ' + '<?= __('regular', 'wm-child-cyclando') ?>';
            }
            if (savedCookie['regular'] && savedCookie['electric']) {
                yRSummarytxtRegularComa = ', ';
            }
            if (savedCookie['electric']) {
                yRSummarytxtElectric =  savedCookie['electric'] + ' ' + '<?= __('Ebike', 'wm-child-cyclando') ?>';
            }
            
            var yRSummarytxtCategory = '';

            if (savedCookie['category']) {
                jQuery('.oc-route-your-reservation-category-title').show();
                jQuery('.oc-route-your-reservation-category-info').show();
                jQuery("#oc-route-your-reservation-category").html(savedCookie['category']);
            } else {
                jQuery('.oc-route-your-reservation-category-title').hide();
                jQuery('.oc-route-your-reservation-category-info').hide();
            }

            yRSummarytxtParticipants =  yRSummarytxtadults + yRSummarytxtKids ;
            yRSummarytxtBikes =  yRSummarytxtRegular + yRSummarytxtRegularComa + yRSummarytxtElectric ;
            jQuery("#oc-route-your-reservation-participants").html(yRSummarytxtParticipants);
            jQuery("#oc-route-your-reservation-bikes").html(yRSummarytxtBikes);
        }

        function cal_sum_cookies(savedCookie) {
            parseInt(savedCookie['kids']) ? k = parseInt(savedCookie['kids']) : k = 0;
            parseInt(savedCookie['adults']) ? a = parseInt(savedCookie['adults']) : a = 0;
            if (a || k ){
                var psum = a + k;
            } else {
                var psum = null;
            }
            parseInt(savedCookie['regular']) ? r = parseInt(savedCookie['regular']) : r = 0;
            parseInt(savedCookie['electric']) ? e = parseInt(savedCookie['electric']) : e = 0;
            if (e || r ){
                var bsum = e + r;
            } else {
                var bsum = null;
            }
            var sums = {};
            sums['participants'] = psum;
            sums['bikes'] = bsum;
            return sums;
        }

        // ajax on route purchase / pay button that creates a new hubspot deal 
        function ajaxCreatHubspotDeal(form){
            if (Cookies.get('oc_participants_cookie')) {
            var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
            }
            var data = {
                'action': 'oc_ajax_create_hs_deal',
                'postid':  post_id,
                'cookies':  savedCookie,
            };
            jQuery.ajax({
                url: '/wp-admin/admin-ajax.php',
                type : 'post',
                data: data,
                beforeSend: function(){
                },
                success : function( response ) {
                },
                complete:function(response){
                    var obj = JSON.parse(response.responseText);
                    var res = JSON.parse(obj);
                    var savedCookie = ocmCheckCookie();
                    savedCookie['hsdealid'] = res.id;
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    form.submit();
                }
            });
        }


        jQuery(document).ready(function() {
            
            setTimeout(function() {
                jQuery(".cyc-single-route-main-container .rsFullscreenIcn").html(
                    "<span class='gallery-expand-desktop'>Guarda tutte le foto</span><span class='gallery-expand-mobile'><i class='fas fa-expand'></i></span>"
                );
            }, 400);

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
                if (modal) {
                    modal.style.display = 'none';
                }
                contactModal.style.display = 'block';
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
            }

            // Close prices modal and open contact modal
            function closeModalOpenContact() {
                modal.style.display = 'none';
                contactModal.style.display = 'block';
            }


            // MONARCH interaction with Share Condividi button

            jQuery('.et_social_sidebar_networks').removeClass('et_social_visible_sidebar');
            jQuery('.et_social_sidebar_networks').addClass('et_social_hidden_sidebar');
            
            jQuery('#cyc-single-route-monarch-share-button').click(function() {
                jQuery('.et_social_sidebar_networks').toggle(300);
                // jQuery('.et_social_hide_sidebar').toggleClass('et_social_hidden_sidebar');
                jQuery('.et_social_sidebar_networks').toggleClass(
                    'et_social_hidden_sidebar et_social_visible_sidebar');
            });

            // Ajax call for program content in modal
            jQuery( ".oc-tab-program" ).on( "click", function() {
                ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>'; // get ajaxurl
                post_id = <?php echo $post_id; ?>;
                data = {
                    'action': 'wm_ajax_program_content',
                    'postid':  post_id,
                };
                jQuery.ajax({
                    url: ajaxurl,
                    type : 'post',
                    data: data,
                    beforeSend: function(){
                    },
                    success : function( response ) {
                    },
                    complete:function(response){
                        obj = JSON.parse(response.responseText);
                        jQuery(".oc-route-tab-mobile-program-body").html(obj);
                    }
                });
            });
        });
        jQuery(document).ready(function(){
            jQuery('#cyc-single-route-mobile-gallery-button').click(function(){jQuery('.rsFullscreenBtn').trigger('click')})
        });

            
        </script>
    <?php
    }
    do_action('us_after_page');
    ?>
</main>

<?php get_footer() ?>