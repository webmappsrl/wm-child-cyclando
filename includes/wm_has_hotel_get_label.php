<?php 
function wm_has_hotel_get_label($variation_name,$variation_desc,$place,$from,$to) {
    $label = '';       
   
    // --------------------------------------------------------
    if('adult-extra' == $variation_name) {           
    
        $label = __('Basic price in 3rd bed adult' ,'wm-child-verdenatura');
                
    }
    // --------------------------------------------------------
    if('halfboard_adult' == $variation_name) {           
    
        $label = __('Supplement for half board' ,'wm-child-verdenatura');
                
    }
    // --------------------------------------------------------
    if('nightsBefore_adult' == $variation_name) {           
    
        $label = sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$from, $place);
                
    }
    // --------------------------------------------------------
    if('nightsBefore_adult-single' == $variation_name) {           
    
        $label = sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$from, $place);
                
    }
    // --------------------------------------------------------
    if('nightsBefore_adult-extra' == $variation_name) {           
    
        $label = sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$from);
                
    }
    // --------------------------------------------------------
    if('nightsAfter_adult' == $variation_name) {           
    
        $label = sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$to, $place);;
                
    }
    // --------------------------------------------------------
    if('nightsAfter_adult-single' == $variation_name) {           
    
        $label = sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$to, $place);
                
    }
    // --------------------------------------------------------
    if('nightsAfter_adult-extra' == $variation_name) {           
    
        $label = sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$to);;
                
    }
    return strip_tags($label);
}