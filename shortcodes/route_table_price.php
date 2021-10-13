<?php

include ('vn_route_tabs_td.php');
include ('wm_product_attribute_mapping.php');
add_shortcode( 'route_table_price', 'cyclando_render_route_tabs_shortcode' );

function output_extra_price_input($name,$extra_variation_name_price,$catname,$seasonname) {
    $id = $extra_variation_name_price[$name.'_id'];
    $price = $extra_variation_name_price[$name];
    echo "<div class='cell-status-icon-wrapper input-$name-$id'></div><input type='text' id='$id' placeholder='$price' name='$name'><div class='dp-delete-icon-wrapper'><i class='fal fa-trash-alt dp-delete-icon' id='$id' name='$name' catname='$catname' seasonname='$seasonname'></i></div>";
}
function output_hotel_price_input($name,$value,$catname,$seasonname) {
    $id = $value['id'];
    // $parent_id = wp_get_post_parent_id($id);
    $price = $value['price'];
    $output = '';
    $output .= "<div class='cell-status-icon-wrapper input-$name-$id'></div><input type='text' id='$id' placeholder='$price' name='$name'>";
    if ($name !== 'adult') {
        $output .= "<div class='dp-delete-icon-wrapper'><i class='fal fa-trash-alt dp-delete-icon' id='$id' name='$name' catname='$catname' seasonname='$seasonname'></i></div>";
    }
    echo $output;
}


function cyclando_render_route_tabs_shortcode() {

ob_start();


//----------------------- query in variable products of route --------
if (defined('ICL_LANGUAGE_CODE')) {
    $language = ICL_LANGUAGE_CODE;
} else {
    $language = 'it';
}
//check if the route is in boat or not
$boat_trip = get_field('trip_with_boat');
if ($boat_trip) {
    $place = __('cabin','wm-child-verdenatura');
} else {
    $place = __('room','wm-child-verdenatura');
}

// get the name of the cities From e To
$from = get_field('from');
$to = get_field('to');

//var
$post_id = get_the_ID();
$ini_activated = get_field('ini_activated',$post_id);
$prduct_list_hotel = array();
$attributes_name_hotel = array();
$variations_name_price = array();
$extra_variation_name_price = array();
$extra_variation_name_description = array();
$list_all_variations_name = array();
$has_hotel = false;
$has_extra = false;
$product_id_model_hotel = '';


$products = get_field('product');
if( $products ){
    foreach( $products as $p ){ // variables of each product
    $product = wc_get_product($p); 
        if($product->is_type('variable')){
            $product_with_variables = wc_get_product( $p );
            $category = $product_with_variables->get_categories();
            $attributes_list = $product_with_variables->get_variation_attributes();
            foreach ($attributes_list as $value => $key ) {
                $product_attribute_name = $value;
            }
            if(strip_tags($category) == 'hotel'){
                $product_id_model_hotel = $p;
                $has_hotel = true;
                array_push($attributes_name_hotel,$product_attribute_name);
                $product_variation_name_price = array();
                foreach($product->get_available_variations() as $variation ){

                    // hotel Name
                    $attributes = $variation['attributes'];
                    $variation_name = '';
                    foreach($attributes as $name_var){
                        $variation_name = $name_var;
                    }
                    // Prices
                    if ($variation['display_price'] == 0){
                        $price = __('Free' ,'wm-child-verdenatura');
                    } else {
                        $variation_obj = wc_get_product($variation['variation_id']);
                        $price = $variation_obj->regular_price;
                    }
                    $list_all_variations_name += array($variation_name => $variation['price_html']);
                    $variation_name_price = array($variation_name => array('id'=>$variation['variation_id'],'price'=>intval($price)));
                    $product_variation_name_price += $variation_name_price;
                }
                $variations_name_price[$product_attribute_name] = $product_variation_name_price;
            }
            if(strip_tags($category) == 'extra'){
                $has_extra = true;
                foreach($product->get_available_variations() as $variation ){
                    // Extra Name
                    // print_r($variation);
                    $xattributes = $variation['attributes'];
                    $xvariation_name = '';
                    foreach($xattributes as $name_var){
                        $xvariation_name = $name_var;
                    }
                    // Prices
                    if ($variation['display_price'] == 0){
                        $xprice = __('Free' ,'wm-child-verdenatura');
                    } else {
                        $variation_obj = wc_get_product($variation['variation_id']);
                        $xprice = $variation_obj->regular_price;
                    }
                    $extra_name_price = array($xvariation_name => $xprice);
                    $extra_id_array = array($xvariation_name.'_id' => $variation['variation_id']);
                    $extra_variation_name_price += $extra_name_price;
                    $extra_variation_name_price += $extra_id_array;
                    $extra_name_description = array($xvariation_name => $variation['variation_description']);
                    $extra_variation_name_description += $extra_name_description;
                }
            }
        }
    }
    
}
?>
    <div id="tabs-4">
        <div class="durata-preventivo"> <!------------ Duration -->
            <p class="tab-section"> 
                <?php
                if (get_field('vn_durata')) {
                    echo __('Duration:' ,'wm-child-verdenatura');
                }
                ?>
            </p>
            <?php $days = get_field('vn_durata');
            if ( $days )
            {
                $nights = $days - 1;
                ?>
                <?php
                echo "<span class=''>" . "$days " . __( 'days' , 'wm-child-verdenatura' ) . " / $nights " . __( 'nights' , 'wm-child-verdenatura' ) ;
                ?>
                </span>

                <?php

                $vn_note_dur = get_field( 'vn_note_dur' );
                if ( $vn_note_dur )
                    echo "<span class='webmapp_route_duration_notes'> ($vn_note_dur)</span>";
            }
            ?>
        </div> <!------------Fine  Duration -->
        
        <div class="departure-preventivo-aside"><!------------ Departure / Partenze -->
            <span class='durata-txt'>
                <p class="tab-section">
                    <?php
                    if (have_rows('departures_periods')) {
                        echo __('Dates:', 'wm-child-verdenatura');
                    } ?>
                </p>
            </span>

            <?php
            if (have_rows('departures_periods')) : ?>
                <div class="departure_name">
                </div>
                <div class="grid-container-period-aside">

                    <?php while (have_rows('departures_periods')) : the_row();

                        // vars
                        $name = get_sub_field('name');
                        $start = get_sub_field('start');
                        $stop = get_sub_field('stop');
                        $week_days = get_sub_field('week_days');
                        $dateformatstring = "l";

                        ?>

                        <div class="departure_start">
                            <?php if ($start) : ?>
                                <i class="cy-icons icon-plane-departure1"></i>
                                <p><?php echo __('From:', 'wm-child-verdenatura') . ' ' . $start; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="departure_stop">
                            <?php if ($stop) : ?>
                                <p><?php echo __('To:', 'wm-child-verdenatura') . ' ' . $stop; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="departure_week_days">
                            <?php if ($week_days) : ?>
                                <ul>
                                    <?php if (count($week_days) == 7) { ?>
                                        <li style="display: inline;"><?php echo __('Every day', 'wm-child-verdenatura'); ?></li>
                                    <?php } else { ?>
                                        <span><?php echo __('Each', 'wm-child-verdenatura') . ' '; ?></span>
                                        <?php
                                        $i = 0;
                                        $len = count($week_days);
                                        foreach ($week_days as $week_day) :
                                            if ($i == 0) { ?>
                                                <li style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                            <?php } elseif ($i == $len - 1) { ?>
                                                <?php echo __('and', 'wm-child-verdenatura') . ' '; ?><li style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                            <?php } else { ?>
                                                <span><?php echo __(',', 'wm-child-verdenatura') . ' '; ?></span>
                                                <li style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></li>
                                            <?php }
                                        $i++; ?>
                                        <?php endforeach;
                                } ?>
                                </ul>
                            <?php endif; ?>
                        </div>

                    <?php endwhile; ?>

                </div>

            <?php endif; ?>

            <?php // ---------- single departures ----------------//
            while (have_rows('departure_dates')) : the_row();
                $date = get_sub_field('date');
            endwhile;
            if (have_rows('departure_dates') && $date) : ?>
                <div class="single-departure">
                    <p class="tab-section"><?php if (have_rows('departures_periods') && !empty($start) && have_rows('departure_dates')) {
                                                echo __('Other dates:', 'wm-child-verdenatura');
                                            } else {
                                                echo __('Dates:', 'wm-child-verdenatura');
                                            } ?></p>
                </div>
                <div class="grid-container-single">

                    <?php while (have_rows('departure_dates')) : the_row();

                        // vars
                        $date = get_sub_field('date');
                        ?>

                        <div class="departure_name">
                            <?php if ($date) : ?>
                                <p><?php echo $date; ?></p>
                            <?php endif; ?>
                        </div>

                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div><!------------END  Departure / Partenze -->

        <?php 
        if( have_rows('model_season') ):
        ?>
        <p class="tab-section"> 
            <?php
                echo __('Prices:' ,'wm-child-verdenatura');
            ?>
        </p>
        <div id="tab-stagioni" class="container-stagionalita"> <!-- Start TAB Stagionalita -->
            <ul> 
                <?php while( have_rows('model_season') ): the_row();
                $season_name = get_sub_field('season_name');
                $season_disactive = get_sub_field('season_disactive');
                $season_name_id = preg_replace('/\s*/', '', $season_name);
                    if (!$season_disactive):
                    ?>
                    <li><a href="#tab-<?php echo $season_name_id; ?>" ><?php
                        if ($language == 'en') {
                            $season_name_en = str_replace('Stagione', 'Season', $season_name);
                            if ($season_name_en) {
                                echo __($season_name_en ,'wm-child-verdenatura');
                            } else {
                                echo __($season_name ,'wm-child-verdenatura');
                            }
                        } else {
                            echo __($season_name ,'wm-child-verdenatura');
                        }?>
                        </a>
                    </li>
                    <?php endif; 
                endwhile; ?>
            </ul>
            <?php while( have_rows('model_season') ): the_row(); ?> <!-- starti TABS stagionalita -->
            <?php
            $season_name = get_sub_field('season_name');
            $season_disactive = get_sub_field('season_disactive');
            $season_name_id = preg_replace('/\s*/', '', $season_name); 
            $season_products = get_sub_field('wm_route_quote_model_season_product'); 
            $season_periods = get_sub_field('periods');
            if (!$season_disactive):
            ?>
            <div id="tab-<?php echo $season_name_id; ?>" class="container-stagione"><!---- start  -------- TAB stagione --------->
                <div class="grid-container-period-seasonal"> 
                    <?php foreach ( $season_periods as $season_period ):
                        // vars
                        $start = $season_period['start'];
                        $stop = $season_period['stop'];
                        ?>
                
                        <div class="departure_start_stop">
                            <?php if ($start == $stop ):  ?>
                            <p><?php echo $start; ?></p>
                            <?php elseif( $start != $stop): ?>
                                <p><?php echo __('From:' ,'wm-child-verdenatura').' '.$start; ?></p>
                                <p><?php echo __('To:' ,'wm-child-verdenatura').' '.$stop; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach;?>
                </div>
                <div class="quotes-preventivo 1">
                        <?php 
                        if ($season_products){  //----------- start hotel product table
                            $attributes_name_hotel_seasonal = array();
                            $attributes_name_hotel_seasonal_modal = array();
                            $variations_name_price_seasonal = array();
                            $list_all_variations_name_seasonal = array();
                                foreach( $season_products as $p ){ // variables of each product
                                $product = wc_get_product($p); 
                                    if($product->is_type('variable')){
                                        $product = wc_get_product( $p );
                                        $category = $product->get_categories();
                                        $attributes_list = $product->get_variation_attributes();
                                        foreach ($attributes_list as $value => $key ) {
                                            $product_attribute_name = $value;
                                            // $product_attribute_name_modal = array($p => $value);
                                            $attributes_name_hotel_seasonal_modal[$p] = $value;
                                        }
                                        if(strip_tags($category) == 'hotel'){
                                            array_push($attributes_name_hotel_seasonal,$product_attribute_name);
                                            // array_push($attributes_name_hotel_seasonal_modal,$product_attribute_name_modal);
                                            $product_variation_name_price = array();
                                            foreach($product->get_available_variations() as $variation ){
    
                                                // hotel Name
                                                $attributes = $variation['attributes'];
                                                $variation_name = '';
                                                foreach($attributes as $name_var){
                                                    $variation_name = $name_var;
                                                }
                                                // Prices
                                                if ($variation['display_price'] == 0){
                                                    $price = __('Free' ,'wm-child-verdenatura');
                                                } else {
                                                    $variation_obj = wc_get_product($variation['variation_id']);
                                                    $price = $variation_obj->regular_price;
                                                }
                                                $variation_name_price = array($variation_name => array('id'=>$variation['variation_id'],'price'=>intval($price)));

                                                $list_all_variations_name_seasonal += array($variation_name => $variation['price_html']);
                                                $product_variation_name_price += $variation_name_price;
                                            }
                                            $variations_name_price_seasonal[$product_attribute_name] = $product_variation_name_price;
                                        }
                                    }
                                }
                                ?>
                                <div class="addVariant_button_wrapper">
                                    <div class="addVariant addVariantbtn" data-productarray='<?= json_encode($attributes_name_hotel_seasonal_modal) ?>' data-routeid="<?= $post_id ?>" data-place="<?= $place ?>" data-from="<?= $from ?>" data-to="<?= $to ?>" data-seasonname="<?= $season_name_id ?>"><?= __('Add raw' ,'wm-child-cyclando'); ?> <i class="fas fa-plus"></i></div>
                                </div>
                                <?php
                            }
                        ?>
                    <table class="departures-quotes">
                        <thead>
                            <tr>
                                <td style="width:10%"></td>
                                <th>
                                    <p class="tab-section"> 
                                        <?php 
                                        if( $season_products ){
                                            echo __('Individual rates:' ,'wm-child-verdenatura');}?>
                                    </p>
                                </th>
                                <?php
                                if (count($attributes_name_hotel_seasonal) > 1) {
                                    foreach ($attributes_name_hotel_seasonal as $hotel){
                                        if ($language == 'en') {
                                            ?>
                                            <th class="tab-section-quotes"><?php echo product_attr_map($hotel);?></th>
                                            <?php
                                        } else {
                                            ?>
                                            <th class="tab-section-quotes"><?php echo $hotel;?></th>
                                            <?php
                                        }
                                        
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php vn_route_tabs_body ($list_all_variations_name_seasonal,$variations_name_price_seasonal,$place,$from,$to,$season_name_id)?>
                        </tbody>       
                    </table>
                </div> <!---- END  -------- quote hotel alberghi -->
            </div> <?php endif; ?><!---- END  -------- TAB stagione -->
            <?php endwhile; ?>  <!---- END  -------- TAB stagionalita -->
        </div> <!---- END  -------- TAB Stagionalita --------->

        <?php endif; ?>

                <?php   
                    if (empty($low_season_products) && empty($high_season_products ) && $has_hotel){  //----------- start hotel product table
                ?>
                <div class="quotes-preventivo 2"><!------------ quote ---------------------->
                    <div class="addVariant_button_wrapper">
                        <div class="addVariant addVariantbtn" data-productid="<?= $product_id_model_hotel ?>" data-routeid="<?= $post_id ?>" data-place="<?= $place ?>" data-from="<?= $from ?>" data-to="<?= $to ?>" data-seasonname="<?= $season_name_id ?>"><?= __('Add raw' ,'wm-child-cyclando'); ?> <i class="fas fa-plus"></i></div>
                    </div>
                    <table class="departures-quotes">
                        <thead>
                            <tr>
                                <td style="width:10%"></td>
                                <th><p class="tab-section"> 
                                    <?php
                                    if( empty($season_products) && $has_hotel ){
                                    echo __('Individual rates:' ,'wm-child-verdenatura');}?>
                                    </p>
                                </th>
                                <?php
                                if (count($attributes_name_hotel) > 1) {
                                    foreach ($attributes_name_hotel as $hotel){
                                        if ($language == 'en') {
                                            ?>
                                            <th class="tab-section-quotes"><?php echo product_attr_map($hotel);?></th>
                                            <?php
                                        } else {
                                            ?>
                                            <th class="tab-section-quotes"><?php echo $hotel;?></th>
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php vn_route_tabs_body($list_all_variations_name,$variations_name_price,$place,$from,$to,$season_name_id)?>
                        </tbody>       
                    </table>
                </div> <!---- END  -------- quote hotel alberghi -->
                <?php
                }  //----------- END hotel product table
                ?>

        <div class="extra-quotes"> <!------------ quote extra ---------------------->
            <p class="tab-section"> 
                <?php
                if( $has_extra ){ //have_rows('product')
                echo __('Extra rates: ' ,'wm-child-verdenatura');}?>
            </p>
            <?php 
            if ($has_extra){  //----------- start extra product table
            ?>
            <table class="extra-quotes-table">
                <tbody>
                        <?php  // row bike --------------------------------------------------------
                        if(array_key_exists('bike',$extra_variation_name_price)) {           
                            $title = __('Supplement for bike rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike ---->
                        <?php  // row ebike --------------------------------------------------------
                        if(array_key_exists('ebike',$extra_variation_name_price)) {           
                            $title =  __('Supplement for e-bike rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('ebike',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row ebike ---->
                        <?php  // row kidbike --------------------------------------------------------
                        if(array_key_exists('kidbike',$extra_variation_name_price)) {           
                            $title =  __('Supplement for children bike' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('kidbike',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row kidbike ---->
                        <?php  // row bike_tandem --------------------------------------------------------
                        if(array_key_exists('bike_tandem',$extra_variation_name_price)) {           
                            $title =  __('Supplement for tandem bike' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_tandem',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_tandem ---->
                        <?php  // row bike_road --------------------------------------------------------
                        if(array_key_exists('bike_road',$extra_variation_name_price)) {           
                            $title =  __('Supplement for road bike rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_road',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_road ---->
                        <?php  // row babyseat --------------------------------------------------------
                        if(array_key_exists('babyseat',$extra_variation_name_price)) {           
                            $title =  __('Supplement for child back seat rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('babyseat',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row babyseat ---->
                        <?php  // row trailer --------------------------------------------------------
                        if(array_key_exists('trailer',$extra_variation_name_price)) {           
                            $title =  __('Supplement for children trailer rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('trailer',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row trailer ---->
                        <?php  // row trailgator --------------------------------------------------------
                        if(array_key_exists('trailgator',$extra_variation_name_price)) {           
                            $title =  __('Supplement for children trailgator' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('trailgator',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row trailgator ---->
                        <?php  // row tagalong --------------------------------------------------------
                        if(array_key_exists('tagalong',$extra_variation_name_price)) {           
                            $title =  __('Supplement for follow-me rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('tagalong',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row tagalong ---->
                        <?php  // row bikewarranty --------------------------------------------------------
                        if(array_key_exists('bikewarranty',$extra_variation_name_price)) {           
                            $title =  __('Bike Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bikewarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bikewarranty ---->
                        <?php  // row ebikewarranty --------------------------------------------------------
                        if(array_key_exists('ebikewarranty',$extra_variation_name_price)) {           
                            $title =  __('E-bike Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('ebikewarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row ebikewarranty ---->
                        <?php  // row bike_tandemwarranty --------------------------------------------------------
                        if(array_key_exists('bike_tandemwarranty',$extra_variation_name_price)) {           
                            $title =  __('Tandem bike Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_tandemwarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_tandemwarranty ---->
                        <?php  // row bike_roadwarranty --------------------------------------------------------
                        if(array_key_exists('bike_roadwarranty',$extra_variation_name_price)) {           
                            $title =  __('Road bike Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_roadwarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_roadwarranty ---->
                         <?php  // row helmet --------------------------------------------------------
                        if(array_key_exists('helmet',$extra_variation_name_price)) {           
                            $title =  __('Supplement for adult helmet rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('helmet',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row helmet ---->
                         <?php  // row kidhelmet --------------------------------------------------------
                        if(array_key_exists('kidhelmet',$extra_variation_name_price)) {           
                            $title =  __('Supplement for kid helmet rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('kidhelmet',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row kidhelmet ---->
                        <?php  // row Roadbook --------------------------------------------------------
                        if(array_key_exists('roadbook',$extra_variation_name_price)) {           
                            $title =  __('Printed road book maps' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('roadbook',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row Roadbook ---->
                        <?php  // row cookingclass --------------------------------------------------------
                        if(array_key_exists('cookingclass',$extra_variation_name_price)) {           
                            $title =  __('Supplement for cooking class' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('cookingclass',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row cookingclass ---->
                        <?php  // row transferBefore --------------------------------------------------------
                        if(array_key_exists('transferBefore',$extra_variation_name_price)) {           
                            $title =  __('Supplement for transfer before the trip' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('transferBefore',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row transferBefore ---->
                        <?php  // row transferAfter --------------------------------------------------------
                        if(array_key_exists('transferAfter',$extra_variation_name_price)) {           
                            $title =  __('Supplement transfer after the trip' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('transferAfter',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row transferAfter ---->
                        <?php  // row boardingtax --------------------------------------------------------
                        if(array_key_exists('boardingtax',$extra_variation_name_price)) {           
                            $title =  __('Port charges (to be paid in advance)' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('boardingtax',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row boardingtax ---->
                         <?php  // row bike_plus --------------------------------------------------------
                        if(array_key_exists('bike_plus',$extra_variation_name_price)) {           
                            $title =  __('Supplement for bike rental Premium' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_plus',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_plus ---->
                        <?php  // row bike_pluswarranty --------------------------------------------------------
                        if(array_key_exists('bike_pluswarranty',$extra_variation_name_price)) {           
                            $title =  __('Supplement for bike coverage Premium' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_pluswarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_pluswarranty ---->
                        <?php  // row bike_mtb --------------------------------------------------------
                        if(array_key_exists('bike_mtb',$extra_variation_name_price)) {           
                            $title =  __('Supplement for MTB rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_mtb',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_mtb ---->
                        <?php  // row bike_mtbwarranty --------------------------------------------------------
                        if(array_key_exists('bike_mtbwarranty',$extra_variation_name_price)) {           
                            $title =  __('Supplement for tandem rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_mtbwarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_mtbwarranty ---->
                        <?php  // row bike_ebikemtb --------------------------------------------------------
                        if(array_key_exists('bike_ebikemtb',$extra_variation_name_price)) {           
                            $title =  __('Supplemento nolo E-MTB' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ebikemtb',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ebikemtb ---->
                        <?php  // row bike_ebikemtbwarranty --------------------------------------------------------
                        if(array_key_exists('bike_ebikemtbwarranty',$extra_variation_name_price)) {           
                            $title =  __('Supplement for E-MTB coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ebikemtbwarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ebikemtbwarranty ---->
                        <?php  // row bike_ebikeroad --------------------------------------------------------
                        if(array_key_exists('bike_ebikeroad',$extra_variation_name_price)) {           
                            $title =  __('Supplement for road e-bike rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ebikeroad',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ebikeroad ---->
                        <?php  // row bike_ecargo --------------------------------------------------------
                        if(array_key_exists('bike_ecargo',$extra_variation_name_price)) {           
                            $title =  __('Supplement for ecargo rental' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ecargo',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ecargo ---->
                        <?php  // row bike_ecargowarranty --------------------------------------------------------
                        if(array_key_exists('bike_ecargowarranty',$extra_variation_name_price)) {           
                            $title =  __('Supplement for ecago Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ecargowarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ecargowarranty ---->
                        <?php  // row bike_own --------------------------------------------------------
                        if(array_key_exists('bike_own',$extra_variation_name_price)) {           
                            $title =  __('Supplement for your own bike' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_own',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_own ---->
                        <?php  // row bike_ownwarranty --------------------------------------------------------
                        if(array_key_exists('bike_ownwarranty',$extra_variation_name_price)) {           
                            $title =  __('Supplement for own bike Coverage' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_ownwarranty',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_ownwarranty ---->
                        <?php  // row bike_recumbent --------------------------------------------------------
                        if(array_key_exists('bike_recumbent',$extra_variation_name_price)) {           
                            $title =  __('Supplement for recumbent bike' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('bike_recumbent',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row bike_recumbent ---->
                        <?php  // row gps --------------------------------------------------------
                        if(array_key_exists('gps',$extra_variation_name_price)) {           
                            $title =  __('Supplement for GPS' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('gps',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row gps ---->
                        <?php  // row weehoo --------------------------------------------------------
                        if(array_key_exists('weehoo',$extra_variation_name_price)) {           
                            $title =  __('Supplement for Weehoo trailer' ,'wm-child-verdenatura');
                            wm_route_tabs_body_extra_tr('weehoo',$title,$extra_variation_name_price,'extratableraw');
                        }
                        ?> <!---- END row weehoo ---->
                        <?php  // row variable extras --------------------------------------------------------
                        foreach ($extra_variation_name_price as $extra_key => $extra_value) {       
                            $name_explode = explode ('_',$extra_key);
                            if (!empty($name_explode) && $name_explode[0] == 'extra' && !in_array('id',$name_explode)) {
                                $extra_name = $extra_variation_name_description[$extra_key];
                                wm_route_tabs_body_extra_tr($extra_key,$extra_name,$extra_variation_name_price,'extratableraw');
                            }
                        }
                        ?> <!---- END row variable extras  ---->
                    </tbody>
            </table>
            <?php
            }  //----------- END hotel product table
            ?>
        </div><!---- END  -------- quote extra -->
        <!-- IF Included and Not Included is activated show the options -->
        <?php if ( $ini_activated ) : ?>
            <?php echo wm_route_included_not_included($post_id); ?>
        <?php endif; ?>
        <!-- END ------ Included and Not Included is activated show the options -->
            <div class="prezzi-description">
                <?php 
                    $vn_part_pr = get_field( 'vn_part_pr' );
                    if ($vn_part_pr) {
                    echo $vn_part_pr;
                    }
                ?>
            </div>
    </div>

    
    <!-- Modal add raw html -->
    <div id="dp_add_variation-modal" class="dp_add_variation_container">
        <div class="ocm-participants-content">
            <div class="ocm-participants-header">
                <div class="">
                    <h2><?= __('Add a new variant' ,'wm-child-cyclando'); ?></h2>
                </div>
                <div class="ocm-close-button-container"><span class="dp_add_variation_container_close">×</span></div>
            </div>
            <div class="dp_add_variation_body">
                
            </div>                            
        </div>
    </div>

    <?php

    $html = ob_get_clean();
    return $html;
}


function wm_route_tabs_body_extra_tr($variation,$title,$variations_name_price,$season_name_id){
    ?>
        <tr id="dp_<?= $season_name_id ?>_variation_<?= $variation ?>">  
            <th><?= $title; ?></th>
                
            <td id="dp_category_<?= $season_name_id?>_variation_<?= $variations_name_price[$variation.'_id']?>"> 
                <?php output_extra_price_input($variation,$variations_name_price,'catextra',$season_name_id);?>
            </td>
                
        </tr>
    <?php
}