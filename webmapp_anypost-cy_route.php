<?php


global $wm_anypost_bootstrap_col_type,
    $wm_anypost_global_taxonomies,
    $wm_anypost_template,
    $wm_anypost_post_type;

$title_link = get_the_permalink();
$current_post_type = get_post_type();
$post_id = get_the_ID();

$first_departure = '';
$start_array = array();
if (have_rows('departures_periods')) {
    while (have_rows('departures_periods')) : the_row();
        $start = get_sub_field('start');
        $start = str_replace("/", "-", $start);
        array_push($start_array, $start);
    endwhile;
}
if (have_rows('departure_dates')) {
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

// route duration 
$duration = get_field('vn_durata');
$nights = $duration - 1;

// places to go where 
$places_to_go = 'where';
$tax_places_to_go = get_the_terms($post_id, $places_to_go);
$parent_id = $tax_places_to_go[0]->parent;
$parent  = get_term($parent_id)->name;


$get_the_post_thumbanil = '';
if (get_the_post_thumbnail_url()) {
    $get_the_post_thumbanil = get_the_post_thumbnail_url(get_the_ID(), 'large');
} else {
    $verde_natura_image = wp_get_attachment_image_src(40702, array(300, 201));
    $get_the_post_thumbanil = $verde_natura_image[0];
}

$coming_soon = get_field('not_salable',$post_id);
if ($coming_soon) {
    $coming_soon_class = 'coming-soon-button';
}

?>

<div class="col-sm-12 col-md-<?php echo $wm_anypost_bootstrap_col_type ?> webmapp_shortcode_any_post post_type_<?php echo $wm_anypost_post_type ?>">


    <div class="single-post-wm">
        <div class="webmapp_post-featured-img">
            <?php
            echo "<a href='$title_link' title=\"" . get_the_title() . "\">";

            ?>

            <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil; ?>')">
                <div class="webmapp_post-title">
                    <h3>
                        <?php the_title() ?>
                    </h3>
                </div>
            </figure>


            <?php
            echo "</a>";
            ?>
        </div>
        <div class="webmapp_post_meta">
            <div class="post_meta_info">
                <?php if (!$coming_soon) { ?>
                <p class="route_first_date"><?php echo __('From', 'wm-child-verdenatura') . ' <span class="meta_info_data">' . date_i18n('d F Y', strtotime($start_array[0])); ?></span><span class="route_duration"><?php echo ' - ' . $nights . ' ' . __('nights', 'wm-child-cyclando') ?></span></p>
                <p class='meta-bar-txt-light'>
                    <?php echo $tax_places_to_go[0]->name;
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
            <?php
            if (!$coming_soon) {
            $price = get_field('wm_route_price');
            $price = (float)$price;
            $sale_price_p = '';
            $sale_price = get_field('vn_prezzo_sc');
            if ($sale_price > 0)
                $sale_price_p = number_format($sale_price, 0, ',', '.') . ' € ';

            echo "<div class='prezzo-tab'><p><span class='cifra'>$price €</span></p></div>";
            } else {
                ?> <div class='prezzo-tab <?php echo $coming_soon_class?>'><p><span><?php echo __('Coming soon!', 'wm-child-cyclando'); ?></span></p></div> <?php
            }
            ?>
        </div>

    </div>

</div>