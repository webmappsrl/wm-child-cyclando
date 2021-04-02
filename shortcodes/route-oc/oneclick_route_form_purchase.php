<?php 

add_shortcode( 'oneclick_route_form_purchase', 'oneclick_route_form_purchase' );
  
function oneclick_route_form_purchase($atts) {
    extract( shortcode_atts( array(
        'route' => '',
    ), $atts ) );

    
    ob_start();

    ?>
    <div class="cyc-single-route-cta-buttons oc-acquista-route">
        <div id="oc-acquista-route" class="">
            <div class="cy-btn-contact">
                <p><?php echo __('Purchase', 'wm-child-cyclando'); ?></p>
            </div>
        </div>
    </div>


    <!-- HTML modal for extras, proceed btn -->
    <div id="oc-extras-modal" class="ocm-proceed-container">
        <div class="ocm-proceed-content">
            <div class="ocm-extras-proceed-detail-container">
                <div class="ocm-proceed-extras-body ">
                    <div class="facetwp-checkbox checked"><div class="label"><?= __('Supplemento nolo casco adulto (25.50)‎€','wm-child-cyclando')?></div></div>
                    <button class="modal-btn oc-substract-btn" name="adult-participants"><i class="fas fa-minus"></i></button>
                    <div id="adult-participants" class="oc-number-input">2</div>
                    <button class="modal-btn oc-add-btn" name="adult-participants"><i class="fas fa-plus"></i></button>
                </div>
                <div class="ocm-proceed-extras-body ">
                    <div class="facetwp-checkbox"><div class="label"><?= __('Borse laterali','wm-child-cyclando')?></div></div>
                    <button class="modal-btn oc-substract-btn" name="adult-participants"><i class="fas fa-minus"></i></button>
                    <div id="adult-participants" class="oc-number-input">0</div>
                    <button class="modal-btn oc-add-btn" name="adult-participants"><i class="fas fa-plus"></i></button>
                </div>
            </div>
            <div class="ocm-extras-proceed-summary-container">
                <div class="oc-route-mobile-plan-price-container">
                    <div class="cifraajax-title"><?= __('Total', 'wm-child-cyclando') ?></div><div class="cifraajax"></div>
                </div>
                <div id="oc-proceed-done-btn" class="oc-proceed-done-btn"><?= __('Proceed','wm-child-cyclando')?></div>
            </div>
        </div>
    </div>
    <script>
        (function ($) {
            $(document).ready(function () {
                $('#oc-acquista-route .cy-btn-contact').on('click',function(){
                    $('.ocm-proceed-container').show();
                });
                $('.oc-proceed-done-btn').on('click',function(){
                    $('.ocm-proceed-container').hide();
                    $('.oc-route-mobile-search-form-container').hide();
                    $('.oc-route-mobile-your-reservation-container').show();
                });
                window.addEventListener('click', outsideClick);
                // Close If Outside Click
                function outsideClick(e) {
                    if (e.target.id == 'oc-extras-modal') {
                        $('.ocm-proceed-container').hide();
                    }
                }
            });
        })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

