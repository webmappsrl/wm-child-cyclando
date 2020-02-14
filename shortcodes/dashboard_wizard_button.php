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
    <div id="dialog" title="Basic dialog">
        <?php echo do_shortcode("[testAngular conf='". WebMapp_getWizardConfiguration() ."']"); ?>
    </div> 
    <?php } ?>

    <script>
        jQuery(document).ready(function () {
            jQuery( "#dialog" ).dialog({
                autoOpen: false, //FALSE if you open the dialog with, for example, a button click
                closeOnEscape: false,
                modal: true,
                width: "auto",
                height: 600
            });

            // add the onclick handler
            jQuery("#dialogButton").click(function() {
                jQuery("#dialog").dialog("open");
                return false;
            });
        } );
    </script>
    <?php

    echo ob_get_clean();
}