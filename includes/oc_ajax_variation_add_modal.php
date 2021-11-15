<?php

add_action( 'wp_ajax_nopriv_oc_ajax_variation_add_modal', 'oc_ajax_variation_add_modal' );
add_action( 'wp_ajax_oc_ajax_variation_add_modal', 'oc_ajax_variation_add_modal' );
function oc_ajax_variation_add_modal(){
    $productarray = $_POST['productarray']; 
    $routeid = $_POST['routeid']; 
    $place = $_POST['place']; 
    $from = $_POST['from']; 
    $to = $_POST['to']; 
    $seasonname = $_POST['seasonname']; 
    $res = oc_ajax_variation_add_modal_request($productarray,$routeid,$place,$from,$to,$seasonname);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_add_modal_request($productarray,$routeid,$place,$from,$to,$seasonname){

    $res = '<select name="variations" id="dpvariationsmodal">';
    $cat_inputs = '<div class="dp-catinputs-wrapper">';
    $variations_name_array = array();
    foreach($productarray as $id => $name) {
        $cat_inputs .= "<div>$name</div><input class='dpcatinputsmodal' type='text' id='$id' placeholder='€' name='$id'>";
        $product = wc_get_product($id); 
        $variations = $product->get_available_variations();
        foreach($variations as $var){
            foreach($var['attributes'] as $attr) {
                array_push($variations_name_array,$attr);
            }
        }
    }
    $cat_inputs .= '</div>';
    
    $variations_name_array = array_unique($variations_name_array);
    $var_list = wm_create_hotel_variation_mapping($place,$from,$to);
    foreach($var_list as $key => $val) {
        if(in_array($key,$variations_name_array)){

        } else {
            $res .= "<option value='$key'>$val</option>";
        }
    }
    $res .= "</select>";
    $res .= $cat_inputs;
    $savetxt = __('Save' ,'wm-child-cyclando');
    $res .= "<div style='display:none;' class='dpseasonnamemodal' id='$seasonname'></div><div class='addVariant_button_wrapper_modal'><div class='dp_loader_modal'></div><div class='createVariantbtn addVariantbtn'>$savetxt</div></div>";
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

