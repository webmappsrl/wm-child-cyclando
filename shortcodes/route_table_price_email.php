<?php

// include ('vn_route_tabs_td.php');
// include ('wm_product_attribute_mapping.php');
add_shortcode( 'route_table_price_email', 'cyclando_render_route_tabs_shortcode_email' );
// [bartag foo="foo-value"]
function cyclando_render_route_tabs_shortcode_email($atts) {
    extract( shortcode_atts( array(
        'post_id' => ''
    ), $atts ) );
ob_start();

global $sitepress;
$lang = 'en';
$sitepress->switch_lang($lang);
//----------------------- query in variable products of route --------
if (defined('ICL_LANGUAGE_CODE')) {
    $language = ICL_LANGUAGE_CODE;
} else {
    $language = 'it';
}
//check if the route is in boat or not
$boat_trip = get_field('trip_with_boat',$post_id);
if ($boat_trip) {
    $place = __('cabin','wm-child-verdenatura');
} else {
    $place = __('room','wm-child-verdenatura');
}

// get the name of the cities From e To
$from = get_field('from',$post_id);
$to = get_field('to',$post_id);

//var
// $post_id = get_the_ID();
$ini_activated = get_field('ini_activated',$post_id);
$prduct_list_hotel = array();
$attributes_name_hotel = array();
$variations_name_price = array();
$extra_variation_name_price = array();
$extra_variation_name_description = array();
$list_all_variations_name = array();
$has_hotel = false;
$has_extra = false;

$products = get_field('product',$post_id);
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
                    } 
                    elseif (!empty($variation['price_html'])){
                        $price = $variation['price_html'];
                    } else {
                        $price = $variation['display_price'].'€';
                    }
                    $variation_name_price = array($variation_name => $price);
                    $list_all_variations_name += array($variation_name => $variation['price_html']);
                    $product_variation_name_price += $variation_name_price;
                }
                array_push($variations_name_price,$product_variation_name_price);
            }
            if(strip_tags($category) == 'extra'){
                $has_extra = true;
                foreach($product->get_available_variations() as $variation ){
                    // Extra Name
                    $xattributes = $variation['attributes'];
                    $xvariation_name = '';
                    foreach($xattributes as $name_var){
                        $xvariation_name = $name_var;
                    }
                    // Prices
                    if ($variation['display_price'] == 0){
                        $xprice = __('Free' ,'wm-child-verdenatura');
                    } 
                    elseif (!empty($variation['price_html'])){
                        $xprice = $variation['price_html'];
                    } else {
                        $xprice = $variation['display_price'].'€';
                    }
                    $extra_name_price = array($xvariation_name => $xprice);
                    $extra_variation_name_price += $extra_name_price;
                    $extra_name_description = array($xvariation_name => $variation['variation_description']);
                    $extra_variation_name_description += $extra_name_description;
                }
            }
        }
    }
    
}
//  add the lowest price to vn_prezzp ACF : price from... 
$lowest_price_list = array();
foreach ( $variations_name_price as $var ) {
    $price = preg_replace('/&.*?;/', '', $var['adult']);
    $price = preg_replace('/€/', '', $price);
    $price = strip_tags($price);
    $price_e = explode(',',$price);
    $price_e = str_replace('.', '', $price_e[0]);
    array_push($lowest_price_list , $price_e);
}
?>




    <div id="tabs-4">
        <div class="durata-preventivo"> <!------------ Duration -->
            <p class="tab-section"><strong>
                <?php
                if (get_field('vn_durata',$post_id)) {
                    echo __('Duration:' ,'wm-child-verdenatura');
                }
                ?></strong>
                <?php $days = get_field('vn_durata',$post_id);
                if ( $days )
                {
                    $nights = $days - 1;
                    ?>
                    <?php
                    echo "<span class=''>" . "$days " . __( 'days' , 'wm-child-verdenatura' ) . " / $nights " . __( 'nights' , 'wm-child-verdenatura' ) ;
                    ?>
                    </span>

                    <?php

                    $vn_note_dur = get_field( 'vn_note_dur',$post_id );
                    if ( $vn_note_dur )
                        echo "<span class='webmapp_route_duration_notes'> ($vn_note_dur)</span>";
                }
                ?>
            </p>
            
        </div> <!------------Fine  Duration -->
        
        <div class="departure-preventivo-aside"><!------------ Departure / Partenze -->
            <span class='durata-txt'>
                <p class="tab-section"><strong>
                    <?php
                    if (have_rows('departures_periods')) {
                        echo __('Dates:', 'wm-child-verdenatura');
                    } ?></strong>
                </p>
            </span>

            <?php
            if (have_rows('departures_periods')) : ?>
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
                                <table>
                                    <?php if (count($week_days) == 7) { ?>
                                        <td style="display: inline;"><?php echo __('Every day', 'wm-child-verdenatura'); ?></td>
                                    <?php } else { ?>
                                        <span><?php echo __('Each', 'wm-child-verdenatura') . ' '; ?></span>
                                        <?php
                                        $i = 0;
                                        $len = count($week_days);
                                        foreach ($week_days as $week_day) :
                                            if ($i == 0) { ?>
                                                <td style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></td>
                                            <?php } elseif ($i == $len - 1) { ?>
                                                <?php echo __('and', 'wm-child-verdenatura') . ' '; ?><td style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></td>
                                            <?php } else { ?>
                                                <span><?php echo __(',', 'wm-child-verdenatura') . ' '; ?></span>
                                                <td style="display: inline;"><?php echo date_i18n($dateformatstring, strtotime($week_day)); ?></td>
                                            <?php }
                                        $i++; ?>
                                        <?php endforeach;
                                } ?>
                                </table>
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
                    <p class="tab-section"><strong><?php if (have_rows('departures_periods') && !empty($start) && have_rows('departure_dates')) {
                                                echo __('Other dates:', 'wm-child-verdenatura');
                                            } else {
                                                echo __('Dates:', 'wm-child-verdenatura');
                                            } ?></strong></p>
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
                                        }
                                        if(strip_tags($category) == 'hotel'){
                                            array_push($attributes_name_hotel_seasonal,$product_attribute_name);
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
                                                } 
                                                elseif (!empty($variation['price_html'])){
                                                    $price = $variation['price_html'];
                                                } else {
                                                    $price = $variation['display_price'].'€';
                                                }
                                                $variation_name_price = array($variation_name => $price);
                                                $list_all_variations_name_seasonal += array($variation_name => $variation['price_html']);
                                                $product_variation_name_price += $variation_name_price;
                                            }
                                            array_push($variations_name_price_seasonal,$product_variation_name_price);
                                        }
                                    }
                                }
                                
                                foreach ( $variations_name_price_seasonal as $var ) {
                                    $price = preg_replace('/&.*?;/', '', $var['adult']);
                                    $price = preg_replace('/€/', '', $price);
                                    $price = strip_tags($price);
                                    $price_e = explode(',',$price);
                                    $price_e = str_replace('.', '', $price_e[0]);
                                    array_push($lowest_price_list , $price_e);
                                }
                            }
                        ?>
                    <span class='durata-txt'> <!------------ quote ---------------------->
                    </span>
                    <table class="departures-quotes" cellspacing="0" cellpadding="6" border="1" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;text-align: left;max-width: 800px;width: 100%;">
                        <thead>
                            <tr>
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
                        <?php vn_route_tabs_body ($list_all_variations_name_seasonal,$variations_name_price_seasonal,$place,$from,$to)?>
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
                    <table class="departures-quotes" cellspacing="0" cellpadding="6" border="1" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle; text-align: left;max-width: 800px;width: 100%;">
                        <thead>
                            <tr>
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
                        <?php vn_route_tabs_body ($list_all_variations_name,$variations_name_price,$place,$from,$to)?>
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
            <table class="extra-quotes-table" cellspacing="0" cellpadding="6" border="1" style="color: #636363;border: 1px solid #e5e5e5;vertical-align: middle;text-align: left;max-width: 800px;width: 100%;">
                <tbody>
                        <?php  // row bike --------------------------------------------------------
                        if(array_key_exists('bike',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for bike rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike ---->
                        <?php  // row ebike --------------------------------------------------------
                        if(array_key_exists('ebike',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php 
                                    echo __('Supplement for e-bike rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['ebike'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row ebike ---->
                        <?php  // row kidbike --------------------------------------------------------
                        if(array_key_exists('kidbike',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for children bike' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['kidbike'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row kidbike ---->
                        <?php  // row bike_tandem --------------------------------------------------------
                        if(array_key_exists('bike_tandem',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for tandem bike' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_tandem'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_tandem ---->
                        <?php  // row bike_road --------------------------------------------------------
                        if(array_key_exists('bike_road',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for road bike rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_road'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_road ---->
                        <?php  // row babyseat --------------------------------------------------------
                        if(array_key_exists('babyseat',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for child back seat rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['babyseat'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row babyseat ---->
                        <?php  // row trailer --------------------------------------------------------
                        if(array_key_exists('trailer',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for children trailer rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['trailer'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row trailer ---->
                        <?php  // row trailgator --------------------------------------------------------
                        if(array_key_exists('trailgator',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for children trailgator' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['trailgator'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row trailgator ---->
                        <?php  // row tagalong --------------------------------------------------------
                        if(array_key_exists('tagalong',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for follow-me rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['tagalong'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row tagalong ---->
                        <?php  // row bikewarranty --------------------------------------------------------
                        if(array_key_exists('bikewarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bikewarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bikewarranty ---->
                        <?php  // row ebikewarranty --------------------------------------------------------
                        if(array_key_exists('ebikewarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('E-bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['ebikewarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row ebikewarranty ---->
                        <?php  // row bike_tandemwarranty --------------------------------------------------------
                        if(array_key_exists('bike_tandemwarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Tandem bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_tandemwarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_tandemwarranty ---->
                        <?php  // row bike_roadwarranty --------------------------------------------------------
                        if(array_key_exists('bike_roadwarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Road bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_roadwarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_roadwarranty ---->
                         <?php  // row helmet --------------------------------------------------------
                        if(array_key_exists('helmet',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for adult helmet rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['helmet'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row helmet ---->
                         <?php  // row kidhelmet --------------------------------------------------------
                        if(array_key_exists('kidhelmet',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for kid helmet rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['kidhelmet'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row kidhelmet ---->
                        <?php  // row Roadbook --------------------------------------------------------
                        if(array_key_exists('roadbook',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Printed road book maps' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['roadbook'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row Roadbook ---->
                        <?php  // row cookingclass --------------------------------------------------------
                        if(array_key_exists('cookingclass',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for cooking class' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['cookingclass'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row cookingclass ---->
                        <?php  // row transferBefore --------------------------------------------------------
                        if(array_key_exists('transferBefore',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for transfer before the trip' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['transferBefore'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row transferBefore ---->
                        <?php  // row transferAfter --------------------------------------------------------
                        if(array_key_exists('transferAfter',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement transfer after the trip' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['transferAfter'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row transferAfter ---->
                        <?php  // row boardingtax --------------------------------------------------------
                        if(array_key_exists('boardingtax',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Port charges (to be paid in advance)' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['boardingtax'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row boardingtax ---->
                        <?php  // row bike_plus --------------------------------------------------------
                        if(array_key_exists('bike_plus',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for bike rental Premium' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_plus'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_plus ---->
                        <?php  // row bike_pluswarranty --------------------------------------------------------
                        if(array_key_exists('bike_pluswarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for bike coverage Premium' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_pluswarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_pluswarranty ---->
                        <?php  // row bike_mtb --------------------------------------------------------
                        if(array_key_exists('bike_mtb',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for MTB rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_mtb'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_mtb ---->
                        <?php  // row bike_mtbwarranty --------------------------------------------------------
                        if(array_key_exists('bike_mtbwarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for tandem rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_mtbwarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_mtbwarranty ---->
                        <?php  // row bike_ebikemtb --------------------------------------------------------
                        if(array_key_exists('bike_ebikemtb',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplemento nolo E-MTB' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ebikemtb'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ebikemtb ---->
                        <?php  // row bike_ebikemtbwarranty --------------------------------------------------------
                        if(array_key_exists('bike_ebikemtbwarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for E-MTB coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ebikemtbwarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ebikemtbwarranty ---->
                        <?php  // row bike_ebikeroad --------------------------------------------------------
                        if(array_key_exists('bike_ebikeroad',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for road e-bike rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ebikeroad'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ebikeroad ---->
                        <?php  // row bike_ecargo --------------------------------------------------------
                        if(array_key_exists('bike_ecargo',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for ecargo rental' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ecargo'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ecargo ---->
                        <?php  // row bike_ecargowarranty --------------------------------------------------------
                        if(array_key_exists('bike_ecargowarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for ecago Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ecargowarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ecargowarranty ---->
                        <?php  // row bike_own --------------------------------------------------------
                        if(array_key_exists('bike_own',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for your own bike' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_own'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_own ---->
                        <?php  // row bike_ownwarranty --------------------------------------------------------
                        if(array_key_exists('bike_ownwarranty',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for own bike Coverage' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_ownwarranty'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_ownwarranty ---->
                        <?php  // row bike_recumbent --------------------------------------------------------
                        if(array_key_exists('bike_recumbent',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for recumbent bike' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['bike_recumbent'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row bike_recumbent ---->
                        <?php  // row gps --------------------------------------------------------
                        if(array_key_exists('gps',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for GPS' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['gps'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row gps ---->
                        <?php  // row weehoo --------------------------------------------------------
                        if(array_key_exists('weehoo',$extra_variation_name_price)) {           
                        ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo __('Supplement for Weehoo trailer' ,'wm-child-verdenatura');
                                    ?>
                                </th>
                                    
                                <td>
                                <?php
                                    echo $extra_variation_name_price['weehoo'];
                                    ?>
                                </td>
                                   
                            </tr>
                        <?php
                        }
                        ?> <!---- END row weehoo ---->
                        <?php  // row variable extras --------------------------------------------------------
                        foreach ($extra_variation_name_price as $extra_key => $extra_value) {       
                            $name_explode = explode ('_',$extra_key);
                            if (!empty($name_explode) && $name_explode[0] == 'extra') {
                                $extra_name = $extra_variation_name_description[$extra_key]
                            ?>
                            <tr>  
                                <th>
                                    <?php
                                    echo $extra_name;
                                    ?>
                                </th>
                                <td>
                                    <?php
                                    echo $extra_value;
                                    ?>
                                </td>
                            </tr>
                            <?php
                            }
                        }
                        ?> <!---- END row variable extras  ---->
                    </tbody>
            </table>
            <?php
            }  //----------- END hotel product table
            //  add the lowest price to vn_prezzp ACF : price from... 
            if ($lowest_price_list) {
                $lowest_price = min($lowest_price_list);
                //update_field('wm_route_price', $lowest_price);
            }
            ?>
        </div><!---- END  -------- quote extra -->
        <!-- IF Included and Not Included is activated show the options -->
        <?php if ( $ini_activated ) : ?>
            <?php echo wm_route_included_not_included_email($post_id); ?>
        <?php endif; ?>
        <!-- END ------ Included and Not Included is activated show the options -->
            <div class="prezzi-description">
                <?php 
                    $vn_part_pr = get_field( 'vn_part_pr' ,$post_id);
                    if ($vn_part_pr) {
                    echo $vn_part_pr;
                    }
                ?>
            </div>
    </div>






    <?php

    $html = ob_get_clean();
    return $html;
}