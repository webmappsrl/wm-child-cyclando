<?php 

// action that process ajax call : webmapp_anypost-cy_route_advancedsearch-oneclick.php to update route price
add_action( 'wp_ajax_nopriv_oc_ajax_route_price', 'oc_ajax_route_price' );
add_action( 'wp_ajax_oc_ajax_route_price', 'oc_ajax_route_price' );
function oc_ajax_route_price(){
    $cookies = $_POST['cookies'];
    $post_id = $_POST['postid']; 
    (!isset($cookies)) ?  $price = (float)get_field('wm_route_price', $post_id) : $price = oc_ajax_price_calculate($cookies,$post_id);    
    
    echo json_encode($price);
    wp_die();
}

function oc_ajax_price_calculate($cookies,$post_id){
    $product = new routeProductsOC($cookies,$post_id);
    $price = $product->calculatePrice();
    return $price;
};


