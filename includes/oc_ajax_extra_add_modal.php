<?php

add_action( 'wp_ajax_nopriv_oc_ajax_extra_add_modal', 'oc_ajax_extra_add_modal' );
add_action( 'wp_ajax_oc_ajax_extra_add_modal', 'oc_ajax_extra_add_modal' );
function oc_ajax_extra_add_modal(){
    $productextra = $_POST['productextra']; 
    $res = oc_ajax_extra_add_modal_request($productextra);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_extra_add_modal_request($productextra){

    $variations_name_array = array();
    if ($productextra !== 'false') {
        $product = wc_get_product($productextra); 
        $variations = $product->get_available_variations();
        foreach($variations as $var){
            foreach($var['attributes'] as $attr) {
                array_push($variations_name_array,$attr);
            }
        }
    }

    $res = '<select name="extra" id="dpextrasmodal">';
    $cat_inputs = '<div class="dp-catinputs-wrapper">';
        $cat_inputs .= "<div>Extra</div><input class='dpextrainputmodal' type='text' placeholder='€'>";
    $cat_inputs .= '</div>';
    
    $variations_name_array = array_unique($variations_name_array);
    $var_list = wm_create_extra_variation_mapping();
    foreach($var_list as $key => $val) {
        if(in_array($key,$variations_name_array)){

        } else {
            $res .= "<option value='$key'>$val</option>";
        }
    }
    $res .= "</select>";
    $res .= $cat_inputs;
    $savetxt = __('Save' ,'wm-child-cyclando');
    $res .= "</div><div class='addVariant_button_wrapper_modal'><div class='dp_loader_modal'></div><div class='createExtrabtn addVariantbtn'>$savetxt</div></div>";
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

