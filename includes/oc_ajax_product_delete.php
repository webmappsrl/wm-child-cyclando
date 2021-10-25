<?php 

add_action( 'wp_ajax_nopriv_oc_ajax_product_delete', 'oc_ajax_product_delete' );
add_action( 'wp_ajax_oc_ajax_product_delete', 'oc_ajax_product_delete' );
function oc_ajax_product_delete(){
    $post_id = $_POST['productid']; 
    $seasonnameid = $_POST['seasonnameid']; 
    $routeid = $_POST['routeid']; 
    $repeaterrawid = $_POST['repeaterrawid']; 
    $subfieldkey = $_POST['subfieldkey']; 
    $repeatername = $_POST['repeatername']; 
    $res = oc_ajax_product_delete_request($post_id,$seasonnameid,$routeid,$repeatername,$repeaterrawid,$subfieldkey);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_product_delete_request($post_id,$seasonnameid,$routeid,$repeatername,$repeaterrawid,$subfieldkey){
    $output = get_permalink($routeid).'?dateprices=true#tab-'.$seasonnameid;
    
    wp_delete_post( $post_id , true); 
    $acf_update = 'false';
    // update ACF 
    if ( $seasonnameid == 'false') {
        // get current value of acf 
        $values = get_field($subfieldkey,$routeid,false);
        $new_values = array_diff($values,[$post_id]);
        $acf_update = update_field( $subfieldkey, $new_values, $routeid );
    } else {
        // get current value of acf 
        $rows = get_field($repeatername,$routeid,false);
        $rawid = intval($repeaterrawid) - 1;
        $values = $rows[$rawid][$subfieldkey];
        $new_values = array_diff($values,[$post_id]);
        $acf_update = update_sub_field( array($repeatername, intval($repeaterrawid), $subfieldkey), $new_values, $routeid );
    }
    // END update ACF 

    // if ($acf_update){
    //     $res = wp_update_post( array('ID' => $routeid ));
    // }
    $outcome = 'false';
    if ($acf_update !== false || $acf_update !== null) {
        $outcome = 'true';
    }

    $response = array(
        'response'=>$outcome,
        'output'=>$output
    );
    return $response;
};


