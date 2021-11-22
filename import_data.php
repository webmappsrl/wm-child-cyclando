<?php
/**
 * REGISTER TMP ROUTE FIELDS
 * 27/11/2018
 * MB
 */

if ( class_exists('WebMapp_RegisterFieldsGroup') )
{
    $custom_fields = array(
        //"sih" => array( 'key' => "vn_sih" , 'type' => "true_false" , 'label' => "Show in home",'wpml_cf_preferences' => WEBMAPP_COPY_CUSTOM_FIELD ),//show in home
        //"new" => array( 'key' => "vn_new" , 'type' => "true_false" , 'label' => "Novità",'wpml_cf_preferences' => WEBMAPP_COPY_CUSTOM_FIELD ),//novità
        // "diff" => array( 'key' => "vn_diff" , 'type' => "number" , 'label' => "Difficoltà" ),
        //"mezza_pensione" => array( 'key' => "vn_mezza_pensione" , 'type' => "true_false" , 'label' => "Mezza pensione",'wpml_cf_preferences' => WEBMAPP_COPY_CUSTOM_FIELD ),
        // "sopraponte" => array( 'key' => "vn_sopraponte" , 'type' => "true_false" , 'label' => "Sopraponte" ),
        "durata" => array( 'key' => "vn_durata" , 'type' => "number" , 'label' => "Durata",'wpml_cf_preferences' => WEBMAPP_COPY_CUSTOM_FIELD ),
        // "note_dur" => array( 'key' => "vn_note_dur" , 'type' => "text" , 'label' => "Note Durata",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        //"partenze" => array( 'key' => "vn_partenze" , 'type' => "textarea" , 'label' => "Partenze" ),
        "part_sum" => array( 'key' => "vn_part_sum" , 'type' => "wysiwyg" , 'label' => "Partenze Riassunto",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        // "desc_min" => array( 'key' => "vn_desc_min" , 'type' => "textarea" , 'label' => "Descrizione Breve" ),
        // "note" => array( 'key' => "vn_note" , 'type' => "textarea" , 'label' => "Note",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        // "desc" => array( 'key' => "vn_desc" , 'type' => "wysiwyg" , 'label' => "Descrizione" ),
        "prog" => array( 'key' => "vn_prog" , 'type' => "wysiwyg" , 'label' => "Programma",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        //"scheda_tecnica" => array( 'key' => "vn_scheda_tecnica" , 'type' => "wysiwyg" , 'label' => "Scheda Tecnica",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        // "part_pr" => array( 'key' => "vn_part_pr" , 'type' => "wysiwyg" , 'label' => "Partenze e Prezzi",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        // "come_arrivare" => array( 'key' => "vn_come_arrivare" , 'type' => "wysiwyg" , 'label' => "Come Arrivare",'wpml_cf_preferences' => WEBMAPP_TRANSLATE_CUSTOM_FIELD ),
        // "latitude" => array( 'key' => "vn_latitude" , 'type' => "text" , 'label' => "Latitudine" ),
        // "longitude" => array( 'key' => "vn_longitude" , 'type' => "text" , 'label' => "Longitudine" ),
        //"prezzo" => array( 'key' => "vn_prezzo" , 'type' => "number" , 'label' => "Prezzo €" ),
        // "prezzo_sc" => array( 'key' => "vn_prezzo_sc" , 'type' => "number" , 'label' => "Prezzo Scontato €" ),
        // "ordine" => array( 'key' => "vn_ordine" , 'type' => "number" , 'label' => "Ordine" ),
        // "meta_dog" => array( 'key' => "vn_meta_dog" , 'type' => "true_false" , 'label' => "Dog Friendly" ),
        // "hide" => array( 'key' => "vn_hide" , 'type' => "true_false" , 'label' => "Nascondi dalla ricerca" ),
        //immagini
        // "immagine_mappa" => array( 'key' => "vn_immagine_mappa" , 'type' => "image" , 'label' => "Immagine mappa" ),
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
        $std['wpml_cf_preferences'] = $details['wpml_cf_preferences'] ;
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