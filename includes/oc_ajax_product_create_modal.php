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

    $res = 'true';

    $outcome = 'false';
    if ($res) {
        $outcome = 'true';
    }

    $output = get_permalink($routeid).'?dateprices=true&seasonid='.$seasonnameid;
    $response = array(
        'response'=>$outcome,
        'output'=> $output
    );
    return $response;
};


function create_product( $product_id, $variation_data ,$varname, $catname, $newvarname){
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $attr_label = $catname;
    $attr_slug = sanitize_title($attr_label);

    $attributes_array[$attr_slug] = array(
        'name' => $attr_label,
        'value' => implode('|',$varname),
        'is_visible' => '1',
        'is_variation' => '1',
        'is_taxonomy' => '0' // for some reason, this is really important       
    );
    update_post_meta( $product_id, '_product_attributes', $attributes_array );
    $product->save();

    $variation_post = array(
        'post_title'  => $product->get_name(),
        'post_name'   => 'product-'.$product_id.'-variation',
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );

    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation( $variation_id );

    $variation->set_attributes([$attr_slug => $newvarname]);
    
    // Prices
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );

    // Stock
    $variation->set_manage_stock(false);
    WC_Product_Variable::sync( $product_id );

    return $variation->save(); // Save the data
}