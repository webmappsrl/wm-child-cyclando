<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header('route');

wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
wp_enqueue_script( 'route-single-post-style-animation', get_stylesheet_directory_uri() . '/jquery/child-main.js', array ('jquery'));
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
		
		// var 
		$how_to_arrive = get_field('vn_come_arrivare');
		$program = get_field('vn_prog');
		$scheda_tecnica = get_field('vn_scheda_tecnica');
		$organizzazione = get_field('vn_part_pr');
		$gallery_items = get_field('wm_route_gallery');
		$touroperator_id_array = get_field('tour_operator');
		$touroperator_id = $touroperator_id_array[0];
		$touroperator = get_the_title($touroperator_id);
		$gallery_ids = array();
		foreach ($gallery_items as $gallery_item) {
			array_push($gallery_ids,$gallery_item['ID']);
		}
 		//checks if it has promotion and creates a list of dates of promotion period
        $in_promotion_active = get_field('wm_route_in_promotion');
        $in_promotion = false;
        $promotion_dates_list = array();
        while( have_rows('model_promotion') ): the_row();
        $promotion_periods = get_sub_field('periods');
        $promotion_departure_dates = get_sub_field('departure_dates');
        foreach ( $promotion_periods as $period ) {
            $start_period = str_replace('/', '-', $period['start']);
            $stop_period = str_replace('/', '-', $period['stop']);
            $begin = new DateTime( $start_period );
            $end = new DateTime( $stop_period );
            $end = $end->modify( '+1 day' );

            $interval = new DateInterval('P1D');
            $daterange = new DatePeriod($begin, $interval ,$end);
            foreach($daterange as $date){
                $promotion_single_date = $date->format("d-m-Y");
                array_push( $promotion_dates_list, $promotion_single_date );
            }
        }
        foreach ($promotion_departure_dates as $date){
            $single_date =  str_replace('/', '-', $date['date']);
            array_push( $promotion_dates_list, $single_date );
        }
        endwhile;
        $current_date = date("d-m-Y");
        foreach ( $promotion_dates_list as $dates_list ){
            if ( $dates_list == $current_date ){
                $in_promotion = true;
            }
        }

		?>
		
		<section  class="l-section height_auto for_sidebar at_right intro-section"> <!--- first section -->
			<div id="introduction" class="l-section-h">
				<div class="g-cols type_default valign_top">
					<div class="vc_col-sm-9 vc_column_container l-content">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<section class="l-section">
									<div class="l-section-h i-cf">
									<?php 
									echo "<h2>Un viaggio per ...</h2>";
									echo "<p class='route-excerpt'>".get_the_excerpt()."</p>";			
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
								<div class="departure-preventivo-aside"> <!------------ Departure / Partenze -->
									<span class='durata-txt'>
										<p class="tab-section">
											<?php
											if( have_rows('departures_periods') ){
												echo __('Dates:' ,'wm-child-verdenatura');}?>
										</p>
									</span>
									
									<?php
										if( have_rows('departures_periods') ): ?>
										<div class="departure_name">
										</div>
										<div class="grid-container-period-aside">
										
											<?php while( have_rows('departures_periods') ): the_row(); 
									
											// vars
											$name = get_sub_field('name');
											$start = get_sub_field('start');
											$stop = get_sub_field('stop');
											$week_days = get_sub_field('week_days');
											$dateformatstring = "l";
									
											?>
									
											<div class="departure_start">
												<?php if( $start ): ?>
													<i class="cy-icons icon-plane-departure1"></i><p><?php echo __('From:' ,'wm-child-verdenatura').' '.$start; ?></p>
												<?php endif; ?>
											</div>
											<div class="departure_stop">
												<?php if( $stop ): ?>
													<p><?php echo __('To:' ,'wm-child-verdenatura').' '.$stop; ?></p>
												<?php endif; ?>
											</div>
											<div class="departure_week_days">
												<?php if( $week_days ): ?>
													<ul>
														<?php if (count($week_days) == 7) { ?>
															<li style="display: inline;" ><?php echo __('Every day' ,'wm-child-verdenatura'); ?></li>
															<?php }else { ?>
																<span><?php echo __('Each' ,'wm-child-verdenatura').' '; ?></span>
																<?php 
																$i = 0;
																$len = count($week_days);
																foreach( $week_days as $week_day ): 
																	if ($i == 0){ ?>
																		<li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
																	<?php } elseif ($i == $len -1){ ?>
																		<?php echo __('and' ,'wm-child-verdenatura').' '; ?><li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
																	<?php } else { ?>
																	<span><?php echo __(',' ,'wm-child-verdenatura').' '; ?></span><li style="display: inline;" ><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
																	<?php } $i++ ;?>
														<?php endforeach; } ?>
													</ul>
												<?php endif; ?>
										</div>
									
										<?php endwhile; ?>
									
										</div>
									
										<?php endif; ?>
										
										<?php // ---------- single departures ----------------//
										while( have_rows('departure_dates') ): the_row(); 
										$date = get_sub_field('date');            
										endwhile;
										if( have_rows('departure_dates') && $date ): ?>
										<div class="single-departure">
												<p class="tab-section"><?php if (have_rows('departures_periods') && !empty($start) && have_rows('departure_dates')) { echo __('Other dates:' ,'wm-child-verdenatura'); } else{ echo __('Dates:' ,'wm-child-verdenatura');}?></p>
										</div>
										<div class="grid-container-single">
										
										<?php while( have_rows('departure_dates') ): the_row(); 
									
											// vars
											$date = get_sub_field('date');            
											?>
									
											<div class="departure_name">
												<?php if( $date ): ?>
													<p><?php echo $date; ?></p>
												<?php endif; ?>
											</div>
									
										<?php endwhile; ?>
										</div>
										<?php endif; ?> <!-- End ---------- single departures -->
										<!-- <p class="tab-section"> -->
											<?php
											// if( $how_to_arrive ){
											// echo __('Arrive:' ,'wm-child-verdenatura');
											// echo $how_to_arrive;
											// }?>
										<!-- </p> -->
										
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>  <!--- END first section -->
		<?php echo do_shortcode('[vc_row height="600" width="full"][vc_column][us_image_slider ids="'. implode( ',' , $gallery_ids ) .'" fullscreen="1" img_size="large" img_fit="cover"][/vc_column][/vc_row]');?>
		<?php if ($program): ?> 
		<section id="program" class="l-section height_auto for_sidebar at_right"> <!--- third section  program-->
			<div class="l-section-h">
				<div class="g-cols type_default valign_top">
					<div class="vc_col-sm-9 vc_column_container l-content">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<section class="l-section">
									<div class="l-section-h i-cf">
									<?php	
									echo "<h2>Programma</h2>";				
									echo $program;
									?>
								</section>
							</div>
						</div>
					</div>
					<!-- <div class="vc_col-sm-3 vc_column_container l-sidebar">
						<div class="vc_column-inner">
							<div class="wpb_wrapper sidebar-departures">
								<div class="route-map">
									<img src="/wp-content/uploads/2019/10/map-route-cyclando.jpg" alt="">
								</div>
							</div>
						</div>
					</div>
				</div> -->
			</div>
		</section>  <!--- END third section -->
		<?php endif;?>
		<?php if ($scheda_tecnica): ?> 
		<section id="caratteristiche" class="l-section height_auto for_sidebar at_right"> <!--- forth section  Caratteristiche-->
			<div class="l-section-h">
				<div class="g-cols type_default valign_top">
					<div class="vc_col-sm-9 vc_column_container l-content">
						<div class="vc_column-inner">
							<div class="wpb_wrapper">
								<section class="l-section">
									<div class="l-section-h i-cf">
									<?php			
									echo "<h2>Caratteristiche</h2>";				
									echo $scheda_tecnica;
									?>
									</div>
								</section>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>  <!--- END forth section Caratteristiche-->
		<?php endif;?>
		<?php if ($touroperator): ?> 
		 <!--- forth section  touroperator-->
		<section id="touroperator" class="l-section height_auto for_sidebar at_right">
			<div class="l-section-h">
				<div class="g-cols type_default valign_top">
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
		<?php endif;?>
		<!--- END forth section touroperator-->

		<!-- HTML modal for contact in route -->
		<div id="cy-route-contact" class="cy-route-contact">
			<div class="cy-modal-content">
				<div class="cy-modal-header">
					<span class="cy-close-contact">&times;</span>
					<h2>Richiedi informazioni per questo viaggio</h2>
					<!-- <div class="meta-bar wm-book"><p class="meta-bar-txt-bold">chiedi informazione</p></div> -->
				</div>
				<div class="cy-modal-body">
					<?php echo do_shortcode('[contact-form-7 id="54052" title="Contact form route"]');?>
				</div>
			</div>
		</div>
		<!-- END HTML modal for contact in route -->
		<?php
			// Post comments
			if ( comments_open() OR get_comments_number() != '0' ) {

				$show_comments = FALSE;
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
	do_action( 'us_after_page' );
	?>
</main>

<?php get_footer() ?>
