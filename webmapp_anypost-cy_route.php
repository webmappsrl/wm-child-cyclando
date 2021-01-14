<?php


global $wm_anypost_bootstrap_col_type,
    $wm_anypost_global_taxonomies,
    $wm_anypost_template,
    $wm_anypost_post_type;

$title_link = get_the_permalink();
$current_post_type = get_post_type();
$post_id = get_the_ID();

//get the first departure date
$start_array = array();
if (have_rows('departures_periods',get_the_ID())) {
    $dates = array();
    while (have_rows('departures_periods')) : the_row();
        $sta = get_sub_field('start');
        $sto = get_sub_field('stop');
        $w_d = get_sub_field('week_days');
        $d_o_w = new DaysOfWeek( $sta , $sto );
        $w_d_int = wm_weekDayToWeekNumber($w_d);
        $dates = array_merge($dates,$d_o_w->query_byDayOfWeek( $w_d_int , 'none' ));
    endwhile;
    $dates = array_unique( $dates , SORT_REGULAR );
    foreach ($dates as $date) {
        $d = $date->format('d-m-Y');
        array_push($start_array, $d);
    }
}

if (have_rows('departure_dates',get_the_ID())) {
    $dates = get_field('departure_dates');
    foreach ($dates as $date) {
        foreach ($date as $single_date) {
            $start = str_replace("/", "-", $single_date);
            array_push($start_array, $start);
        }
    }
}
usort($start_array, function ($a, $b) {
    $dateTimestamp1 = strtotime($a);
    $dateTimestamp2 = strtotime($b);

    return $dateTimestamp1 < $dateTimestamp2 ? -1 : 1;
});
foreach ($start_array as $date) {
    if ( date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date)) ){
        $first_departure_date = date_i18n('d F', strtotime($date));
        break;
    } 
}

// route duration 
$duration = get_field('vn_durata');
$days = intval($duration);

// distance
$distance = intval(get_field('distance'));
$ascent = floatval(get_field('ascent'));
//echo "<br>".$distance ." = ". the_title()." dislivello ".$ascent."<br>";



// places to go where 
$places_to_go = 'where';
$tax_places_to_go = get_the_terms($post_id, $places_to_go);
$parent_id = $tax_places_to_go[0]->parent;
if ($parent_id) {
    $parent  = get_term($parent_id)->name;
}


$get_the_post_thumbanil = '';
if (get_the_post_thumbnail_url()) {
    $get_the_post_thumbanil = get_the_post_thumbnail_url(get_the_ID(), 'large');
} else {
    $cyclando_image = wp_get_attachment_image_src(40702, array(300, 201));
    $get_the_post_thumbanil = $cyclando_image[0];
}

$coming_soon = get_field('not_salable',$post_id);
if ($coming_soon) {
    $coming_soon_class = 'coming-soon-button';
}

// get the route price
$price = get_field('wm_route_price',$post_id);
$price = (float)$price;

// get the post promotion name and value
$promotion_name = get_field('promotion_name',$post_id);
$promotion_value = get_field('promotion_value',$post_id);
if ($promotion_value) {
    $promotion_price = intval($price) - intval($promotion_value); 
}
?>

<div class="col-sm-12 col-md-<?php echo $wm_anypost_bootstrap_col_type ?> webmapp_shortcode_any_post post_type_<?php echo $wm_anypost_post_type ?>">

<div class="single-post-wm">
    <div class="webmapp_post-featured-img">
        
        
        <?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) == false) { echo "<a href='$title_link' title=\"" . get_the_title() . "\">"; }?>
        <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil; ?>')">
            
            <?php
            if (!$coming_soon) {
                ?>
                <div class="post_meta_info">
                    <?php if (!$coming_soon) { ?>
                    <p class="route_first_date"><span class="route_duration"><?php echo  $days . ' ' . __('days', 'wm-child-cyclando') ?></span></p>

                    <?php } else { ?>
                    <p class="route_first_date"><span class="route_duration"><?php echo (!empty($duration))?$days . ' ' . __('days', 'wm-child-cyclando'):'' ?></span></p>
                    <?php } ?>
                </div>
                
                <div class='prezzo-tab'>
                    <span class="leavingFrom">
                    <?php  echo 'A PARTIRE DA' ?>
                    </span>
                    <span class="cifra<?php if ( $promotion_value){ echo 'old-price';}?>">
                    <?php  echo $price. '€'; ?>
                    </span>
                    <?php if ( $promotion_value):?>
                    <span class="cifraPromo<?php if ( $promotion_value){ echo 'new-price';}?>">
                    <?php  echo $promotion_price. '€'; ?>
                    </span>
                    <?php endif; ?>
                </div>
                
            <?php } elseif (return_route_targets_has_cyclando($post_id)) {?>
            <a class="download-app-link" target="_blank" href="https://info.cyclando.com/app">
                <div class="scarica-app">
                    <span class='meta-bar-txt-light'><?php echo __('Download', 'wm-child-cyclando'); ?></span>
                </div>
            </a>
            <?php } else {?>
                <div class='prezzo-tab <?php echo $coming_soon_class?>'><p><span><?php echo __('On Request', 'wm-child-cyclando'); ?></span></p></div>
            <?php } ?>
            </figure>
            <?php if (!$coming_soon && return_route_targets_has_cyclando($post_id) == false) { echo "</a>"; }?>
            
        </div>
        <?php echo "<a href='$title_link' title=\"" . get_the_title() . "\">"; ?>
        <div class="webmapp_post_meta">
        
                <p class='meta-bar-txt-light'>
                    <?php echo strtoupper($tax_places_to_go[0]->name);
                    if ($parent) : echo ', <strong>' . $parent . '</strong>';
                    endif; ?>
                </p>
                <div class="webmapp_post-title">
                    <h3>
                        <?php the_title()  ?>
                    </h3>
                </div>
            
        </div>
        <?php echo "</a>"; ?>
    </div>
    
</div>