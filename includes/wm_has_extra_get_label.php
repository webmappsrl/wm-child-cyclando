<?php 
function wm_has_extra_get_label($extra_variation_name,$extra_variation_desc) {
    $label = '';       
    ?>
                        <?php  // row kidbike --------------------------------------------------------
                        if('kidbike' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for children bike' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row kidbike ---->
                        <?php  // row bike_tandem --------------------------------------------------------
                        if('bike_tandem' == $extra_variation_name) {           
                        
                            $label = __('Supplement for tandem bike' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row bike_tandem ---->
                        <?php  // row bike_road --------------------------------------------------------
                        if('bike_road' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for road bike rental' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row bike_road ---->
                        <?php  // row babyseat --------------------------------------------------------
                        if('babyseat' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for child back seat rental' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row babyseat ---->
                        <?php  // row trailer --------------------------------------------------------
                        if('trailer' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for children trailer rental' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row trailer ---->
                        <?php  // row trailgator --------------------------------------------------------
                        if('trailgator' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for children trailgator' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row trailgator ---->
                        <?php  // row tagalong --------------------------------------------------------
                        if('tagalong' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for follow-me rental' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row tagalong ---->
                        <?php  // row bikewarranty --------------------------------------------------------
                        if('bikewarranty' == $extra_variation_name) {           
                        
                                    $label = __('Bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                
                                    
                                
                                <?php
                                    $label = $extra_variation_name['bikewarranty'];
                                    
                        }
                        ?> <!---- END row bikewarranty ---->
                        <?php  // row ebikewarranty --------------------------------------------------------
                        if('ebikewarranty' == $extra_variation_name) {           
                        
                                    $label = __('E-bike Coverage' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row ebikewarranty ---->
                        <?php  // row bike_tandemwarranty --------------------------------------------------------
                        if('bike_tandemwarranty' == $extra_variation_name) {           
                        
                                    $label = __('Tandem bike Coverage' ,'wm-child-verdenatura');
                                   
                        }
                        ?> <!---- END row bike_tandemwarranty ---->
                        <?php  // row bike_roadwarranty --------------------------------------------------------
                        if('bike_roadwarranty' == $extra_variation_name) {           
                        
                                    $label = __('Road bike Coverage' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row bike_roadwarranty ---->
                         <?php  // row helmet --------------------------------------------------------
                        if('helmet' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for adult helmet rental' ,'wm-child-verdenatura');
                                   
                        }
                        ?> <!---- END row helmet ---->
                         <?php  // row kidhelmet --------------------------------------------------------
                        if('kidhelmet' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for kid helmet rental' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row kidhelmet ---->
                        <?php  // row Roadbook --------------------------------------------------------
                        if('roadbook' == $extra_variation_name) {           
                        
                                    $label = __('Printed road book maps' ,'wm-child-verdenatura');
                                   
                        }
                        ?> <!---- END row Roadbook ---->
                        <?php  // row cookingclass --------------------------------------------------------
                        if('cookingclass' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for cooking class' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row cookingclass ---->
                        <?php  // row transferBefore --------------------------------------------------------
                        if('transferBefore' == $extra_variation_name) {           
                        
                                    $label = __('Supplement for transfer before the trip' ,'wm-child-verdenatura');
                                   
                        }
                        ?> <!---- END row transferBefore ---->
                        <?php  // row transferAfter --------------------------------------------------------
                        if('transferAfter' == $extra_variation_name) {           
                        
                                    $label = __('Supplement transfer after the trip' ,'wm-child-verdenatura');
                                    
                        }
                        ?> <!---- END row transferAfter ---->
                        <?php  // row boardingtax --------------------------------------------------------
                        if('boardingtax' == $extra_variation_name) {           
                        
                                    $label = __('Port charges (to be paid in advance)' ,'wm-child-verdenatura');
                                  
                        }
                        ?> <!---- END row boardingtax ---->
                        <?php  // row variable extras --------------------------------------------------------
                            $name_explode = explode ('_',$extra_variation_name);
                            if (!empty($name_explode) && $name_explode[0] == 'extra') {
                            
                                    $label = $extra_variation_desc;
                                
                            }
                        ?> <!---- END row variable extras  ---->
    <?php
    return strip_tags($label);
}