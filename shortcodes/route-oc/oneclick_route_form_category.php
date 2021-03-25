<?php 

add_shortcode( 'oneclick_route_form_category', 'oneclick_route_form_category' );
  
function oneclick_route_form_category($atts) {
    extract( shortcode_atts( array(
        'post_id' => '',
        'first_departure' => '',
    ), $atts ) );

    $has_hotel_category = array();
    $categories = array();
    $has_hotel_category = route_has_hotel_category($post_id,$first_departure);
    
    if (is_array($has_hotel_category['model']) && count($has_hotel_category['model']) > 1) {
        $categories = $has_hotel_category['model'];
    } elseif (is_array($has_hotel_category['modelseasonal']) && count($has_hotel_category['modelseasonal']) > 1) {
        $categories = $has_hotel_category['modelseasonal'];
    }
    ob_start();

    ?>
    <div class="category-select-holder">
        <select>
            <option selected="selected" disabled="disabled"><?= __('Select a category', 'wm-child-cyclando') ?></option>
        <?php 
        if ($categories) { 
            foreach ($categories as $hotel) {
        ?>
                <option value="<?= $hotel ?>"><?= $hotel ?></option>
        <?php 
            } 
        }
        ?>
        </select>
    </div> 
    <script>
    (function ($) {
        $(document).ready(function () {
            $( function() {
                $('select').on('change', function() {
                    var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie')); 
                    savedCookie['category'] = this.value;
                    console.log(savedCookie['category']);
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

