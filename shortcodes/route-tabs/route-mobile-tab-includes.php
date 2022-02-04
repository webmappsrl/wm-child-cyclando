<?php 

add_shortcode( 'route_mobile_tab_includes', 'route_mobile_tab_includes' );
  
function route_mobile_tab_includes($atts) {
    extract( shortcode_atts( array(
        'post_id' => '',
        'shape' => '',
        'activity' => '',
    ), $atts ) );
    echo wm_route_included_not_included($post_id,$shape,$activity);

}

