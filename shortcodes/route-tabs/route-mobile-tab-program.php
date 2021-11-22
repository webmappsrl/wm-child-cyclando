<?php 

add_shortcode( 'route_mobile_tab_program', 'route_mobile_tab_program' );
  
function route_mobile_tab_program($atts) {

    extract( shortcode_atts( array(
        'program' => '',
        'has_track' => '',
        'home_site' => '',
        'post_id' => '',
        'language' => '',
    ), $atts ) );
    ob_start();

        ?>
        <div class="route-program-body">
            <a class="load-more btn-program" href="#!"><p><?= __('Read more', 'wm-child-cyclando') ?><span> ...</span></p></a>
        </div>
        <a class="read-more btn-program" href="#!"><p><?= __('Read more', 'wm-child-cyclando') ?><span> ...</span></p></a>
        <a class="read-less btn-program" href="#program"><p><?= __('Show less', 'wm-child-cyclando') ?></p></a>
        <?php



    echo ob_get_clean();
}

