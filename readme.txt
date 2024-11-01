=== Plugin Name ===
Contributors: Akisbis
Donate link: http://wso.li/donation
Tags: countdown, timer, javascript, simple, quicktag, sct, widget, shortcode
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: 0.2.3

Simple Countdown Timer allows you to create multiple countdown on your blog with a widget, or using a shortcode into your post and pages.

== Description ==

Simple Countdown Timer (SCT) is, as you might expect, really simple to use. It gives you tools to create a Countdown as a widget or into your posts with a shortcode ([sct date="..."]).

SCT supports 2 or 3 digits for days, so you can really create a long long countdown if you want. Simple Countdown Timer has also a quicktag, so you can really add a new countdown into your post in one click!

To have the translated texts "days, hours, minutes and seconds" on your blog. Feel free to edit the first lines of the `simple-countdown-timer.php` and replace the text by yours. For French text for example, it will be :

`$sct_text_date = array(
		'days'		=>	'jours',
		'hours'		=>	'heures',
		'minutes'	=>	'min',
		'seconds'	=>	'sec'
);`

= Key features =
* Simple to use.
* No flash, only javascript!
* A quicktag to quickly add a new countdown into your posts.
* A shortcode [sct date="" align="" size=""]

Feel free to use the plugin and let me know if there are errors or if you have some ideas to improve it. You can contact me on <a href="http://twitter.com/tommy">Twitter @Tommy</a>, <a href="http://wordpress.org/tags/simple-countdown-timer?forum_id=10">the forum</a>, or by <a href="http://scr.im/wordsocial">email</a>.

== Installation ==

1. Extract the zip file and just drop the contents in the <code>wp-content/plugins/</code> directory of your WordPress installation (or install it directly from your dashboard) and then activate the Plugin from Plugins page.
1. Go to widget page and create the countdown you want, or use the shortcode to create a countdown into your post.
1. This is it.. Simple, isn't it ?

== Frequently Asked Questions ==

Nothing.

== Screenshots ==

1. An example of countdown with 2 digits days
1. An example of countdown with 3 digits days
1. An example of an ended countdown
1. The widget configuration
1. The widget configuration with calendar panel
1. The Quicktag and shortcode
1. The Quicktag in visual mode
1. Different size of the countdown (1 to 4)

== Changelog ==

= 0.1 =
- Initial version

= 0.2 =
- added an alignment
- added a size
- added a quicktag in "visual mode"
- fixed a bug in the javascript when the countdown changed the day

= 0.2.1 =
- fixed a bug with the size in the widget tool

= 0.2.2 =
- fixed width of the countdown

= 0.2.3 =
- fixed seconds in the countdown (59 instead of 99)

== Upgrade Notice ==

Nothing.