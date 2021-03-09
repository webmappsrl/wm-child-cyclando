<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan() {

    ob_start();


    ?>plan<?php


    echo ob_get_clean();
}

