<?php
 	
	/*

	NAME: SMC_countRecords($meetingID, $userName, $yesOnly, $guests)
	
	DESCRIPTION: Allows to count records from the database based on some criteria
	
	INPUTS:
		$meetingID [string] : A meeting identifier
		$userName [string] : A user name
		$yesOnly [bool] : Only where answer is equal to checked
		$guests [bool] : Only guests
		
	OUTPUTS:
		$row[0]: The number of records
		
	PROCESS:
		[1] Check parameters
		[2] Prepare query
		[3] Run query
		[4] Return result
		
	NOTES:
		
	*/
	
	function SMC_countRecords($meetingID, $userName, $yesOnly, $guests){
	
		global $wpdb;
		global $SMC_plugin_table;
		$result;

		if ($userName == null) 
			$sqlQuery = 'SELECT COUNT(*) FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" ;';
		else 
			$sqlQuery = 'SELECT COUNT(*) FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" AND userName = "' . $userName .'";';

		if ($yesOnly == true)
			$sqlQuery = 'SELECT COUNT(*) FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" AND answer = "checked";';

		if ($guests == true)
			$sqlQuery = 'SELECT sum(nbParticipants) FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '";';
			
			
		$result = mysql_query($sqlQuery);

		if (!$result) {
			die('countRecords() - Invalid query: ' . mysql_error());
		}
		
		$row = mysql_fetch_array($result);
			
		return $row[0];
	
	}

		/*
	
	NAME: SMC_displayRecords($meetingID)
	
	DESCRIPTION: Displays records in an HTML table
	
	INPUTS:
		$meetingID [String] : A meeting identifier
		
	OUTPUTS:
		0: Default return
		
	PROCESS:
		[1] Prepare query
		[2] Run query
		[3] Display results in an HTML table
		
	NOTES:
	
	*/
	
	function SMC_displayRecords($meetingID){

		global $wpdb;
		global $SMC_plugin_table;
		global $SMC_reqguests;
		global $SMC_plugin_name;
		
		$row;
		$sqlQuery;
		$resultData;

		$sqlQuery = 'SELECT * FROM ' . $SMC_plugin_table .' WHERE meetingID="' . $meetingID . '" ;';
		
		$resultData =  mysql_query($sqlQuery);		
		
		echo '<table>';
		echo '<th>' . __('Name', $SMC_plugin_name) . '</th>';
		echo '<th>' . __('Present', $SMC_plugin_name) .'</th>';
		if ($SMC_reqguests == 'true')
			echo '<th>' . __('Number of guests', $SMC_plugin_name) . '</th>';
		echo '<th>' . __('Comments', $SMC_plugin_name) . '</th>';
		
		while($row = mysql_fetch_array($resultData)){
			echo '<tr>';		
				echo '<td>' . $row['userName'] . '</td>';
				echo '<td>' ; 
					if($row['answer'] == 'checked') 
						echo __('Yes', $SMC_plugin_name);
					else 
						echo __('No', $SMC_plugin_name); 
				echo '</td>';
				if ($SMC_reqguests == 'true')
					echo '<td>' . $row['nbParticipants'] . '</td>';		
				echo '<td>' . $row['comments'] . '</td>';		
				
			echo '</tr>';		
		}

		echo '<tr>';
		echo '<td><b>' . __('Grand Total: ', $SMC_plugin_name) . __('Yes', $SMC_plugin_name) .'</b></td>';
		echo '<td><b>' . SMC_countRecords($meetingID, null, true, false) . '/' . SMC_countRecords($meetingID, null, false, false) . '</b></td>';
		if ($SMC_reqguests == 'true'){
			echo '<td><b>' . SMC_countRecords($meetingID, null, true, true) . '</b></td>';
			echo '<td><b>' . (SMC_countRecords($meetingID, null, true, false) + SMC_countRecords($meetingID, null, true, true)) . '</b></td>';
		}
		else 
			echo '<td></td>';
		echo '</tr>';

		echo '</table>';

		echo '<hr>';	

		return 0;
		
	}
	


	/*  

	NAME: SMC_addRegisteredUserToMeeting($meetingID, $current_user, $answer, $nbParticipants, $comments)
	
	DESCRIPTION: Add a user to an event
	
	INPUTS:
		$meetingID [String] : The unique identifier of the meeting
		$current_user [String] : The current (registered) user
		$answer [String] : The answer provided by the current user
		$nbParticipants [int] : The number of participants invited by the current user
		$comments [String] : Comments provided by the current user
	
	OUTPUTS:
		1 if query run properly
		0 if issue in the query
		
	PROCESS:
		[1] Check parameters
		[2] Prepare query
		[3] Run query
		[4] Return query result
		
	NOTES:
		
	*/
	function SMC_addRegisteredUserToMeeting($meetingID, $current_user, $answer, $nbParticipants, $comments){
	
		global $wpdb;
		global $SMC_plugin_table;
		
		if ($current_user == null)
			$current_user = SMC_getCurrentUser()->user_login;
		if ($nbParticipants == '')
			$nbParticipants = 0;
		if ($answer == 'on')
			$answer = 'checked';
			
		$sqlQuery = 'INSERT INTO ' . $SMC_plugin_table . '(userName, meetingID, answer, nbParticipants, comments) VALUES("' . $current_user . '", "' . $meetingID . '", "' . $answer . '", ' . $nbParticipants .', "' . $comments . '");';
		$result = mysql_query($sqlQuery);

		if (!$result) {
			die('addRegisteredUserToMeeting() - Invalid query: ' . mysql_error());
		}
		
		// run the query and return result
		return $result;		
	}

	
	/*  
	
	NAME: SMC_updateRegisteredUserToMeeting($current_user, $meetingID, $answer, $nbParticipants, $comments)
	
	DESCRIPTION: Update user information in an event
	
	INPUTS:
		$meetingID [String] : The unique identifier of the meeting
		$current_user [String] : The current (registered) user
		$answer [String] : The answer provided by the current user
		$nbParticipants [int] : The number of participants invited by the current user
		$comments [String] : Comments provided by the current user
	
	OUTPUTS:
		1 if query run properly
		0 if issue in the query
		
	PROCESS:
		[1] Check parameters
		[2] Prepare query
		[3] Run query
		[4] Return query result
		
	NOTES:
	
	*/
	function SMC_updateRegisteredUserToMeeting($current_user, $meetingID, $answer, $nbParticipants, $comments){
	
		global $wpdb;
		global $SMC_plugin_table;
		
		if ($current_user == null)
			$current_user = SMC_getCurrentUser()->user_login;
		if ($nbParticipants == '')
			$nbParticipants = 0;
		if ($answer == 'on')
			$answer = 'checked';
		
		$sqlQuery = 'UPDATE ' . $SMC_plugin_table . ' SET answer = "' . $answer . '", nbParticipants = ' . $nbParticipants . ', comments = "' . $comments . '" WHERE userName = "' . $current_user . '" AND meetingID = "' . $meetingID . '";';
		
		$result = mysql_query($sqlQuery);
		
		if (!$result) {
			die('updateRegisteredUserToMeeting() - Invalid query: ' . mysql_error());
		}

		return $result;		
	}

	
	/*  

	NAME: SMC_deleteRegisteredUserToMeeting($meetingID, $current_user)
	
	DESCRIPTION: Delete an event or a user from an event
	
	INPUTS:
		$meetingID [String] : The unique identifier of the meeting
		$current_user [String] : A user or * (for all users)
	
	OUTPUTS:
		1 if query run properly
		0 if issue in the query
		
	PROCESS:
		[1] Check parameters
		[2] Prepare query
		[3] Run query
		[4] Return query result
		
	NOTES:
		This function is never called. It has been developped for the administration interface, allowing the administrator to
		delete all users from a meeting.
	
	*/
	
	function SMC_deleteRegisteredUserToMeeting($meetingID, $current_user){
	
		global $wpdb;
		global $SMC_plugin_table;
		
		// $current_user = *, all users from the meeting are deleted
		if ($current_user == '*') 
			$sqlQuery = 'DELETE FROM ' . $SMC_plugin_table . ' WHERE meetingID = "' . $meetingID . '";';
		else 
			$sqlQuery = 'DELETE FROM ' . $SMC_plugin_table . ' WHERE meetingID = "' . $meetingID . '" AND userName = "' . $current_user . '";';			
		
		$result = mysql_query($sqlQuery);
		
		if (!$result) {
			die('deleteRegisteredUserToMeeting() - Invalid query: ' . mysql_error());
		}

		return $result;	
	}
	
?>