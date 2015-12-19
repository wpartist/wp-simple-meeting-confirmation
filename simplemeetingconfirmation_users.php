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

	function SMC_loadUserData($meetingID, $userName){

		global $wpdb;
		global $SMC_plugin_table;
		$row_data;
		$result;

		$sqlQuery = 'SELECT userName, meetingID, answer, nbParticipants, comments FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" AND userName="' . $userName . '";';
		$result =  mysql_query($sqlQuery);

		if (!$result) {
			die('loadUserData() - Invalid query: ' . mysql_error());
		}

		$row_data = mysql_fetch_array($result);

		return $row_data;
	}

