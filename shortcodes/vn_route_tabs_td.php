<?php
function vn_route_tabs_body ($list_all_variations_name,$variations_name_price,$place,$from,$to,$season_name_id){

    // row adult --------------------------------------------------------
    if(array_key_exists('adult',$list_all_variations_name)) {           
        $title = sprintf(__('Basic price in double %s' ,'wm-child-verdenatura'),$place);
        wm_route_tabs_body_tr('adult',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row adult ---->
    <?php  // row adult-single --------------------------------------------------------
    if(array_key_exists('adult-single',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for single %s' ,'wm-child-verdenatura'),$place);
        wm_route_tabs_body_tr('adult-single',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row adult-single ---->
    <?php  // row single-traveller --------------------------------------------------------
    if(array_key_exists('single-traveller',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for single traveller' ,'wm-child-verdenatura'),$place);
        wm_route_tabs_body_tr('single-traveller',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row single-traveller ---->
    <?php  // row adult-extra --------------------------------------------------------
    if(array_key_exists('adult-extra',$list_all_variations_name)) {           
        $title = __('Basic price in 3rd bed adult' ,'wm-child-verdenatura');
        wm_route_tabs_body_tr('adult-extra',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row adult-extra ---->
    <?php  // row kid1 --------------------------------------------------------
    $kid1_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid1') {
        $kid1_max_range = $name_explode[1];
        $title = sprintf(__('3rd/4th bed child price 0/%s yo' ,'wm-child-verdenatura'),$kid1_max_range);
        wm_route_tabs_body_tr($var_name,$title,$variations_name_price,$season_name_id);
        }
    }
    ?> <!---- END row kid1 ---->
    <?php  // row kid2 --------------------------------------------------------
    $kid2_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid2') {
        $kid2_max_range = $name_explode[1];
        $title = sprintf(__('3rd/4th bed child price %d/%s yo' ,'wm-child-verdenatura'), $kid1_max_range+1, $kid2_max_range);
        wm_route_tabs_body_tr($var_name,$title,$variations_name_price,$season_name_id);
        }
    }
    ?> <!---- END row kid2 ---->
    <?php  // row kid3 --------------------------------------------------------
    $kid3_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid3') {
        $kid3_max_range = $name_explode[1];
        $title = sprintf(__('3rd/4th bed child price %d/%s yo' ,'wm-child-verdenatura'), $kid2_max_range+1, $kid3_max_range);
        wm_route_tabs_body_tr($var_name,$title,$variations_name_price,$season_name_id);
        }
    }
    ?> <!---- END row kid3 ---->
    <?php  // row kid4 --------------------------------------------------------
    $kid4_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid4') {
        $kid4_max_range = $name_explode[1];
        $title = sprintf(__('Child price 0/%d yo, in twin %s with adult' ,'wm-child-verdenatura'), $kid4_max_range, $place);
        wm_route_tabs_body_tr($var_name,$title,$variations_name_price,$season_name_id);
        }
    }
    ?> <!---- END row kid4 ---->
    <?php  // row halfboard_adult --------------------------------------------------------
    if(array_key_exists('halfboard_adult',$list_all_variations_name)) {           
        $title = __('Supplement for half board' ,'wm-child-verdenatura');
        wm_route_tabs_body_tr('halfboard_adult',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row halfboard_adult ---->
    <?php  // row halfboard_kid1 --------------------------------------------------------
    if(array_key_exists('halfboard_kid1',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for half board child 0/%s yo' ,'wm-child-verdenatura'),$kid1_max_range);
        wm_route_tabs_body_tr('halfboard_adult',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row halfboard_kid1 ---->
    <?php  // row halfboard_kid2 --------------------------------------------------------
    if(array_key_exists('halfboard_kid2',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for half board child %d/%s yo' ,'wm-child-verdenatura'),$kid1_max_range, $kid2_max_range);
        wm_route_tabs_body_tr('halfboard_kid2',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row halfboard_kid2 ---->
    <?php  // row halfboard_kid3 --------------------------------------------------------
    if(array_key_exists('halfboard_kid3',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for half board child %d/%s yo' ,'wm-child-verdenatura'),$kid2_max_range, $kid3_max_range);
        wm_route_tabs_body_tr('halfboard_kid3',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row halfboard_kid3 ---->
    <?php  // row nightsBefore_adult --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$from, $place);
        wm_route_tabs_body_tr('nightsBefore_adult',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_adult ---->
    <?php  // row nightsBefore_adult-single --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult-single',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$from, $place);
        wm_route_tabs_body_tr('nightsBefore_adult-single',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_adult-single ---->
    <?php  // row nightsBefore_adult-extra --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult-extra',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$from);
        wm_route_tabs_body_tr('nightsBefore_adult-extra',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_adult-extra ---->
    <?php  // row nightsBefore_kid1 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid1',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child 0/%s yo)' ,'wm-child-verdenatura'),$from,$kid1_max_range);
        wm_route_tabs_body_tr('nightsBefore_kid1',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_kid1 ---->
    <?php  // row nightsBefore_kid2 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid2',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$from,$kid1_max_range,$kid2_max_range);
        wm_route_tabs_body_tr('nightsBefore_kid2',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_kid2 ---->
    <?php  // row nightsBefore_kid3 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid3',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$from,$kid2_max_range,$kid3_max_range);
        wm_route_tabs_body_tr('nightsBefore_kid3',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_kid3 ---->
    <?php  // row nightsBefore_kid4 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid4',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (Child in extra bed)' ,'wm-child-verdenatura'),$from); 
        wm_route_tabs_body_tr('nightsBefore_kid4',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsBefore_kid4 ---->
    <?php  // row nightsAfter_adult --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$to, $place);
        wm_route_tabs_body_tr('nightsAfter_adult',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_adult ---->
    <?php  // row nightsAfter_adult-single --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult-single',$list_all_variations_name)) {           
        $title = sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$to, $place);
        wm_route_tabs_body_tr('nightsAfter_adult-single',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_adult-single ---->
    <?php  // row nightsAfter_adult-extra --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult-extra',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$to);
        wm_route_tabs_body_tr('nightsAfter_adult-extra',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_adult-extra ---->
    <?php  // row nightsAfter_kid1 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid1',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child 0/%s yo)' ,'wm-child-verdenatura'),$to,$kid1_max_range);
        wm_route_tabs_body_tr('nightsAfter_kid1',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_kid1 ---->
    <?php  // row nightsAfter_kid2 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid2',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$to,$kid1_max_range,$kid2_max_range);
        wm_route_tabs_body_tr('nightsAfter_kid2',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_kid2 ---->
    <?php  // row nightsAfter_kid3 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid3',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$to,$kid2_max_range,$kid3_max_range);
        wm_route_tabs_body_tr('nightsAfter_kid3',$title,$variations_name_price,$season_name_id);
    }
    ?> <!---- END row nightsAfter_kid3 ---->
    <?php  // row nightsAfter_kid4 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid4',$list_all_variations_name)) {           
        $title = sprintf(__('Extra night in %s (Child in extra bed)' ,'wm-child-verdenatura'),$to);
        wm_route_tabs_body_tr('nightsAfter_kid4',$title,$variations_name_price,$season_name_id);
    }
}

function wm_route_tabs_body_tr($variation,$title,$variations_name_price,$season_name_id){
    ?>
        <tr id="dp_<?= $season_name_id ?>_variation_<?= $variation ?>">
            <td style="width: 70px;"><div class='dp-delete-icon-wrapper'>
                <?php if ($variation !== 'adult') { ?>
                    <i class='fal fa-trash-alt dp-row-delete-icon'></i>
                <?php } ?>
            </div></td>
            <th><?= $title; ?></th>
                <?php foreach ($variations_name_price as $catname => $array) { 
                    $catname_replace = preg_replace("/[^A-Za-z0-9]/", '', $catname);
                ?>
                    <td id="dp_category_<?= $catname_replace?>_variation_<?=$variations_name_price[$catname][$variation]['id']?>">
                <?php
                    $not_exist = false;
                        if ($variations_name_price[$catname][$variation]) {
                                output_hotel_price_input($variation,$variations_name_price[$catname][$variation],$catname_replace,$season_name_id);
                                $not_exist = true;
                        }
                        if ($not_exist == false) {
                            $parent_id = wp_get_post_parent_id($variations_name_price[$catname][$variation]['id']);
                            echo "<span>$parent_id</span>";
                        }
                        ?>
                </td>
            <?php } ?>
        </tr>
    <?php
}