<?php 
function wm_has_extra_get_label($extra_variation_name,$extra_variation_desc) {
    $label = '';       
    
    if('kidbike' == $extra_variation_name) {           
    
                $label = __('Supplement for children bike' ,'wm-child-verdenatura');
                
    }
      
    if('bike_tandem' == $extra_variation_name) {           
    
        $label = __('Supplement for tandem bike' ,'wm-child-verdenatura');
                
    }
      
    if('bike_road' == $extra_variation_name) {           
    
                $label = __('Supplement for road bike rental' ,'wm-child-verdenatura');
                
    }
      
    if('babyseat' == $extra_variation_name) {           
    
                $label = __('Supplement for child back seat rental' ,'wm-child-verdenatura');
                
    }
    
    if('trailer' == $extra_variation_name) {           
    
                $label = __('Supplement for children trailer rental' ,'wm-child-verdenatura');
                
    }
    
    if('trailgator' == $extra_variation_name) {           
    
                $label = __('Supplement for children trailgator' ,'wm-child-verdenatura');
                
    }
    
    if('tagalong' == $extra_variation_name) {           
    
                $label = __('Supplement for follow-me rental' ,'wm-child-verdenatura');
                
    }
    
    if('bikewarranty' == $extra_variation_name) {           
    
                $label = __('Bike Coverage' ,'wm-child-verdenatura');
                
    }
    
    if('ebikewarranty' == $extra_variation_name) {           
    
                $label = __('E-bike Coverage' ,'wm-child-verdenatura');
                
    }
    
    if('bike_tandemwarranty' == $extra_variation_name) {           
    
                $label = __('Tandem bike Coverage' ,'wm-child-verdenatura');
                
    }
    
    if('bike_roadwarranty' == $extra_variation_name) {           
    
                $label = __('Road bike Coverage' ,'wm-child-verdenatura');
                
    }
    
    if('helmet' == $extra_variation_name) {           
    
                $label = __('Supplement for adult helmet rental' ,'wm-child-verdenatura');
                
    }
    
    if('kidhelmet' == $extra_variation_name) {           
    
                $label = __('Supplement for kid helmet rental' ,'wm-child-verdenatura');
                
    }
    
    if('roadbook' == $extra_variation_name) {           
    
                $label = __('Printed road book maps' ,'wm-child-verdenatura');
                
    }
    
    if('cookingclass' == $extra_variation_name) {           
    
                $label = __('Supplement for cooking class' ,'wm-child-verdenatura');
                
    }
    
    if('transferBefore' == $extra_variation_name) {           
    
                $label = __('Supplement for transfer before the trip' ,'wm-child-verdenatura');
                
    }
    
    if('transferAfter' == $extra_variation_name) {           
    
                $label = __('Supplement transfer after the trip' ,'wm-child-verdenatura');
                
    }
    
    if('boardingtax' == $extra_variation_name) {           
    
                $label = __('Port charges (to be paid in advance)' ,'wm-child-verdenatura');
                
    }

    if('bike_plus' == $extra_variation_name) {           
    
                $label = __('Supplement for bike rental Premium' ,'wm-child-verdenatura');
                
    }

    if('bike_pluswarranty' == $extra_variation_name) {           
    
                $label = __('Supplement for bike coverage Premium' ,'wm-child-verdenatura');
                
    }
    if('bike_mtb' == $extra_variation_name) {           
    
                $label = __('Supplement for MTB rental' ,'wm-child-verdenatura');
                
    }
    if('bike_mtbwarranty' == $extra_variation_name) {           
    
                $label = __('Supplement for tandem rental' ,'wm-child-verdenatura');
                
    }
    if('bike_ebikemtb' == $extra_variation_name) {           
    
                $label = __('Supplemento nolo E-MTB' ,'wm-child-verdenatura');
                
    }
    if('bike_ebikemtbwarranty' == $extra_variation_name) {           
    
                $label = __('Supplement for E-MTB coverage' ,'wm-child-verdenatura');
                
    }
    if('bike_ebikeroad' == $extra_variation_name) {           
    
                $label = __('Supplement for road e-bike rental' ,'wm-child-verdenatura');
                
    }
    if('bike_ecargo' == $extra_variation_name) {           
    
                $label = __('Supplement for ecargo rental' ,'wm-child-verdenatura');
                
    }
    if('bike_ecargowarranty' == $extra_variation_name) {           
    
                $label = __('Supplement for ecago Coverage' ,'wm-child-verdenatura');
                
    }
    if('bike_own' == $extra_variation_name) {           
    
                $label = __('Supplement for your own bike' ,'wm-child-verdenatura');
                
    }
    if('bike_ownwarranty' == $extra_variation_name) {           
    
                $label = __('Supplement for own bike Coverage' ,'wm-child-verdenatura');
                
    }
    if('bike_recumbent' == $extra_variation_name) {           
    
                $label = __('Supplement for recumbent bike' ,'wm-child-verdenatura');
                
    }
    if('gps' == $extra_variation_name) {           
    
                $label = __('Supplement for GPS' ,'wm-child-verdenatura');
                
    }
    if('weehoo' == $extra_variation_name) {           
    
                $label = __('Supplement for Weehoo trailer' ,'wm-child-verdenatura');
                
    }
    
    $name_explode = explode ('_',$extra_variation_name);
    if (!empty($name_explode) && $name_explode[0] == 'extra') {
    
            $label = $extra_variation_desc;
        
    }
    
    return strip_tags($label);
}