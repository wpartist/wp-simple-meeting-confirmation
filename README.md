# WordPress Simple Meeting Confirmation

Allows registered and/or non registered users to confirm if they will be
present at a planned meeting and optionally add comments and number of
participants with them.

This is a modified version of Frederic Vuong's original implementation. It is
based on version 1.8.3 that can be found here:

https://wordpress.org/plugins/wp-simplemeetingconfirmation/

## Usage

Create a new post and add the following shortcode:

[SMC date="dateParameter"]

You can optionally add the following parameters:

[SMC date="dateParameter" expireson="dateParameter"  location="address" reqguests="true" description="a meeting description" displayresults="true" usersonly="true"]

- date: A date or any string that will be used as a unique identifier of the
  meeting [mandatory field]
- location: A location [optional field]
- reqguests: if set to "true", will show the fields to add a number of guests
  invited by the registered user [optional field]. False by default
- description: if added, will add a short description to the meeting [optional
  field]
- displayresults: if set to "true", will show the results in a table format
  [optional field]. True by default
- usersonly: if set to true, will prevent non registered users to add their
  name to the event. True by default
- expireson: if set, will prevent to add/update records on the defined date.

Also optionally, add some additional content with the SMC tags that will
appear in the form table.

Ex.: [SMC date="17/10/2010" location="Our Place" description="Housewarming party"]<img src="http://mysite.com/ourplace.jpg"[/SMC]

## Possible improvements

- modify alter_table function
- administration page + administration menu
	- drop table
	- delete records
- pop up page to create shortcode parameters
- create a parameter notification to send an email to someone when response is sent
