<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan($atts) {
    extract( shortcode_atts( array(
        'post_id' => '',
        'first_departure' => '',
    ), $atts ) );

    $has_hotel_category = array();
    $product_sample = array();
    $has_category = false;
    $has_kids = false;
    $has_single = false;
    $has_bike = false;
    $has_ebike = false;
    $has_hotel_category = route_has_hotel_category($post_id,$first_departure);
    $has_extra_category = route_has_extra_category($post_id);
    // echo '<pre>';
    // print_r($has_hotel_category);
    // echo '</pre>';
    if (count($has_hotel_category['model']) > 1 || count($has_hotel_category['modelseasonal']) > 1) {
        $has_category = true;
    }
    if (count($has_hotel_category['modelseasonal']) > 1) {
        $product_sample = $has_hotel_category['modelseasonal'][array_key_first($has_hotel_category['modelseasonal'])];
    } else {
        $product_sample = $has_hotel_category['model'][array_key_first($has_hotel_category['model'])];
    }
    foreach ($product_sample as $key => $value) {
        if (strpos($key,'kid') !== false) {
            $has_kids = true;
        }
        if ($key == 'adult-single') {
            $has_single = true;
        }
    }
    if (is_array($has_extra_category)){
        if (array_key_exists('bike', $has_extra_category['name'])) {
            $has_bike = true;
        }
        if (array_key_exists('ebike', $has_extra_category['name'])) {
            $has_ebike = true;
        }
    }
    ob_start();


    ?>
    <div class="oc-route-mobile-search-form-container">
        <?= do_shortcode("[oneclick_route_form_datepicker]")?>
        <?php if ($has_category) { ?>
            <?= do_shortcode('[oneclick_route_form_category post_id="'.$post_id.'" first_departure="'.$first_departure.'"]')?>
        <?php } ?>
        <div class="oc-route-mobile-search-form-asbb-wrapper">
            <?= do_shortcode("[oneclick_search_form_participants route='true' has_kids=$has_kids ]")?>
        <?php if ($has_single) { ?>
            <?= do_shortcode("[oneclick_route_form_single_room]")?>
            <?php } ?>
        <?php if ($has_bike || $has_ebike) { ?>
            <?= do_shortcode("[oneclick_search_form_bikes route='true' has_bike=$has_bike has_ebike=$has_ebike ]")?>
        <?php } ?>
        </div>
        <div class="cifraajax"></div>
	</div>
    <?php


    echo ob_get_clean();
}

