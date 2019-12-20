	<?php defined('ABSPATH') or die('This script cannot be accessed directly.');

	/**
	 * Template header
	 */

	$us_layout = US_Layout::instance();

	// variable and queries
	$days = get_field('vn_durata');
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
	// get terms targets
	$tax_targets = get_the_terms($post_id, $target);
	$tax_places_to_go = get_the_terms($post_id, $places_to_go);

	$tax_activities = get_the_terms($post_id, $activity);
	$get_term_activity = get_term_by('slug', $tax_activities[0]->slug, $activity);
	$term_activity = 'term_' . $get_term_activity->term_id;
	$iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);


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
					<div id='webmapp-layer-1' class="webmapp-featured-image">
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
								<?php
								if ($days) {

									?>
									<div class="route-duration">
										<?php
										echo "<span class='header-txt-layer-1 dur-txt'><i class='cy-icons icon-calendar'></i>" .  " $days " . __('days', 'wm-child-cyclando') . " / $nights " . __('nights', 'wm-child-cyclando') . "</span>";
										?>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>

					<div id='webmapp-layer-2' class="webmapp-featured-meta">
						<div class="webmapp-featured-meta-info" style="background-image: url('/cyclandolocal/wp-content/themes/wm-child-cyclando/images/background_menu_route_verde.png')">
							<div class="container">
								<!-- <div class="meta-bar price-from"> -->
								<div class="prezzo-container">
									<!-- prezzo start-->
									<!-- <span class=""><?php
														?></span> -->
									<div class="prezzo">
										<p class='meta-bar-txt-light'><?php echo __('Prices from', 'wm-child-verdenatura'); ?></p>
										<p class="cifra <?php if ($in_promotion) {
															echo 'old-price';
														} ?>"><?php
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
										<?php echo '<br>'; ?>
									</div>
								</div>
								<!--.prezzo  end-->
								<!-- </div> -->
								<!-- <div class="meta-bar show-prices"><?php
																		?></div> -->

								<div class="meta-bar show-prices">
									<div id="popup-show-prices" class="popup-show-prices">
										<div class="w-btn-wrapper"><span class=""><i class="fas fa-angle-down"></i><span class="w-btn-label">vedi i prezzi</span></span></div>
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
											$places_count = 1;
											foreach ($tax_places_to_go as $tax_place_to_go) {
												if ($places_count == 1) {
													echo $tax_places_to_go[0]->name;
												} elseif ($places_count == 2) {
													echo "<span class='show-more-places'> ...</span>";
												}
												$places_count++;
											}
											?>
										</p>
									</div>
								</div>
								<div class="meta-bar wm-distance">
									<i class="cy-icons icon-route1"></i>
									<div class="meta-bar-container">
										<p class='meta-bar-txt-light'>
											<?php
											echo __('Distance', 'wm-child-verdenatura')
											?>
										</p>
										<p class='meta-bar-txt-bold'>
											<?php
											echo $distance . "<span class='txt-font-light'> km</span>";
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
											$places_count = 1;
											foreach ($tax_activities as $tax_place_to_goo) {
												if ($places_count == 1) {
													echo $tax_activities[0]->name;
												} elseif ($places_count == 2) {
													echo "<span class='show-more-places'> ...</span>";
												}
												$places_count++;
											}
											?>
										</p>
									</div>
								</div>
								<div id="wm-book" class="meta-bar wm-book">
									<p class='meta-bar-txt-bold'><?php echo __('Book now', 'wm-child-verdenatura'); ?></p>
								</div>
							</div>
						</div>
						<div id="fixed-ancor-section" class="webmapp-fixed-ancor">
							<!--- first section -->
							<div class="l-section-h">
								<div class="g-cols type_default valign_top">
									<div class="vc_col-sm-9 vc_column_container l-content fixed-ancor-col-9">
										<div class="vc_column-inner">
											<div class="wpb_wrapper">
												<ul class="fixed-ancor">
													<li><a class="fixed-ancor-menu" href="#introduction">Introduzione</a></li>
													<?php if ($program) : ?><li><a class="fixed-ancor-menu" href="#program">Programma</a></li><?php endif; ?>
													<?php if ($scheda_tecnica) : ?><li><a class="fixed-ancor-menu" href="#caratteristiche">Caratteristiche</a></li><?php endif; ?>
													<?php if ($touroperator_id_array) : ?><li><a class="fixed-ancor-menu" href="#touroperator">Fatto da</a></li><?php endif; ?>
													<!-- <li><a class="fixed-ancor-menu" href="#organizzazione">Organizzazione e costi</a></li> -->
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--- END fixed ancor section -->

						<!-- HTML modal for prices -->
						<div id="cy-prices-modal" class="cy-prices-modal">
							<div class="cy-modal-content">
								<div class="cy-modal-header">
									<div class="close-button-container"><span class="cy-close">&times;</span></div>
									<h2>Vedi i prezzi</h2>
									<div class="meta-bar wm-activity"><i class="<?php echo $iconimage_activity; ?>"></i></div>
									<div class="meta-bar wm-book">
										<p class="meta-bar-txt-bold"><?php echo __('Book now', 'wm-child-verdenatura'); ?></p>
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
					</div>
				</div> <!-- END div webmapp-layer-container -->
				<script>
					jQuery(document).ready(function() {
						// Get DOM Elements
						const modal = document.querySelector('#cy-prices-modal');
						const modalBtn = document.querySelector('#popup-show-prices');
						const closeBtn = document.querySelector('.cy-close');
						const fixedAncor = document.querySelectorAll('.fixed-ancor-menu');

						// Get contact elements
						const contactModal = document.querySelector('#cy-route-contact');
						const contactModalBtn = document.querySelector('#wm-book');
						const closeContactBtn = document.querySelector('.cy-close-contact');

						// Get button element inside prices modal
						const contactInsideModal = document.querySelector('#cy-prices-modal .wm-book');

						// Events modal prices
						modalBtn.addEventListener('click', openModal);
						closeBtn.addEventListener('click', closeModal);
						window.addEventListener('click', outsideClick);
						// fixedAncor.addEventListener('click', scrollOffset);

						// Events contactModal
						contactModalBtn.addEventListener('click', openContactModal);
						closeContactBtn.addEventListener('click', closeContactModal);
						contactInsideModal.addEventListener('click', closeModalOpenContact);

						// Open modal prices
						function openModal() {
							modal.style.display = 'block';
						}
						// Open contact modal
						function openContactModal() {
							contactModal.style.display = 'block';
						}

						// Close modal prices
						function closeModal() {
							modal.style.display = 'none';
						}

						// Close contact modal
						function closeContactModal() {
							contactModal.style.display = 'none';
						}

						// Close If Outside Click
						function outsideClick(e) {
							if (e.target == modal) {
								modal.style.display = 'none';
							}
							if (e.target == contactModal) {
								contactModal.style.display = 'none';
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