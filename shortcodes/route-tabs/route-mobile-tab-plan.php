<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan() {

    ob_start();


    ?>
    <div class="oc-route-mobile-search-form-container">
        <?= do_shortcode("[onclick_route_form_datepicker]")?>

        <div class="oc-route-mobile-search-form-asbb-wrapper">
            <?= do_shortcode("[oneclick_search_form_participants adults_kids='true']")?>
            <?= do_shortcode("[oneclick_search_form_bikes adults_kids='true']")?>
        </div>
        <div class="cifraajax"></div>
	</div>
    <?php


    echo ob_get_clean();
}

