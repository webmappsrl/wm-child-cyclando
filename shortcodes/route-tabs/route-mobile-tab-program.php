<?php 

add_shortcode( 'route_mobile_tab_program', 'route_mobile_tab_program' );
  
function route_mobile_tab_program() {

    ob_start();


    ?>program<?php


    echo ob_get_clean();
}

