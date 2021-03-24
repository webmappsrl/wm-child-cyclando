<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan($atts) {
    extract( shortcode_atts( array(
        'post_id' => '',
        'first_departure' => '',
    ), $atts ) );

    $has_hotel_category = array();
    $has_category = false;
    $has_hotel_category = route_has_hotel_category($post_id,$first_departure);
    if (count($has_hotel_category['model']) > 1 || count($has_hotel_category['modelseasonal']) > 1) {
        $has_category = true;
    }
    ob_start();


    ?>
    <div class="oc-route-mobile-search-form-container">
        <?= do_shortcode("[oneclick_route_form_datepicker]")?>
        <?php if ($has_category) { ?>
            <?= do_shortcode('[oneclick_route_form_category post_id="'.$post_id.'" first_departure="'.$first_departure.'"]')?>
        <?php } ?>
        <div class="oc-route-mobile-search-form-asbb-wrapper">
            <?= do_shortcode("[oneclick_search_form_participants route='true']")?>
            <?= do_shortcode("[oneclick_search_form_bikes route='true']")?>
        </div>
        <div class="cifraajax"></div>
	</div>
    <?php


    echo ob_get_clean();
}

