<?php /* Template Name: hs-blog-related-routes */
$route_ids = $_GET['id'];
if ($_GET['lang']) {
    $lang = $_GET['lang'];
    if($lang && $lang == 'en') {
        do_action( 'wpml_switch_language', "en" );
    }
}
?>

<head>
    <style>.webmapp_anypost_shortcode { padding:0 20px!important; } .webmapp_post_image { height:200px!important; } .webmapp-pagination {display: none !important;}</style>
    <link rel="stylesheet" id="route-single-post-style-css"
        href="https://cyclando.com/wp-content/themes/wm-child-cyclando/single-route-style.css?ver=5.4.2" type="text/css"
        media="all">
    <link rel="stylesheet" id="us-style-css"
        href="https://cyclando.com/wp-content/themes/Impreza/css/style.min.css?ver=7.4.2" type="text/css" media="all">
    <link rel="stylesheet" id="us-woocommerce-css"
        href="https://cyclando.com/wp-content/themes/Impreza/common/css/plugins/woocommerce.min.css?ver=7.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="us-responsive-css"
        href="https://cyclando.com/wp-content/themes/Impreza/common/css/responsive.min.css?ver=7.4.2" type="text/css"
        media="all">
    <link rel="stylesheet" id="theme-style-css"
        href="https://cyclando.com/wp-content/themes/wm-child-cyclando/style.css?ver=7.4.2" type="text/css" media="all">
    <link rel="stylesheet" id="webmapp-icons-css" href="https://icon.webmapp.it/style.css?ver=5.4.2" type="text/css"
        media="all">
    <link rel="stylesheet" id="webmap_font_awesome-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/third-part/font-awesome-4.7.0/css/font-awesome.min.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmap_style_net7-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/assets/css/style.css?ver=5.4.2" type="text/css"
        media="all">
    <link rel="stylesheet" id="webmap_leaflet-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/third-part/leaflet/leaflet.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmap_leaflet_vector_markers-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/third-part/leaflet/leaflet-vector-markers.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmap_leaflet_cluster_icon_default-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/third-part/Leaflet.markercluster-1.4.1/dist/MarkerCluster.Default.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmapp_bootsrap_grid_system-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/third-part/bootstrap-grid-system/webmapp-grid-system.min.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmapp_theme_templates-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/themes_templates/style.css?ver=5.4.2" type="text/css"
        media="all">

    <link rel="stylesheet" id="webmapp_anypost_all_templates-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/pluggable/duplicable/frontend/shortcodes/AnyPost/assets/all_templates.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmapp_anypost_template_compact_css-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/pluggable/duplicable/frontend/shortcodes/AnyPost/assets/template_compact.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmapp_anyterm_css-css"
        href="https://cyclando.com/wp-content/plugins/wp-webmapp2/pluggable/duplicable/frontend/shortcodes/AnyTerm/assets/style.css?ver=5.4.2"
        type="text/css" media="all">
    <link rel="stylesheet" id="webmapp_google_fonts-css"
        href="https://fonts.googleapis.com/css?family=Merriweather%3A400%2C700%7CMontserrat%3A500&amp;ver=5.4.2"
        type="text/css" media="all">
    <base target="_blank">
</head>
<div class="vc_col-sm-12 wpb_column vc_column_container">
    <div class="vc_column-inner">
        <div class="wpb_wrapper">
            <div class="w-separator size_medium"></div>
            <div class="wpb_raw_code wpb_content_element wpb_raw_html">
                <div class="wpb_wrapper">
                    <!-- 79197,63262,43870 -->
                    <!-- 249069,225179,225037  EN -->
                    <?php echo do_shortcode('[webmapp_anypost post_type="route" template="cy_route" posts_count=1 rows=1 posts_per_page=1 post_ids="'.$route_ids.'"]');?>

                </div>
            </div>
        </div>
    </div>
</div>