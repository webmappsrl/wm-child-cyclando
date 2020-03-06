<?php 

add_shortcode( 'facetwp_search_route_wizard', 'webmapp_facetwp_search_route_wizard' );
  
function webmapp_facetwp_search_route_wizard() {

    ob_start();
    ?>

    <div id="vn-search-element-container">
    <div id="vn-search-bar-header" class="fselect-template">
    <?php echo do_shortcode('[facetwp facet="dove_vuoi_andare2"]'); ?>
    <?php echo do_shortcode('[facetwp template="home_dove_vuoi_andare"]');?>
    </div>
    </div>
    <?php

    echo ob_get_clean();
}