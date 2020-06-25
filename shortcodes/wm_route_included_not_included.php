<?php

function wm_route_included_not_included($post_id) {



    ob_start();

    
    $fields = get_fields($post_id);

    ?>
    <div class="extra-quotes">
        <p class="tab-section"> 
            <?php
            echo __('Included: ' ,'wm-child-cycladno');?>
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
                                        $field_object = get_field_object($field_key,$post_id); 
                                        echo $field_object['label'];
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
                                            the_sub_field('ini_included_aditional');
                                        ?>
                                    </th>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
            <?php endif; ?>
                
            </tbody>
        </table>

        <p class="tab-section"> 
            <?php
            echo __('Not Included: ' ,'wm-child-cycladno');?>
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
                                        $field_object = get_field_object($field_key,$post_id); 
                                        echo $field_object['label'];
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
                                        the_sub_field('ini_not_included_aditional');
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