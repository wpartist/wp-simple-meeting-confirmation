=== Plugin Name ===

Contributors:      Frederic VUONG
Plugin Name:       WP-SimpleMeetingConfirmation
Plugin URI:        http://wordpress.org/extend/plugins/wp-simplemeetingconfirmation/
Tags:              Simple, Meeting, Meetings, Event, Events, Confirmation, Registered, Doodle, Management
Author URI:        http://vuong.fr/myitblog/about/
Author:            Frederic VUONG
Donate link:       
Requires at least: 3.0 
Tested up to:      4.4
Stable tag:        1.8.3
Version:           1.8.3

== Description ==

Allows registered and/or non registered users to confirm if they will be
present at a planned meeting and optionally add comments and number of
participants with them.

== Installation ==

1. Download 'WP-SimpleMeetingConfirmation.zip'.
2. Unzip it.
3. Upload the folder 'WP-SimpleMeetingConfirmation' directory to the /wp-content/plugins/ directory.
4. Activate the plugin through the 'Plugins' menu in WordPress.
5. Add the shortcode [SMC date="dateParameter" location="a location" reqguests="true" description="a meeting description" displayresults="true" usersonly="true" expireson="dateParameter"] in your post content.
6. Optionnaly add some content between the [SMC] tags. Ex.: [SMC date="17/10/2010" location="Our Place" description="Housewarming party"]<img src="http://mysite.com/ourplace.jpg"[/SMC]

== Upgrade Notice ==

== Screenshots ==
1. Shortcode example

2. Table in post

== Changelog ==

= 2010/09/21 =
* Small bug fixed in save function
* Added error messages

= 2010/09/20 =
* Trim name before posting

= 2010/09/17 =
* Huge bug fixed that was updating the previous meetings *
* Added an html page to create the shortcode *

= 2010/09/15 v1.6.2 =
* German translation provide by Arne Schuch.

= 2010/09/07 - v1.6 =
* Add a new parameter [expireson] to add expiration date to reply to an event.
  Will make the event non editable after expiration date.

= 2010/08/30 - v1.5.4 =
* Fixed a bug on translation
* Fixed issue with case sensitive file names
* Fixed bug when parameter usersonly is set to true

= 2010/08/25 - v1.4 =
* Fixed a bug on location
* Renamed global variables and functions to avoid naming collision
* Fixed a bug on plugin activation (database verification)

= 2010/08/24 - v1.3 =
* Add a new function to alter table to add, modify, delete columns

= 2010/08/23 - v1.2 = 
* Add a new function to avoid creating again the table if already exist

= 2010/08/23 - v1.1 =
* Add a new parameter [usersonly] to the shortcode to enable registration of
  non registered users.

= 2010/08/20 - v1.0 =
* Initial release

== Frequently Asked Questions ==

= How to use the plugin? =
Create a new post, and add the shortcode [SMC date="xx/xx/xxxx"]

= What are the mandatory parameters? =
Only [date] is a mandatory parameter to the shortcode.

= What are the various parameters available? =
date="dd/mm/yyyy", location="a location", reqguests="true", description="a
meeting description", displayresults="true", usersonly="true",
expireson="dd/mm/yyyy"

= Can I add a picture or a map to the meeting? =
Just include the HTML code between the [SMC date="dd/mm/yyyy"]<img
src="mysite.com/mypic.jpg">[/SMC]

= Can I open the registration to non WP site registered users? =
Yes, just add the parameter usersonly="false" to the shortcode. Ex.: [SMC
date="dd/mm/yyyy" usersonly="false"]

= Can I add an expiration date to the responses = 
Yes, just add the parameter expireson="dd/mm/yyyy" to the shortcode. Ex.: [SMC
date="dd/mm/yyyy" expireson="dd/mm/yyyy"]

= Can I send you my translations for your plugin? =
Please do :o) I will integrate it to the next release.

= Which languages are available for this plugin? =
Currently, there are three languages:
* English (thanks to me)
* German
* French
* Dutch
