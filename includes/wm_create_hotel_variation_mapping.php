<?php 
function wm_create_hotel_variation_mapping($place){
    $array = $variations = [
        'adult' => sprintf(__('Basic price in double %s' ,'wm-child-verdenatura'),$place),
    ];
    return $array;
}