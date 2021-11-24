<?php 

add_shortcode( 'route_mobile_tab_plan', 'route_mobile_tab_plan' );
  
function route_mobile_tab_plan($atts) {
    extract( shortcode_atts( array(
        'post_id' => '',
        'first_departure' => '',
        'has_extra' => '',
        'hotel_product_items' => ''
    ), $atts ) );


    $has_hotel_category = array();
    $product_sample = array();
    $has_category = 0;
    $has_kids = 0;
    $min_kid_age = '';
    $has_bike = 0;
    $has_ebike = 0;
    $has_single = false;
    $has_hotel_category = route_has_hotel_category($post_id,$first_departure);
    $has_extra_category = route_has_extra_category($post_id);
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
            // Set min kid age select if there is any
            if (strpos($key,'kid') !== false) {
                $ageSplit = explode('_',$key);
                if ($ageSplit[2]) {
                    $min_kid_age = $ageSplit[2];
                }
            }
            // Activate single room select if there is any
            if ($key == 'adult-single') {
                $has_single = true;
            }
        }
    }
    if (is_array($has_extra_category)){
        if (array_key_exists('bike', $has_extra_category)) {
            $has_bike = true;
        }
        if (array_key_exists('ebike', $has_extra_category)) {
            $has_ebike = true;
        }
    }
    $coming_soon = get_field('not_salable',$post_id);

    ob_start();


    ?>
    <div class="oc-route-mobile-search-form-container">
        <?php if ($coming_soon) :?>
            <h4 class="form-lable"><?php echo __('On request','wm-child-cyclando'); ?></h4>
            <div class="cyc-single-route-contact-button-container">
                <p class="label"><?php 
                    if ($coming_soon) {
                        echo __('Do you want to request a quote for this tour?', 'wm-child-cyclando');
                    } else {
                        // echo __('Do you have doubts about your quote?', 'wm-child-cyclando');
                    } 
                ?></p>
                <div id="cy-contact-in-plan-tab" class="cy-contact-in-basso cy-contact-in-plan-tab">
                    <div class="cy-btn-plan-contact">
                        <p><?php echo __('Request information', 'wm-child-cyclando'); ?></p>
                    </div>
                </div>
            </div>
        <?php else:?>
            <?= do_shortcode('[onclick_route_mobile_calculator_header back_btn="close-calculator" scheda_name="calculator"]')?>
            <h4 class="form-lable"><?php echo __('Calculate your quote', 'wm-child-cyclando'); ?></h4>
            <p class="oc-route-mobile-search-form-label-p"><?php echo __('Select the departure date', 'wm-child-cyclando'); ?></p>
            <?= do_shortcode("[oneclick_route_form_datepicker]")?>
            <?php if ($has_category) { ?>
                <?= do_shortcode('[oneclick_route_form_category post_id="'.$post_id.'" first_departure="'.$first_departure.'"]')?>
            <?php } ?>
            <p class="oc-route-mobile-search-form-label-p"><?php echo __('Select the number of participants and bikes', 'wm-child-cyclando'); ?></p>
            <div class="oc-route-mobile-search-form-asbb-wrapper">
                <?= do_shortcode("[oneclick_route_search_form_participants route='true' has_kids='$has_kids' min_kid_age='$min_kid_age']")?>
            <?php if ($has_bike || $has_ebike) { ?>
                <?= do_shortcode("[oneclick_route_search_form_bikes route='true' has_bike='$has_bike' has_ebike='$has_ebike' ]")?>
            <?php } else { ?>
                <div class="selected bikes-included"><?php echo __('Bikes are included','wm-child-cyclando'); ?></div>
            <?php } ?>
            <?php if ($has_single) { ?>
                <?= do_shortcode("[oneclick_route_search_form_single route='true']")?>
            <?php } ?>
            </div>
            <div class="oc-route-mobile-plan-summary-container">
                <h4><?= __('Best price for', 'wm-child-cyclando') ?></h4>
                <div class="oc-route-mobile-plan-summary"></div>
            </div>
            <?php promoBannerOnRouteSummary();?>
            <div class="oc-route-mobile-plan-price-container">
                <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div><div class="cifraajax"></div>
            </div>
            <div class="oc-route-mobile-plan-exclusive-online">
                <div class="exclusive-online-title"><?= __('Online Exclusive!', 'wm-child-cyclando') ?></div>
            </div>
            <?= do_shortcode("[oneclick_route_form_purchase route='true' hotel_product_items='$hotel_product_items' first_departure='$first_departure']")?>
        <?php endif;?>
        
	</div>
    <div class="oc-route-mobile-your-reservation-container">
        <?= do_shortcode("[oneclick_route_your_reservation_panel route='true' hotel_product_items='$hotel_product_items']")?>
    </div>
    <script>
        (function ($) {
            $(document).ready(function () {
                var has_bike = <?= $has_bike ?>;
                var has_ebike = <?= $has_ebike ?>;
                if (!has_bike && !has_ebike) {
                    var savedCookie = ocmCheckCookie();
                    delete savedCookie['electric'];
                    delete savedCookie['regular'];
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                }
            });
        })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

