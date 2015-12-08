<?php
 
	/*
 
	NAME: create_MeetingTable()
	
	DESCRIPTION: Create, in the WordPress database, the table that stores meeting confirmations
	
	INPUTS:
	
	OUTPUTS:
		1 if table created
		0 if table not created
		
	PROCESS:
		[1] Prepare query
		[2] Run query
		[2] Return query result
		
	NOTES:
	
	*/
	function create_MeetingTable(){
		
		global $wpdb;
		global $SMC_plugin_table;
		$result;
		$sqlQuery;
		
		$sqlQuery = 'CREATE TABLE ' . $SMC_plugin_table . ' (id int not null auto_increment, userName varchar(50), meetingID varchar(20), answer varchar(10), nbParticipants int, comments varchar(255), PRIMARY KEY(id));';
		
		$result = mysql_query($sqlQuery);
		
		if (!$result) {
			die('create_MeetingTable() - Invalid query: ' . mysql_error());
		}
		
		// run the query and return result
		return $result;		
	}

 	/*
 
	NAME: alter_MeetingTable()
	
	DESCRIPTION: Alter, in the WordPress database, the table that stores meeting confirmations
	
	INPUTS:
	
	OUTPUTS:
		1 if table created
		0 if table not created
		
	PROCESS:
		[1] Prepare query
		[2] Run query
		[2] Return query result
		
	NOTES:
	
	*/
	function alter_MeetingTable($fieldName, $fieldDefinition){
		
		global $wpdb;
		global $SMC_plugin_table;
		$result;
		$alreadyCreated;
		$sqlQuery;

		$alreadyCreated = false;
		
		$sqlQuery = 'SHOW COLUMNS FROM ' . $SMC_plugin_table . ';';
		
		$result = mysql_query($sqlQuery);
		
		if (!$result) 
			die('create_MeetingTable() - Invalid query: ' . mysql_error());

		if (mysql_num_rows($result) > 0) {

			while ($row = mysql_fetch_assoc($result)) {
				echo 'champ:' . $row["Field"] . '<br/>';
				if($row["Field"] == $fieldName)
					$alreadyCreated = true;
			}				
		}
		
		if($alreadyCreated != true){
			echo 'creation';
			$sqlQuery = 'ALTER TABLE ' . $SMC_plugin_table . ' ADD COLUMN ' . $fieldName . ' ' . $fieldDefinition . ';';
			$result = mysql_query($sqlQuery);
		}
		else
			echo 'deja la';

		if (!$result) 
			die('create_MeetingTable() - Invalid query: ' . mysql_error());
		
		// run the query and return result
		return $result;		
	}



	
	/* 

	NAME: drop_MeetingTable()
	
	DESCRIPTION: Drops, in the WordPress database, the table that stores meeting confirmations
	
	INPUTS:
	
	OUTPUTS:
		1 if table dropped
		0 if table not dropped
		
	PROCESS:
		[1] Prepare query
		[2] Run query
		[2] Return query result
		
	NOTES:

	*/
	function drop_MeetingTable(){
	
		global $wpdb;
		global $SMC_plugin_table;
		
		$sqlQuery = 'DROP TABLE ' . $SMC_plugin_table . ';';
		
		$result = mysql_query($sqlQuery);

		if (!$result) {
			die('drop_MeetingTable() - Invalid query: ' . mysql_error());
		}
		
		// run the query and return result
		return $result;		
	}

?>