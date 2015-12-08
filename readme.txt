=== Plugin Name ===

Contributors:      Frederic VUONG
Plugin Name:       WP-SimpleMeetingConfirmation
Plugin URI:        http://wordpress.org/extend/plugins/wp-simplemeetingconfirmation/
Tags:              Simple, Meeting, Meetings, Event, Events, Confirmation, Registered, Doodle, Management
Author URI:        http://vuong.fr/myitblog/about/
Author:            Frederic VUONG
Donate link:       
Requires at least: 3.0 
Tested up to:      3.0.1
Stable tag:        1.8.3
Version:           1.8.3

== Description ==

Allows registered and/or non registered users to confirm if they will be present at a planned meeting and optionally add comments and number of participants with them.

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
* Add a new parameter [expireson] to add expiration date to reply to an event. Will make the event non editable after expiration date.

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
* Add a new parameter [usersonly] to the shortcode to enable registration of non registered users.

= 2010/08/20 - v1.0 =
* Initial release

== Frequently Asked Questions ==

= How to use the plugin? =
Create a new post, and add the shortcode [SMC date="xx/xx/xxxx"]

= What are the mandatory parameters? =
Only [date] is a mandatory parameter to the shortcode.

= What are the various parameters available? =
date="dd/mm/yyyy", location="a location", reqguests="true", description="a meeting description", displayresults="true", usersonly="true", expireson="dd/mm/yyyy"

= Can I add a picture or a map to the meeting? =
Just include the HTML code between the [SMC date="dd/mm/yyyy"]<img src="mysite.com/mypic.jpg">[/SMC]

= Can I open the registration to non WP site registered users? =
Yes, just add the parameter usersonly="false" to the shortcode. Ex.: [SMC date="dd/mm/yyyy" usersonly="false"]

= Can I add an expiration date to the responses = 
Yes, just add the parameter expireson="dd/mm/yyyy" to the shortcode. Ex.: [SMC date="dd/mm/yyyy" expireson="dd/mm/yyyy"]

= Can I send you my translations for your plugin? =
Please do :o) I will integrate it to the next release.

= Which languages are available for this plugin? =
Currently, there are three languages:
* English (thanks to me)
* French (thanks to me)
* German (thanks to Arne)


== Donations ==
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input value="_s-xclick" name="cmd" type="hidden"/> <input value="-----BEGIN PKCS7-----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-----END PKCS7----- " name="encrypted" type="hidden"/> <input border="0" alt="" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" name="submit" type="image"/> <img border="0" alt="" width="1" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" height="1"/> </form>
