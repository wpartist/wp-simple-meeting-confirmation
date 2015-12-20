<?php

	/*

	NAME: loadUserData($meetingID, $userName)

	DESCRIPTION: Load user data from the database

	INPUTS:
		$meetingID [String] : A meeting identifier
		$userName [String] : A user name

	OUTPUTS:
		$row_data: User/meeting information from the database in an array

	PROCESS:
		[1] Check parameters
		[2] Prepare query
		[3] Run query
		[4] Return result

	NOTES:

	*/

	function SMC_loadUserData( $meetingID, $posted_username ) {

		global $wpdb;
		global $SMC_plugin_table;

		$sqlQuery = 'SELECT userName, meetingID, answer, nbParticipants, comments FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" AND userName="' . $posted_username . '";';
		$result =  $wpdb->get_row( $sqlQuery );

		return $result;

	}
