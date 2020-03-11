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

    echo ob_get_clean();
}
