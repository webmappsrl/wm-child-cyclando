<?php

require ('includes/oc_ajax_route_price.php');
require ('includes/oc_ajax_variation_price_update.php');
require ('includes/oc_ajax_variation_delete.php');
require ('includes/oc_ajax_product_delete.php');
require ('includes/oc_ajax_variation_add_modal.php');
require ('includes/oc_ajax_extra_variation_create_modal.php');
require ('includes/oc_ajax_extra_add_modal.php');
require ('includes/oc_ajax_product_options_add_modal.php');
require ('includes/oc_ajax_variation_create_modal.php');
require ('includes/oc_ajax_product_create_modal.php');
require ('import_data.php');
require ('shortcodes/route_table_price.php');
require ('shortcodes/route_table_price_email.php');
require ('shortcodes/wm_route_included_not_included.php');
require ('shortcodes/wm_route_included_not_included_email.php');
require ('shortcodes/dashboard_wizard_button.php');
require ('shortcodes/mobile_menu_quote_form.php');
require ('shortcodes/menu_search_facetwp_wizard.php');
require ('shortcodes/oneclick_search_form.php');
require ('shortcodes/oneclick_search_form_participants_bikes.php');
require ('shortcodes/oneclick_search_form_participants.php');
require ('shortcodes/oneclick_route_search_form_participants.php');
require ('shortcodes/oneclick_search_form_bikes.php');
require ('shortcodes/oneclick_route_search_form_bikes.php');
require ('shortcodes/oneclick_route_search_form_single.php');
require ('shortcodes/route-tabs/route-mobile-tab-includes.php');
require ('shortcodes/route-tabs/route-mobile-tab-plan.php');
require ('shortcodes/route-tabs/route-mobile-tab-program.php');
require ('shortcodes/route-oc/oneclick_route_form_datepicker.php');
require ('shortcodes/route-oc/oneclick_route_form_category.php');
require ('shortcodes/route-oc/oneclick_route_form_single_room.php');
require ('shortcodes/route-oc/oneclick_route_form_purchase.php');
require ('shortcodes/route-oc/oneclick_route_your_reservation_panel.php');
require ('shortcodes/route-oc/onclick_route_mobile_calculator_header.php');
require ('api/api-loader.php');
require ('includes/class_routeProductsOC.php') ;

require ('includes/wm_has_extra_get_label.php');
require ('includes/wm_has_hotel_get_label.php');


if ( class_exists( 'WP_CLI' ) ) {
    require ('wp-cli/cy-index-routes.php');
    require ('wp-cli/wm-save-routes-cyc.php');
}


// Uncomment to disable GUTHENBERG
// add_filter('use_block_editor_for_post_type', '__return_false');

//add_action('woocommerce_before_cart', 'preventivi_json_to_text',15);
//add_action('woocommerce_before_checkout_form', 'preventivi_json_to_text',15);


/**
 * Load translations for wm-child-verdenatura
 */
function vn_theme_setup(){
    load_theme_textdomain('wm-child-verdenatura', get_stylesheet_directory() . '/languages');
}
add_action('after_setup_theme', 'vn_theme_setup');



add_action( 'wp_enqueue_scripts', 'impreza_theme_enqueue_styles' );
function impreza_theme_enqueue_styles() {
    
    wp_enqueue_style('jqeury-ui-tabs-style', 'https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_script( 'general_javascript', get_stylesheet_directory_uri() . '/js/general.js', array ('jquery') );
    // wp_enqueue_script( 'hightlight', get_stylesheet_directory_uri() . '/js/home_highlight.js');
    wp_enqueue_script('hubspot_contact_form', '//js.hsforms.net/forms/v2.js', array('jquery'));
    wp_enqueue_script('datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js', array('jquery'));
    //add hubspot to Browser IE 8
    wp_register_script('hubspot_contact_form_IE8', '//js.hsforms.net/forms/v2-legacy.js', array('jquery'));
    wp_enqueue_script( 'hubspot_contact_form_IE8');
    wp_script_add_data( 'hubspot_contact_form_IE8', 'conditional', 'lt IE 8' );
    wp_enqueue_style('cyclando-icons', get_stylesheet_directory_uri() . '/fonts/cyclando-icons/style.css');
    if (is_singular('route')){
        wp_enqueue_style('route-single-post-style', get_stylesheet_directory_uri() . '/single-route-style.css');
        wp_enqueue_script('jQueryValidate', 'https://ajax.aspnetcdn.com/ajax/jquery.validate/1.19.2/jquery.validate.min.js', array('jquery'));
    }
    if (is_singular('route') && current_user_can('administrator')){
        wp_enqueue_script( 'dp_javascript', get_stylesheet_directory_uri() . '/js/dp_variation.js', array ('jquery') );
    }

}

function admin_css_load() {
	wp_enqueue_style('style-admin-css', get_stylesheet_directory_uri().'/style-admin.css');
}
add_action('admin_enqueue_scripts', 'admin_css_load');


add_action( 'wp_head' , 'wm_add_seo_script' );
function wm_add_seo_script(){
    if ($_SERVER['SERVER_NAME'] == 'cyclando.com') {
        echo '<meta name="google-site-verification" content="WuvVk6Oe2JdEzjKoI8vXlWjz20YFgwj32vSEoZMF9mU" />';
        echo '<meta name="facebook-domain-verification" content="2lvld3fv2q0bm9uasx5n0mr0njgbp8" />';
        echo '<script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/6554435.js"></script>';
    }
}

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
                    // var pathname = window.location.href;
                    // var url = new URL(pathname);
                    // var lang = url.searchParams.get("lang");
                    var lang = document.documentElement.lang;

                    var $facet = $(this);
                    var facet_name = $facet.attr('data-name');
                    var facet_label = FWP.settings.labels[facet_name];

                    if (lang == 'en-US') {
                        if (facet_label == 'Cosa vuoi fare?') {
                            facet_label = 'Select Tour Type';
                        }
                        if (facet_label == 'Come deve essere la forma del viaggio?') {
                            facet_label = 'Select Tour Shape';
                        }
                        if (facet_label == 'Come vuoi viaggiare?') {
                            facet_label = 'Select Tour Category';
                        }
                        if (facet_label == 'Quanto ti senti allenato?') {
                            facet_label = 'Select difficulty';
                        }
                        if (facet_label == 'Quanto vuoi che duri la tua vacanza?') {
                            facet_label = 'Select duration';
                        }
                        if (facet_label == 'Quanto vuoi spendere?') {
                            facet_label = 'Select price';
                        }
                        if (facet_label == 'Cerchi un viaggio in promozione?') {
                            facet_label = 'Are you looking for a holiday deal?';
                        }
                    }

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
            return 'wm-icon-cyc_percorso-lineare';
            break;
        case "roundtrip":
            return 'wm-icon-cyc_percorso-ad-anello';
            break;
        case "daisy":
            return 'wm-icon-cyc_percorso-a-margherita';
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
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    } else {
        $language = 'it';
    }
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
            $output .= '<i class="'.$iconimage_activity.'"></i>'. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
            $output .= '</div>';
        }
    }

    if ( 'come_vuoi_viaggiare' == $params['facet']['name'] ) {
        $exclude = ['city-tours-en','weekend-en','private','guided','with-family','self-guided-en'];
        $output = '';
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];
        foreach ( $values as $result ){
            if ($language == 'it' && in_array($result['facet_value'],$exclude)) {

            } else {
                $get_term_activity = get_term_by('slug', esc_attr( $result['facet_value'] ), 'who');
                $term_activity = 'term_' . $get_term_activity->term_id;
                $iconimage_activity = get_field('wm_taxonomy_icon', $term_activity);
    
                $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
                $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
                $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
                $output .= '<i class="'.$iconimage_activity.'"></i>'. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
                $output .= '</div>';
            }
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
                    $shape_name = 'wm-icon-cyc_percorso-lineare';
                    break;
                case "roundtrip":
                    $shape_name = 'wm-icon-cyc_percorso-ad-anello';
                    break;
                case "daisy":
                    $shape_name = 'wm-icon-cyc_percorso-a-margherita';
                    break;
            }
            if ($language == 'it') {
                $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
                $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
                $output .= '<div class="facetwp-checkbox' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
                $output .= '<i class="'. $shape_name.'"></i>'. esc_html( $result['facet_display_value'] ) . ' (' .$result['counter'].')';
                $output .= '</div>';
            } else {
                $selected = in_array( $result['facet_value'], $selected_values ) ? ' checked' : '';
                $selected .= ( 0 == $result['counter'] && '' == $selected ) ? ' disabled' : '';
                $output .= '<div class="facetwp-checkbox checkbox-eng' . $selected . '" data-value="' . esc_attr( $result['facet_value'] ) . '">';
                $output .= '<i class="'. $shape_name.'"></i>'. esc_html( $result['facet_value'] ) . ' (' .$result['counter'].')';
                $output .= '</div>';
            }
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
// Exclude Taxonomy Cyclando from facetwp results in come_vuoi_viaggiare facet
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
    if ( 'come_vuoi_viaggiare' == $params['facet_name'] ) {
        $excluded_terms = array( 'Cyclando' );
        if ( in_array( $params['facet_display_value'], $excluded_terms ) ) {
            $params['facet_value'] = '';
        }
    }
    if ( 'cosa_vuoi_fare' == $params['facet_name'] ) {
        $excluded_terms = array( 'Trekking' );
        if ( in_array( $params['facet_display_value'], $excluded_terms ) ) {
            $params['facet_value'] = '';
        }
    }
    return $params;

}, 10, 2 );

// /**changes the breadcrumb link of POI in yoast */
// add_filter( 'wpseo_breadcrumb_links', 'yoast_seo_breadcrumb_append_link' );
// function yoast_seo_breadcrumb_append_link( $links ) {
	
//     if ( is_singular( 'route' ) ) {
//         $breadcrumb[] = array(
//             'url' => site_url( '/cerca/' ),
//             'text' => __('Routes', 'wm-child-cyclando'),
//         );
//         array_splice( $links, 1,1, $breadcrumb );
//     }
    
//     if ( is_singular( 'post' ) ) {
//         $breadcrumb[] = array(
//             'url' => site_url( '/blog/' ),
//             'text' => __('Blog', 'wm-child-cyclando'),
//         );
//         array_splice( $links, 1,0, $breadcrumb );
// 	}
	

//     return $links;
	
// }

/**changes the breadcrumb link of POI and blog in Math rank breadcrumb */
add_filter( 'rank_math/frontend/breadcrumb/items', function( $crumbs, $class ) {
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    }
    if ( is_singular( 'route' ) ) {
        if ($language == 'it') { 
            $breadcrumb[] = array(
                '0' => __('Routes', 'wm-child-cyclando'),
                '1' => site_url( '/cerca/' ),
            );
        } else {
            $breadcrumb[] = array(
                '0' => __('Routes', 'wm-child-cyclando'),
                '1' => site_url( '/en/tours/' ),
            );
        }
        
        array_splice( $crumbs, 1,1, $breadcrumb );
    }
    if ( is_singular( 'post' ) ) {
        $breadcrumb[] = array(
            '0' => __('Blog', 'wm-child-cyclando'),
            '1' => site_url( '/blog/' ),
        );
        array_splice( $crumbs, 1,0, $breadcrumb );
	}
	return $crumbs;
}, 10, 2);



// //  order wpfacet Duration and Seasosn months in archive route page
add_filter( 'facetwp_facet_orderby', function( $orderby, $facet ) {
    if (defined('ICL_LANGUAGE_CODE')) {
        $language = ICL_LANGUAGE_CODE;
    } else {
        $language = 'it';
    }
    if ( 'quando_vuoi_partire' == $facet['name'] ) {
        if ($language == 'it') {
            $orderby = 'FIELD(f.facet_display_value, "Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre")';
        } 
        if ($language == 'en') {
            $orderby = 'FIELD(f.facet_display_value, "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")';
        } 
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
        $translations['en']['Cerca'] = 'Apply';
        $translations['en']['Giorni'] = 'Days';
        $translations['en']['Percorso ad anello'] = 'Roundtrip';
        $translations['en']['Percorso a margherita'] = 'Daisy';
        $translations['en']['Percorso lineare'] = 'Linear';

        if ( isset( $translations[ $lang ][ $string ] ) ) {
            return $translations[ $lang ][ $string ];
        }
    }

    return $string;
});

add_filter( 'facetwp_facet_render_args', function( $args ) {
    if ( 'quando_vuoi_partire' == $args['facet']['name'] ) {
        $translations = [
            'Gennaio' => __( 'January', 'wm-child-cyclando' ),
            'Febbraio' => __( 'February', 'wm-child-cyclando' ),
            'Marzo' => __( 'March', 'wm-child-cyclando' ),
            'Aprile' => __( 'April', 'wm-child-cyclando' ),
            'Maggio' => __( 'May', 'wm-child-cyclando' ),
            'Giugno' => __( 'June', 'wm-child-cyclando' ),
            'Luglio' => __( 'July', 'wm-child-cyclando' ),
            'Agosto' => __( 'August', 'wm-child-cyclando' ),
            'Settembre' => __( 'September', 'wm-child-cyclando' ),
            'Ottobre' => __( 'October', 'wm-child-cyclando' ),
            'Novembre' => __( 'November', 'wm-child-cyclando' ),
            'Dicembre' => __( 'December', 'wm-child-cyclando' ),
        ];

        if ( ! empty( $args['values'] ) ) {
            foreach ( $args['values'] as $key => $val ) {
                $display_value = $val['facet_display_value'];
                if ( isset( $translations[ $display_value ] ) ) {
                    $args['values'][ $key ]['facet_display_value'] = $translations[ $display_value ];
                }
            }
        }
    }
    return $args;
});




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


add_action( 'save_post' , 'sync_route_dates_with_when',10,3);
function sync_route_dates_with_when( $post_id, $post, $update ){
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
                // Create When taxonomies with MONTH and YEAR
                // $dateString = date_i18n("F Y", $dateTime->getTimestamp() ) ;
                // Create When taxonomies with MONTH only
                $dateString = date_i18n("F", $dateTime->getTimestamp() ) ;

                // if ( $today <= $dateTime && ! in_array( $dateString, $toRegister) )
                // {
                    $term = get_term_by('name', $dateString, 'when');
                    if ( $term == FALSE )
                    {
                        // $term = wp_insert_term( $dateString , 'when' , [
                        //     'slug' => $dateTime->getTimestamp()
                        // ]);
                        $term = wp_insert_term( $dateString , 'when' , [
                            'slug' => $dateString
                        ]);
                    }
                    if ( $term instanceof WP_Term )
                        $toRegister[] = $term->term_id;
                // }
            }   
        }
    }
    

    if ( count( $toRegister ) > 0 )
    {
        wp_set_post_terms( $post_id , $toRegister , 'when');
    }
        
};


add_filter( "views_edit-route", function($views){

    if ( ! get_option('webmapp_use_wizards') )
        return $views;

    ob_start();
    ?>
        <?php if ($_SERVER['SERVER_NAME'] !== 'cyclando.com') { ?>
            <div id="wm-wizards-container">
                <?php
                $conf = "singleFieldRouteWizard";
                echo do_shortcode ("[wmWizards conf='$conf']");
                ?>
            </div>
        <?php }
        echo ob_get_clean();
    return $views;
} );



function custom_button_example($wp_admin_bar){

    if ( ! get_option('webmapp_use_wizards') )
        return;

    /** add custom button on admin bar for taxonomy page edit */
    require_once(ABSPATH . 'wp-admin/includes/screen.php');
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


function return_route_targets_array($post_id){
    $target = 'who';
    $tax_targets = get_the_terms($post_id, $target);
    
    $tax_targets_slug = array();
    foreach ($tax_targets as $tax_target) {
        array_push($tax_targets_slug, $tax_target->slug );
    }
    return $tax_targets_slug;
}


function return_route_targets_has_cyclando($post_id){
    $target = 'who';
    $tax_targets = get_the_terms($post_id, $target);
    if (!$tax_targets)
        return false;
    $tax_targets_slug = array();
    foreach ($tax_targets as $tax_target) {
        array_push($tax_targets_slug, $tax_target->slug );
    }
    if (in_array('cyclando',$tax_targets_slug)) {
        return true;
    } else {
        return false;
    }
}

// Check if url exists / route has geojson
function URL_exists($url){
    return false;
    $headers=get_headers($url);
    return stripos($headers[0],"200 OK")?true:false;
}

// Orders facetwp results by price DESC or ASC
// add_filter( 'facetwp_sort_options', function( $options, $params ) {
//     $options['price_desc'] = array(
//         'label' => __( 'Price (Highest)', 'wm-child-cyclando' ),
//         'query_args' => array(
//             'orderby' => 'meta_value_num',
//             'meta_key' => 'wm_route_price',
//             'order' => 'DESC',
//         )
//     );
//     $options['price_asc'] = array(
//         'label' => __( 'Price (Lowest)', 'wm-child-cyclando' ),
//         'query_args' => array(
//             'orderby' => 'meta_value_num',
//             'meta_key' => 'wm_route_price',
//             'order' => 'ASC',
//         )
//     );
//     return $options;
// }, 10, 2 );

// code to grant all additional WPML capabilities to administrators
// Trun this code off after using it
function wpmlsupp_1706_reset_wpml_capabilities() {
    if ( function_exists( 'icl_enable_capabilities' ) ) {
        icl_enable_capabilities();
    }
}
add_action( 'shutdown', 'wpmlsupp_1706_reset_wpml_capabilities' );


// Gives Editor role possibility to modify users
function wm_editor_can_edit_user(){
    $editorRole = get_role( 'editor' );
    $editorRole->add_cap( 'edit_users' );
    $editorRole->add_cap('list_users');
}
add_action( 'init', 'wm_editor_can_edit_user' );


// Prepare the program content for ajax call wm_ajax_program_content in single_route
add_action( 'wp_ajax_wm_ajax_program_content', 'wm_ajax_program_content' );
add_action( 'wp_ajax_nopriv_wm_ajax_program_content', 'wm_ajax_program_content' );
function wm_ajax_program_content(){
    $post_id = $_POST['postid'];          
    $program = get_field('vn_prog', $post_id);
    echo json_encode($program);
    wp_die();
}


// Adds a function to routes update/save that sets lowest price selected from product or 3.999 if 
// the route is not_salable (coming soon)
add_action( 'save_post' , 'wm_toggle_route_price',15,3);
function wm_toggle_route_price( $route_id, $post, $update )
{
    if ( $post->post_type != 'route' )
        return;
    
    //get post language
    $post_lang = apply_filters( 'wpml_post_language_details', NULL, $route_id );
    //WP_CLI::line( 'Route language is: '.$post_lang['language_code'].'');
    
    if ( $post_lang['language_code'] == 'it') {

        //check if route is coming soon
        $coming_soon = get_field('not_salable',$route_id);
        
        if ($coming_soon) {
            // get the route price
            $price = get_field('wm_route_price',$route_id);
            update_field('wm_route_price', '3999', $route_id);
            $num += 1;
        } else {
            //var
            $attributes_name_hotel = array();
            $variations_name_price = array();
            $list_all_variations_name = array();

            $attributes_name_hotel_seasonal = array();
            $variations_name_price_seasonal = array();
            $list_all_variations_name_seasonal = array();

            $lowest_price_list = array();

            $products = get_field('product',$route_id);
            

            if( $products ){
                foreach( $products as $p ){ // variables of each product
                $product = wc_get_product($p); 
                    if($product->is_type('variable')){
                        $product_with_variables = wc_get_product( $p );
                        $category = $product_with_variables->get_categories();
                        $attributes_list = $product_with_variables->get_variation_attributes();
                        foreach ($attributes_list as $value => $key ) {
                            $product_attribute_name = $value;
                        }
                        if(strip_tags($category) == 'hotel'){
                            array_push($attributes_name_hotel,$product_attribute_name);
                            $product_variation_name_price = array();
                            foreach($product->get_available_variations() as $variation ){

                                // hotel Name
                                $attributes = $variation['attributes'];
                                $variation_name = '';
                                foreach($attributes as $name_var){
                                    $variation_name = $name_var;
                                }
                                // Prices
                                if ($variation['display_price'] == 0){
                                    $price = __('Free' ,'wm-child-verdenatura');
                                } 
                                elseif (!empty($variation['price_html'])){
                                    $price = $variation['price_html'];
                                } else {
                                    $price = $variation['display_price'].'€';
                                }
                                $variation_name_price = array($variation_name => $price);
                                $list_all_variations_name += array($variation_name => $variation['price_html']);
                                $product_variation_name_price += $variation_name_price;
                            }
                            $variations_name_price += array( $product_attribute_name =>$product_variation_name_price);
                        }
                    }
                }
            }
            while( have_rows('model_season',$route_id) ): the_row();
                $season_products = get_sub_field('wm_route_quote_model_season_product',$route_id); 
                if ($season_products){  //----------- start hotel product table
                    $attributes_name_hotel_seasonal = array();
                    $variations_name_price_seasonal = array();
                    $list_all_variations_name_seasonal = array();
                    foreach( $season_products as $p ){ // variables of each product
                    $product = wc_get_product($p); 
                        if($product->is_type('variable')){
                            $product_with_variables = wc_get_product( $p );
                            $category = $product_with_variables->get_categories();
                            $attributes_list = $product_with_variables->get_variation_attributes();
                            foreach ($attributes_list as $value => $key ) {
                                $product_attribute_name = $value;
                            }
                            if(strip_tags($category) == 'hotel'){
                                array_push($attributes_name_hotel_seasonal,$product_attribute_name);
                                $product_variation_name_price = array();
                                foreach($product->get_available_variations() as $variation ){

                                    // hotel Name
                                    $attributes = $variation['attributes'];
                                    $variation_name = '';
                                    foreach($attributes as $name_var){
                                        $variation_name = $name_var;
                                    }
                                    // Prices
                                    if (!empty($variation['price_html'])){
                                        $price = $variation['price_html'];
                                    } else {
                                        $price = $variation['display_price'].'€';
                                    }
                                    $variation_name_price = array($variation_name => $price);
                                    $list_all_variations_name_seasonal += array($variation_name => $variation['price_html']);
                                    $product_variation_name_price += $variation_name_price;
                                }
                                $variations_name_price_seasonal += array( $product_attribute_name =>$product_variation_name_price);
                            }
                        }
                    }
                    foreach ( $variations_name_price_seasonal as $var ) {
                        $price = preg_replace('/&.*?;/', '', $var['adult']);
                        $price = strip_tags($price);
                        $price = str_replace('€', '', $price);
                        $price_e = explode(',',$price);
                        $price_e = str_replace('.', '', $price_e[0]);
                        array_push($lowest_price_list , $price_e);
                    }
                }
            endwhile;

            //  add the lowest price to vn_prezzp ACF : price from... 
            foreach ( $variations_name_price as $var ) {
                $price = preg_replace('/&.*?;/', '', $var['adult']);
                $price = strip_tags($price);
                $price = str_replace('€', '', $price);
                $price_e = explode(',',$price);
                $price_e = str_replace('.', '', $price_e[0]);
                array_push($lowest_price_list , $price_e);
            }
            if ($lowest_price_list) {
                $lowest_price = min($lowest_price_list);
                update_field('wm_route_price', $lowest_price,$route_id);
            }
            $num += 1;
        }
    }
};

/** filter to return post_ids from a dove_vuoi_andare facet, narrow results facet **/
add_filter( 'facetwp_facet_filter_posts', function( $return, $params ) {
	if ( 'dove_vuoi_andare' == $params['facet']['name'] ) {
	    
		global $wpdb;

		$return          = array();
		$facet           = $params['facet'];
		$selected_values = $params['selected_values'];


        $sql = $wpdb->prepare( "SELECT DISTINCT post_id
            FROM {$wpdb->prefix}facetwp_index
            WHERE facet_name = %s",
			$facet['name']
		);

		// Match ALL values
		if ( $selected_values ) {

            
            // 
            // magic regex string
            // explained here -> https://regex101.com/r/T1TN8y/3
            // $regexString = "(?=.*((?:[^a-z]|^)(%s)(?:[^a-z]|$)).*)";
            $regexString = "(?=.*\b%s\b.*)";

            // get single words from search phrase (a word has a space after or before) -> insert them in array
            $wordsToSearch = explode( ' ' , $selected_values );

            // prepare regex string
            $regexFull = '';
            // foreach word print a regex rule as $regexString -> replace %s with single word
            foreach( $wordsToSearch as $w )
                $regexFull .= sprintf($regexString,$w);

            // escape words (security improvment for sql injection attacks)
            $sqlString = $wpdb->prepare("SELECT DISTINCT post_id
            FROM {$wpdb->prefix}facetwp_index
            WHERE facet_name = 'dove_vuoi_andare' AND facet_display_value REGEXP %s",
            $regexFull
            );
            // $return = $wpdb->get_results( $sqlString );
            $return = facetwp_sql( $sqlString , $facet );

			if ( empty( $return ) ) {
				return;
			}
		}

	}

	return $return;
}, 10, 2 );


//change header id for mobile version Search page - pagina cerca and Single route page
//add_filter('us_get_page_area_id','wm_custom_header_id_mobile');
function wm_custom_header_id_mobile($area_id){
    // Search page
    // if ($area_id == 57181 && wm_isMobileDev()) {
    //     $area_id = 103585;
    // }
    // Single route
    // if ($area_id == 50234 && wm_isMobileDev()) {
    //     // $area_id = 100474;
    //     $area_id = 103584;
    // }
    return $area_id;
}


function wm_isMobileDev(){
    if(isset($_SERVER['HTTP_USER_AGENT']) and !empty($_SERVER['HTTP_USER_AGENT'])){
       $user_ag = $_SERVER['HTTP_USER_AGENT'];
       if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$user_ag)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($user_ag,0,4))){
          return true;
       };
    };
    return false;
}


// Disable Facet Auto-Refresh
function fwp_disable_auto_refresh() {
?>
<script>
(function($) {
    $(function() {
        if ('undefined' !== typeof FWP) {
            FWP.auto_refresh = false;
        }
    });
})(jQuery);
</script>
<?php
}
add_action( 'wp_head', 'fwp_disable_auto_refresh', 100 );

// Function to get all the dates in given range 
function getDatesFromRange($start, $end, $format = 'd-m-Y') { 
      
    // Declare an empty array 
    $array = array(); 
      
    // Variable that store the date interval 
    // of period 1 day 
    $interval = new DateInterval('P1D'); 
  
    $realEnd = new DateTime($end); 
    $realEnd->add($interval); 
  
    $period = new DatePeriod(new DateTime($start), $interval, $realEnd); 
  
    // Use loop to store date into array 
    foreach($period as $date) {                  
        $array[] = $date->format($format);  
    } 
  
    // Return the array elements 
    return $array; 
} 

function route_has_extra_category($route_id) { 

    $products = get_field('product',$route_id);
    $object = array();
    if( $products ){
        foreach( $products as $p ){ // variables of each product
        $product = wc_get_product($p); 
            if($product->is_type('variable')){
                $product_with_variables = wc_get_product( $p );
                $category = $product_with_variables->get_categories();
                if(strip_tags($category) == 'extra'){
                    foreach($product->get_available_variations() as $variation ){
                        // Extra Name
                        $xattributes = $variation['attributes'];
                        $xvariation_name = '';
                        foreach($xattributes as $name_var){
                            $xvariation_name = $name_var;
                        }
                        // Prices
                        if ($variation['display_price'] == 0){
                            $xprice = __('Free' ,'wm-child-verdenatura');
                        }  else {
                            $xprice = $variation['display_price'];
                        }
                        $object[$xvariation_name]['label'] = wm_has_extra_get_label($xvariation_name,$variation['variation_description']);
                        $object[$xvariation_name]['price'] = str_replace('€','',strip_tags($xprice));
                    }
                }
            }
        }
    }
    return $object;
}
function route_has_hotel_category($route_id,$first_departure) {
    $attributes_name_hotel = array();
    $variations_name_price = array();

    $attributes_name_hotel_seasonal = array();
    $variations_name_price_seasonal = array();
    $list_all_variations_name_seasonal = array();

    $products = get_field('product',$route_id);
    
    //check if the route is in boat or not
    $place = '';
    $from = '';
    $to = '';
    $boat_trip = get_field('trip_with_boat',$route_id);
    if ($boat_trip) {
        $place = __('cabin','wm-child-verdenatura');
    } else {
        $place = __('room','wm-child-verdenatura');
    }
    // get route from and to
    // get the name of the cities From e To
    $from = get_field('from',$route_id);
    $to = get_field('to',$route_id);

    if( $products ){
        foreach( $products as $p ){ // variables of each product
        $product = wc_get_product($p); 
            if($product->is_type('variable')){
                $product_with_variables = wc_get_product( $p );
                $category = $product_with_variables->get_categories();
                $attributes_list = $product_with_variables->get_variation_attributes();
                foreach ($attributes_list as $value => $key ) {
                    $product_attribute_name = $value;
                }
                if(strip_tags($category) == 'hotel'){
                    array_push($attributes_name_hotel,$product_attribute_name);
                    $product_variation_name_price = array();
                    foreach($product->get_available_variations() as $variation ){

                        // hotel Name
                        $attributes = $variation['attributes'];
                        $variation_name = '';
                        foreach($attributes as $name_var){
                            $variation_name = $name_var;
                        }
                        // Prices
                        if ($variation['display_price'] == 0){
                            $price = __('Free' ,'wm-child-verdenatura');
                        }  else {
                            $price = $variation['display_price'];
                        }
                        $product_variation_name_price[$variation_name]['label'] = wm_has_hotel_get_label($variation_name,$variation['variation_description'],$place,$from,$to);
                        $product_variation_name_price[$variation_name]['price'] = str_replace('€','',strip_tags($price));
                    }
                    $variations_name_price += array( $product_attribute_name =>$product_variation_name_price);
                }
            }
        }
    }
    while( have_rows('model_season',$route_id) ): the_row();
        $season_products = get_sub_field('wm_route_quote_model_season_product'); 
        $variation_disacitve = get_sub_field('wm_route_quote_model_season_disactive');
        if (!$variation_disacitve) {
            if (have_rows('wm_route_quote_model_season_dates_periods_repeater')) {
                while( have_rows('wm_route_quote_model_season_dates_periods_repeater') ): the_row();
                $start = get_sub_field('wm_route_quote_model_season_dates_periods_start');
                $stop = get_sub_field('wm_route_quote_model_season_dates_periods_stop');
                $start = DateTime::createFromFormat('d/m/Y', $start);
                $stop = DateTime::createFromFormat('d/m/Y', $stop);
                $start = $start->format('m/d/Y');
                $stop = $stop->format('m/d/Y');
                $days = getDatesFromRange($start, $stop); 
                foreach ( $days as $day )
                {
                    if ( $day == $first_departure ) 
                    {
                        if ($season_products){  //----------- start hotel product table
                            foreach( $season_products as $p ){ // variables of each product
                            $product = wc_get_product($p); 
                                if($product->is_type('variable')){
                                    $product_with_variables = wc_get_product( $p );
                                    $category = $product_with_variables->get_categories();
                                    $attributes_list = $product_with_variables->get_variation_attributes();
                                    foreach ($attributes_list as $value => $key ) {
                                        $product_attribute_name = $value;
                                    }
                                    if(strip_tags($category) == 'hotel'){
                                        array_push($attributes_name_hotel_seasonal,$product_attribute_name);
                                        $product_variation_name_price = array();
                                        foreach($product->get_available_variations() as $variation ){
            
                                            // hotel Name
                                            $attributes = $variation['attributes'];
                                            $variation_name = '';
                                            foreach($attributes as $name_var){
                                                $variation_name = $name_var;
                                            }
                                            // Prices
                                            if ($variation['display_price'] == 0){
                                                $price = __('Free' ,'wm-child-verdenatura');
                                            }  else {
                                                $price = $variation['display_price'];
                                            }
                                            $product_variation_name_price[$variation_name]['label'] = wm_has_hotel_get_label($variation_name,$variation['variation_description'],$place,$from,$to);
                                            $product_variation_name_price[$variation_name]['price'] = str_replace('€','',strip_tags($price));
                                        }
                                        $variations_name_price_seasonal += array( $product_attribute_name =>$product_variation_name_price);
                                    }
                                }
                            }
                            
                        }
                    }
                }
                endwhile;
            }
            
        }
    endwhile;
    $object['model'] = $variations_name_price;
    $object['modelseasonal'] = $variations_name_price_seasonal;
    return $object;
}

// add custom text in checkout page for payment with bank transfer pagamento con bonifico
function wm_wc_review_order_before_payment(  ) { 
    ob_start();

    ?>
    <div class="woocommerce_custom_payment_info">
    <p><strong><?php echo __('If you prefer you can pay by bank transfer:', 'wm-child-cyclando');?></strong></p>
    <p><?php echo __('Payable to: Cyclando srl', 'wm-child-cyclando');?></p>
    <p><?php echo __('IBAN: IT13K0200867019000105916036', 'wm-child-cyclando');?></p>
    <p><?php echo __('Indicate in the reason: "Surname; Tour; departure date" and send a copy of the bank transfer to <a href="mailto:info@cyclando.com">INFO@CYCLANDO.COM</a>', 'wm-child-cyclando');?></p>
    </div>
    <?php

    echo ob_get_clean();
}; 
add_action( 'woocommerce_review_order_before_payment', 'wm_wc_review_order_before_payment', 10, 0 );

// change checkout place order button text
add_filter( 'woocommerce_order_button_text', 'wm_checkout_custom_button_text' );
 
function wm_checkout_custom_button_text( $button_text ) {
   return __('Pay', 'wm-child-cyclando');
}

// writes logs into a file in upload directory
function wm_write_log_file($entry, $mode = 'a', $file = 'wm_child_cyclando') {
    // Get WordPress uploads directory.
    $upload_dir = wp_upload_dir();
    $upload_dir = $upload_dir['basedir'];

    // If the entry is array, json_encode.
    $entry = json_encode( $entry ); 
    if (!file_exists($upload_dir.'/hubspot')) {
        mkdir($upload_dir.'/hubspot', 0777, true);
    }
    // Write the log file.
    $file  = $upload_dir . '/hubspot/' . $file . '.log';
    $file  = fopen( $file, $mode );
    $bytes = fwrite( $file, current_time( 'mysql' ) . "\n" ); 
    $bytes = fwrite( $file, $entry . "\n\n" ); 
    fclose( $file ); 

    return $bytes;
}

function route_has_single($has_hotel_category) {
    $has_single = '';
    if (count($has_hotel_category['modelseasonal']) >= 1) {
        $product_sample = $has_hotel_category['modelseasonal'][array_key_first($has_hotel_category['modelseasonal'])];
    } else {
        $product_sample = $has_hotel_category['model'][array_key_first($has_hotel_category['model'])];
    }
    if ($product_sample) {
        foreach ($product_sample as $key => $value) {
            // Activate single room select if there is any
            if ($key == 'adult-single') {
                $has_single = true;
            }
        }
    }
    return $has_single;
}

// function used in ajax route variation and product creation fronend
function wm_create_hotel_variation_mapping($place,$from,$to){
    $array = $variations = [
        'adult' => sprintf(__('Basic price in double %s' ,'wm-child-verdenatura'),$place),
        'adult-single' => sprintf(__('Supplement for single %s' ,'wm-child-verdenatura'),$place),
        'single-traveller' => sprintf(__('Supplement for single traveller' ,'wm-child-verdenatura'),$place),
        'adult-extra' => __('Basic price in 3rd bed adult' ,'wm-child-verdenatura'),
        'halfboard_adult' => __('Supplement for half board' ,'wm-child-verdenatura'),
        'nightsBefore_adult' => sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$from, $place),
        'nightsBefore_adult-single' => sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$from, $place),
        'nightsBefore_adult-extra' => sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$from),
        'nightsAfter_adult' => sprintf(__('Extra night in %s (Double %s)' ,'wm-child-verdenatura'),$to, $place),
        'nightsAfter_adult-single' => sprintf(__('Supplement for extra night in %s (Single %s)' ,'wm-child-verdenatura'),$to, $place),
        'nightsAfter_adult-extra' => sprintf(__('Extra night in %s (extra bed)' ,'wm-child-verdenatura'),$to),
    ];
    return $array;
}

// function used in ajax route variations for Extra creation in fronend
function wm_create_extra_variation_mapping(){
    $array = $variations = [
        'bike' => __('Supplement for bike rental' ,'wm-child-verdenatura'),
        'ebike' => __('Supplement for e-bike rental' ,'wm-child-verdenatura'),
        'kidbike' => __('Supplement for children bike' ,'wm-child-verdenatura'),
        'bike_tandem' => __('Supplement for tandem bike' ,'wm-child-verdenatura'),
        'bike_road' => __('Supplement for road bike rental' ,'wm-child-verdenatura'),
        'babyseat' => __('Supplement for child back seat rental' ,'wm-child-verdenatura'),
        'trailer' => __('Supplement for children trailer rental' ,'wm-child-verdenatura'),
        'trailgator' => __('Supplement for children trailgator' ,'wm-child-verdenatura'),
        'tagalong' => __('Supplement for follow-me rental' ,'wm-child-verdenatura'),
        'bikewarranty' => __('Bike Coverage' ,'wm-child-verdenatura'),
        'ebikewarranty' => __('E-bike Coverage' ,'wm-child-verdenatura'),
        'bike_tandemwarranty' => __('Tandem bike Coverage' ,'wm-child-verdenatura'),
        'bike_roadwarranty' => __('Road bike Coverage' ,'wm-child-verdenatura'),
        'helmet' => __('Supplement for adult helmet rental' ,'wm-child-verdenatura'),
        'kidhelmet' => __('Supplement for kid helmet rental' ,'wm-child-verdenatura'),
        'roadbook' => __('Printed road book maps' ,'wm-child-verdenatura'),
        'cookingclass' => __('Supplement for cooking class' ,'wm-child-verdenatura'),
        'transferBefore' => __('Supplement for transfer before the trip' ,'wm-child-verdenatura'),
        'transferAfter' => __('Supplement transfer after the trip' ,'wm-child-verdenatura'),
        'boardingtax' => __('Port charges (to be paid in advance)' ,'wm-child-verdenatura'),
        'bike_plus' => __('Supplement for bike rental Premium' ,'wm-child-verdenatura'),
        'bike_pluswarranty' => __('Supplement for bike coverage Premium' ,'wm-child-verdenatura'),
        'bike_mtb' => __('Supplement for MTB rental' ,'wm-child-verdenatura'),
        'bike_mtbwarranty' => __('Supplement for tandem rental' ,'wm-child-verdenatura'),
        'bike_ebikemtb' => __('Supplemento nolo E-MTB' ,'wm-child-verdenatura'),
        'bike_ebikemtbwarranty' => __('Supplement for E-MTB coverage' ,'wm-child-verdenatura'),
        'bike_ebikeroad' => __('Supplement for road e-bike rental' ,'wm-child-verdenatura'),
        'bike_ecargo' => __('Supplement for ecargo rental' ,'wm-child-verdenatura'),
        'bike_ecargowarranty' => __('Supplement for ecago Coverage' ,'wm-child-verdenatura'),
        'bike_own' => __('Supplement for your own bike' ,'wm-child-verdenatura'),
        'bike_ownwarranty' => __('Supplement for own bike Coverage' ,'wm-child-verdenatura'),
        'bike_recumbent' => __('Supplement for recumbent bike' ,'wm-child-verdenatura'),
        'gps' => __('Supplement for GPS' ,'wm-child-verdenatura'),
        'weehoo' => __('Supplement for Weehoo trailer' ,'wm-child-verdenatura'),
    ];
    return $array;
}


function create_variable_product_with_variations( $product_name, $products, $category, $attributeName,$varnames ){

    $product_id = wp_insert_post( array(
        'post_title' => $product_name,
        'post_status' => 'publish',
        'post_type' => "product",
        ) );
    wp_set_object_terms( $product_id, 'variable', 'product_type' );
    
    $product = new WC_Product_Variable( $product_id );

    // Add category to product
    wp_set_object_terms( $product_id, $category, 'product_cat' );

    // Visibility ('hidden', 'visible', 'search' or 'catalog')
    $product->set_catalog_visibility( 'visible' );
    $product->save();
    
    
    $attr_slug = sanitize_title($attributeName);

    $attributes_array[$attr_slug] = array(
        'name' => $attributeName,
        'value' => implode('|',$varnames),
        'is_visible' => '1',
        'is_variation' => '1',
        'is_taxonomy' => '0' // for some reason, this is really important       
    );
    update_post_meta( $product_id, '_product_attributes', $attributes_array );
    $product->save();
    WC_Product_Variable::sync( $product_id );

    foreach( $products as $variationname => $price) {
        if ($variationname !== 'attribute_name') {
            $variation_post = array(
                'post_title'  => $product->get_name(),
                'post_name'   => 'product-'.$product_id.'-variation',
                'post_status' => 'publish',
                'post_parent' => $product_id,
                'post_type'   => 'product_variation',
            );
        
            // Creating the product variation
            $variation_id = wp_insert_post( $variation_post );
        
            // Get an instance of the WC_Product_Variation object
            $variation = new WC_Product_Variation( $variation_id );
        
            $variation->set_attributes([$attr_slug => $variationname]);
            
            // Prices
            $variation->set_price( intval($price) );
            $variation->set_regular_price( intval($price) );
        
            // Stock
            $variation->set_manage_stock(false);
        
            WC_Product_Variable::sync( $product_id );
        
            $variation->save(); // Save the data
        }
    }
    
    $product_id = $product->save();

    return $product_id;
}


function sync_route_acf_with_new_product($repeatername,$repeaterrawid,$subfieldkey,$routeid,$product_id) {
    if ( $repeatername == 'false') {
        // get current value of acf 
        $values = get_field($subfieldkey,$routeid, false);
        $new_values = array();
        foreach ($values as $val)   {
            array_push($new_values,intval($val));
        }
        // add new id to the array
        $new_values[] = $product_id;

        return update_field( $subfieldkey, $new_values, $routeid );
    } else {
        // get current value of acf 
        $rows = get_field($repeatername,$routeid, false);
        $rawid = intval($repeaterrawid) - 1;
        $values = $rows[$rawid][$subfieldkey];
        $new_values = array();
        foreach ($values as $val)   {
            array_push($new_values,intval($val));
        }
        // add new id to the array
        $new_values[] = $product_id;
    
        return update_sub_field( array($repeatername, intval($repeaterrawid), $subfieldkey), $new_values, $routeid );
    }
}

// get the json file of promotion detail in root folder
function getPromoJsonContent(){
    return json_decode(file_get_contents(get_site_url().'/promo.json'));
    // $path = get_site_url().'/promo.json';
    // if (file_exists($path)) {
    // } else {
    //     return (object) [
    //         "price" => 50,
    //         "start" => "2021-11-01",
    //         "stop" => "2021-11-04"
    //     ];
    // }
}

// a function that checks if the promo is between the opening and closing dates
function isPromoActive($promo){
    $start = date( "Y-m-d",strtotime($promo->start));
    $stop = date( "Y-m-d",strtotime($promo->stop));
    $today = date("Y-m-d");
    if ($today >= $start && $today <= $stop) {
        return true;
    }
    return false;
}

// Adds the promo banner to header of all pages
add_action('us_before_canvas','add_cyc_promo_banner_header');
function add_cyc_promo_banner_header(){
    $promo = getPromoJsonContent();
    // switch the month and date for english translation
    $stop = date( "d/m",strtotime($promo->stop));
    $stop = explode('/',$stop);
    $stop_day = $stop[0];
    $stop_month = $stop[1];
    if (isPromoActive($promo)) {
        $output = '<div class="active-promo"><div class="promo-wrapper"><div class="promo-icon"><i class="fas fa-bullhorn"></i></div><div class="promo-text">';
        $output .= sprintf(__('Block your trip with just %s€ down payment until %3$s/%2$s!' ,'wm-child-cyclando'),$promo->price, $stop_day,$stop_month);
        $output .= '</div></div></div>';
        echo $output;
    }
}

function promoBannerOnRouteSummary(){
    $promoacconto = getPromoJsonContent();
    if (isPromoActive($promoacconto)) {
        $promoaccontostop = date( "d/m",strtotime($promoacconto->stop));
        $stop = explode('/',$promoaccontostop);
        $stop_day = $stop[0];
        $stop_month = $stop[1];
        $output = '<div class="route-active-promo"><p>';
        $output .= sprintf(__('Promo: Trip blocked by deposit until %2$s/%1$s' ,'wm-child-cyclando'),$stop_day,$stop_month);
        $output .= '</p></div>';
        echo $output;
    }
}


add_filter( 'facetwp_preload_url_vars', function( $url_vars ) {
    if ( 'cerca' == FWP()->helper->get_uri() || 'search' == FWP()->helper->get_uri() ) {
        if ( empty( $url_vars['quanto_vuoi_che_duri_la_tua_vacanza'] ) ) {
            $url_vars['quanto_vuoi_che_duri_la_tua_vacanza'] = [ '2.00','19.00' ];
        }
    }
    return $url_vars;
} );