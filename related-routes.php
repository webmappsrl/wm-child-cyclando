<?php /* Template Name: related-routes */
wp_head();
$route_ids = $_GET['id'];

?>
<head>
<base target="_blank">
</head>
<div class="vc_col-sm-12 wpb_column vc_column_container">
    <div class="vc_column-inner">
        <div class="wpb_wrapper">
            <div class="w-separator size_medium"></div>
            <div class="wpb_raw_code wpb_content_element wpb_raw_html">
		        <div class="wpb_wrapper">
			<!-- 79197,63262,43870 -->
                <?php echo do_shortcode('[webmapp_anypost post_type="route" template="cy_route" posts_count=3 rows=1 posts_per_page=3 post_ids="'.$route_ids.'"]');?>  
    
		        </div>
	        </div>
        </div>
    </div>
</div>