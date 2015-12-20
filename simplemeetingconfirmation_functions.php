<?php

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

function SMC_activatePlugin() {

	global $wpdb;
	global $SMC_plugin_table;
	$result = null;

	if ( $wpdb->get_var( "SHOW TABLES LIKE '$SMC_plugin_table'" ) != $SMC_plugin_table ) {
		$result = create_MeetingTable( $SMC_plugin_table );
	}

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

function SMC_deactivatePlugin() {
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

function SMC_loadShortCodeParameters( $atts ) {

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

function SMC_main( $atts, $content = null ) {

	global $meta;
	global $SMC_date;
	global $SMC_location;
	global $SMC_reqguests;
	global $SMC_description;
	global $SMC_displayresults;
	global $SMC_usersonly;
	global $SMC_expireson;
	global $SMC_plugin_name;

	$row_data;

	$output = "";

	// load custom variables
	SMC_loadShortCodeParameters( $atts );

	// check if SMC_Date field was filled, if so, display meeting information
	if ( $SMC_date == '' ) {
		displayErrorMsg('dateMissing');
		return false;
	}

	// check if page was posted back
	if ( @$_POST['hidUpdate'] == true ) {
		if ( $_POST['txtDate'] == $SMC_date ) {
			if ($_POST['hidError'] != ''){
				displayErrorMsg( $_POST['hidError'] );
			} else {
				if ( SMC_countRecords($_POST['txtDate'], $_POST['txtUser'], false, false ) > 0 ) {
					if ( wp_get_current_user()->display_name != "" ) {
						// update existing record if registered user
						SMC_updateRegisteredUserToMeeting( $_POST['txtUser'], $_POST['txtDate'], @$_POST['chkPresent'], @$_POST['txtNbGuests'], $_POST['txtComments'] );
					} else {
						// do not update if the name already exists for open event entry
					}
				} else {
					// new record
					SMC_addRegisteredUserToMeeting( $_POST['txtDate'], $_POST['txtUser'], @$_POST['chkPresent'], @$_POST['txtNbGuests'], $_POST['txtComments'] );
				}
			}
		}
	}

	// load data into a record
	$row_data = SMC_loadUserData($SMC_date, @$_POST['txtUser']);
	$output .= '<script src="' . plugin_dir_url( __FILE__ ) . 'simplemeetingconfirmation.js" type="text/javascript"></script>';
	$output .= '<link rel="stylesheet" type="text/css" href="' . plugin_dir_url( __FILE__) . 'simplemeetingconfirmation.css" />';
	if ($SMC_displayresults == 'true') {
		$output .= SMC_displayRecords( $SMC_date );
	}

	if ( $SMC_usersonly == "true" && wp_get_current_user()->display_name == "" ) {

		$output .= "<p>" . __( "If you are logged in you can sign up", $SMC_plugin_name ) . ".</p>";

	} else {

		$output .= '<form name="frmSMCRegistration" method="post" action="' . get_permalink() . '">
			<table border="2" width="80%">
				<tbody>';

		if ( $SMC_description != '' ) {
			$output .= '<tr>';
				$output .= '<th>' . __('Event', $SMC_plugin_name) . ':</th>';
				$output .= '<td colspan="'. (($content != null)? 1 : 2) . '">' . $SMC_description . '</td>';
			$output .= '</tr>';
		}

		$output .= '<tr>
			<th>' . __('Date (dd/mm/yyyy)', $SMC_plugin_name) . ':</th>
			<td colspan="' . (($content != null)? 1 : 2) . '">' .$SMC_date . '</td>
			<input type="hidden" name="txtDate" value="' . $SMC_date . '">';
		if ($content != null) {
			$i = 3; // for default fields (date,  Present, submit button)
			if ($SMC_description != '') $i++;
			if ($SMC_location != '') $i++;
			if ($SMC_reqguests == 'true') $i++;
			if ($SMC_expireson != '') {
				if (date('d/m/Y') > date($SMC_expireson)){
					$i = 4; // already expired, display limited number of fields
				}
			}
			$output .= '<td align="center" rowspan="' . $i . '">' . $content . '</td>';
		}
		$output .= '</tr>';

		if ( $SMC_location != '' ) {
			$output .= '<tr>';
			$output .= '<th>' . __('Location', $SMC_plugin_name) . ':</th>';
			$output .= '<td colspan="' . (($content != null)? 1 : 2) . '">' . $SMC_location . '</td>';
			$output .= '</tr>';
		}

		if (($SMC_expireson == '') || (date('d/m/Y') <= date($SMC_expireson))) {

			$output .= '<tr>
					<th>' . __('Name', $SMC_plugin_name) . ':</th>
					<td colspan="' . (($content != null)? 1 : 2) . '">';

			if ( $SMC_usersonly == 'true' ) {
				$output .= wp_get_current_user()->display_name;
				$output .= '<input type="hidden" name="txtUser" value="' . esc_attr( wp_get_current_user()->display_name ) . '">';
			} else {
				$public_username = @$_POST['txtUser'];
				if ($public_username == "") $public_username = wp_get_current_user()->display_name;
				$output .= '<input type="text" name="txtUser" onBlur="this.value=trim(this.value)" value="' . esc_attr( $public_username ) . '">';
			}

			$output .= '</td>
					</tr>
					<tr>
					<th>' . __('Present', $SMC_plugin_name) .':</th>
					<td colspan="' . (($content != null)? 1 : 2)  . ' align="center"><input type="checkbox" checked="checked" name="chkPresent" ' . @$row_data->answer . '></td>
					</tr>';
			if ($SMC_reqguests == 'true') {
					$output .= '<tr>';
					$output .= '<th>' . __('Number of people', $SMC_plugin_name) . ':</th>';
					$output .= '<td colspan="' . (($content != null)? 1 : 2) . '"><input type="textbox" name="txtNbGuests" value="' . @$row_data->nbParticipants . '"></td>';
					$output .= '</tr>';
			}

			$output .= '<tr>
					<th>' . __('Comments', $SMC_plugin_name) .':</th>
					<td colspan="2"><textarea name="txtComments" rows="5" cols="20">' . @$row_data->comments . '</textarea></td>
					</tr>
					<tr>';
		}

		if ( $SMC_expireson != '' ) {
				$output .= '<th>' . __('Please reply before', $SMC_plugin_name) . ':</th>';
				$output .= '<td>' . $SMC_expireson . '</td>';
		} else {
				$output .= '<th></th>';
		}

		if ( ( $SMC_expireson == '' ) || (date('d/m/Y') <= date( $SMC_expireson )) ) {
			$output .= '<td colspan="' . (($SMC_expireson != '')? 1 : 2) .'" align="right"><input type="submit" onClick="checkFields();" name="cmdSubmit" value="' . __('Submit', $SMC_plugin_name) . '" ></td>';
		}

		$output .= '</tr>
				</tbody>
				</table>
				<input name="hidUpdate" type="hidden" value="true">
				<input name="hidError" type="hidden" value="">
				</form>';
	}

    return $output;

}

/*

NAME: SMC_languages()

DESCRIPTION: Load the localisation files

INPUTS:

OUTPUTS:

PROCESS:
	[1] Load localisation file

NOTES:

*/

function SMC_languages() {

	global $SMC_plugin_name;
	load_plugin_textdomain( $SMC_plugin_name, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}


function displayErrorMsg( $errorMsg ) {

	global $SMC_plugin_name;
	$message;

	if($errorMsg == 'dateMissing')
		$message = __('Please add at least date="xx/xx/xxxx" as a parameter of the shortcode. <BR/> Ex.: [SMC date="13/12/2010"]!', $SMC_plugin_name);

	if($errorMsg == 'txtUser')
		$message = __('Name cannot be blank.', $SMC_plugin_name);

	if($errorMsg == 'txtNbGuests')
		$message = __('Number of people has an invalid value.', $SMC_plugin_name);

	echo '<p class="error">';
		echo __('ERROR: ', $SMC_plugin_name);
		echo $message;
	echo '</p>';

	return true;
}
