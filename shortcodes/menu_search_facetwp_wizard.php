<?php 

add_shortcode( 'facetwp_search_route_wizard', 'webmapp_facetwp_search_route_wizard' );
  
function webmapp_facetwp_search_route_wizard() {

    echo '<div id="cy-search-bar-header"><form id="searchform" action="/cerca/"  method="get">
	<input type="search" placeholder="' . __( 'Dove vuoi andare?','wm-child-verdenatura' ) . '" value="" name="_dove_vuoi_andare">
	<button id="cy-search-lente" type="submit"><i class="fa fa-search"></i></button>
    </form></div>';
}

