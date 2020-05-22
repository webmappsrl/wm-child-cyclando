<?php
add_shortcode( 'mobile_menu_quote_form', 'mobile_menu_quote_form_function' );
function mobile_menu_quote_form_function() {
    ob_start();
    
    $post_id = get_the_ID();
    $coming_soon = get_field('not_salable');
    if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
        $language = ICL_LANGUAGE_CODE;
    } else {
        $language = 'it';
    }
    ?>
    <?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) === false) {?>
    <div id="wm-book-quote" class="meta-bar wm-book long-txt">
        <p class='meta-bar-txt-bold'><?php echo __('Quote', 'wm-child-cyclando'); ?></p>
        <a  target="_blank" href="https://cyclando.com/quote/#/<?php echo $post_id.'?lang='.$language;?>">
        </a>
    </div>
    <?php } else { ?>
        <div id="in-basso-mobile" class="meta-bar wm-book long-txt">
            <p class='meta-bar-txt-bold'><?php echo __('Contact us', 'wm-child-verdenatura'); ?></p>
        </div>
    <?php } ?>
    <?php 
    echo ob_get_clean();
}