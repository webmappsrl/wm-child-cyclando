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

function create_variable_product_with_variations( $product_name, $products, $category, $attributeName,$varnames ){

    $product_id = wp_insert_post( array(
        'post_title' => $product_name,
        'post_status' => 'publish',
        'post_type' => "product",
        ) );
    wp_set_object_terms( $product_id, 'variable', 'product_type' );
    
    $product = new WC_Product_Variable( $product_id );

    // Add category to product
    wp_set_object_terms( $product_id, $category, 'product_cat' );

    // Visibility ('hidden', 'visible', 'search' or 'catalog')
    $product->set_catalog_visibility( 'visible' );
    $product->save();
    
    //------------------------------------------------------------------------
    $attr_slug = sanitize_title($attributeName);

    $attributes_array[$attr_slug] = array(
        'name' => $attributeName,
        'value' => implode('|',$varnames),
        'is_visible' => '1',
        'is_variation' => '1',
        'is_taxonomy' => '0' // for some reason, this is really important       
    );
    update_post_meta( $product_id, '_product_attributes', $attributes_array );
    $product->save();
    WC_Product_Variable::sync( $product_id );

    foreach( $products as $variationname => $price) {
        if ($variationname !== 'attribute_name') {
            $variation_post = array(
                'post_title'  => $product->get_name(),
                'post_name'   => 'product-'.$product_id.'-variation',
                'post_status' => 'publish',
                'post_parent' => $product_id,
                'post_type'   => 'product_variation',
            );
        
            // Creating the product variation
            $variation_id = wp_insert_post( $variation_post );
        
            // Get an instance of the WC_Product_Variation object
            $variation = new WC_Product_Variation( $variation_id );
        
            $variation->set_attributes([$attr_slug => $variationname]);
            
            // Prices
            $variation->set_price( intval($price) );
            $variation->set_regular_price( intval($price) );
        
            // Stock
            $variation->set_manage_stock(false);
        
            WC_Product_Variable::sync( $product_id );
        
            $variation->save(); // Save the data
        }
    }
    
    //------------------------------------------------------------------------



    $product_id = $product->save();

    return $product_id;
}


function sync_route_acf_with_new_product($repeatername,$repeaterrawid,$subfieldkey,$routeid,$product_id) {
    if ( $repeatername == 'false') {
        // get current value of acf 
        $values = get_field($subfieldkey,$routeid, false);
        $new_values = array();
        foreach ($values as $val)   {
            array_push($new_values,intval($val));
        }
        // add new id to the array
        $new_values[] = $product_id;

        return update_field( $subfieldkey, $new_values, $routeid );
    } else {
        // get current value of acf 
        $rows = get_field($repeatername,$routeid, false);
        $rawid = intval($repeaterrawid) - 1;
        $values = $rows[$rawid][$subfieldkey];
        $new_values = array();
        foreach ($values as $val)   {
            array_push($new_values,intval($val));
        }
        // add new id to the array
        $new_values[] = $product_id;
    
        return update_sub_field( array($repeatername, intval($repeaterrawid), $subfieldkey), $new_values, $routeid );
    }
}