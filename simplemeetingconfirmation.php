<?php

	/*
	
	Plugin Name: wp-simplemeetingconfirmation
	Plugin URI: http://wordpress.org/extend/plugins/wp-simplemeetingconfirmation/
	Description: Allows registered and/or non registered users to confirm if they will be present at a planned meeting and optionally add comments, number of participants coming with them, expiration date...
	Version: 1.8.3
	Author: Frederic VUONG
	Author URI: http://vuong.fr/myitblog/about/
	License: GPL2
		
	*/

	/*
		
	Usage: Create a new post and add the following shortcode
	
	[SMC date="dateParameter"]
	
	You can optionally add the following parameters:  [SMC date="dateParameter" expireson="dateParameter"  location="address" reqguests="true" description="a meeting description" displayresults="true" usersonly="true"]
	
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
	- css file for messages
	- modify alter_table function
	- administration page + administration menu	
		- drop table
		- delete records
	- pop up page to create shortcode parameters
	- check if name is entered when defined as usersonly=false, show an error message
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

	include(dirname(__FILE__) . "/" . $SMC_plugin_name . "_database.php");
	include(dirname(__FILE__) . "/" . $SMC_plugin_name . "_meetings.php");
	include(dirname(__FILE__) . "/" . $SMC_plugin_name . "_users.php");

	/*

	NAME: SMC_activatePlugin()
	
	DESCRIPTION: Create the table in the database when the plugin is activated
	
	INPUTS:
	
	OUTPUTS:
		1 if table created
		0 if table not created
		
	PROCESS:
		[1] Call function create_MeetingTable() 
		
	NOTES:
		
	*/
	
	function SMC_activatePlugin(){

		global $wpdb;
		global $SMC_plugin_table;
		$result;
		
		if($wpdb->get_var("SHOW TABLES LIKE '$SMC_plugin_table'") != $SMC_plugin_table)
			$result = create_MeetingTable();
		//else
			//$result = alter_MeetingTable();
			
		return $result;
	}
	
	/*
	
	NAME: SMC_deactivatePlugin()
	
	DESCRIPTION: 
	
	INPUTS:
	
	OUTPUTS:
		
	PROCESS:
		[1]  
		
	NOTES:
		For future options of deleting records or dropping the table
	 
	*/
	
	function SMC_deactivatePlugin(){	
	}

	
	/*
	
	NAME: SMC_loadShortCodeParameters($atts)
	
	DESCRIPTION: Load parameters from the shortcode
	
	INPUTS:
		$atts [Array] : the Array of parameters
		
	OUTPUTS:
		0: Default return
		
	PROCESS:
		[1] Read parameters
		[2] Store them in global variables
		
	NOTES:
		When no parameters are passed, empty strings will be store in the global variables.
		
	*/
	
	function SMC_loadShortCodeParameters($atts) {
		
		global $SMC_date;
		global $SMC_location;
		global $SMC_reqguests;
		global $SMC_description;
		global $SMC_displayresults;
		global $SMC_usersonly;
		global $SMC_expireson;
		
		$date;
		$location;
		$reqguests;
		$description;
		$displayresults;
		$usersonly;
		$expireson;

		
		// read parameters or assign them null values
		extract(shortcode_atts(array(
		'date' => '',
		'location' => '',
		'reqguests' => 'false',
		'description' => '',
		'displayresults' => 'true',		
		'usersonly' => 'true',
		'expireson' => '',
		), $atts));
		
		$SMC_date = $date;
		$SMC_location = $location;
		$SMC_reqguests = $reqguests;
		$SMC_description = $description;
		$SMC_displayresults = $displayresults;
		$SMC_usersonly = $usersonly;
		$SMC_expireson = $expireson;
		
		return 0;
	}
	

		/*
	
	NAME: SMC_main($atts, $content=null)
	
	DESCRIPTION: Main function
	
	INPUTS:
		$atts [Array] : The Array of parameters
		
	OUTPUTS:
		0: Default return
		
	PROCESS:
		[1] Call function to read parameters
		[2] Check if mandatory parameters are provided and if user is logged
		[3] Check if page is posted back
		[4] Insert or Update record in database
		[5] Load inserted/updated user data
		[6] Load form, pre-filled if existing data
		[7] Display results, if option is provided
		
		
	NOTES:
		When no parameters are passed or user is not logged, an error message will be displayed.
	
	*/
	
	function SMC_main($atts, $content=null){
	
		global $meta;
		global $SMC_date;
		global $SMC_location;
		global $SMC_reqguests;
		global $SMC_description;
		global $SMC_displayresults;
		global $SMC_usersonly;
		global $SMC_expireson;
		global $SMC_plugin_name;
		global $SMC_thisDir;
		
		$row_data;

		
		
		// load custom variables
		SMC_loadShortCodeParameters($atts);
			
		// check if SMC_Date field was filled, if so, display meeting information
		if ($SMC_date == '')  {
		
			displayErrorMsg('dateMissing');
			return false;
		}

		
		// check if page was posted back
		if ($_POST['hidUpdate'] == true) {

			if($_POST['txtDate'] == $SMC_date){

				if ($_POST['hidError'] != ''){				
						displayErrorMsg($_POST['hidError']);
				}
			
				// new record
				else{
				
					if(SMC_countRecords($_POST['txtDate'], $_POST['txtUser'], false, false) > 0)
						SMC_updateRegisteredUserToMeeting($_POST['txtUser'], $_POST['txtDate'], $_POST['chkPresent'], $_POST['txtNbGuests'], $_POST['txtComments']);
					
					// update existing record
					else
						SMC_addRegisteredUserToMeeting($_POST['txtDate'], $_POST['txtUser'], $_POST['chkPresent'], $_POST['txtNbGuests'], $_POST['txtComments']);					
				}
			}
		}
		
		// load data into a record
		$row_data = SMC_loadUserData($SMC_date, $_POST['txtUser']);
		
?>

		<script src='<?php bloginfo('wpurl'); ?>/wp-content/plugins/wp-<?php echo $SMC_plugin_name ?>/<?php echo $SMC_plugin_name ?>.js' type = 'text/javascript'></script>		
		<link rel="stylesheet" type="text/css" href="<?php bloginfo('wpurl'); ?>/wp-content/plugins/wp-<?php echo $SMC_plugin_name ?>/<?php echo $SMC_plugin_name ?>.css" />
		
		<!-- Display meeting form -->
		<form name='frmSMCRegistration' method='POST' action=''>
			<table border='2' width="80%">
				<tbody>
					<tr>
						<th><?php echo __('Date (dd/mm/yyyy):', $SMC_plugin_name) ?></th>
						<td colspan=" <?php echo ($content != null)? 1 : 2 ?> "><?php echo $SMC_date ?></td>

						<input type="hidden" name="txtDate" value="<?php echo $SMC_date ?>">
						
						<?php if($content != null){
						
							$i = 3; // for default fields (date,  Present, submit button)
							if ($SMC_description != '') $i++;
							if ($SMC_location != '') $i++;
							if ($SMC_reqguests == 'true') $i++;
							if ($SMC_expireson != '') {
								if (date('d/m/Y') > date($SMC_expireson)){
									$i = 4; // already expired, display limited number of fields
								}
							}
							
							echo '<td align="center" rowspan="' . $i . '">' . $content . '</td>';
						}
						?>
						
					</tr>
					<?php 

						if ($SMC_description != ''){
							echo '<tr>';
								echo '<th>' . __('Description:', $SMC_plugin_name) . '</th>';
								echo '<td colspan="'. (($content != null)? 1 : 2) . '">' . $SMC_description . '</td>';
							echo '</tr>';
						}

						if ($SMC_location != ''){
							echo '<tr>';
								echo '<th>' . __('Location:', $SMC_plugin_name) . '</th>';
								echo '<td colspan="' . (($content != null)? 1 : 2) . '">' . $SMC_location . '</td>';
							echo '</tr>';
						}

					if (($SMC_expireson == '') || (date('d/m/Y') <= date($SMC_expireson))){
						
					?>
					
						<tr>
							<th><?php echo __('Name:', $SMC_plugin_name) ?></th>
							<td colspan="<?php echo ($content != null)? 1 : 2 ?>">					
					<?php 
							
							if ($SMC_usersonly == 'true'){
								echo SMC_getCurrentUser()->first_name . ' ' . SMC_getCurrentUser()->last_name;
								echo '<input type="hidden" name="txtUser" value="' . SMC_getCurrentUser()->first_name . ' ' . SMC_getCurrentUser()->last_name . '">';
							}
							else
								echo '<input type="text" name="txtUser" onBlur="this.value=trim(this.value)" value="' . SMC_getCurrentUser()->first_name . ' ' . SMC_getCurrentUser()->last_name . '">';
							
							?></td>
						</tr>
						<tr>
							<th><?php echo __('Present:', $SMC_plugin_name) ?></th>
							<td colspan="<?php echo ($content != null)? 1 : 2 ?> align="center"><input type='checkbox' name='chkPresent' <?php echo $row_data['answer'] ?>></td>
						</tr>
						<?php if ($SMC_reqguests == 'true'){
								echo '<tr>';
									echo '<th>' . __('Number of guests:', $SMC_plugin_name) . '</th>';
									echo '<td colspan="' . (($content != null)? 1 : 2) . '"><input type="textbox" name="txtNbGuests" value="' . $row_data['nbParticipants'] . '"></td>';
								echo '</tr>';
							}
						?>
						<tr>
							<th><?php echo __('Comments:', $SMC_plugin_name) ?></th>				
							<td colspan='2'><textarea name='txtComments' rows='10' cols='20'><?php echo $row_data['comments'] ?></textarea></td>
						</tr>
						<tr>
						<?php 
											
					}
					
					if($SMC_expireson != ''){
								echo '<th>' . __('Please reply before: ', $SMC_plugin_name) . '</th>';
								echo '<td>' . $SMC_expireson . '</td>';
					}
					else{
								echo '<th></th>';
					}
					
					if (($SMC_expireson == '') || (date('d/m/Y') <= date($SMC_expireson))){
					?>
					
						<td colspan="<?php echo ($SMC_expireson != '')? 1 : 2 ?>" align='right'><input type='submit' onClick='checkFields();' name='cmdSubmit' value='<?php echo __('Submit', $SMC_plugin_name) ?>'></td>					
						
					<?php
					}
					?>
					
					</tr>
				</tbody>
			</table>
			<input name='hidUpdate' type='hidden' value='true'>
			<input name='hidError' type='hidden' value=''>
		</form>			
			
<?php

		if ($SMC_displayresults == 'true') {			
			SMC_displayRecords($SMC_date);			
		}
		
	}
		
	/*
	
	NAME: SMC_initialize()
	
	DESCRIPTION: Load the localisation files 
	
	INPUTS:
				
	OUTPUTS:
		0: Default return
		
	PROCESS:
		[1] Load localisation file
		
	NOTES:

	*/
	
	function SMC_initialize() {
	
		global $SMC_plugin_name;
		global $SMC_thisDir;
		
		load_plugin_textdomain($SMC_plugin_name, false, $SMC_thisDir . '/languages');
		
		return 0;
	}

	/*
	
	NAME: add_action('init', 'initialize');
	
	DESCRIPTION: Call the initialize function on init hook
	
	INPUTS:
		[1] hook name
		[2] function name
				
	OUTPUTS:
				
	PROCESS:
				
	NOTES:

	*/

	add_action( 'init', 'SMC_initialize' );

		/*
	
	NAME: add_shortcode('SMC', 'main');
	
	DESCRIPTION: Enable the shortcode in the post
	
	INPUTS:
		[1] Shortcode name
		[2] function name
				
	OUTPUTS:
				
	PROCESS:
				
	NOTES:

	*/
		
	add_shortcode('SMC', 'SMC_main');

	/*
	
	NAME: register_activation_hook(__FILE__, 'activatePlugin');
	
	DESCRIPTION: Call the activatePlugin function when plugin is activated
	
	INPUTS:
		[1] location of the function
		[2] function name
				
	OUTPUTS:
				
	PROCESS:
				
	NOTES:

	*/
	
	register_activation_hook(__FILE__, 'SMC_activatePlugin');

	/*
	
	NAME: register_deactivation_hook(__FILE__, 'deactivatePlugin');
	
	DESCRIPTION: Call the deactivatePlugin function when plugin is deactivated
	
	INPUTS:
		[1] location of the function
		[2] function name
				
	OUTPUTS:
				
	PROCESS:
				
	NOTES:

	*/
	register_deactivation_hook(__FILE__, 'SMC_deactivatePlugin');

	
	
	function displayErrorMsg($errorMsg){
		
		global $SMC_plugin_name;
		$message;
	
		if($errorMsg == 'dateMissing')
			$message = __('Please add at least date="xx/xx/xxxx" as a parameter of the shortcode. <BR/> Ex.: [SMC date="13/12/2010"]!', $SMC_plugin_name);
		
		if($errorMsg == 'txtUser')
			$message = __('Name cannot be blank.', $SMC_plugin_name);

		if($errorMsg == 'txtNbGuests')
			$message = __('Number of guests has an invalid value.', $SMC_plugin_name);
	
		echo '<p class="error">';
			echo __('ERROR:', $SMC_plugin_name);
			echo $message;
		echo '</p>';
	
		return true;
	}
	
?>