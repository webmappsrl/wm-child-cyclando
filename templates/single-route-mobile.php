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
		foreach ($start_array as $date) {
			if (date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date))) {
				$first_departure_date = date_i18n('d F', strtotime($date));
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

        $route_has_geojson = URL_exists("https://a.webmapp.it/cyclando.com/geojson/$post_id.geojson");
        $featured_image = get_the_post_thumbnail_url($post_id,'us_600_0');
        if (! $featured_image) {
            $featured_image = get_the_post_thumbnail_url($post_id,'large');
            if (!$featured_image) {
                $featured_image = wp_get_attachment_image_src($gallery_ids[0],'us_600_0');
                $featured_image = $featured_image[0];
            }
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
                            <div class="cyc-route-mobile-introduction-gallery">
                                <div class="cyc-route-mobile-gallery-container">
                                    <?php if ($gallery_ids) {
                                        echo do_shortcode('[us_image_slider ids="' . implode(',', $gallery_ids) . '" fullscreen="1" img_size="large" img_fit="cover" arrows="hide" nav="dots"]');
                                    } ?>
                                </div>
                            </div>
                            <div class="cyc-route-mobile-introduction-days">
                                <p><?= ($days) ? $days_info : '' ?></p>
                                <p><?= ($distance) ? $distance_info : '' ?></p>
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
                                <p id="cyc-single-route-monarch-gallery-button" class="cyc-single-route-monarch-share">
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
    <!-- START section taxonomies and difficulty block START  -->
    <div class="cyc-route-mobile-taxonomy-container">
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
    <!-- END section taxonomies and difficulty block END  -->
    
    <!-- START section Second menu Tab START  -->
    <div class="cyc-route-mobile-tab-container">
        <?php 
        echo do_shortcode('[vc_tta_tabs][vc_tta_section active="1" tab_id="1615221700207-6345d186-4e71" title="'.__('Plan', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_plan][/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1615221700263-b2f1f133-a833" el_class="oc-tab-program" title="'.__('Program', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_program program="'.$program.'" has_track="'.$has_track_program.'" route_has_geojson="'.$route_has_geojson.'" home_site="'.$home_site.'" post_id="'.$post_id.'" language="'.$language.'"][/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1615221704269-1b7373dd-65c0" title="'.__('Includes', 'wm-child-cyclando').'"][vc_column_text][route_mobile_tab_includes][/vc_column_text][/vc_tta_section][/vc_tta_tabs]');
        ?>
    </div>
    <!-- END section Second menu Tab END  -->
    <!-- START section Second menu START  -->
    <section class="l-section wpb_row height_auto cyc-single-route-second-menu-container">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-9 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div class="wpb_text_column">
                                <div class="wpb_wrapper cyc-single-route-second-menu-wrapper">
                                    <div>
                                        <h4><?php echo __('Plan your trip', 'wm-child-cyclando'); ?></h4>
                                    </div>
                                    <div class="cyc-sr-sm-items">
                                        <?php if ($program or (get_option('webmapp_show_interactive_route_map') && $route_has_geojson)) {
												echo "<h4 id='expand-map'>" . __('Program', 'wm-child-cyclando') . "</h4>";
											} ?>
                                    </div>
                                    <div id="<?php echo $popup_show_prices_class ?>"
                                        class="cyc-sr-sm-items <?php echo $coming_soon_class ?>">
                                        <?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
                                        <h4 class="prezzo-container">
                                            <?php echo __('Dates & prices', 'wm-child-cyclando') ?>
                                        </h4>
                                        <?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
                                        <a class="download-app-link" target="_blank"
                                            href="https://info.cyclando.com/app">
                                            <div class="scarica-app">
                                                <h4 class='meta-bar-txt-light'>
                                                    <?php echo __('Download', 'wm-child-cyclando'); ?></h4>
                                            </div>
                                        </a>
                                        <?php } else { ?>
                                        <div class="coming-soon">
                                            <h4 class='meta-bar-txt-light'>
                                                <?php echo __('On Request', 'wm-child-cyclando'); ?></h4>
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
                                        <?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) { ?>
                                        <a target="_blank"
                                            href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
                                            <div id="wm-book-quote" class="cy-btn-quote">
                                                <p><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
                                            </div>
                                        </a>
                                        <?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
                                        <a class="download-app-link" target="_blank"
                                            href="https://info.cyclando.com/app">
                                            <div class="cy-btn-quote">
                                                <p class=''><?php echo __('Download', 'wm-child-cyclando'); ?></p>
                                            </div>
                                        </a>
                                        <?php } else { ?>
                                        <div class="cy-btn-quote">
                                            <p class=''><?php echo __('On Request', 'wm-child-cyclando'); ?></p>
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
    <!-- START section cose da sapere FAQ START  -->
    <section class="l-section wpb_row height_small cyc-single-route-faq-container">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_top">
                <div class="vc_col-sm-12 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <h5 style="text-align: left" class="vc_custom_heading"><?php echo __('Things to know', 'wm-child-cyclando') ?></h5>
                        </div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container">
					<div class="vc_column-inner">
                        <div class="wpb_wrapper">
							<h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Bicycles', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling="" c_icon=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="Posso portare la mia bici?"][vc_column_text]Certo! Ad ogni tour è possibile partecipare con la propria bicicletta o noleggiarne una. Noi tuttavia ti consigliamo il noleggio perché i ricambi non sono tutti uguali e solo con le nostre bici possiamo garantirti sempre l’assistenza meccanica migliore.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="Che tipo di assistenza ho durante il viaggio?"][vc_column_text]Avrai sempre un numero di telefono d’emergenza a cui fare riferimento. Nei viaggi self-guided dovrai essere in grado di eseguire piccole riparazioni, come sostituire una camera d’aria in caso di foratura, o rimettere a posto una catena caduta, ma potrai sempre contare sull\'assistenza in loco per rotture più gravi.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="Come capisco se un tour è alla mia portata?"][vc_column_text]Classifichiamo i tour in una scala da 1 a 5 sulla base della lunghezza, del dislivello e della complessità dell’itinerario, ma se hai dubbi contattaci e ti aiuteremo a trovare il viaggio più adatto a te.[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
							?>
						</div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
							<h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Booking', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling="" c_icon=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="Come faccio a prenotare un tour?"][vc_column_text]Per prenotare un tour ti basta cliccare sul campo “preventivo” compilare tutti i campi, e versare l’acconto. Noi ci occuperemo della verifica di disponibilità presso i nostri corrispondenti e ti daremo conferma entro poche ore.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="Perché dovrei prenotare su Cyclando ?"][vc_column_text]Perché ti garantiamo il prezzo più basso e nel caso il tuo tour non sia disponibile o confermato, potrai sempre optare per la restituzione dell’importo versato, oppure scegliere un altro viaggio tra gli oltre 800 presenti in piattaforma.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="Dovrei acquistare un\'assicurazione di viaggio?"][vc_column_text]L’assicurazione medico-bagaglio la offriamo noi gratuitamente, a tutti i cittadini italiani, su tutti i nostri tour. In più ti consigliamo di stipulare anche una polizza annullamento; fai click su <a href="https://cyclando.com/assicurazione-covid/">questo link</a> per maggiori informazioni[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
							?>
						</div>
                    </div>
                </div>
                <div class="vc_col-sm-4 wpb_column vc_column_container">
					<div class="vc_column-inner">
                        <div class="wpb_wrapper">
							<h6 style="text-align: left" class="vc_custom_heading"><?php echo __('Hotel', 'wm-child-cyclando') ?></h6>
							<?php 
							echo do_shortcode('[vc_tta_accordion scrolling="" c_icon=""][vc_tta_section tab_id="1603987578996-293b52a0-7a07" title="Posso conoscere prima gli hotel in cui alloggerò?"][vc_column_text]Diamo molta importanza alla scelta delle strutture e per garantirti più flessibilità in fase di prenotazione, abbiamo spesso più hotel a cui fare riferimento, per questo motivo non ci è possibile fornirti la lista esatta degli hotel prima della prenotazione.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987709666-e05915db-c8e0" title="Cosa è incluso nel prezzo del tour ?"][vc_column_text]In ogni viaggio, cliccando su “Date e prezzi” potrai vedere cosa è incluso e cosa no. Naturalmente puoi sempre scriverci o chiamarci per avere maggiori informazioni o chiarimenti.[/vc_column_text][/vc_tta_section][vc_tta_section tab_id="1603987706680-2cb9135f-e91b" title="Come faccio ad arrivare/tornare al punto di partenza?"][vc_column_text]Il viaggio di andata e ritorno da casa al punto di partenza del viaggio è sempre escluso, possiamo però aiutarti a trovare la migliore soluzione disponibile.[/vc_column_text][/vc_tta_section][/vc_tta_accordion]')
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
    <!-- HTML modal for prices -->
    <div id="cy-prices-modal" class="cy-prices-modal">
        <div class="cy-modal-content">
            <div class="cy-modal-header">
                <div class="close-button-container"><span class="cy-close">&times;</span></div>
                <div class="vedi-prezzi">
                    <h2><?php echo __('See the prices', 'wm-child-cyclando'); ?></h2>
                </div>
                <div class="meta-bar wm-activity"><i class="<?php echo $iconimage_activity; ?>"></i></div>
                <div id="wm-book-quote" class="meta-bar wm-book long-txt">
                    <p class='meta-bar-txt-bold'><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
                    <a target="_blank"
                        href="https://cyclando.com/quote/#/<?php echo $post_id . '?lang=' . $language; ?>">
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
        jQuery(document).ready(function() {
            setTimeout(function() {
                jQuery(".cyc-single-route-main-container .rsFullscreenIcn").html(
                    "<span class='gallery-expand-desktop'>Guarda tutte le foto</span><span class='gallery-expand-mobile'><i class='fas fa-expand'></i></span>"
                );
                console.log("foto");
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
                modal.style.display = 'none';
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
                jQuery('#cyc-single-route-monarch-gallery-button').click(function(){jQuery('.rsFullscreenBtn').trigger('click')})
            });
        </script>
    <?php
    }
    do_action('us_after_page');
    ?>
</main>

<?php get_footer() ?>