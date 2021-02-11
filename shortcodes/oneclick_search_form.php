<?php 

add_shortcode( 'oneclick_search_form', 'webmapp_oneclick_search_form' );
  
function webmapp_oneclick_search_form() {

    ob_start();


    ?>
    <div class="wpb_text_column  cy-search-form-container">
		<div class="wpb_wrapper">
            <div id="cy-search-element-container"><?= do_shortcode("[facetwp facet='dove_vuoi_andare'][facetwp facet='quando_vuoi_partire']")?><?= do_shortcode("[oneclick_search_form_participants]")?><div id="cy-search-lente"><i class="cy-icons icon-search1"></i><?= __('Find a trip','wm-child-cyclando')?></div></div>
            <div id="cy-search-template-container"><?= do_shortcode("[facetwp template='home_dove_vuoi_andare']")?></div>
		</div>
	</div>
    <?php


    echo ob_get_clean();
}

