<?php

add_action( 'wp_ajax_nopriv_oc_ajax_variation_create_modal', 'oc_ajax_variation_create_modal' );
add_action( 'wp_ajax_oc_ajax_variation_create_modal', 'oc_ajax_variation_create_modal' );
function oc_ajax_variation_create_modal(){
    $products = $_POST['products']; 
    $varname = $_POST['varname']; 
    $seasonname = $_POST['seasonname']; 
    $res = oc_ajax_variation_create_modal_request($products,$varname,$seasonname);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_create_modal_request($products,$varname,$seasonname){

    $res = false;

    foreach (json_decode($products) as $id => $price) {
        $variation_data =  array(
            'regular_price' => $price,
        );
        $res = create_product_variation( $id, $variation_data ,$varname);
    }


    $outcome = 'false';
    if ($res !== false || $res !== null) {
        $outcome = 'true';
    }

    $output = `<tr>
    $res
    </tr>`;
    $response = array(
        'response'=>$outcome,
        'output'=> $output
    );
    return $response;
};


function create_product_variation( $product_id, $variation_data ,$varname){
    // Get the Variable product object (parent)
    $product = wc_get_product($product_id);

    $variation_post = array(
        'post_title'  => $product->get_name(),
        'post_name'   => $varname,
        'post_status' => 'publish',
        'post_parent' => $product_id,
        'post_type'   => 'product_variation',
        'guid'        => $product->get_permalink()
    );

    // Creating the product variation
    $variation_id = wp_insert_post( $variation_post );

    // Get an instance of the WC_Product_Variation object
    $variation = new WC_Product_Variation( $variation_id );


    ## Set/save all other data

    // SKU
    if( ! empty( $variation_data['sku'] ) )
        $variation->set_sku( $variation_data['sku'] );

    // Prices
    if( empty( $variation_data['sale_price'] ) ){
        $variation->set_price( $variation_data['regular_price'] );
    } else {
        $variation->set_price( $variation_data['sale_price'] );
        $variation->set_sale_price( $variation_data['sale_price'] );
    }
    $variation->set_regular_price( $variation_data['regular_price'] );

    // Stock
    if( ! empty($variation_data['stock_qty']) ){
        $variation->set_stock_quantity( $variation_data['stock_qty'] );
        $variation->set_manage_stock(true);
        $variation->set_stock_status('');
    } else {
        $variation->set_manage_stock(false);
    }
    
    $variation->set_weight(''); // weight (reseting)

    return $variation->save(); // Save the data
}