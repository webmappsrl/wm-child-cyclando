<?php



add_shortcode( 'vn_home_tabs', 'vn_render_home_tabs_shortcode' );
function vn_render_home_tabs_shortcode( $atts ) {

    ob_start();
    ?>
    <div id="tabs" class="vn-tab-home">
        <ul class="tabs-home-tab">
        <?php 
            $catsArray = array(84,522,81,505,538,369);
            $terms = get_terms( array(
                'hide_empty' => false,
                'include' => $catsArray,
                'orderby'  => 'include',
            ) );
            $i = 1;
            $li_string = '';
            $div_string ='';
            foreach ( $terms as $term ) {
                if ($term->taxonomy == 'who') {
                    $term_slug = $term->slug;
                    $get_term = get_term_by( 'slug', $term_slug , 'who');
                    $term_id = $get_term->term_id;
                    $term = 'term_'.$get_term->term_id;
                    $iconimage = get_field('wm_taxonomy_featured_icon',$term);
                    $iconimageurl = $iconimage['url'];
                    $color = get_field('wm_taxonomy_color',$term);
                    $page_title = $get_term->name;
                    $term_description = $get_term->description;
                    $li_string .= '<li class="'.$term_slug.'"><a href="#tabs-'.$term_slug.'" id="'.$term_slug.'" style="border-right: 1px solid #ccc; color: '.$color.'; font-weight: bold;"><img src="'.$iconimageurl.'" class="bici-img" style="width: 2.5em; vertical-align: bottom; margin-right: 20px;">'.$page_title.'</a></li>';
                    $div_string .= '
                        <div id="tabs-'.$term_slug.'" class="'.$term_slug.'" style="border-top: 5px solid '.$color.';">
                            <div><img src="'.$iconimageurl.'" style="float: left; padding: 15px 10px 0 0;"><h2>'.$page_title.'</h2>
                            <p>'.$term_description.'</p></div>
                            
                            [webmapp_anypost post_type="route" term_id="'.$term_id.'" template="vnhome" posts_count=3 rows=1 posts_per_page=3]
                            
                        </div>';
                    $i++;
                } else {
                    $term_slug = $term->slug;
                    $get_term = get_term_by( 'slug', $term_slug , 'activity');
                    $term_id = $get_term->term_id;
                    $term = 'term_'.$get_term->term_id;
                    $iconimage = get_field('wm_taxonomy_featured_icon',$term);
                    $iconimageurl = $iconimage['url'];
                    $color = get_field('wm_taxonomy_color',$term);
                    $page_title = $get_term->name;
                    $term_description = $get_term->description;
                    $li_string .= '<li class="'.$term_slug.'"><a href="#tabs-'.$term_slug.'" id="'.$term_slug.'" style="border-right: 1px solid #ccc; color: '.$color.'; font-weight: bold;"><img src="'.$iconimageurl.'" class="bici-img" style="width: 2.5em; vertical-align: bottom; margin-right: 20px;">'.$page_title.'</a></li>';
                    $div_string .= '
                        <div id="tabs-'.$term_slug.'" class="'.$term_slug.'" style="border-top: 5px solid '.$color.';">
                            <div><img src="'.$iconimageurl.'" style="float: left; padding: 15px 10px 0 0;"><h2>'.$page_title.'</h2>
                            <p>'.$term_description.'</p></div>
                            
                            [webmapp_anypost post_type="route" term_id="'.$term_id.'" template="vnhome" posts_count=3 rows=1 posts_per_page=3]
                            
                        </div>';
                    $i++;
                }
                
            }
            echo $li_string;
        ?>
        </ul>
        <?php
        echo do_shortcode($div_string);
        ?>
    </div>

    <script>
        ( function($) {
            $( "#tabs" ).tabs({
                activate: function( event, ui ) {
                    ui.newPanel.find('.webmapp_post_image').each(function(i,e){
                        force_aspect_ratio($(e));
                    } );
                }
            });
        } )(jQuery);

        jQuery(function(){
            window.et_pb_smooth_scroll = () => {};
        });
    </script>


    <?php

    $html = ob_get_clean();
    return $html;
}