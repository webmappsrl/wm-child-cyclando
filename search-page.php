<?php /* Template Name: Search page */ 

defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Template to show single page or any post type
 */

$us_layout = US_Layout::instance();



get_header();


?>
<main id="page-content" class="l-main"<?php echo ( us_get_option( 'schema_markup' ) ) ? ' itemprop="mainContentOfPage"' : ''; ?>>
	<?php
	while ( have_posts() ) {
		the_post();

        // Plan your bike itinerary and leave immediately for your next vacation
        // Pianifica il tuo itinerario in bici e parti subito per la tua prossima vacanza

        // With Cyclando, calculating the itinerary of your cycling holiday is very simple. Our cycle itinerary planner will allow you to find the trip that suits you best, based on your choices on:
        // Con Cyclando calcolare l’itinerario della tua vacanza in bici è semplicissimo. Il nostro pianificatore di itinerari ciclabili ti permetterà di trovare il viaggio su misura per te, in base alle tue scelte su:
			
		load_template( __DIR__ . '/templates/search-page-desktop.php' );

        // Loads the content from the page
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

			
		}
	}
	?>
</main>
<!-- filter popup modal header -->
<div id="cerca-facets-container-modal-header">
    <div class="">  
        <h2><?php echo __('Filter','wm-child-cyclando'); ?></h2>
    </div>
    <div class="searchpage-form-close-button-container"><span class="searchpage-form-close">&times;</span></div>
</div>
<?php get_footer() ?>
