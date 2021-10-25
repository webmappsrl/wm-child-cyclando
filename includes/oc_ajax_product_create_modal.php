<?php

add_action( 'wp_ajax_nopriv_oc_ajax_product_create_modal', 'oc_ajax_product_create_modal' );
add_action( 'wp_ajax_oc_ajax_product_create_modal', 'oc_ajax_product_create_modal' );
function oc_ajax_product_create_modal(){
    $products = $_POST['products']; 
    $productarray = $_POST['productarray']; 
    $routeid = $_POST['routeid']; 
    $seasonname = $_POST['seasonname']; 
    $seasonnameid = $_POST['seasonnameid']; 
    $repeaterrawid = $_POST['repeaterrawid']; 
    $subfieldkey = $_POST['subfieldkey']; 
    $repeatername = $_POST['repeatername']; 
    $res = oc_ajax_product_create_modal_request($products,$productarray,$routeid,$seasonname,$seasonnameid,$repeaterrawid,$subfieldkey,$repeatername);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_product_create_modal_request($products,$productarray,$routeid,$seasonname,$seasonnameid,$repeaterrawid,$subfieldkey,$repeatername){

    // hotel = 454 , extra = 453
    $category = 'hotel';
    $output = get_permalink($routeid).'?dateprices=true#tab-'.$seasonnameid;
    $attributeName = '';
    $varnames = array();
    foreach ($products as $p => $val) {
        if ($p == 'attribute_name') {
            $attributeName = $val;
        } else {
            array_push($varnames,$p);
        }
    }
    
    $product_name = $routeid.'-hotel-'.$seasonnameid.'-'.$attributeName;
    $product_id = create_variable_product_with_variations( $product_name, $products, $category, $attributeName,$varnames );

    // function sync ACF product
    $acf_res = sync_route_acf_with_new_product($repeatername,$repeaterrawid,$subfieldkey,$routeid,$product_id);
    

    $outcome = 'false';
    if ($acf_res) {
        $outcome = 'true';
    }

    $response = array(
        'response'=>$outcome,
        'output'=> $output
    );
    return $response;
};

