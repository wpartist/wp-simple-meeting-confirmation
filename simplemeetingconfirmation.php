<?php

/*
Plugin Name: Simple Meeting Confirmation
Description: Allows registered and/or non registered users to confirm if they will be present at a planned meeting. Modified from the original version 1.8.3 by Frederic Vuong.
Version: 2.0.0
Author: Frederic VUONG
Author URI: http://vuong.fr/myitblog/about/
License: GPL2
*/

// Global data for configuration
global $SMC_plugin_table;
global $SMC_plugin_name;

// Global data to store parameters from the short code
global $SMC_date;
global $SMC_reqguests;
global $SMC_description;
global $SMC_displayresults;
global $SMC_usersonly;
global $SMC_location;
global $SMC_expireson;

// define the name of the table and the name of the plugin
$SMC_plugin_table = 'WP_SMC';
$SMC_plugin_name = 'simplemeetingconfirmation';

include dirname(__FILE__) . "/simplemeetingconfirmation_database.php";
include dirname(__FILE__) . "/simplemeetingconfirmation_meetings.php";
include dirname(__FILE__) . "/simplemeetingconfirmation_users.php";
include dirname(__FILE__) . "/simplemeetingconfirmation_functions.php";

add_action( 'init', 'SMC_languages' );
add_shortcode( 'SMC', 'SMC_main' );
register_activation_hook( __FILE__, 'SMC_activatePlugin' );
register_deactivation_hook( __FILE__, 'SMC_deactivatePlugin' );

