<?php 

require_once __DIR__ . '/class_routeProductsOC.php' ;

// action that process ajax call : webmapp_anypost-cy_route_advancedsearch-oneclick.php to update route price
add_action( 'wp_ajax_oc_ajax_route_price', 'oc_ajax_route_price' );
function oc_ajax_route_price(){
    $cookies = $_POST['cookies'];
    $post_id = $_POST['postid']; 
    (!isset($cookies)) ?  $price = (float)get_field('wm_route_price', $post_id) : $price = oc_ajax_price_calculate($cookies,$post_id);    
    
    echo json_encode($price);
    wp_die();
}

function oc_ajax_price_calculate($cookies,$post_id){
    $adults = $cookies['adults'];
    $kids = $cookies['kids'];
    $regular = $cookies['regular'];
    $electric = $cookies['electric'];
    $ages = $cookies['ages'];
    if ($ages){
        foreach ($ages as $age) {

        }
    }
    $product = new routeProductsOC($cookies,$post_id);
    $productList = $product->getHotelProducts();
    $price = intval($adults);
    return $productList;
};


