<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan() {

    ob_start();


    ?>
    <div class="oc-route-mobile-search-form-container">
        <?= do_shortcode("[onclick_route_form_datepicker]")?>
        <?= do_shortcode("[oneclick_search_form_participants]")?>
        <?= do_shortcode("[oneclick_search_form_bikes]")?>
        <div class="cifraajax"></div>
	</div>
    <?php


    echo ob_get_clean();
}

