<?php



add_shortcode( 'vn_blog_tabs', 'vn_render_blog_tabs_shortcode' );
function vn_render_blog_tabs_shortcode( $atts ) {

    ob_start();
    ?>




<div id="tabs" class="vn-tab-blog">
  <ul class="tabs-blog-tab">
      <li class="all"><a href="#tabs-1" >ALL</a></li>
      <li class="bici-barca"><a href="#tabs-2" ><?php echo __('Travelling with kids','wm-child-verdenatura');?></a></li>
      <li class="a-piedi"><a href="#tabs-3" ><?php echo __('Practical tips','wm-child-verdenatura');?></a></li>
      <li class="in-famiglia"><a href="#tabs-4" ><?php echo __('Discoveries','wm-child-verdenatura');?></a></li>
      <li class="esplo"><a href="#tabs-5" ><?php echo __('Our trips','wm-child-verdenatura');?></a></li>
      <li class="wend"><a href="#tabs-6" ><?php echo __('People and their stories','wm-child-verdenatura');?></a></li>
  </ul>

    <div id="tabs-1" class="bici-content">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>

    <div id="tabs-2">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" term_id="480" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>

    <div id="tabs-3">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" term_id="472" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>

    <div id="tabs-4">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" term_id="478" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>

    <div id="tabs-5">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" term_id="469" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>

    <div id="tabs-6">
        <?php
        echo do_shortcode('[webmapp_anypost post_type="post" term_id="473" template="vnblog" posts_count=100 rows=10 posts_per_page=10 ]');
        ?>
    </div>
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
