<?php

require ('import_data.php');
require ('shortcodes/route_table_price.php');
require ('shortcodes/dashboard_wizard_button.php');
require ('shortcodes/mobile_menu_quote_form.php');
require ('url_filters.php');


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
    // css for dialog jquery not
    // wp_enqueue_script( 'dialogJquery', get_stylesheet_directory_uri() . 'https://code.jquery.com/jquery-1.12.4.js');
    // wp_enqueue_script( 'dialogUIJquery', get_stylesheet_directory_uri() . 'https://code.jquery.com/ui/1.12.1/jquery-ui.js');
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
?>
