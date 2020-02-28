<?php 

add_action('wp_dashboard_setup', 'webmapp_wizard_dashboard_button');
  
function webmapp_wizard_dashboard_button() {

wp_add_dashboard_widget('custom_help_widget', 'Webmapp wizard', 'wizard_button', 'dashboard', 'normal', 'high');
}
 
function wizard_button() {
    ob_start();
    ?>
    <p>Crea una nuova route:</p>
    <button class="button button-primary button-hero load-customize hide-if-no-customize" id="dialogButton">Nuova route</button>
    <?php if ($_SERVER['SERVER_NAME'] !== 'cyclando.com') {?>
    <div id="wm-wizards-dialog-container" title="Basic dialog" style="display:none">
        <?php 
        $conf = array(
            'routeWizard' =>  WebMapp_getWizardConfiguration() ,
            'singleFieldRouteWizard' =>  WebMapp_getWizardConfiguration('singleFieldRouteWizard')
        );
        echo do_shortcode("[wmWizards conf='" . json_encode($conf) . "']"); 
        ?>
    </div> 
    
    <?php } ?>

    <script>
        jQuery(document).ready(function ($) {
            $( "#wm-wizards-dialog-container" ).dialog({
                autoOpen: false, //FALSE if you open the dialog with, for example, a button click
                closeOnEscape: false,
                modal: true,
                width: "auto",
                height: "auto",
                create: function( event, ui ) {
                    // Set maxWidth
                    $(this).css({
                            "min-width" : (window.innerWidth / 100 * 90) + 'px',
                    });

                    $(this).parents('.ui-dialog').css({
                        "min-height" : (window.innerHeight / 100 * 90) + 'px',
                    });
                }
            });

            // add the onclick handler
            $("#dialogButton").click(function() {
                $("#wm-wizards-dialog-container").dialog("open");
                return false;
            });


        } );
    </script>
    <?php

    echo ob_get_clean();
}