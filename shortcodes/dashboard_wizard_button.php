<?php

add_action('wp_dashboard_setup', 'webmapp_wizard_dashboard_button');

function webmapp_wizard_dashboard_button()
{

    wp_add_dashboard_widget('custom_help_widget', 'Webmapp wizard', 'wizard_button', 'dashboard', 'normal', 'high');
}

function wizard_button()
{
    ob_start();
?>
    <p>Crea una nuova route:</p>
    <?php if ($_SERVER['SERVER_NAME'] !== 'cyclando.com') { ?>
        <div id="wm-wizards-container">
            <?php
            $conf = "routeWizard,singleFieldRouteWizard";
            echo do_shortcode ("[wmWizards conf='$conf']");
            ?>
        </div>
    <?php }
    echo ob_get_clean();
}