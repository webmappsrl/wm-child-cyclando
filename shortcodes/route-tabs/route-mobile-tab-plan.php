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
    $min_kid_age = '';
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
    if (count($has_hotel_category['modelseasonal']) >= 1) {
        $product_sample = $has_hotel_category['modelseasonal'][array_key_first($has_hotel_category['modelseasonal'])];
    } else {
        $product_sample = $has_hotel_category['model'][array_key_first($has_hotel_category['model'])];
    }
    if ($product_sample) {
        foreach ($product_sample as $key => $value) {
            // Activate kid select if there is any
            if (strpos($key,'kid') !== false) {
                $has_kids = true;
            }
            // Activate single room select if there is any
            if ($key == 'adult-single') {
                $has_single = true;
            }
            // Set min kid age select if there is any
            if (strpos($key,'kid') !== false) {
                $ageSplit = explode('_',$key);
                if ($ageSplit[2]) {
                    $min_kid_age = $ageSplit[2];
                }
            }
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
            <?= do_shortcode("[oneclick_search_form_participants route='true' has_kids='$has_kids' min_kid_age='$min_kid_age']")?>
        <?php if ($has_single) { ?>
            <?= do_shortcode("[oneclick_route_form_single_room]")?>
            <?php } ?>
        <?php if ($has_bike || $has_ebike) { ?>
            <?= do_shortcode("[oneclick_search_form_bikes route='true' has_bike='$has_bike' has_ebike='$has_ebike' ]")?>
        <?php } else { ?>
            <div id="" class="oc-input-btn selected"><?php echo __('Bikes are included','wm-child-cyclando'); ?></div>
        <?php } ?>
        </div>
        <div class="oc-route-mobile-plan-summary-container">
            <h4><?= __('Best price for', 'wm-child-cyclando') ?></h4>
            <div class="oc-route-mobile-plan-summary"></div>
        </div>
        <div class="oc-route-mobile-plan-price-container">
            <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div><div class="cifraajax"></div>
        </div>
        <div class="cyc-single-route-cta-buttons">
            <div id="cy-contact-in-basso" class="">
                <div class="cy-btn-contact">
                    <p><?php echo __('Contact us', 'wm-child-cyclando'); ?></p>
                </div>
            </div>
        </div>
	</div>
    <?php


    echo ob_get_clean();
}

