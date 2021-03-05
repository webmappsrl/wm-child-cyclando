<?php

defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

if (wm_isMobileDev()) {
    load_template( __DIR__ . '/templates/single-route-mobile.php' );
} else {
    load_template( __DIR__ . '/templates/single-route-desktop.php' );
}