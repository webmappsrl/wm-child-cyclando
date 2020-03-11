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
<<<<<<< HEAD
            <?php
            $conf = "routeWizard,singleFieldRouteWizard";
            echo do_shortcode ("[wmWizards conf='$conf']");
            ?>
        </div>
    <?php } 
=======
        </div>
    <?php } ?>

    <script>
        jQuery(document).ready(function($) {
            const $routeWizard = $("#wm-wizards-container");
            $.post(ajaxurl, {
                action: 'getAngularApp',
                name: 'wmWizards',
                conf: 'routeWizard'
            }, function(response) {
                $routeWizard.html(response);
            });
        });
    </script>
<?php

>>>>>>> 7c1de4cdbbac69a184022149ecc7a97fbf339639
    echo ob_get_clean();
}
