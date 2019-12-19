<?php

require ('import_data.php');
require ('shortcodes/vn_home_tabs.php');
require ('shortcodes/route_table_price.php');
require ('shortcodes/vn_blog_tabs.php');
require ('shortcodes/calendar_departures_home.php');
require ('shortcodes/calendar_departures_all.php');
require ('shortcodes/wm_gallery.php');
require ('includes/woocommerce.php');
require ('includes/preventivi-json.php');
require('url_filters.php');


// Uncomment to disable GUTHENBERG
// add_filter('use_block_editor_for_post_type', '__return_false');

add_action('woocommerce_before_cart', 'preventivi_json_to_text',15);
add_action('woocommerce_before_checkout_form', 'preventivi_json_to_text',15);



add_action('after_setup_theme', 'vn_theme_setup');


/**
 * Filter activities in admin columns pro dropdown
 * IMPORTANT -> temporary fix!!
 * todo -> implements ACP cache
 */
add_filter( 'acp/filtering/cache/enable', function( $c_enabled , $column){
    if ( method_exists( $column , 'get_acf_field' ) )
    {
        $test = $column->get_acf_field();
        if ( isset( $test['key'] ) && $test['key'] == 'wm_route_tax_activity' )
        {
            $c_enabled = false;//disable cache for this meta field key
            class CYCLANDO_FILTER_META_QUERY//filter meta fields by language
            {
                private $routes_ids;

                function __construct()
                {
                    $routes = get_posts( array(
                            'post_type' => 'route',
                            'post_status' => 'any',
                            'nopaging' => 'true',
                            'fields' => 'ids',
                            'suppress_filters' => false //explicit by lanquage query
                    ) );
                    if ( $routes && is_array($routes) )
                    {
                        $this->routes_ids = $routes;
                        add_filter('query', array( $this , 'query'), 10 );
                    }

                }
                function query($query)
                {
                    $post_ids = $this->routes_ids;
                    $cut_pos = strpos( $query , 'WHERE ') + 6;
                    $query_1 = substr( $query , 0 , $cut_pos );
                    $my_filter = "mt.post_id IN (" . implode( ',' , $post_ids ) . ") AND ";
                    $query_2 = substr( $query , $cut_pos );
                    remove_filter('query', array( $this , 'query' ), 10 );
                    return $query_1.$my_filter.$query_2;
                }
            }
            $check = new CYCLANDO_FILTER_META_QUERY();
        }
    }
    return $c_enabled;
}, 10 , 2 );






/**
 * Load translations for wm-child-verdenatura
 */
function vn_theme_setup(){
    load_theme_textdomain('wm-child-verdenatura', get_stylesheet_directory() . '/languages');
}

/**
 * exclude the test page from search
 */
// if ( !$query->is_admin ) {
//     $query->set('post__not_in', array(49693) ); // id of page or post
// }  && $query->is_main_query()
function fb_search_filter( $query ) {
    
    if ( is_post_type_archive('route') && $query->is_main_query()) {
        if ( isset($_GET['wm_route_code']) ) {

            global $wpdb;
            $get_value = $_GET['wm_route_code'];
            
            $result = $wpdb->get_results("SELECT DISTINCT ID FROM vn_posts AS posts INNER JOIN vn_postmeta AS postmeta ON posts.ID = postmeta.post_id AND ( posts.post_title LIKE '%$get_value%' OR ( postmeta.meta_value = '$get_value' AND postmeta.meta_key = 'n7webmapp_route_cod' ) ) WHERE posts.post_type = 'route'", ARRAY_A);
            
            if ( !empty($result )) {
                $result = array_map( function ($e){
                    return isset($e['ID']) ? $e['ID'] : 0 ;
                }, $result);
                $query->set( 'post__in', $result );
            }
    
        }
        $query->set('order_by', 'meta_value' );
        $query->set('meta_key', 'vn_ordine' );
        $query->set('order', 'DESC' );
    }
}
add_action( 'pre_get_posts', 'fb_search_filter' );


//change query args of wpfacet template in home page dove vuoi andare adding the route_code 
add_filter( 'facetwp_indexer_row_data', function( $rows, $params ) {
    if ( 'search_route' == $params['facet']['name'] ) {
        $rows = [];
        // $term_id = (int) $params['defaults']['term_id'];
        // $term = get_term( $term_id, 'where' );
        $terms = get_terms( array( 
            'taxonomy' => 'where',
            'hide_empty' => true
        ) );
        foreach ( $terms as $term ) {
            $name = $term->name;
            $new_row = $params['defaults'];
            $new_row['facet_value'] = $term->slug; ; // value gets the post id
            $new_row['facet_display_value'] = $term->name;; // label
            $rows[] = $new_row;
        }

    }
    return $rows;
}, 10, 2 );


/**
 * exclude the taxonomy Bici e barca from tipologia
 */
add_filter( 'facetwp_index_row', function( $params, $class ) {
    if ( 'tipologia' == $params['facet_name'] ) {
        print_r($params);
        $excluded_terms = array( 'in-bici-e-barca' );
        if ( in_array( $params['facet_display_value'], $excluded_terms ) ) {
            return false;
        }
    }
    return $params;
}, 10, 2 );


add_action( 'wp_enqueue_scripts', 'Divi_parent_theme_enqueue_styles' );
function Divi_parent_theme_enqueue_styles() {
    wp_enqueue_style( 'divi-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
    wp_enqueue_script( 'hightlight', get_stylesheet_directory_uri() . '/js/home_highlight.js');
    wp_enqueue_script( 'general_javascript', get_stylesheet_directory_uri() . '/js/general.js', array ('jquery') );
    
}

function admin_css_load() {
    wp_enqueue_style('style-admin-css', get_stylesheet_directory_uri().'/style-admin.css');
}
add_action('admin_enqueue_scripts', 'admin_css_load');

/**
 * Material Icons
 */

add_action( 'wp_head' , 'aggiungi_material_icons' );
function aggiungi_material_icons(){
    // echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
    //load jquery ui theme css
    echo '<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="stylesheet">';
    echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>';
}


/**
 * Search bar e map search
 */

 add_action ('et_header_top', 'vn_search_bar');
function vn_search_bar() {
    $lang = $_GET['lang'];
    echo '<div id="vn-search-bar-header"><form id="searchform" action="/route/"  method="get">
	<input type="search" placeholder="' . __( 'Search &hellip;','wm-child-verdenatura' ) . '" value="" name="wm_route_code"><input type="hidden" name="lang" value="'.$lang.'"/>
	<button id="vn-search-lente" type="submit"><i class="fa fa-search"></i></button>
    </form></div>';

}


add_action( 'et_header_top', 'vn_search_map' );
function vn_search_map() {
    echo '<div id="vn-search-map"><i class="material-icons">language</i></div>';
}


function my_search_form( $form ) {
    $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( 'http://vnpreprod.webmapp.it/route/?fwp_search_box' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __( 'Cerca:' ) . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Cerca' ) .'" />
    </div>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'my_search_form' );


/**
 * VN E-book Forme
 */

add_action( 'et_after_main_content', 'vn_add_ebook_form' );
function vn_add_ebook_form()
{
    ob_start (); ?>
            <div class="vn-form-prefooter" style="background-color: #63BCF8;">
            <form action="https://fexe.mailupclient.com/Frontend/subscribe.aspx">
            <input name="list" type="hidden" value="108" autocomplete="off">
            <input name="group" type="hidden" value="1348" autocomplete="off">
                <header style="background-image:url(/wp-content/themes/wm-child-verdenatura/images/tree_spring.png); background-repeat: no-repeat;
                background-position: right top; position: relative; background-size: 50%; display:block; height: 9.375rem;">
                <h3 class="title-vn-form-ebook center"  style="text-align: center; color: #FFF; padding: 70px 0px 0px 0px; font-size: 38px;
                font-family: PT Sans, sans-serif; font-weight: bold;">
                <?php
                echo __('Subscribe to our newsletter' ,'wm-child-verdenatura');
                ?>
                </h3></header>
                <p class="txt-white p-vn-form-ebook pad-lr-ml container pad-tb-s center" style="color: #FFF; font-size: 16px; font-family: Lato, sans-serif; text-align: center; font-weight: bold; line-height: 1.4;
   ">
                    <?php
                    echo __('Subscribe to our newsletter to stay updated on new tours and all promotional offers. Verde Natura monthly newsletter also includes latest news from our blog, comments and tips.' ,'wm-child-verdenatura');
                    ?>
                </p>
            <fieldset class="pad-lr-ml container pad-tb-s">
                <input data-cons-subject="first_name" type="text" name="campo1" value="" size="40" placeholder="<?php echo __('First name' ,'wm-child-verdenatura'); ?>">
                <input data-cons-subject="last_name" type="text" name="campo2" value="" size="40" placeholder="<?php echo __('Last name' ,'wm-child-verdenatura'); ?>">
                <input data-cons-subject="email" type="email" name="email" value="" size="40" required="required" placeholder="Email"><br>
                    <div class="block center clear mrg-b-m">
                    <input data-cons-preference="general" type="checkbox" name="privacy" id="privacy1" required="required"><label for="privacy1" class="block center" style="line-height:1.2; text-align:left; color:#fff!important"><?php
                            echo __('*I accept to receive promotionals e-mails as written in our' ,'wm-child-verdenatura'); ?> <a target="_blank" href="https://www.verde-natura.it/privacy/" class="txt-dark-green">Privacy</a>.</label>
                    </div>
             </fieldset>
                <input data-iub-consent-form="" name="Submit" type="submit" value="<?php
                echo __('Subscribe' ,'wm-child-verdenatura'); ?>" class="btn btn-flat center-align">
           </form>
           </div> <!--chiudo .vn-form-prefooter-->
    <?php
    $html= ob_get_clean();

    if ( ! is_home() && ! is_front_page() )
    {

        echo $html;
    }
}

/**
 * Load Footer Image
 */


add_action( 'et_after_main_content', 'vn_add_footer_image' );
function vn_add_footer_image() {

    echo '<div id="vn-footer-img"></div>';
}

/**
 * Load tab for Single Route post type
 */


function vn_add_route_tabs () {

ob_start();
get_template_part('schede_single_route');
$scheda = ob_get_clean();


echo do_shortcode( $scheda );

}

/**
 * Icons for Single Route post type
 */

function the_calcola_url( $num )
{

    $numero_arrotondato = floor( $num );
    echo "/wp-content/themes/wm-child-verdenatura/images/diff-" . $numero_arrotondato . ".png";
}


function the_term_image_with_name( $post_id , $taxonomy )
{
    $terms = get_the_terms( $post_id , $taxonomy );
    if ( is_array( $terms ) )
    {
        foreach ( $terms as $term )
        {
            if ( $taxonomy == 'where' )
            {
                echo "<span class='vn_taxonomy_image_single_route vn_{$taxonomy}_image_single_route'>";
                echo "<img src='/wp-content/themes/wm-child-verdenatura/images/dest.png'>";
                echo $term->name;
                echo "</span>";
            }
            else
            {
                $image = get_field('wm_taxonomy_featured_icon' , $term );
                if ( isset($image['url']) )
                {
                    echo "<span class='vn_taxonomy_image_single_route vn_{$taxonomy}_image_single_route'>";
                    echo "<img src='".$image['url']."'>";
                    echo $term->name;
                    echo "</span>";

                }
            }
            if ( $taxonomy == 'who' )
            {
                echo "<div class='targets vn_{$taxonomy}_image_single_route'>";
                echo "<img src='".$image['url']."'>";
                $image = get_field('wm_taxonomy_featured_icon' , $term );
                echo "</div>";

            }
}
        }
    }

/**
 * Comments in single route
 */

add_filter( 'comment_text' , 'filtra_commento' , 10 , 3 );

function filtra_commento( $comment_text, $comment , $args )
{
    $date_html = '';
    $date = get_field('wm_comment_journey_date', $comment);
    if ( $date )
    {
        $date_html = "<div class='journey-comment'>" . __('Journey from' , 'wm_comment_journey_date' ) . " $date</div>";
    }

    $gallery = '';

    $vn_gallery = get_field ('wm_comment_gallery' , $comment );
    if ( is_array( $vn_gallery) && ! empty( $vn_gallery ) )
    {
        $vn_gallery_ids =  array_map(
            function ($i) {
                return $i ['ID'];
            },
            $vn_gallery );

        $gallery = "<div class='wm-comment-images'>";
        foreach ( $vn_gallery_ids  as $id)
        {
            $gallery .= '<span class="wm-comment-image">';
            $gallery .= wp_get_attachment_image( $id, 'thumbnail');
            $gallery .= '</span>';
        }
        $gallery = "</div>";

    }


    return "$date_html<div class='my_comment_text'>$comment_text</div>$gallery";
}

/**
 * Adds meta for social sharing
 */

//add_action( 'wp_head' , 'vn_add_meta_for_social_sharing' );
function vn_add_meta_for_social_sharing()
{
    if ( ! is_singular('route') )
        return;

    ob_start();
    ?>

    <meta property="og:title" content="<?php the_title(); ?>">
    <meta property="og:description" content="<?php the_excerpt()?>">
    <meta property="og:image" content="<?php the_post_thumbnail_url(); ?>">
    <meta property="og:url" content="<?php the_permalink();?>">

    <meta name="twitter:title" content="<?php the_title(); ?>">
    <meta name="twitter:description" content="<?php the_excerpt()?>">
    <meta name="twitter:image" content="<?php the_post_thumbnail_url(); ?>">
    <meta name="twitter:card" content="<?php the_permalink();?>">
    <?php
    echo ob_get_clean();
}


add_filter( "megamenu_nav_menu_args", 'vn_fix_megamenu_mobile_menu' , 10 ,3 ) ;
function vn_fix_megamenu_mobile_menu( $defaults, $menu_id, $current_theme_location )
{
    if ( isset( $defaults['items_wrap'] ) && strpos( $defaults['items_wrap'], 'data-mobile-force-width="10%"') !== false )
        $defaults['items_wrap'] = str_replace( 'data-mobile-force-width="10%"' , '' , $defaults['items_wrap'] );

    return $defaults;
}

function fwp_add_facet_labels() {
    ?>
    <script>
        (function($) {
            $(document).on('facetwp-loaded', function() {
                $('.facetwp-facet').each(function() {
                    var $facet = $(this);
                    var facet_name = $facet.attr('data-name');
                    var facet_label = FWP.settings.labels[facet_name];

                    if ($facet.closest('.facet-wrap').length < 1) {
                        $facet.wrap('<div class="facet-wrap"></div>');
                        $facet.before('<h3 class="facet-label">' + facet_label + '</h3>');
                    }
                });
            });
        })(jQuery);
    </script>
    <?php
}
add_action( 'wp_head', 'fwp_add_facet_labels', 100 );

// remove drop down show counts from wpfacet
add_filter( 'facetwp_facet_dropdown_show_counts', function( $return, $params ) {
    
    $return = false;
    
    return $return;
}, 10, 2 );

//  order wpfacet Duration and Seasosn months in archive route page
add_filter( 'facetwp_facet_orderby', function( $orderby, $facet ) {
    if ( 'durata' == $facet['name'] ) {
        $orderby = 'f.facet_value+0 ASC';
    }
    if ( 'seasons' == $facet['name'] ) {
        $orderby = 'FIELD(f.facet_display_value, "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre")';
    }
    return $orderby;
}, 10, 2 );

/*
    wpfacet translate default labels
*/

add_filter( 'facetwp_i18n', function( $string ) {
    if ( isset( FWP()->facet->http_params['lang'] ) ) {
        $lang = FWP()->facet->http_params['lang'];

        $translations = array();
        $translations['en']['Cerca'] = 'Search';

        if ( isset( $translations[ $lang ][ $string ] ) ) {
            return $translations[ $lang ][ $string ];
        }
    }

    return $string;
});

// /**
//  * Filter the upload size limit for non-administrators.
//  *
//  * @param string $size Upload size limit (in bytes).
//  * @return int (maybe) Filtered size limit.
//  */
// function filter_site_upload_size_limit( $size ) {
//     // Set the upload size limit to 60 MB for users lacking the 'manage_options' capability.
//     // if ( ! current_user_can( 'manage_options' ) ) {
//         // 60 MB.
//         $size = 60 * 1024 * 1024;
//     // }
//     return $size;
// }
// add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );
?>
