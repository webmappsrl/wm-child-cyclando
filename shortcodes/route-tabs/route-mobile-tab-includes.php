<?php 

add_shortcode( 'route_mobile_tab_includes', 'route_mobile_tab_includes' );
  
function route_mobile_tab_includes() {

    ob_start();


    ?>includes<?php

    echo ob_get_clean();
}

