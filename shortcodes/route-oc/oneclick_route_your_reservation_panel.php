<?php 

add_shortcode( 'oneclick_route_your_reservation_panel', 'oneclick_route_your_reservation_panel' );
  
function oneclick_route_your_reservation_panel($atts) {
    extract( shortcode_atts( array(
        'route' => '',
    ), $atts ) );

    
    ob_start();

    ?>
    <div class="oc-route-your-reservation-header">
        <div class="oc-route-your-reservation-title">
            <h4><?php echo __('Your reservation','wm-child-cyclando'); ?></h4>
        </div>
        <div class="oc-route-your-reservation-modify">
            <p id="oc-route-your-reservation-modify"><span><?php echo __('Modify','wm-child-cyclando'); ?></span></p>
        </div>
    </div>


    <script>
        (function ($) {
            $(document).ready(function () {
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

