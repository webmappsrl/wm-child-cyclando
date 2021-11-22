<?php

add_shortcode('onclick_route_mobile_calculator_header', 'onclick_route_mobile_calculator_header');

function onclick_route_mobile_calculator_header($atts)
{
    extract(shortcode_atts(array(
        'scheda_name' => '',
        'back_btn' => ''
    ), $atts));

    if ($scheda_name == "reservation" ) {
        $margin = '-15px -15px 10px';
    }
    if ($scheda_name == "extras" ) {
        $margin = '0';
    }
    if ($scheda_name == "calculator" ) {
        $margin = '-15px -15px 0px';
    }
    ob_start();

?>
    <div class="mobile-calculator-header-wrapper" style="margin:<?= $margin ?>;">
        <div id='<?=$back_btn?>' class="go-back-btn">
            <i class="far fa-chevron-left"></i>
        </div>
        <div class="calculator-route-title">
            <?php the_title() ?>
        </div>
    </div>
<?php


    echo ob_get_clean();
}
