<?php while (have_posts()) : the_post();
    $post_id = get_the_ID(); ?>
<div class="webmapp_posts_controller webmapp-grid-system webmapp-anypost-template-cy_route_advancedsearch wm_template_advancedsearch">
    <?php
        if (defined('ICL_LANGUAGE_CODE')) {
            $language = ICL_LANGUAGE_CODE;
        } else {
            $language = 'it';
        }
        $title_link = get_the_permalink();
        $current_post_type = get_post_type();
        $post_id = get_the_ID();
        $target = 'who';
        $activity = 'activity';
        $diff_numero = get_field('n7webmapp_route_difficulty');

        $title_path = $array = [
            'daisy' => 'Percorso a margherita',
            'linear' => 'Percorso lineare',
            'roundtrip' => 'Percorso ad anello'
        ];
        $shape = get_field('shape');

        // get terms targets icon
        $tax_targets = get_the_terms($post_id, $target);
        $array_targets = array();

        if ($tax_targets) {
            foreach ($tax_targets as $target) {
                $term_target = 'term_' . $target->term_id;
                $title_target = $target->name;
                $iconimage_target = get_field('wm_taxonomy_icon', $term_target);
                $array_targets[$title_target] = $iconimage_target;
            }
        }
        // get terms activities icon
        $tax_activities = get_the_terms($post_id, $activity);
        $array_activities = array();
        if ($tax_activities) {
            foreach ($tax_activities as $activity) {
                $term_activity = 'term_' . $activity->term_id;
                $title_activity = $activity->name;
                $iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);
                $array_activities[$title_activity] = $iconimage_activity;
            }
        }
        //get the first departure date
        $start_array = array();
        if (have_rows('departures_periods', get_the_ID())) {
            $dates = array();
            while (have_rows('departures_periods')) : the_row();
                $sta = get_sub_field('start');
                $sto = get_sub_field('stop');
                $w_d = get_sub_field('week_days');
                $d_o_w = new DaysOfWeek($sta, $sto);
                $w_d_int = wm_weekDayToWeekNumber($w_d);
                $dates = array_merge($dates, $d_o_w->query_byDayOfWeek($w_d_int, 'none'));
            endwhile;
            $dates = array_unique($dates, SORT_REGULAR);
            foreach ($dates as $date) {
                $d = $date->format('d-m-Y');
                array_push($start_array, $d);
            }
        }

        if (have_rows('departure_dates', get_the_ID())) {
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
            if (date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date))) {
                $first_departure_date = date_i18n('d F', strtotime($date));
                break;
            }
        }

        // route duration 
        $duration = get_field('vn_durata');
        $days = intval($duration);

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

        $coming_soon = get_field('not_salable', $post_id);
        if ($coming_soon) {
            $coming_soon_class = 'coming-soon-button';
        }

        // get the route price
        $price = get_field('wm_route_price', $post_id);
        $price = (float)$price;

        // get the post promotion name and value
        $promotion_name = get_field('promotion_name', $post_id);
        $promotion_value = get_field('promotion_value', $post_id);
        if ($promotion_value) {
            $promotion_price = intval($price) - intval($promotion_value);
        }
        ?>

    <div class="col-sm-12 col-md-12 webmapp_shortcode_any_post post_type_route">
        <?php echo "<a href='$title_link' title=\"" . get_the_title() . "\">"; ?>
        <div class="single-post-wm">

            <figure class="webmapp_post_image" style="background-image: url('<?php echo $get_the_post_thumbanil; ?>')">
                <div class="webmapp_post_meta">

                    <div class="post_meta_info">
                        <?php if (!$coming_soon) { ?>
                        <?php if ($days == 1) { ?>
                        <span class="route_duration"><?php echo $days . ' ' . __('day', 'wm-child-cyclando') ?></span>

                        <?php } else { ?>
                        <span class="route_duration"><?php echo $days . ' ' . __('days', 'wm-child-cyclando') ?></span>
                        <?php } ?>


                        <?php } else { ?>
                        <?php if ($days == 1) { ?>

                        <div class="route_first_date"><span
                                class="route_duration"><?php echo $days . ' ' . __('day', 'wm-child-cyclando') ?></span></div>

                        <?php } else { ?>
                        <div class="route_first_date"><span
                                class="route_duration"><?php echo (!empty($duration)) ? $days . ' ' . __('days', 'wm-child-cyclando') : '' ?></span>
                        </div>
                        <?php } ?>
                        <?php } ?>

                    </div>

                    <div class="post_title_info">
                        <div class='meta-bar-txt-title'>
                            <?php echo $tax_places_to_go[0]->name;
                            if ($parent) : echo ', <strong>' . $parent . '</strong>';
                            endif; ?>
                        </div>
                        <div class="title-holder">
                            <?php
                            echo  '<h3>' . get_the_title() . '</h3>';
                            ?>
                        </div>
                    </div>
                </div>



            </figure>
            <div class="webmapp_post-featured-img">
                
                <div class="post_meta_with_icons">
                    <?php if ($array_activities) {
                    ?><?php
                            foreach ($array_activities as $title => $icon) {
                                echo '<div class="icon_holder"><i class="' . $icon . '"><span class="textIcon">' . $title . '</span></i></div>';
                            }

                            ?><?php //$title_activities[$icon]

                            } ?>
                    <?php if ($shape) {
                    ?>      <div class="icon_holder">
                                <i class="<?php echo the_shape_icon($shape); ?>"><span class="textIcon <?php if ($language == 'en') { echo 'wm-advanced-search-shape-text-eng';} ?>"><?php
                                    if ($language == 'it') {
                                        echo $title_path[$shape]; 
                                    } else {
                                        echo $shape;
                                    }
                                    ?></span>
                                 </i>
                            </div>
                    <?php //$title_path[$shape]
                    } ?>
                    <?php if ($array_targets) {
                    ?><?php
                            foreach ($array_targets as $title => $icon) {
                                echo '<div class="icon_holder"><i class="' . $icon . '"><span class="textIcon">' . $title . '</span></i></div>';
                            }
                            ?><?php

                            } ?>

                    <?php if ($diff_numero) {
                    ?><div class="icon_holder">
                        <i class="<?php $e = the_calcola_url($diff_numero); ?> wm_difficulty_icon_holder"><span
                                class="textIcon wm_difficulty_number"><?php echo $diff_numero . '/5'; ?></span><span
                                class="wm_difficulty_suffix"><?php echo __('Difficulty', 'wm-child-cyclando') ?></span></i>
                        </div> <?php
                        } ?>
                </div>
                <div class="oc_search_adv_detail_container">
                    <div class="oc_search_adv_info_text_<?= $post_id ?> oc_search_adv_info_text_container"> </div>
                    <div class="oc_search_adv_price_container">
    
                        <?php if (!$coming_soon) { ?>
                                <div class='prezzo-tab'>
                                    <div class="cifra cifra-<?= $post_id ?>">
                                    </div>
                                </div>
                                <?php } elseif (return_route_targets_has_cyclando($post_id)) { ?>
                                <a class="download-app-link" target="_blank" href="https://info.cyclando.com/app">
                                    <div class="scarica-app">
                                        <span class='meta-bar-txt-light'><?php echo __('Download', 'wm-child-cyclando'); ?></span>
                                    </div>
                                </a>
                                <?php } else { ?>
                                <div class='prezzo-tab <?php echo $coming_soon_class ?>'>
                                    <p><span><?php echo __('On Request', 'wm-child-cyclando'); ?></span></p>
                                </div>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo "</a>"; ?>
    </div>
</div>
<script>
    (function ($) {
        $(document).ready(function () {
            if (Cookies.get('oc_participants_cookie')) {
                var savedCookie = JSON.parse(Cookies.get('oc_participants_cookie'));
                var sums = cal_sum_cookies(savedCookie);
                var text = '<?= __('Lowset price', 'wm-child-cyclando') ?>';
                if (sums['participants'] !== null) {
                    text = '<?= __('Price for', 'wm-child-cyclando') ?>' + ' ' +  sums['participants'] + ' ' + '<?= __('participants', 'wm-child-cyclando') ?>'
                }
                if (sums['bikes'] !== null) {
                    text += ', ' +  sums['bikes'] + ' ' + '<?= __('bikes', 'wm-child-cyclando') ?>'
                }
                $('.oc_search_adv_info_text_<?= $post_id ?>').append('<div>'+text+'</div>');
            }
        });
        $(document).on("facetwp-loaded", function () {
            var ocCookies = '';
            if (Cookies.get('oc_participants_cookie')) {
                ocCookies = JSON.parse(Cookies.get('oc_participants_cookie'));
            }
            // console.log(ocCookies);
            var post_id = <?= $post_id ?>;
                var data = {
                    'action': 'oc_ajax_route_price',
                    'postid':  post_id,
                    'cookies':  ocCookies,
                };
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type : 'post',
                    data: data,
                    beforeSend: function(){
                        $(".cifra-"+post_id).html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
                    },
                    success : function( response ) {
                        console.log(response.responseText);
                        $(".cifra-"+post_id).html('<div class="w-iconbox-icon"><i class="fas fa-spinner fa-spin"></i></div>');
                    },
                    complete:function(response){
                        obj = JSON.parse(response.responseText);
                        console.log(response.responseText);
                        console.log(obj);
                        $(".cifra-"+post_id).html(obj.price+'€');
                    }
                });
        });
    })(jQuery);;
</script>
<?php endwhile; ?>