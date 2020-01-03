<?php /* Template Name: cerca */ ?>
<?php get_header();

$args = array(
    'post_type' => 'route',
    'post_status' => 'publish',
    'posts_per_page' => 14,
    'orderby' => 'order',
    'order' => 'ASC'
);

$loop = new WP_Query($args);

$page_title = __('Tours', 'wm-child-verdenatura');
if (isset($_GET['_dove_vuoi_andare'])) {
    $term_slug = $_GET['_dove_vuoi_andare'];
    $get_term = get_term_by('slug', $term_slug, $taxonomy = 'where');
    $term = 'term_' . $get_term->term_id;
    $iconimage = get_field('wm_taxonomy_featured_icon', $term);
    $featuredimage = get_field('wm_taxonomy_featured_image', $term);
    $iconimageurl = $iconimage['url'];
    $featuredimageurl = $featuredimage['url'];
    $color = get_field('wm_taxonomy_color', $term);
    $page_title = $get_term->name;
    $term_description = $get_term->description;
    $term_description_discovery = get_field('wm_html_description', $term);
}
if (isset($_GET['_cosa_vuoi_fare'])) {
    $term_slug = $_GET['_cosa_vuoi_fare'];
    $get_term = get_term_by('slug', $term_slug, $taxonomy = 'activity');
    $term = 'term_' . $get_term->term_id;
    $iconimage = get_field('wm_taxonomy_featured_icon', $term);
    $iconimageurl = $iconimage['url'];
    $color = get_field('wm_taxonomy_color', $term);
    $page_title = $get_term->name;
    $term_description = $get_term->description;
    $term_description_discovery = get_field('wm_html_description', $term);
}


?>

<main id="page-content" class="l-main" itemprop="mainContentOfPage">
    <section class="l-section wpb_row height_small" style="background-image: url(<?php 
                                                                                    ?>);">
        <div class="l-section-h i-cf">
            <div class="g-cols vc_row type_default valign_middle">
                <div class="vc_col-sm-12 wpb_column vc_column_container">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <h1 id="cerca-title" class="w-page-title align_left" itemprop="headline"><?php echo $page_title; ?>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="l-section height_auto for_sidebar at_right">
        <div class="l-section-h">
            <div class="g-cols type_default valign_top cerca-grid-container">
                <div class="vc_col-sm-9 vc_column_container l-content result">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <section class="l-section wpb_row height_medium cerca-result-container">
                                <div class="l-section-h i-cf">
                                    <div class="g-cols vc_row type_default valign_top">
                                        <div class="vc_col-sm-12 wpb_column vc_column_container">
                                            <div class="vc_column-inner">
                                                <div class="wpb_wrapper">
                                                    <div class="wpb_text_column ">
                                                        <div class="wpb_wrapper">
                                                            <div class="facetwp-template" data-name="routes">
                                                                <?php
                                                                while ($loop->have_posts()) :
                                                                    $loop->the_post();
                                                                    $post_id = get_the_ID();

                                                                    echo do_shortcode('[webmapp_anypost post_type="route" template="cy_route" post_id="' . $post_id . '"]');



                                                                endwhile;
                                                                echo do_shortcode('[facetwp pager="true"]');
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="vc_col-sm-3 vc_column_container l-sidebar search">
                    <div class="vc_column-inner">
                        <div class="wpb_wrapper">
                            <div id="custom_html-4" class="widget_text widget widget_custom_html">
                                <?php
                                if (shortcode_exists('facetwp')) :
                                    ?>
                                    <div id="sidebar" class="h-facet">
                                        <!-- <h2 class="filtra-viaggi"><?php echo __('Filter tours by', 'wm-child-verdenatura') . '...'; ?></h2> -->
                                    </div> <!-- chiudo .h-facet -->
                                    <div id="sidebar" class="side-facet">
                                        <?php
                                        $facets = array(
                                            'cosa_vuoi_fare' => '',
                                            'dove_vuoi_andare' => '',

                                        );
                                        foreach ($facets as $facet => $label) {
                                                echo do_shortcode("[facetwp facet='$facet' title='$label']");
                                                echo '<br>';
                                            }

                                        ?>
                                        <div class="no-filters"><a href="/cerca/<?php if (isset($_GET['lang'])) {
                                                                                    echo "?lang=" . $_GET['lang'];
                                                                                } ?>">
                                                <p><?php echo __('Remove filters', 'wm-child-verdenatura'); ?></p>
                                            </a></div>
                                        <script>
                                            (function($) {
                                                $(document).on('facetwp-loaded', function() { // function for wpfacet labels

                                                    // scroll to top on facetwp pagination reload
                                                    if (FWP.loaded) {
                                                        $('html, body').animate({
                                                            scrollTop: $('#page-content').offset().top
                                                        }, 0);
                                                    }
                                                    searchResult = FWP.facets["dove_vuoi_andare"];
                                                    console.log(searchResult[0]);
                                                    const pageTitle = document.querySelector('#cerca-title');
                                                    $.ajax({
                                                        url: '/wp-content/themes/wm-child-verdenatura/cerca-function.php',
                                                        data: {
                                                            action: searchResult[0]
                                                        },
                                                        type: 'post',
                                                        success: function(output) {
                                                            console.log(output);
                                                            pageTitle.innerHTML = output;
                                                        },
                                                        error: (error) => {
                                                            console.log(JSON.stringify(error));
                                                        }
                                                    });

                                                });

                                                $(".showmore").hide();
                                                $(".ng-hide").click(function() {
                                                    $(this).hide();
                                                    $(".showmore").show();
                                                });
                                            })(jQuery);
                                        </script>
                                    </div>
                                <?php
                            endif;
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<?php

get_footer();
