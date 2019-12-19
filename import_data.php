<?php
/**
 * Created by PhpStorm.
 * User: Silvia
 * Date: 27/11/18
 * Time: 09:29
 */

// Max Mega Menu Import //

function megamenu_add_theme_verde_natura_1543588772($themes) {
    $themes["verde_natura_1543588772"] = array(
        'title' => 'verde_natura_import',
        'container_background_from' => 'rgb(255, 255, 255)',
        'container_background_to' => 'rgb(255, 255, 255)',
        'container_padding_bottom' => '10px',
        'menu_item_align' => 'center',
        'menu_item_background_hover_from' => 'rgb(221, 221, 221)',
        'menu_item_background_hover_to' => 'rgb(217, 217, 217)',
        'menu_item_spacing' => '5 px',
        'menu_item_link_font' => 'Arial, Helvetica, sans-serif',
        'menu_item_link_font_size' => '17px',
        'menu_item_link_height' => '45px',
        'menu_item_link_color' => 'rgb(51, 51, 51)',
        'menu_item_link_weight' => 'bold',
        'menu_item_link_text_align' => 'center',
        'menu_item_link_color_hover' => 'rgb(15, 122, 104)',
        'menu_item_link_weight_hover' => 'bold',
        'menu_item_link_padding_left' => '20.625px',
        'menu_item_link_padding_right' => '20.625px',
        'menu_item_border_color' => 'rgb(122, 180, 0)',
        'menu_item_border_right' => '2px',
        'menu_item_border_color_hover' => 'rgb(122, 180, 0)',
        'menu_item_divider_color' => 'rgb(122, 180, 0)',
        'panel_background_from' => 'rgb(255, 255, 255)',
        'panel_background_to' => 'rgb(255, 255, 255)',
        'panel_width' => 'body',
        'panel_header_color' => 'rgb(255, 255, 255)',
        'panel_header_font' => 'Arial, Helvetica, sans-serif',
        'panel_header_margin_bottom' => '10px',
        'panel_header_border_color' => '#555',
        'panel_padding_top' => '10px',
        'panel_padding_bottom' => '10px',
        'panel_widget_padding_left' => '5px',
        'panel_widget_padding_right' => '5px',
        'panel_widget_padding_top' => '5px',
        'panel_widget_padding_bottom' => '10px',
        'panel_font_size' => '14px',
        'panel_font_color' => '#666',
        'panel_font_family' => 'Arial, Helvetica, sans-serif',
        'panel_second_level_font_color' => 'rgb(255, 255, 255)',
        'panel_second_level_font_color_hover' => 'rgb(15, 122, 104)',
        'panel_second_level_text_transform' => 'uppercase',
        'panel_second_level_font' => 'Arial, Helvetica, sans-serif',
        'panel_second_level_font_size' => '15 px',
        'panel_second_level_font_weight' => 'bold',
        'panel_second_level_font_weight_hover' => 'bold',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_padding_left' => '10px',
        'panel_second_level_border_color' => 'rgb(15, 122, 104)',
        'panel_second_level_border_bottom' => '2px',
        'panel_third_level_font_color' => 'rgb(51, 51, 51)',
        'panel_third_level_font_color_hover' => 'rgb(15, 122, 104)',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '15px',
        'panel_third_level_font_weight' => 'bold',
        'panel_third_level_font_weight_hover' => 'bold',
        'panel_third_level_padding_left' => '10px',
        'panel_third_level_padding_top' => '10px',
        'panel_third_level_padding_bottom' => '10px',
        'flyout_link_size' => '14px',
        'flyout_link_color' => '#666',
        'flyout_link_color_hover' => '#666',
        'flyout_link_family' => 'inherit',
        'responsive_breakpoint' => '768px',
        'line_height' => '3',
        'z_index' => '0',
        'shadow' => 'on',
        'resets' => 'on',
        'mobile_columns' => '1',
        'toggle_background_from' => 'rgb(255, 255, 255)',
        'toggle_background_to' => 'rgb(255, 255, 255)',
        'mobile_menu_overlay' => 'on',
        //'mobile_menu_force_width_selector' => '10%',
        'mobile_background_from' => 'rgb(122, 180, 0)',
        'mobile_background_to' => 'rgb(122, 180, 0)',
        'mobile_menu_item_link_font_size' => '14px',
        'mobile_menu_item_link_color' => '#ffffff',
        'mobile_menu_item_link_text_align' => 'left',
        'mobile_menu_item_link_color_hover' => '#ffffff',
        'mobile_menu_item_background_hover_from' => '#333',
        'mobile_menu_item_background_hover_to' => '#333',
        'custom_css' => '/** Push menu onto new line **/ 
#{$wrap} { 
    clear: both; 
}',
    );
    return $themes;
}
add_filter("megamenu_themes", "megamenu_add_theme_verde_natura_1543588772");

/**
 * REGISTER TMP ROUTE FIELDS
 * 27/11/2018
 * MB
 */




if ( class_exists('WebMapp_RegisterFieldsGroup') )
{
    $custom_fields = array(
        "sih" => array( 'key' => "vn_sih" , 'type' => "true_false" , 'label' => "Show in home" ),//show in home
        "new" => array( 'key' => "vn_new" , 'type' => "true_false" , 'label' => "Novità" ),//novità
        "diff" => array( 'key' => "vn_diff" , 'type' => "number" , 'label' => "Difficoltà" ),
        "mezza_pensione" => array( 'key' => "vn_mezza_pensione" , 'type' => "true_false" , 'label' => "Mezza pensione" ),
        "sopraponte" => array( 'key' => "vn_sopraponte" , 'type' => "true_false" , 'label' => "Sopraponte" ),
        "durata" => array( 'key' => "vn_durata" , 'type' => "number" , 'label' => "Durata" ),
        "note_dur" => array( 'key' => "vn_note_dur" , 'type' => "text" , 'label' => "Note Durata" ),
        //"partenze" => array( 'key' => "vn_partenze" , 'type' => "textarea" , 'label' => "Partenze" ),
        "part_sum" => array( 'key' => "vn_part_sum" , 'type' => "wysiwyg" , 'label' => "Partenze Riassunto" ),
        "desc_min" => array( 'key' => "vn_desc_min" , 'type' => "textarea" , 'label' => "Descrizione Breve" ),
        "note" => array( 'key' => "vn_note" , 'type' => "textarea" , 'label' => "Note" ),
        "desc" => array( 'key' => "vn_desc" , 'type' => "wysiwyg" , 'label' => "Descrizione" ),
        "prog" => array( 'key' => "vn_prog" , 'type' => "wysiwyg" , 'label' => "Programma" ),
        "scheda_tecnica" => array( 'key' => "vn_scheda_tecnica" , 'type' => "wysiwyg" , 'label' => "Scheda Tecnica" ),
        "part_pr" => array( 'key' => "vn_part_pr" , 'type' => "wysiwyg" , 'label' => "Partenze e Prezzi" ),
        "come_arrivare" => array( 'key' => "vn_come_arrivare" , 'type' => "wysiwyg" , 'label' => "Come Arrivare" ),
        "latitude" => array( 'key' => "vn_latitude" , 'type' => "text" , 'label' => "Latitudine" ),
        "longitude" => array( 'key' => "vn_longitude" , 'type' => "text" , 'label' => "Longitudine" ),
        //"prezzo" => array( 'key' => "vn_prezzo" , 'type' => "number" , 'label' => "Prezzo €" ),
        "prezzo_sc" => array( 'key' => "vn_prezzo_sc" , 'type' => "number" , 'label' => "Prezzo Scontato €" ),
        "ordine" => array( 'key' => "vn_ordine" , 'type' => "number" , 'label' => "Ordine" ),
        "meta_dog" => array( 'key' => "vn_meta_dog" , 'type' => "true_false" , 'label' => "Dog Friendly" ),
        "hide" => array( 'key' => "vn_hide" , 'type' => "true_false" , 'label' => "Nascondi dalla ricerca" ),
        //immagini
        "immagine_mappa" => array( 'key' => "vn_immagine_mappa" , 'type' => "image" , 'label' => "Immagine mappa" ),
        //"image" => array( 'key' => "vn_immagine_mappa" , 'type' => "image" , 'label' => "Immagine" ),
        //"gallery" => array( 'key' => "vn_gallery" , 'type' => "gallery" , 'label' => "Galleria" ),
    );


    /**
     * MANCANO
     *
     * immagine mappa ( immagine )
     * image ( immagine )
     * gallery ( galleria )
     *
     */

    $std = array(
        'key' => '',
        'label' => '',
        'name' => '',
        'type' => ''
    );

    $fields = array();
    foreach ( $custom_fields as $field => $details )
    {
        $std['key'] = $details['key'];
        $std['name'] = $details['key'];
        $std['label'] = isset( $details['label'] ) && $details['label'] ? $details['label'] : $std['name'] ;
        $std['type'] = $details['type'] ;
        $fields[] = $std;
    }

    $args = array(
        'key' => 'group_vn_58528c8aa5b2ffaskd',
        'title' => 'Importazione Verde Natura',
        'fields' => $fields,
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'route',
                ),
            ),
        ),
        'menu_order' => 0,
        'active' => 1
    );
    new WebMapp_RegisterFieldsGroup('route' ,$args );
}

