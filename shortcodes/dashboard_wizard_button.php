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
    <button class="button button-primary button-hero load-customize hide-if-no-customize" id="dialogButton">Nuova route</button>
    <button class="button button-primary button-hero load-customize hide-if-no-customize" id="tfRoutedialogButton">Tf Nuova route</button>
    <?php if ($_SERVER['SERVER_NAME'] !== 'cyclando.com') { ?>
        <div id="wm-wizards-dialog-container" title="Basic dialog" style="display:none">
        </div>
        <div id="wm-wizards-tfRoute-container" title="Basic dialog" style="display:none">
        </div>
    <?php } ?>

    <script>
        jQuery(document).ready(function($) {

            const $routeWizard = $("#wm-wizards-dialog-container");
            $routeWizard.dialog({
                autoOpen: false, //FALSE if you open the dialog with, for example, a button click
                closeOnEscape: false,
                modal: true,
                width: "auto",
                height: "auto",
                create: function(event, ui) {
                    // Set maxWidth
                    $(this).css({
                        "min-width": (window.innerWidth / 100 * 90) + 'px',
                    });
                    $(this).parents('.ui-dialog').css({
                        "min-height": (window.innerHeight / 100 * 90) + 'px',
                    });
                }
            });

            // add the onclick handler
            $("#dialogButton").click(function() {
                const $this = $(this);
                $.post(ajaxurl, {
                    action: 'getAngularApp',
                    name: 'wmWizards',
                    conf: ''
                }, function(response) {
                    $routeWizard.html(response);
                });
                $routeWizard.dialog("open");
                $routeWizard.on('dialogclose', function(event) {
                    $routeWizard.html('');
                });

                return false;
            });


            const $tfRouteWizard = $("#wm-wizards-tfRoute-container");
            $tfRouteWizard.dialog({
                autoOpen: false, //FALSE if you open the dialog with, for example, a button click
                closeOnEscape: false,
                modal: true,
                width: "auto",
                height: "auto",
                create: function(event, ui) {
                    // Set maxWidth
                    $(this).css({
                        "min-width": (window.innerWidth / 100 * 90) + 'px',
                    });
                    $(this).parents('.ui-dialog').css({
                        "min-height": (window.innerHeight / 100 * 90) + 'px',
                    });
                }
            });

            // add the onclick handler
            $("#tfRoutedialogButton").click(function() {
                const $this = $(this);
                $.post(ajaxurl, {
                    action: 'getAngularApp',
                    name: 'wmWizards',
                    conf: 'singleFieldRouteWizard'
                }, function(response) {
                    $tfRouteWizard.html(response);
                });
                $tfRouteWizard.dialog("open");
                $tfRouteWizard.on('dialogclose', function(event) {
                    $tfRouteWizard.html('');
                });

                return false;
            });


        });
    </script>
<?php

    echo ob_get_clean();
}
