<?php 

add_shortcode( 'oneclick_route_your_reservation_panel', 'oneclick_route_your_reservation_panel' );
  
function oneclick_route_your_reservation_panel($atts) {
    extract( shortcode_atts( array(
        'route' => '',
    ), $atts ) );

    
    ob_start();

    ?>
    <div class="oc-route-your-reservation-row oc-route-your-reservation-header">
        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-title">
            <h4><?php echo __('Your reservation','wm-child-cyclando'); ?></h4>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-modify">
            <p id="oc-route-your-reservation-modify"><span><?php echo __('Modify','wm-child-cyclando'); ?></span></p>
        </div>
    </div>
    <div class="oc-route-your-reservation-row oc-route-your-reservation-details">

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-departure-title">
            <p><?php echo __('Departure','wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info">
            <p id="oc-route-your-reservation-departure"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-participant-title">
            <p><?php echo __('Participants','wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info">
            <p id="oc-route-your-reservation-participants"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-bikes-title">
            <p><?php echo __('Bicycles','wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-bikes-info">
            <p id="oc-route-your-reservation-bikes"></p>
        </div>

        <div class="oc-route-your-reservation-column-title oc-route-your-reservation-category-title">
            <p><?php echo __('Category','wm-child-cyclando'); ?></p>
        </div>
        <div class="oc-route-your-reservation-column-info oc-route-your-reservation-category-info">
            <p id="oc-route-your-reservation-category"></p>
        </div>
    </div>
    
    <div class="oc-route-mobile-plan-summary-container">
            <h4><?= __('Best price for', 'wm-child-cyclando') ?></h4>
            <div class="oc-route-mobile-plan-summary"></div>
    </div>
    <div class="oc-route-mobile-plan-price-container">
        <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div><div class="cifraajax"></div>
    </div>

    <script>
        (function ($) {
            $(document).ready(function () {

                // hide and show the plan Tab
                $('#oc-route-your-reservation-modify').on('click',function(){
                    $('.oc-route-mobile-your-reservation-container').hide();
                    $('.oc-route-mobile-search-form-container').show();
                });
            });
        })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

