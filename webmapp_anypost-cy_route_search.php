<?php

$title_link = get_the_permalink();
$current_post_type = get_post_type();
$post_id = get_the_ID();
$target = 'who';
$activity = 'activity';
$diff_numero = get_field('vn_diff');
$shape = get_field('shape');

// get terms targets icon
$tax_targets = get_the_terms($post_id, $target);
$icon_targets = array();
if ($tax_targets){
    foreach ($tax_targets as $target) {
        $term_target = 'term_' . $target->term_id;
        $iconimage_target = get_field('wm_taxonomy_icon', $term_target);
        array_push($icon_targets, $iconimage_target);
    }
}
// get terms activities icon
$tax_activities = get_the_terms($post_id, $activity);
$icon_activities = array();
if ($tax_activities){
    foreach ($tax_activities as $activity) {
        $term_activity = 'term_' . $activity->term_id;
        $iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);
        array_push($icon_activities, $iconimage_activity);
    }
}
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
$nights = intval($duration) - 1;

// places to go where 
$places_to_go = 'where';
$tax_places_to_go = get_the_terms($post_id, $places_to_go);
$parent_id = $tax_places_to_go[0]->parent;
$parent  = get_term($parent_id)->name;


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

<div class="col-sm-12 col-md-12 webmapp_shortcode_any_post post_type_route">
<?php echo "<a href='$title_link' title=\"" . get_the_title() . "\">"; ?>
    <div class="single-post-wm">
        <div class="webmapp_post-featured-img">

            <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil; ?>')">
                <div class="webmapp_post-title">
                    <div class="post_meta_with_icons">
                        <?php if ($icon_activities) {
                            ?><div class="icon_holder"><?php
                                foreach ($icon_activities as $icon) {
                                    echo '<i class="'.$icon.'"></i>'.' ';
                                }
                            ?></div> <?php
                            echo '<div class="icons_separator"> | </div>';
                        } ?>
                        <?php if ($shape) {
                            ?><div class="icon_holder"><?php
                                ?><i class="<?php echo the_shape_icon($shape); ?>"></i><?php
                            ?></div> <?php
                            echo '<div class="icons_separator"> | </div>';
                        } ?>
                        <?php if ($icon_targets) {
                            ?><div class="icon_holder"><?php
                                foreach ($icon_targets as $icon) {
                                    echo '<i class="'.$icon.'"></i>'.' ';
                                }
                            ?></div> <?php
                            echo '<div class="icons_separator"> | </div>';
                        } ?>
                        <?php if ($diff_numero) {
                            ?><div class="icon_holder"><?php
                            ?><i class="<?php echo the_calcola_url( $diff_numero ); ?>"></i><?php ?>
                            </div> <?php
                        } ?>
                    </div>
                    <div class="post_meta_where_duration">
                        <?php if (!$coming_soon) { ?>
                        <p class="route_first_date"><?php echo __('From', 'wm-child-verdenatura') . ' <span class="meta_info_data">' . $first_departure_date; ?></span><span class="route_duration"><?php echo ' - ' . $nights . ' ' . __('nights', 'wm-child-cyclando') ?></span></p>
                        <p class='meta-bar-txt-light'>
                            <?php 
                            if ($tax_places_to_go[0] or $parent) { echo ' | ';}
                            if ($tax_places_to_go[0]) {echo $tax_places_to_go[0]->name;}
                            if ($parent) : echo ', <strong>' . $parent . '</strong>';
                            endif; ?>
                        </p>
                        <?php } else { ?>
                        <p class="route_first_date"><span class="route_duration"><?php echo (!empty($duration))?$nights . ' ' . __('nights', 'wm-child-cyclando'):'' ?></span></p>
                        <p class='meta-bar-txt-light'>
                            <?php echo $tax_places_to_go[0]->name;
                            if ($parent) : echo ', <strong>' . $parent . '</strong>';
                            endif; ?>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </figure>


        </div>
        <div class="webmapp_post_meta">
            <div class="post_meta_info">
                <p>
                    <?php the_title() ?>
                </p>
            </div>
            <?php
            if (!$coming_soon) {
                ?>
                    <div class='prezzo-tab'>
                        <p>
                            <span class="cifra <?php if ( $promotion_value){ echo 'old-price';}?>">
                            <?php  echo $price. ' €'; ?>
                            </span>
                            <?php if ( $promotion_value):?>
                            <span class="new-price">
                                <?php  echo $promotion_price. ' €'; ?>
                            </span>
                            <?php endif; ?>
                        </p>
                    </div>
                <?php 

            } else {
                ?> <div class='prezzo-tab <?php echo $coming_soon_class?>'><p><span><?php echo __('Coming soon!', 'wm-child-cyclando'); ?></span></p></div> <?php
            }
            ?>
        </div>

    </div>

    <?php
            echo "</a>";
            ?>
</div>