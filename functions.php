<?php

require ('import_data.php');
require ('shortcodes/route_table_price.php');
require ('shortcodes/dashboard_wizard_button.php');
require ('shortcodes/mobile_menu_quote_form.php');
require ('shortcodes/menu_search_facetwp_wizard.php');
require ('url_filters.php');
if ( class_exists( 'WP_CLI' ) ) {
    require ('wp-cli/cy-index-routes.php');
}


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



/** create a template for child-pages plugin for SEO pages */
function custom_ccchildpage_inner_template($template) {

    $template = '<div class="ccchildpage-wm"><div class="ccchildpagethumbs" style="background-image:url({{thumbnail_url}});"><div class="ccchildpageinfo"><h3{{title_class}}>{{title}}</h3>{{excerpt}}</div></div><a class="child-page-link" href="{{link}}"></a></div>';
    return $template;
}
add_filter( 'ccchildpages_inner_template' ,'custom_ccchildpage_inner_template' );


/**
 * Load translations for wm-child-verdenatura
 */
function vn_theme_setup(){
    load_theme_textdomain('wm-child-verdenatura', get_stylesheet_directory() . '/languages');
}

// /**
//  * exclude the test page from search
//  */
// // if ( !$query->is_admin ) {
// //     $query->set('post__not_in', array(49693) ); // id of page or post
// // }  && $query->is_main_query()
// function fb_search_filter( $query ) {
    
//     if ( is_post_type_archive('route') && $query->is_main_query()) {
//         if ( isset($_GET['wm_route_code']) ) {

//             global $wpdb;
//             $get_value = $_GET['wm_route_code'];
            
//             $result = $wpdb->get_results("SELECT DISTINCT ID FROM vn_posts AS posts INNER JOIN vn_postmeta AS postmeta ON posts.ID = postmeta.post_id AND ( posts.post_title LIKE '%$get_value%' OR ( postmeta.meta_value = '$get_value' AND postmeta.meta_key = 'n7webmapp_route_cod' ) ) WHERE posts.post_type = 'route'", ARRAY_A);
            
//             if ( !empty($result )) {
//                 $result = array_map( function ($e){
//                     return isset($e['ID']) ? $e['ID'] : 0 ;
//                 }, $result);
//                 $query->set( 'post__in', $result );
//             }
    
//         }
//         $query->set('order_by', 'meta_value' );
//         $query->set('meta_key', 'vn_ordine' );
//         $query->set('order', 'DESC' );
//     }
// }
// add_action( 'pre_get_posts', 'fb_search_filter' );


// //change query args of wpfacet template in home page dove vuoi andare adding the route_code 
// add_filter( 'facetwp_indexer_row_data', function( $rows, $params ) {
//     if ( 'search_route' == $params['facet']['name'] ) {
//         $rows = [];
//         // $term_id = (int) $params['defaults']['term_id'];
//         // $term = get_term( $term_id, 'where' );
//         $terms = get_terms( array( 
//             'taxonomy' => 'where',
//             'hide_empty' => true
//         ) );
//         foreach ( $terms as $term ) {
//             $name = $term->name;
//             $new_row = $params['defaults'];
//             $new_row['facet_value'] = $term->slug; ; // value gets the post id
//             $new_row['facet_display_value'] = $term->name;; // label
//             $rows[] = $new_row;
//         }

//     }
//     return $rows;
// }, 10, 2 );


// /**
//  * exclude the taxonomy Bici e barca from tipologia
//  */
// add_filter( 'facetwp_index_row', function( $params, $class ) {
//     if ( 'tipologia' == $params['facet_name'] ) {
//         print_r($params);
//         $excluded_terms = array( 'in-bici-e-barca' );
//         if ( in_array( $params['facet_display_value'], $excluded_terms ) ) {
//             return false;
//         }
//     }
//     return $params;
// }, 10, 2 );


add_action( 'wp_enqueue_scripts', 'Divi_parent_theme_enqueue_styles' );
function Divi_parent_theme_enqueue_styles() {
    // wp_enqueue_style( 'divi-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
    wp_enqueue_style('jqeury-ui-tabs-style', 'https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css');
    // css for dialog jquery not
    // wp_enqueue_script( 'dialogJquery', get_stylesheet_directory_uri() . 'https://code.jquery.com/jquery-1.12.4.js');
    // wp_enqueue_script( 'dialogUIJquery', get_stylesheet_directory_uri() . 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script( 'hightlight', get_stylesheet_directory_uri() . '/js/home_highlight.js');
    wp_enqueue_script( 'general_javascript', get_stylesheet_directory_uri() . '/js/general.js', array ('jquery') );
}

function admin_css_load() {
	wp_enqueue_style('style-admin-css', get_stylesheet_directory_uri().'/style-admin.css');
	wp_enqueue_script('cyclando-admin', get_stylesheet_directory_uri().'/js/admin.js', array('jquery'));
}
add_action('admin_enqueue_scripts', 'admin_css_load');

/**
 * Material Icons
 */
// //add_action( 'wp_head' , 'aggiungi_material_icons' );
// function aggiungi_material_icons(){
//     // echo '<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">';
//     //load jquery ui theme css
//     echo '<link href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" rel="stylesheet">';
//     echo '<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>';
// }


/**
 * Search bar e map search
 */

//  add_action ('et_header_top', 'vn_search_bar');
// function vn_search_bar() {
//     $lang = $_GET['lang'];
//     echo '<div id="vn-search-bar-header"><form id="searchform" action="/route/"  method="get">
// 	<input type="search" placeholder="' . __( 'Search &hellip;','wm-child-verdenatura' ) . '" value="" name="wm_route_code"><input type="hidden" name="lang" value="'.$lang.'"/>
// 	<button id="vn-search-lente" type="submit"><i class="fa fa-search"></i></button>
//     </form></div>';

// }


// add_action( 'et_header_top', 'vn_search_map' );
// function vn_search_map() {
//     echo '<div id="vn-search-map"><i class="material-icons">language</i></div>';
// }


// function my_search_form( $form ) {
//     $form = '<form role="search" method="get" id="searchform" class="searchform" action="' . home_url( 'http://vnpreprod.webmapp.it/route/?fwp_search_box' ) . '" >
//     <div><label class="screen-reader-text" for="s">' . __( 'Cerca:' ) . '</label>
//     <input type="text" value="' . get_search_query() . '" name="s" id="s" />
//     <input type="submit" id="searchsubmit" value="'. esc_attr__( 'Cerca' ) .'" />
//     </div>
//     </form>';

//     return $form;
// }

// add_filter( 'get_search_form', 'my_search_form' );



/**
 * Comments in single route
 */

// add_filter( 'comment_text' , 'filtra_commento' , 10 , 3 );

// function filtra_commento( $comment_text, $comment , $args )
// {
//     $date_html = '';
//     $date = get_field('wm_comment_journey_date', $comment);
//     if ( $date )
//     {
//         $date_html = "<div class='journey-comment'>" . __('Journey from' , 'wm_comment_journey_date' ) . " $date</div>";
//     }

//     $gallery = '';

//     $vn_gallery = get_field ('wm_comment_gallery' , $comment );
//     if ( is_array( $vn_gallery) && ! empty( $vn_gallery ) )
//     {
//         $vn_gallery_ids =  array_map(
//             function ($i) {
//                 return $i ['ID'];
//             },
//             $vn_gallery );

//         $gallery = "<div class='wm-comment-images'>";
//         foreach ( $vn_gallery_ids  as $id)
//         {
//             $gallery .= '<span class="wm-comment-image">';
//             $gallery .= wp_get_attachment_image( $id, 'thumbnail');
//             $gallery .= '</span>';
//         }
//         $gallery = "</div>";

//     }


//     return "$date_html<div class='my_comment_text'>$comment_text</div>$gallery";
// }

// remove comments in Barche custom post type
add_action( 'init', 'remove_custom_post_comment' );

function remove_custom_post_comment() {
    remove_post_type_support( 'barche', 'comments' );
}


// add labels for each facet
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

/**
 * Icons for Single Route post type difficutly
 */
function the_calcola_url( $num )
{

    $numero_arrotondato = (str_replace(".","p",$num));
    echo "wm-icon-cy-difficulty-$numero_arrotondato";
}
/**
 * Icons for Single Route post type shape
 */
function the_shape_icon( $shape )
{
    switch ($shape) {
        case "linear":
            echo 'wm-icon-cy-shape-linear';
            break;
        case "roundtrip":
            echo 'wm-icon-cy-shape-roundtrip';
            break;
        case "daisy":
            echo 'wm-icon-cy-shape-daisy';
            break;
    }
    
}

// change the output of facetwp Counter
add_filter( 'facetwp_result_count', function( $output, $params ) {
    ($params['total'] == 1) ? $result_string = __('result', 'wm-child-cyclando') : $result_string = __('results', 'wm-child-cyclando');
    if ($params['total'] > 10) {  
        $output = $params['lower'] . '-' . $params['upper'] . ' ' .  __('of', 'wm-child-cyclando'). ' ' . $params['total'] . ' ' . $result_string ;
    } else {
        $output = $params['total'] . ' ' . $result_string ;
    }
    return $output;
}, 10, 2 );

// Edit facetWP HTML to add icons before the choices
add_filter( 'facetwp_facet_html', function( $output, $params ) {
    if ( 'cosa_vuoi_fare' == $params['facet']['name'] ) {
        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];
        foreach ( $values as $result ){
            $get_term_activity = get_term_by('slug', esc_attr( $result['facet_value'] ), 'activity');
            $term_activity = 'term_' . $get_term_activity->term_id;
            $iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);

            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= '<i class="'.$iconimage_activity.'"></i> '. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
            $output .= '</div>';
        }
    }

    if ( 'come_vuoi_viaggiare' == $params['facet']['name'] ) {
        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];
        foreach ( $values as $result ){
            $get_term_activity = get_term_by('slug', esc_attr( $result['facet_value'] ), 'who');
            $term_activity = 'term_' . $get_term_activity->term_id;
            $iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);

            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= '<i class="'.$iconimage_activity.'"></i> '. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
            $output .= '</div>';
        }
    }

    if ( 'come_deve_essere_la_forma_del_viaggio' == $params['facet']['name'] ) {
        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];
        foreach ( $values as $result ){
            $shape_name = '';
            switch (esc_attr( $result['facet_value'] )) {
                case "linear":
                    $shape_name = 'wm-icon-cy-shape-linear';
                    break;
                case "roundtrip":
                    $shape_name = 'wm-icon-cy-shape-roundtrip';
                    break;
                case "daisy":
                    $shape_name = 'wm-icon-cy-shape-daisy';
                    break;
            }

            $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
            $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
            $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
            $output .= '<i class="'. $shape_name.'"></i> '. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
            $output .= '</div>';
        }
    }

    if ( 'quanto_impegno_vorresti_mettere' == $params['facet']['name'] ) {
        $output = '<div class="facetwp-slider-wrap">';
        $output .= '<div class="facetwp-slider"></div>';
        $output .= '<div class="facetwp-slider-icon-container"><i class="wm-icon-cy-difficulty-1"></i> <i class="wm-icon-cy-difficulty-5"></i></div>';
        $output .= '</div>';
        $output .= '<span class="facetwp-slider-label"></span>';
        $output .= '<div><input type="button" class="facetwp-slider-reset" value="' . __( 'Reset', 'fwp-front' ) . '" /></div>';
    }
    return $output;
}, 10, 2 );


// Index the promotion value to yes and no in facetwp
add_filter( 'facetwp_index_row', function( $params, $class ) {
    if ( 'cerchi_un_viaggio_in_promozione' == $params['facet_name'] ) {
        if ( $params['facet_value'] > 0 ) {
            $params['facet_value'] = 'yes';
            $params['facet_display_value'] = __('Yes', 'wm-child-cyclando');
        } else {
            $params['facet_value'] = 'no';
            $params['facet_display_value'] = __('No', 'wm-child-cyclando');
        }
    }
    return $params;
}, 10, 2 );

/**changes the breadcrumb link of POI in yoast */
add_filter( 'wpseo_breadcrumb_links', 'yoast_seo_breadcrumb_append_link' );
function yoast_seo_breadcrumb_append_link( $links ) {
	
    if ( is_singular( 'route' ) ) {
        $breadcrumb[] = array(
            'url' => site_url( '/cerca/' ),
            'text' => __('Routes', 'wm-child-cyclando'),
        );
        array_splice( $links, 1,1, $breadcrumb );
    }
    
    if ( is_singular( 'post' ) ) {
        $breadcrumb[] = array(
            'url' => site_url( '/blog/' ),
            'text' => __('Blog', 'wm-child-cyclando'),
        );
        array_splice( $links, 1,0, $breadcrumb );
	}
	

    return $links;
	
}


// //  order wpfacet Duration and Seasosn months in archive route page
// add_filter( 'facetwp_facet_orderby', function( $orderby, $facet ) {
//     if ( 'durata' == $facet['name'] ) {
//         $orderby = 'f.facet_value+0 ASC';
//     }
//     if ( 'seasons' == $facet['name'] ) {
//         $orderby = 'FIELD(f.facet_display_value, "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre")';
//     }
//     return $orderby;
// }, 10, 2 );

/*
    wpfacet translate default labels
*/

// add_filter( 'facetwp_i18n', function( $string ) {
//     if ( isset( FWP()->facet->http_params['lang'] ) ) {
//         $lang = FWP()->facet->http_params['lang'];

//         $translations = array();
//         $translations['en']['Cerca'] = 'Search';

//         if ( isset( $translations[ $lang ][ $string ] ) ) {
//             return $translations[ $lang ][ $string ];
//         }
//     }

//     return $string;
// });

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


// validation on codice fiscale for a correct format
// add_action( 'woocommerce_after_checkout_validation', 'misha_validate_fname_lname', 10, 2);
 
// function misha_validate_fname_lname( $fields, $errors ){
 
//     // if ( preg_match( '/\\d/', $fields[ 'billing_last_name' ] )  ){
//     //     $errors->add( 'validation', 'Your first or last name contains a number. Really?' );
//     // }
//     // if ($fields['billing_codice_fiscale'] == 'privato') {
//         if ( preg_match( '/[A-Za-z]{6}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{2}[A-Za-z]{1}[0-9LMNPQRSTUV]{3}[A-Za-z]{1}/', $fields[ 'billing_codice_fiscale' ] ) !== 1 ){
//             $errors->add( 'validation', __('Your Tax code is incorrect!','wm-child-verdnatura') );
//         }
//     // }
// }


function wm_weekDayToWeekNumber( $days_of_week ){
    $r = array();
    $map = array('sun','mon', 'tue', 'wed', 'thu', 'fri', 'sat');
    foreach( $days_of_week as $i => $day_name )
    {
        if ( ( $j = array_search( $day_name , $map ) ) != -1  )
            $r[] = $j;
    }
    return $r;
}


class DaysOfWeek
{
    private $start;
    private $end;
    private $input_format = "d/m/Y";
    private $d_by_weekday = array();
    private $d = array();
    private $one_day;
    private $errors = array();

    function __construct($start,$end)
    {
        try{
            $this->start = $this->import_arg($start);
            $this->end = $this->import_arg($end);

            if ( $this->end < $this->start )
                throw new Exception('End of interval provided is lower than start.');

            $this->one_day = new DateInterval('P1D');

            $this->init();

        }
        catch (Exception $e){
            $error = "Invalid arguments provided.";
            $this->error_handler( $e , $error );
        }
    }

    /**
     * Create DateTime object
     * @param $data
     * @return bool|DateTime
     */
    private function import_arg( $data ){
        $format = $this->input_format;
        return DateTime::createFromFormat($format, $data);
    }

    /**
     * Calculate the day number of week for each day in interval start->end
     */
    protected function init(){

        $start = $this->start;
        $end = $this->end;

        //week number
        $w_n = $start->format('w');
        $i = $start ;

        while( $i < $end )
        {
            if ( ! isset( $this->d_by_weekday[$w_n] ) )
                $this->d_by_weekday[$w_n] = array();

            $tmp = clone $i;
            $this->d_by_weekday[$w_n][] = $tmp;
            $this->d[] = $tmp;

            if ( $w_n > 5 )
                $w_n = 0;
            else
                $w_n++;

            $i = $i->add($this->one_day);
        }

        ksort( $this->d_by_weekday);

    }

    /**
     * Error handler, trigger a warning and register it
     * @param bool $message
     * @param $exception - Exception object
     */
    protected function error_handler( $exception , $message = FALSE ){
        $error = "Error on Days of Weeks Constructor. " . ( $message ? $message : '' ) . $exception->getMessage();
        $this->errors[] = $error;
        trigger_error($error);
    }

    /**
     * Get all days divided in their week day
     * @return array - DateTime objects
     */
    public function get_daysOfWeek()
    {
        return $this->d_by_weekday;
    }

    public function get_start(){
        return $this->start;
    }

    public function get_end(){
        return $this->end;
    }

    public function get_errors(){
        return $this->errors;
    }

    public function has_errors(){
        return count( $this->errors ) != 0 ;
    }

    public function get_allDays(){
        return $this->d;
    }

    /**
     * Return days indexed by their week day number
     * @param $week_days - array of int
     * @param string $order_by
     * @return array - DateTime objects
     *
     * todo fix wrong atribute orderby asc/desc
     */
    public function query_byDayOfWeek( $week_days , $order_by = 'day_of_week' )
    {
        $r = array();
        if ( ! is_array( $week_days ) )
        {
            $week_days = array( $week_days );
        }

        switch ($order_by)
        {
            case 'day_of_week':
                foreach ( $week_days as $int )
                {
                    if ( isset($this->d_by_weekday[$int]) )
                        $r[$int] = array_values( $this->d_by_weekday[$int] );
                }
                break;
            case 'none':
                foreach ( $week_days as $int )
                {
                    if ( isset($this->d_by_weekday[$int]) )
                        $r = array_merge($r,$this->d_by_weekday[$int]);
                }
                break;
            default:
                break;
        }


        return $r;
    }




}
// add a control before single-route, that checks the next departure existance, if not turns on "not_salable"
// and turns "Not_salable" off by adding a valid departure date
add_action( 'wp', 'update_route_not_salable' );
function update_route_not_salable()
{
    
    if ( 'route' === get_post_type() AND is_singular() ) {
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
        
        $count = 0;
        foreach ($start_array as $date) {
            if ( date('Y-m-d', strtotime('+7 day')) <= date('Y-m-d', strtotime($date)) ){
                // update_field('not_salable',false,get_the_ID()); 
                break;
            } else {
                $count += 1;
            }
        }
        if ($count == count($start_array)){
            update_field('not_salable',true,get_the_ID());
        }
    } 

}

add_filter( 'facetwp_indexer_row_data', function( $rows, $params ) {
    if ( 'dove_vuoi_andare' == $params['facet']['name'] ) {
        $post_id = $params['defaults']['post_id'];   
        $post = get_post( $post_id );
        //TITLE
        $new_row = $params['defaults'];
        $new_row['facet_value'] = $post->post_name;
        $new_row['facet_display_value'] = $post->post_title;
        $rows[] = $new_row;
        //EXCERPT
        $new_row = $params['defaults'];
        $new_row['facet_value'] = $post->post_name;
        $new_row['facet_display_value'] = $post->post_excerpt;
        $rows[] = $new_row;
    }
    elseif ( 'quando_vuoi_partire' == $params['facet']['name'] )
    {
        if ( isset( $params['defaults']['term_id'] ) && $params['defaults']['term_id'] )
        {
            $term = get_term($params['defaults']['term_id']);
            $new_row = $params['defaults'];
            $new_row['facet_value'] = $term->slug;
            $new_row['facet_display_value'] = $term->name;
            $rows = [ $new_row ];
        }
           
    }

    return $rows;
}, 10, 2 );


add_action( 'save_post' , function( $post_id, $post, $update )
{
    if ( $post->post_type != 'route' )
        return;

    require_once __DIR__ . '/includes/class_cyDaysOfWeek.php' ;
    
    $departure_dates = get_field( 'departure_dates' , $post_id);
    $departure_periods = get_field( 'departures_periods' , $post_id);

    $dateTimes = [];


    if ( is_array( $departure_dates ) )
    {
        foreach ( $departure_dates as $date )
        {
            $dateTime = DateTime::createFromFormat('d/m/Y', $date['date']);
            $dateTimes[] = $dateTime;
        }
    }
   

    if ( is_array( $departure_periods ) )
    {
        foreach ( $departure_periods as $period )
        {
            $start = $period['start'];
            $end = $period['stop'];
            $week_days = isset( $period['week_days'] ) ? $period['week_days'] : FALSE;
            $dayOfWeek = new CyDaysOfWeek( $start , $end );
            if ( $week_days )
                $days = $dayOfWeek->query_byDayOfWeek($week_days);
            else
                $days = $dayOfWeek->get_allDays();  

            foreach ( $days as $daysOfWeekDay )
            {
                if ( is_array($daysOfWeekDay) ) 
                {
                    $merge = array_merge( $dateTimes , $daysOfWeekDay );
                    if ( is_array( $merge ) )
                        $dateTimes = $merge;
                }
            }
                
        }
    }

    $toRegister = [];
    $today = new DateTime();
    do_action( 'wpml_switch_language', "it" );
    if ( is_array( $dateTimes ) )
    {
        sort($dateTimes);
        foreach( $dateTimes as $dateTime )
        {
            if ( $dateTime instanceof DateTime )
            {
                
                $dateString = date_i18n("F Y", $dateTime->getTimestamp() ) ;

                if ( $today <= $dateTime && ! in_array( $dateString, $toRegister) )
                {
                    $term = get_term_by('name', $dateString, 'when');
                    if ( $term == FALSE )
                    {
                        $term = wp_insert_term( $dateString , 'when' , [
                            'slug' => $dateTime->getTimestamp()
                        ]);
                    }
                    if ( $term instanceof WP_Term )
                        $toRegister[] = $term->term_id;
                }
            }   
        }
    }
    

    if ( count( $toRegister ) > 0 )
    {
        wp_set_post_terms( $post_id , $toRegister , 'when');
    }
        
} , 10 , 3);


add_filter( "views_edit-route", function($views){
    ob_start();
    ?>
        <?php if ($_SERVER['SERVER_NAME'] !== 'cyclando.com') { ?>
            <div id="wm-wizards-container">
                <?php
                $conf = "routeWizard,singleFieldRouteWizard";
                echo do_shortcode ("[wmWizards conf='$conf']");
                ?>
            </div>
        <?php }
        echo ob_get_clean();
    return $views;
} );


/** add custom button on admin bar for taxonomy page edit */
require_once(ABSPATH . 'wp-admin/includes/screen.php');
function custom_button_example($wp_admin_bar){
    $screen = get_current_screen();
    // adds a modifica content button on the page edit of a taxonomy in backend 
    $post_type = get_post_type();
    if (! is_admin() && $post_type == 'route'){
        $page_id = get_the_ID();
        $args = array(
            'id' => 'edit-content',
            'meta' => array(
                'html' => '<wm-wizard-container data-conf=\'{"conf":"{\"wizard\":\"singleFieldRouteWizard\",\"feature_id\":\"'.$page_id.'\"}"}\'></wm-wizard-container>',
                'class' => 'edit-content-class',
            )
            );
            $wp_admin_bar->add_node($args);
    } 
}

add_action('admin_bar_menu', 'custom_button_example', 90);


// /** WPML CLONE FEATURED IMAGE **/
add_action( "save_post_route", function( $post_id, $post, $update ){

    //get post language
    $post_lang = apply_filters( 'wpml_post_language_details', NULL, $post_id );
    //get wpml default language
    $default_lang = apply_filters('wpml_default_language', NULL );
    if ( $post_lang['language_code'] && $post_lang['language_code'] == $default_lang )
        return;

    $this_post_thumb = get_post_thumbnail_id( $post );
    if ( $this_post_thumb )
        return;
    
    $post_default_language = apply_filters( 'wpml_object_id', $post_id, 'route', FALSE, $default_lang );
    if ( ! $post_default_language )
        return;
    $post_thumb = get_post_thumbnail_id( $post_default_language );
    if ( ! $post_thumb )
        return;

    set_post_thumbnail( $post, $post_thumb );
        
} , 10 , 3);