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
    system("wp term list when --field=term_id | xargs wp term delete when | tee -a \"$logpath\"");
    system("wp wm-save-routes-cyc | tee -a \"$logpath\"");
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