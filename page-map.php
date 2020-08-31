<?php /* Template Name: map */

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();

get_header();

?>
<style>
p{
    margin: 0;
    padding: 3px 10px;
    border: 1px solid #f5f5f5;
}
p:hover {
    background-color: #f5f5f5;
}
</style>
<main id="page-content" class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
	<?php
	do_action( 'us_before_page' );

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

			//echo $the_content;
			$site_url = site_url();
			if ($site_url = 'http://cyclando.local') {
				$site_url = 'http://localhost/R';
			} else {
				$site_url = 'https://78.47.21.164/r';
			}
			$domande= file_get_contents($site_url.'edrum/psql/api/post/read_all_domande.php');
            ?> 
			<div class="redrum" style="width:100%;height:100%;">
				<div class="questions" style="width:30%;float:left;overflow:auto;height:100vh;">
				<?php echo $domande;?>
				</div>
				<div style="height:100vh;width:69%;float:left;">
				<?php
				//  http://78.47.21.164/redrum/psql/api/post/read_variante.php?id=275
				echo do_shortcode('[wm-embedmaps]');
				?> 
				</div>
			</div>
			<script type="text/javascript" language="javascript">  
				
				jQuery(document).ready(function($) {
					var siteurl = document.location.origin;
					if (siteurl = 'http://cyclando.local') {
						siteurl = 'http://localhost/R';
					} else {
						siteurl = 'https://78.47.21.164/r';
					}
					console.log('esme site: '+siteurl);
					function onDomandaClick (e){
					var domandaid = e.target.id;
						console.log('shomare: '+domandaid);
						$.ajax({  
							type:"GET",  
							url: siteurl+"edrum/psql/api/post/read_domanda.php",  
							data:"id="+domandaid,  
							success:function(data){  
								$('.questions').html(data);
								// Get DOM Elements
								const domanda = document.querySelectorAll('.questions p');
								// Events expand map program Modal
								domanda.forEach((button) => {
									button.addEventListener('click', onVarianteClick);
								});
							}  
						}); 
					}
					function onVarianteClick (e){
						var varianteid = e.target.id;
						console.log('shomare: '+varianteid);
							$.ajax({  
								type:"GET",  
								url:siteurl+"edrum/psql/api/post/read_variante.php", 
								data:"id="+varianteid,  
								success:function(data){  
									try {
										console.log(data);
										var layers = [ data ];
										var definitions = [
											{
												id: '1-routes',
												icon: 'wm-icon-flag',
												color: '#f00'
											}
											// ,{
											// 	id: '2-routes',
											// 	icon: 'wm-icon-flag',
											// 	color: '#ccc'
											// }
										];
										window.localStorage.setItem('wm_geojson_layers', JSON.stringify(layers));
										window.localStorage.setItem('wm_overlays_definition', JSON.stringify(definitions));
										document.dispatchEvent(new Event('wm_overlays_updated'));
									} catch (e) {
										console.error(e);
									}
									// alert(data);  
								}  
							}); 
					}
					// Get DOM Elements
					const domanda = document.querySelectorAll('.questions p');
					// Events expand map program Modal
					domanda.forEach((button) => {
						button.addEventListener('click', onDomandaClick);
					});
				});
			</script>  
            <?php
		}
	}
	do_action( 'us_after_page' );
	?>
</main>

<?php get_footer() ?>
