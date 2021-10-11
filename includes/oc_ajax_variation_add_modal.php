<?php

use function WPMailSMTP\Vendor\GuzzleHttp\json_decode;

add_action( 'wp_ajax_nopriv_oc_ajax_variation_add_modal', 'oc_ajax_variation_add_modal' );
add_action( 'wp_ajax_oc_ajax_variation_add_modal', 'oc_ajax_variation_add_modal' );
function oc_ajax_variation_add_modal(){
    $productarray = $_POST['productarray']; 
    $routeid = $_POST['routeid']; 
    $res = oc_ajax_variation_add_modal_request($productarray,$routeid);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_add_modal_request($productarray,$routeid){

    $productarray = json_decode($productarray);

    $res = '';

    foreach($productarray as $id => $name) {
        $res .= $id;
    }

    $outcome = 'false';
    if ($res !== false || $res !== null) {
        $outcome = 'true';
    }
    $response = array(
        'response'=>$outcome,
        'productid'=>$res
    );
    return $response;
};


