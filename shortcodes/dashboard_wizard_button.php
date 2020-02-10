<?php 

add_action('wp_dashboard_setup', 'webmapp_wizard_dashboard_button');
  
function webmapp_wizard_dashboard_button() {
global $wp_meta_boxes;

wp_add_dashboard_widget('custom_help_widget', 'Webmapp wizard', 'wizard_button', 'dashboard', 'normal', 'high');
}
 
function wizard_button() {
    echo '<p>Crea una nuova route:</p>';
    echo '<button  id="dialogButton">Nuova route</button >';
    echo '<div id="dialog" title="Basic dialog">';
    echo do_shortcode("[testAngular]");
    echo '</div>';
    ?> 
    <script>
        $( function() {
            $( "#dialog" ).dialog({
                autoOpen: false, //FALSE if you open the dialog with, for example, a button click
                title: 'Nuova route',
                modal: true
            });

            // add the onclick handler
            $("#dialogButton").click(function() {
                $("#dialog").dialog("open");
                return false;
            });
        } );
    </script>
    <?php
}