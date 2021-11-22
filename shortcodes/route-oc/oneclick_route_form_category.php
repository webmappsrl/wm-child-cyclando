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
    <div class="category-select-holder oc-route-select-holder">
        <p class="oc-route-mobile-search-form-label-p"><?php echo __('Select a category', 'wm-child-cyclando'); ?></p>
        <?php 
        if ($categories) { 
            foreach ($categories as $name => $hotel) {
                ?>
                <div class="category-radio-wrapper">
                    <input type="radio" id="<?= $name ?>" name="categoryName" value="<?= $name ?>">
                    <label for="<?= $name ?>"><?= $name ?></label>
                </div>
                <?php 
            } 
        }
        ?>
    </div> 
    <script>
    (function ($) {
        $(document).ready(function () {
            $( function() {
                $('.category-select-holder input').on('change', function() {
                    var savedCookie = ocmCheckCookie();
                    savedCookie['category'] = this.value;
                    Cookies.set('oc_participants_cookie', JSON.stringify(savedCookie), { expires: 1, path: '/' });
                    ajaxUpdatePrice();
                });
            });
        });
    })(jQuery);
    </script>
    <?php


    echo ob_get_clean();
}

