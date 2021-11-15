<?php 

// action that process ajax call : webmapp_anypost-cy_route_advancedsearch-oneclick.php to update route price
add_action( 'wp_ajax_nopriv_oc_ajax_variation_price_update', 'oc_ajax_variation_price_update' );
add_action( 'wp_ajax_oc_ajax_variation_price_update', 'oc_ajax_variation_price_update' );
function oc_ajax_variation_price_update(){
    $post_id = $_POST['variationid']; 
    $price = $_POST['variationprice']; 
    $res = oc_ajax_variation_price_update_request($price,$post_id);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_price_update_request($price,$post_id){
    update_post_meta( $post_id, '_regular_price', $price );
    $res = update_post_meta( $post_id, '_price', $price );
    wc_delete_product_transients( $post_id ); 
    return $res;
};


