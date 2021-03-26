<?php 

add_shortcode( 'oneclick_route_form_single_room', 'oneclick_route_form_single_room' );
  
function oneclick_route_form_single_room() {

    ob_start();

    ?>
    <div class="single-room-select-holder oc-route-select-holder">
        <select>
            <option selected="selected" disabled="disabled"><?= __('Single', 'wm-child-cyclando') ?></option>
        </select>
    </div> 

    <script>
    (function ($) {
        $(document).ready(function () {
            $( function() {
                calcSigleSelectOptions();

                $('.single-room-select-holder select').on('change', function() {
                    var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                    savedCookie['single'] = this.value;
                    console.log(savedCookie['single']);
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 7, path: '/' });
                    ajaxUpdatePrice();
                });
            });
        });
        

    })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

