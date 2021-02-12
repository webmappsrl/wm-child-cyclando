<?php 

add_shortcode( 'oneclick_search_form_participants_bikes', 'oneclick_search_form_participants_bikes' );
  
function oneclick_search_form_participants_bikes() {

    ob_start();


    ?>
    <div class="oc-participants-bici-container">
        <?= do_shortcode("[oneclick_search_form_participants]")?>
        <?= do_shortcode("[oneclick_search_form_bikes]")?>
	</div>
    <?php


    echo ob_get_clean();
}

