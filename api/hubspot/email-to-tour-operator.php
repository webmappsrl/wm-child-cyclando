<?php
add_action( 'acf/save_post' ,'send_email_to_tour_operato',5,1);

function send_email_to_tour_operato($post_id) {
    
    $post_type = get_post_type($post_id);
    if ($post_type != 'route') {
        return;
    }

    // Get previous values.
    $prev_stato = get_field('stato_pubblicazione',$post_id);

    // Get submitted values.
    $new_stato = $_POST['acf']['wm_route_stato_pubblicazione'];

    // Check if a specific value was updated.
    if( isset($new_stato) && $new_stato == 'done' && $new_stato != $prev_stato) {
        wp_email_to_tour_operator($post_id);
    }
    
}

function wp_email_to_tour_operator($post_id) {

    $post_permalink = get_permalink($post_id);
    $post_title = get_the_title($post_id);
    // Get tour operator.
    $tour_operator_id = get_field('tour_operator',$post_id);
    $TO_email = get_field('tour_operator_email',$tour_operator_id[0]);
    
    // Define a constant to use with html emails
    define("HTML_EMAIL_HEADERS", array('Content-Type: text/html; charset=UTF-8'));
    $subject = 'Your tour is online on cyclando.com';
    $html_message = 'We are happy to inform you that your tour is now available on cyclando.com.<br>
                    Here’s the link: <br>
                    <a href="'.$post_permalink.'">'.$post_title.'</a><br>
                    Please check tour’s program, dates and prices and if you find anything wrong, please respond to this email providing details on what we should change<br>
                    Best wishes,<br>
                    Cyclando content team'; 
    
    // Send the email using wordpress mail function
    wp_mail( $TO_email, $subject, $html_message, HTML_EMAIL_HEADERS );
}