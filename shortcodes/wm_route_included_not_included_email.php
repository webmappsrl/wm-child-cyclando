<?php

function wm_route_included_not_included_email($post_id) {



    ob_start();

    
    $fields = get_fields($post_id);

    $included_not_included_translations = $array = [
        'daisy' => 'Percorso a margherita',
        'linear' => 'Percorso lineare',
        'roundtrip' => 'Percorso ad anello'
    ];

    //get post language
    $post_lang = apply_filters( 'wpml_post_language_details', NULL, $post_id );
    // $current_lang = $post_lang['language_code'];
    $current_lang = 'en';

    ?>
    <div class="extra-quotes oc-mobile-included-not-included-container">
        <p class="tab-section wm-included-label"> 
            <i class="wm-icon-checkmark-circled"></i>
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
        <p class="tab-section wm-not-included-label"> 
            <i class="wm-icon-close-circled"></i>                                
            <?php
            echo __('Not Included: ' ,'wm-child-cyclando');?>
        </p>
        <table class="extra-quotes-table">
            <tbody>

            <?php
                if( $fields ): ?>
                    <?php foreach( $fields as $field_key => $value ): ?>
                        <?php if (strpos($field_key,'ini_') !== false && $value == false && strpos($field_key,'_repeater') === false) : ?>
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