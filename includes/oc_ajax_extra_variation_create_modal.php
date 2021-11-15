<?php

add_action( 'wp_ajax_nopriv_oc_ajax_extra_variation_create_modal', 'oc_ajax_extra_variation_create_modal' );
add_action( 'wp_ajax_oc_ajax_extra_variation_create_modal', 'oc_ajax_extra_variation_create_modal' );
function oc_ajax_extra_variation_create_modal(){
    $productextra = $_POST['productextra']; 
    $varname = $_POST['varname']; 
    $price = $_POST['price'];  
    $routeid = $_POST['routeid'];  
    $res = oc_ajax_extra_variation_create_modal_request($productextra,$varname,$price,$routeid);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_extra_variation_create_modal_request($productextra,$varname,$price,$routeid){

    $product_name = $routeid.'-extra';
    $th_txt = wm_create_extra_variation_mapping();
    $output = "<tr id='dp_extratableraw_variation_".$varname."'><th>".$th_txt[$varname]."</th>";
    
    $product_id = 0;

    // create a Extra product if it does not exists
    if ($productextra == 'false') {
        $product_id = wp_insert_post( array(
            'post_title' => $product_name,
            'post_status' => 'publish',
            'post_type' => "product",
            ) );
        wp_set_object_terms( $product_id, 'variable', 'product_type' );
        
        $product = new WC_Product_Variable( $product_id );
    
        // Add category to product
        wp_set_object_terms( $product_id, 'extra', 'product_cat' );
    
        $product->set_catalog_visibility( 'visible' );
        $product->save();
    } else {
        $product_id = $productextra;
    }

    $price = intval($price);
    $catname = 'extra';
    $variation_data =  array(
        'attributes' => array(
            $catname  => $varname,
        ),
        'regular_price' => $price,
    );

    $varnameall = array();
    $product = wc_get_product($product_id);
    $variations = $product->get_available_variations();
    foreach($variations as $var){
        foreach($var['attributes'] as $attr) {
            array_push($varnameall,$attr);
        }
    }
    array_push($varnameall,$varname);
    $res = create_product_variation( $product_id, $variation_data ,$varnameall,$catname,$varname);

    // function sync ACF product
    if ($productextra == 'false') {
        $acf_res = sync_route_acf_with_new_product('false','false','wm_route_quote_product',$routeid,$product_id);
    }
    
    $output .= "<td id='dp_category_catextra_variation_".$res."'><div class='cell-status-icon-wrapper input-".$varname."-".$res."'></div><input type='text' id='".$res."' placeholder='".$price."' name='".$varname."'><div class='dp-delete-icon-wrapper'><i class='fal fa-trash-alt dp-delete-icon' id='".$res."' name='".$varname."' catname='catextra' seasonname='extratableraw'></i></div></td>";
        
        
    
    $output .= "</tr>";

    $outcome = 'false';
    if ($res) {
        $outcome = 'true';
    }

    $response = array(
        'response'=>$outcome,
        'output'=> $output
    );
    return $response;
};
