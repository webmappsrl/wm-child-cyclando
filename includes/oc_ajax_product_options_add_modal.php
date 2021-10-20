<?php

add_action( 'wp_ajax_nopriv_oc_ajax_product_options_add_modal', 'oc_ajax_product_options_add_modal' );
add_action( 'wp_ajax_oc_ajax_product_options_add_modal', 'oc_ajax_product_options_add_modal' );
function oc_ajax_product_options_add_modal(){
    $productarray = $_POST['productarray']; 
    $routeid = $_POST['routeid']; 
    $place = $_POST['place']; 
    $from = $_POST['from']; 
    $to = $_POST['to']; 
    $seasonname = $_POST['seasonname']; 
    $res = oc_ajax_product_options_add_modal_request($productarray,$routeid,$place,$from,$to,$seasonname);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_product_options_add_modal_request($productarray,$routeid,$place,$from,$to,$seasonname){

    $res = '<div name="variations" id="dpvariationsmodal">';
    $variations_name_array = array();
    foreach($productarray as $catname) {
        foreach ($catname as $var => $info) {
            array_push($variations_name_array,$var);
        }
        
    }
    $res .= "<div class='modalinputlabel'>".__('Category name' ,'wm-child-verdenatura')."</div><input class='dpproductsoptionsmodal dpproductattributename' type='text' id='attribute_name' placeholder='Name' name='attribute_name'>";

    $variations_name_array_unique = array_unique($variations_name_array);
    $var_list = wm_create_hotel_variation_mapping($place,$from,$to);
    foreach ($variations_name_array_unique as $variation) {
        if($var_list[$variation]){
            $res .= "<div class='modalinputlabel'>$var_list[$variation]</div><input class='dpproductsoptionsmodal' type='text' id='$variation' placeholder='€' name='$variation'>";
        } else {
            $res .= "<div class='modalinputlabel'>$variation</div><input class='dpproductsoptionsmodal' type='text' id='$variation' placeholder='€' name='$variation'>";
        }
    }
    

    $res .= "</div>";
    $savetxt = __('Save' ,'wm-child-cyclando');
    $res .= "<div style='display:none;' class='dpseasonnamemodal' id='$seasonname'></div><div class='addVariant_button_wrapper_modal'><div class='dp_loader_modal'></div><div class='createProductbtn addVariantbtn'>$savetxt</div></div>";
    $outcome = 'false';
    if ($res !== false || $res !== null) {
        $outcome = 'true';
    }
    $response = array(
        'response'=>$outcome,
        'output'=> $res
    );
    return $response;
};
