=== WP-Optimize ===
Contributors: ruhanirabin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2732133
Tags: comments, spam, optimize, database, revisions, users, security, posts
Requires at least: 2.7
Tested up to: 2.9.1
Stable tag: 0.8.0

This plugin allows you to clean up your wordpress database and optimize it without phpMyAdmin. Also renames any username. 

== Description ==

WP-Optimize is a wordpress 2.7++ database cleanup and optimization tool. It doesn't require PhpMyAdmin to optimize your database tables. 
It allows you to remove post revisions, comments in the spam queue, un-approved comments within few clicks. 

Additionally you can rename any username to another username too.

Now Multi-Lingual. 

**Translators are welcome to send their Translated Files to be included.**
Contact me at http://wwww.ruhanirabin.com/contact/


**Features:**

*   Remove the wordpress post revisions
*   Remove all the comments in the spam queue
*   Remove all the un-approved comments
*   Rename one username to another username, it's designed to rename default "Admin" user to something else
*   Apply mysql optimize commands on your database tables without phpMyAdmin.
*   Display Database table statistics. Shows how much space can be optimzied and how much space has been cleared.


**How this could help you?**

*   Everytime you save a new post or pages, wordpress creates a revision of that post or page. If you edit a post 6 times you might have 5 copy of that post as revisions. Imagine if your post or pages are long and big. It is a huge number of bytes thats on your MySQL overhead. Now WP-Optimize allows you to optimize and shrink your posts table by removing not necessary post revisions from the database. As example, if you have a post which is approximately 100KB data and you have 5 revisions of that post, the total space wasted is about 500KB. And if you have 100 posts similar to it, you have 50MB database space wasted.
*   Similar to the scenario described above, there might be thousands of spams and un-approved comments in your comments table, WP-Optimize can clean and remove those in a single click
*   WP-Optimize reports which database tables have overhead and wasted spaces also it allows you to shrink and get rid of those wasted spaces
*   Wordpress doesn't allow you to rename existing username which could be a security issue because your default wordpress admin username is "admin", if you read my [Wordpress Security guide article](http://www.ruhanirabin.com/14-tips-prevent-wordpress-get-hacked/), you will know the risks involved. WP-Optimize can rename any existing username to other name


== Installation ==

*   Unzip the archive into your hard drive. 
*   Upload the folder to your wp-content/plugins folder on your web host.
*   Login to your dashboard and activate the plugin via activate option.
*   The menu item is under Dashboard>WP-Optimize.

== Frequently Asked Questions ==

= Is there any bug in this plugin =

This is a very primary version of the plugin. So I would recommend you to test it out on your local system.

= Wordpress logged me out when I rename my username =

Because you've changed your username, wordpress can't get authentication data from the database. Use your new username and the password you've used before

== Screenshots ==

1. Main screen
2. Database tables report

== Changelog ==

= 0.8.0 =
* Added Multilanguage capability
* Added translation WP-OPTIMIZE.POT file 
* Farsi Translation included now (Thanks to Ali irani)

= 0.7.1 =
* POST META Table cleanup code removed cause it is making problems with many hosts

= 0.7 =
* Added cleanup of POST META Table along with the revisions
* Fixed some minor PHP tags which causes the total numbers to disappear
* Now requires MySQL 5.1.x and PHP 5.1.x

= 0.6.5.1 =
* Fix Interface
