<?php
function set_target_taxonomy_on_durata($post_id) {
    
    $post_type = get_post_type($post_id);
    if ($post_type != 'route') {
        return;
    }
    $route_durata = get_field('vn_durata',$post_id);
    $weekend = get_term_by('slug','weekend','who');
    $weekend_id = intval($weekend->term_id);
    $city_tours = get_term_by('slug','city-tours','who');
    $city_tours_id = intval($city_tours->term_id);

    if ($route_durata == 1) {
        wp_set_object_terms( $post_id, array($city_tours_id), 'who', true );
        wp_remove_object_terms($post_id, array($weekend_id), 'who');
    } elseif ($route_durata >= 2 && $route_durata <= 4 ) {
        wp_set_object_terms( $post_id, array($weekend_id), 'who', true );
        wp_remove_object_terms($post_id, array($city_tours_id), 'who');
    } elseif ($route_durata > 4){
        wp_remove_object_terms($post_id, array($city_tours_id), 'who');
        wp_remove_object_terms($post_id, array($weekend_id), 'who');
    }
    
}
add_action( 'acf/save_post' ,'set_target_taxonomy_on_durata',20,1);