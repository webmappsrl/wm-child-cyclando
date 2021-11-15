<?php

# Register a custom 'foo' command to output a supplied positional param.
#
# $ wp foo bar --append=qux
# Success: bar qux

/**
 * Returns all post meta of post id provided
 *
 *
 * @when after_wp_load
 */
$cy_index_routes = function( $args, $assoc_args ) {
    $logpath = __DIR__ . "/cy-index-routes.log";

    $time = time();
    system( "echo $(date) > \"$logpath\"" );
    //system("wp term list when --field=term_id | xargs wp term delete when | tee -a \"$logpath\"");


    /**
     * DELETE ALL WHEN TERMS (in "it" and "en" languages)
     */
    $whenTermsArgs =  [
        'hide_empty' => FALSE,
        'taxonomy' => 'when'
    ];

    do_action( 'wpml_switch_language', "en" );
    $enTerms = get_terms(
        $whenTermsArgs
    );

    do_action( 'wpml_switch_language', "it" );
    $itTerms = get_terms(
        $whenTermsArgs
    );
    $terms = array_merge( $itTerms , $enTerms );
    foreach ( $terms as $term )
    {
        $check = wp_delete_term( $term->term_id , 'when');
        if ( $check )
        {
            $message = "Deleted term: {$term->slug}";
            WP_CLI::success( $message );
        }

        else {
            $message = "Something goes wrong deleting: {$term->slug}";
            WP_CLI::warning( $message );
        }

        system( "echo \"$message\" >> \"$logpath\"" );

    }

    /**
     * Saves routes to generate when terms
     */
    //save it routes
    system("wp wm-save-routes-cyc | tee -a \"$logpath\"");
    //save en routes
    system("wp wm-save-routes-cyc en | tee -a \"$logpath\"");

    /**
     * Index all on facetwp db table
     */
    system("wp facet index route | tee -a \"$logpath\"");

    $endTime = time();
    $executionTime = $endTime - $time;
    if ( $executionTime > 60 )
    {
        $executionTime = floor($executionTime / 60) . ' min and ' . ($executionTime % 60);
    }

    $executionTime .= ' seconds';


    WP_CLI::success( "DONE in $executionTime" );


};

WP_CLI::add_command( 'cy-index-routes', $cy_index_routes );
