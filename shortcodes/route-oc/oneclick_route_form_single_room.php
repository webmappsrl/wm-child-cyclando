<?php

add_shortcode('oneclick_route_form_single_room', 'oneclick_route_form_single_room');

function oneclick_route_form_single_room()
{

    ob_start();

?>
    
    <div class="ocm-proceed-extras-body ">
        <div class="facetwp-checkbox facetwp-checkbox-single_room" name="single_room" conditional="true">
            <div class="label"><?= __('Single room', 'wm-child-cyclando') ?></div>
        </div>
        <div class="oc-modal-button-container oc-modal-button-container-single_room">
            <button class="modal-btn oc-extra-substract-btn" name="single_room" conditional="true"><i class="fas fa-minus"></i></button>
            <div id="single_room" class="oc-number-input">0</div>
            <button class="modal-btn oc-extra-add-btn" name="single_room" conditional="true"><i class="fas fa-plus"></i></button>
        </div>
    </div>

        <script>
            (function($) {
                $(document).ready(function() {
                    $(function() {
                        calcSigleSelectOptions();

                        $('.single-room-select-holder select').on('change', function() {
                            var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
                            savedCookie['single'] = this.value;
                            Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), {
                                expires: 7,
                                path: '/'
                            });
                            // ajaxUpdatePrice();
                        });
                    });
                });


            })(jQuery);
        </script>
    <?php


    echo ob_get_clean();
}
