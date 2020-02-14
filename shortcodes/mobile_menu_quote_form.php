<?php
add_shortcode( 'mobile_menu_quote_form', 'mobile_menu_quote_form_function' );
function mobile_menu_quote_form_function() {
ob_start();

$post_id = get_the_ID();
if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
	$language = ICL_LANGUAGE_CODE;
} else {
	$language = 'it';
}
?>
<div id="wm-book" class="meta-bar wm-book">
    <p class='meta-bar-txt-bold'><?php echo __('Make a quote', 'wm-child-verdenatura'); ?></p>
    <a  target="_blank" href="http://quote.cyclando.com/#/<?php echo $post_id.'?lang='.$language;?>">
    </a>
</div>
<?php 
ob_get_clean();
}