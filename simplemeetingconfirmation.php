<?php

	/*
	Plugin Name: Simple Meeting Confirmation
	Description: Allows registered and/or non registered users to confirm if they will be present at a planned meeting. Modified from the original version 1.8.3 by Frederic Vuong.
	Version: 2.0.0
	Author: Frederic VUONG
	Author URI: http://vuong.fr/myitblog/about/
	License: GPL2
	*/

	/*

	Usage: Create a new post and add the following shortcode

	[SMC date="dateParameter"]

	You can optionally add the following parameters:
	
	[SMC date="dateParameter" expireson="dateParameter"  location="address" reqguests="true" description="a meeting description" displayresults="true" usersonly="true"]

	- date= A date or any string that will be used as a unique identifier of the meeting [mandatory field]
	- location= A location [optional field]
	- reqguests= if set to "true", will show the fields to add a number of guests invited by the registered user [optional field]. False by default
	- description= if added, will add a short description to the meeting [optional field]
	- displayresults= if set to "true", will show the results in a table format [optional field]. True by default
	- usersonly = if set to true, will prevent non registered users to add their name to the event. True by default
	- expireson = if set, will prevent to add/update records on the defined date.

	and also optionally, add some additional content with the SMC tags that will appear in the form table.

	Ex.: [SMC date="17/10/2010" location="Our Place" description="Housewarming party"]<img src="http://mysite.com/ourplace.jpg"[/SMC]

	TODO:
	- modify alter_table function
	- administration page + administration menu
		- drop table
		- delete records
	- pop up page to create shortcode parameters
	- create a parameter notification to send an email to someone when response is sent

	*/

	// Global data for configuration
	global $SMC_plugin_table;
	global $SMC_plugin_name;
	global $SMC_thisDir;

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
	$SMC_thisDir = dirname( plugin_basename( __FILE__ ) );

	include dirname(__FILE__) . "/simplemeetingconfirmation_database.php";
	include dirname(__FILE__) . "/simplemeetingconfirmation_meetings.php";
	include dirname(__FILE__) . "/simplemeetingconfirmation_users.php";
	include dirname(__FILE__) . "/simplemeetingconfirmation_functions.php";

	add_action( 'init', 'SMC_initialize' );
	add_shortcode( 'SMC', 'SMC_main' );
	register_activation_hook( __FILE__, 'SMC_activatePlugin' );
	register_deactivation_hook( __FILE__, 'SMC_deactivatePlugin' );

