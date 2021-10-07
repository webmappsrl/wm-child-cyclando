<?php 

// action that process ajax call : webmapp_anypost-cy_route_advancedsearch-oneclick.php to update route price
add_action( 'wp_ajax_nopriv_oc_ajax_variation_delete', 'oc_ajax_variation_delete' );
add_action( 'wp_ajax_oc_ajax_variation_delete', 'oc_ajax_variation_delete' );
function oc_ajax_variation_delete(){
    $post_id = $_POST['variationid']; 
    $res = oc_ajax_variation_delete_request($post_id);    
    
    echo json_encode($res);
    wp_die();
}

function oc_ajax_variation_delete_request($post_id){
    
    $res = wp_delete_post( $post_id , true); 
    $outcome = 'false';
    if ($res !== false || $res !== null) {
        $outcome = 'true';
    }
    $response = array(
        'response'=>$outcome,
        'productid'=>$res->post_parent
    );
    return $response;
};


