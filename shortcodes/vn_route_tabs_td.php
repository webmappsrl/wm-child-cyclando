
<?php
function vn_route_tabs_body ($list_all_variations_name,$variations_name_price,$place,$from,$to){

    // row adult --------------------------------------------------------
    if(array_key_exists('adult',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Basic price in double %s' ,'wm-child-verdenatura'),$place);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'adult'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row adult ---->
    <?php  // row adult-single --------------------------------------------------------
    if(array_key_exists('adult-single',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Supplement for single %s' ,'wm-child-verdenatura'),$place);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'adult-single'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row adult-single ---->
    <?php  // row single-traveller --------------------------------------------------------
    if(array_key_exists('single-traveller',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Supplement for single traveller' ,'wm-child-verdenatura'),$place);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'single-traveller'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row single-traveller ---->
    <?php  // row adult-extra --------------------------------------------------------
    if(array_key_exists('adult-extra',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo __('Basic price in 3rd bed adult' ,'wm-child-verdenatura');
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'adult-extra'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row adult-extra ---->
    <?php  // row kid1 --------------------------------------------------------
    $kid1_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid1') {
        $kid1_max_range = $name_explode[1];
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('3rd/4th bed child price 0/%s yo' ,'wm-child-verdenatura'),$kid1_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == $var_name){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
        }
    }
    ?> <!---- END row kid1 ---->
    <?php  // row kid2 --------------------------------------------------------
    $kid2_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid2') {
        $kid2_max_range = $name_explode[1];
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('3rd/4th bed child price %d/%s yo' ,'wm-child-verdenatura'), $kid1_max_range+1, $kid2_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == $var_name){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
        }
    }
    ?> <!---- END row kid2 ---->
    <?php  // row kid3 --------------------------------------------------------
    $kid3_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid3') {
        $kid3_max_range = $name_explode[1];
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('3rd/4th bed child price %d/%s yo' ,'wm-child-verdenatura'), $kid2_max_range+1, $kid3_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == $var_name){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
        }
    }
    ?> <!---- END row kid3 ---->
    <?php  // row kid4 --------------------------------------------------------
    $kid4_max_range = '';
    foreach ($list_all_variations_name as $var_name =>$value){
        $name_explode = explode ('_',$var_name);
        if (!empty($name_explode) && $name_explode[0] == 'kid4') {
        $kid4_max_range = $name_explode[1];
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Child price 0/%d yo, in twin %s with adult' ,'wm-child-verdenatura'), $kid4_max_range, $place);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == $var_name){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
        }
    }
    ?> <!---- END row kid4 ---->
    <?php  // row halfboard_adult --------------------------------------------------------
    if(array_key_exists('halfboard_adult',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo __('Supplement for half board' ,'wm-child-verdenatura');
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'halfboard_adult'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row halfboard_adult ---->
    <?php  // row halfboard_kid1 --------------------------------------------------------
    if(array_key_exists('halfboard_kid1',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Supplement for half board child 0/%s yo' ,'wm-child-verdenatura'),$kid1_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'halfboard_kid1'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row halfboard_kid1 ---->
    <?php  // row halfboard_kid2 --------------------------------------------------------
    if(array_key_exists('halfboard_kid2',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php
                echo sprintf(__('Supplement for half board child %d/%s yo' ,'wm-child-verdenatura'),$kid1_max_range, $kid2_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'halfboard_kid2'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row halfboard_kid2 ---->
    <?php  // row halfboard_kid3 --------------------------------------------------------
    if(array_key_exists('halfboard_kid3',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php 
                echo sprintf(__('Supplement for half board child %d/%s yo' ,'wm-child-verdenatura'),$kid2_max_range, $kid3_max_range);
                ?>
            </th>
                <?php
                foreach ($variations_name_price as $cat) {
                ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'halfboard_kid3'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php
                }
                ?>
        </tr>
    <?php
    }
    ?> <!---- END row halfboard_kid3 ---->
    <?php  // row nightsBefore_adult --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$from, $place); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_adult'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_adult ---->
    <?php  // row nightsBefore_adult-single --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult-single',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$from, $place); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_adult-single'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_adult-single ---->
    <?php  // row nightsBefore_adult-extra --------------------------------------------------------
    if(array_key_exists('nightsBefore_adult-extra',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$from); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_adult-extra'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_adult-extra ---->
    <?php  // row nightsBefore_kid1 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid1',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child 0/%s yo)' ,'wm-child-verdenatura'),$from,$kid1_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_kid1'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_kid1 ---->
    <?php  // row nightsBefore_kid2 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid2',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$from,$kid1_max_range,$kid2_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_kid2'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_kid2 ---->
    <?php  // row nightsBefore_kid3 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid3',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$from,$kid2_max_range,$kid3_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_kid3'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_kid3 ---->
    <?php  // row nightsBefore_kid4 --------------------------------------------------------
    if(array_key_exists('nightsBefore_kid4',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Extra night in %s (Child in extra bed)' ,'wm-child-verdenatura'),$from); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsBefore_kid4'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsBefore_kid4 ---->
    <?php  // row nightsAfter_adult --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$to, $place); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_adult'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_adult ---->
    <?php  // row nightsAfter_adult-single --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult-single',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$to, $place); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_adult-single'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_adult-single ---->
    <?php  // row nightsAfter_adult-extra --------------------------------------------------------
    if(array_key_exists('nightsAfter_adult-extra',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$to); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_adult-extra'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_adult-extra ---->
    <?php  // row nightsAfter_kid1 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid1',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child 0/%s yo)' ,'wm-child-verdenatura'),$to,$kid1_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_kid1'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_kid1 ---->
    <?php  // row nightsAfter_kid2 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid2',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$to,$kid1_max_range,$kid2_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_kid2'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_kid2 ---->
    <?php  // row nightsAfter_kid3 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid3',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
            <?php echo sprintf(__('Extra night in %s (child %s/%s yo)' ,'wm-child-verdenatura'),$to,$kid2_max_range,$kid3_max_range);?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_kid3'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
    ?> <!---- END row nightsAfter_kid3 ---->
    <?php  // row nightsAfter_kid4 --------------------------------------------------------
    if(array_key_exists('nightsAfter_kid4',$list_all_variations_name)) {           
    ?>
        <tr>  
            <th>
                <?php echo sprintf(__('Extra night in %s (Child in extra bed)' ,'wm-child-verdenatura'),$to); ?>
            </th>
                <?php foreach ($variations_name_price as $cat) { ?>
                <td>
                <?php
                    $not_exist = false;
                    foreach ($cat as $var => $value) {
                        if ($var == 'nightsAfter_kid4'){
                            echo $value;
                            $not_exist = true;
                        } 
                    }
                    if ($not_exist == false) {
                        echo '<span>-</span>';
                    }
                ?>
                </td>
                <?php } ?>
        </tr>
    <?php
    }
}
