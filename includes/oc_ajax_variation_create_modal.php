<?php

add_action( 'wp_ajax_nopriv_oc_ajax_variation_create_modal', 'oc_ajax_variation_create_modal' );
add_action( 'wp_ajax_oc_ajax_variation_create_modal', 'oc_ajax_variation_create_modal' );
function oc_ajax_variation_create_modal(){
    $products = $_POST['products']; 
    $varname = $_POST['varname']; 
    $seasonname = $_POST['seasonname']; 
    $place = $_POST['place']; 
    $from = $_POST['from']; 
    $to = $_POST['to']; 
    $productarray = $_POST['productarray']; 
    $res = oc_ajax_variation_create_modal_request($products,$varname,$seasonname,$place,$from,$to,$productarray);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_create_modal_request($products,$varname,$seasonname,$place,$from,$to,$productarray){

    $th_txt = wm_create_hotel_variation_mapping($place,$from,$to);
    $output = "<tr id='dp_".$seasonname."_variation_".$varname."'><td style='width: 70px;'><div class='dp-delete-icon-wrapper'><i class='fal fa-trash-alt dp-row-delete-icon'></i></div></td><th>".$th_txt[$varname]."</th>";
    // $products = json_decode($products,true);
    foreach ($products as $id => $price) {
        if ($price != 0 ) {
            $id = intval($id);
            $price = intval($price);
            $catname = $productarray[$id];
            $variation_data =  array(
                'attributes' => array(
                    $catname  => $varname,
                ),
                'regular_price' => $price,
            );
    
            $varnameall = array();
            $product = wc_get_product($id);
            $variations = $product->get_available_variations();
            foreach($variations as $var){
                foreach($var['attributes'] as $attr) {
                    array_push($varnameall,$attr);
                }
            }
            array_push($varnameall,$varname);
            $res = create_product_variation( $id, $variation_data ,$varnameall,$catname,$varname);
            
            $catname_replace = preg_replace("/[^A-Za-z0-9]/", '', $catname);
    
            $output .= "<td id='dp_category_".$catname_replace."_variation_".$res."'><div class='cell-status-icon-wrapper input-".$varname."-".$res."'></div><input type='text' id='".$res."' placeholder='".$price."' name='".$varname."'><div class='dp-delete-icon-wrapper'><i class='fal fa-trash-alt dp-delete-icon' id='".$res."' name='".$varname."' catname='".$catname_replace."' seasonname='".$seasonname."'></i></div></td>";
        } else {
            $output .= "<td id='dp_category__variation_'><span>-</span></td>";
        }
        
    }
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


function create_product_variation( $product_id, $variation_data ,$varname, $catname, $newvarname){
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