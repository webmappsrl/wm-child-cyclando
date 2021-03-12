<?php 

add_shortcode( 'route_mobile_tab_program', 'route_mobile_tab_program' );
  
function route_mobile_tab_program($atts) {

    extract( shortcode_atts( array(
        'program' => '',
        'has_track' => '',
        'route_has_geojson' => '',
        'home_site' => '',
        'post_id' => '',
        'language' => '',
    ), $atts ) );
    
    ob_start();


    if ($program && !get_option('webmapp_show_interactive_route_map')) : ?>
    <div class="oc-route-tab-mobile-program-body">
        <div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>
    </div>
    <?php
        elseif (!$has_track && get_option('webmapp_show_interactive_route_map')) :
        ?>
    <div class="oc-route-tab-mobile-program-body">
        <div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>
    </div>
    <?php

        elseif ($route_has_geojson == false) :
        ?>
    <div class="oc-route-tab-mobile-program-body">
        <div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>
    </div>
    <?php

        elseif (get_option('webmapp_show_interactive_route_map')) :
            echo '<div class="cy-modal-body cy-modal-body-map">';
            echo do_shortcode('[wm-embedmaps feature_color="#F18E08" color="#9AC250" route="https://a.webmapp.it/' . $home_site . '\/geojson/' . $post_id . '.geojson" height="100%" lang="'.$language.'"]');
            echo '</div>';
        endif;



    echo ob_get_clean();
}

