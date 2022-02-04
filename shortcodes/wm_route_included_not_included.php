<?php

function wm_route_included_not_included($post_id,$shape,$activity) {



    ob_start();

    $activity = explode(',',$activity);
    $fields = get_fields($post_id);

    $included_not_included_translations = $array = [
        'daisy' => 'Percorso a margherita',
        'linear' => 'Percorso lineare',
        'roundtrip' => 'Percorso ad anello'
    ];

    //get post language
    $post_lang = apply_filters( 'wpml_post_language_details', NULL, $post_id );
    $current_lang = $post_lang['language_code'];
    ?>
    <div class="extra-quotes oc-mobile-included-not-included-container">
        <p class="include-label wm-included-label"> 
            <i class="fas fa-check-circle"></i>
            <?php
            echo __('Included: ' ,'wm-child-cyclando');?>
        </p>
        <table class="extra-quotes-table">
            <tbody>

            <?php
                if( $fields ): ?>
                    <?php foreach( $fields as $field_key => $value ): ?>
                        <?php if (strpos($field_key,'ini_') !== false && $value == true && strpos($field_key,'_repeater') === false && strpos($field_key,'ini_activated') === false) : ?>
                            <tr>  
                                <th>
                                    <?php
                                        if ($current_lang && $current_lang == 'it') {
                                            $field_object = get_field_object($field_key,$post_id); 
                                            echo $field_object['label'];
                                        }
                                        if ($current_lang && $current_lang == 'en') {
                                            $field_object = get_field_object($field_key,$post_id); 
                                            echo $field_object['label_eng'];
                                        }
                                    ?>
                                </th>
                            </tr>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if (have_rows('ini_included_aditional_repeater')) : ?>
                            <?php while (have_rows('ini_included_aditional_repeater')) : the_row(); ?>
                                <tr>  
                                    <th>
                                        <?php
                                            if ($current_lang && $current_lang == 'it') {
                                                the_sub_field('ini_included_aditional');
                                            }
                                            if ($current_lang && $current_lang == 'en') {
                                                the_sub_field('ini_included_aditional_en');
                                            }
                                        ?>
                                    </th>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
            <?php endif; ?>
                
            </tbody>
        </table>
        <p class="include-label wm-not-included-label"> 
            <i class="fas fa-times-circle"></i>                                 
            <?php
            echo __('Not Included: ' ,'wm-child-cyclando');?>
        </p>
        <table class="extra-quotes-table">
            <tbody>

            <?php
                if( $fields ): ?>
                    <?php foreach( $fields as $field_key => $value ): ?>
                        <?php if (strpos($field_key,'ini_') !== false && $value == false && strpos($field_key,'_repeater') === false) : ?>
                            <?php 
                                $field_object = get_field_object($field_key,$post_id); 
                                if (($shape == 'daisy' || in_array('bici-e-barca',$activity ) ) && $field_object['label'] == 'Trasporto bagagli da hotel a hotel durante il tour') {

                                } else {
                                    ?>
                                    <tr>  
                                        <th>
                                            <?php
                                                if ($current_lang && $current_lang == 'it') {
                                                    echo $field_object['label'];
                                                }
                                                if ($current_lang && $current_lang == 'en') {
                                                    echo $field_object['label_eng'];
                                                }
                                            ?>
                                        </th>
                                    </tr>
                                    <?php
                                }
                            ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (have_rows('ini_not_included_aditional_repeater')) : ?>
                        <?php while (have_rows('ini_not_included_aditional_repeater')) : the_row(); ?>
                            <tr>  
                                <th>
                                    <?php
                                        if ($current_lang && $current_lang == 'it') {
                                            the_sub_field('ini_not_included_aditional');
                                        }
                                        if ($current_lang && $current_lang == 'en') {
                                            the_sub_field('ini_not_included_aditional_en');
                                        }
                                    ?>
                                </th>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
            <?php endif; ?>
                
            </tbody>
        </table>
    </div>


    <?php
    $html = ob_get_clean();
    return $html;
}