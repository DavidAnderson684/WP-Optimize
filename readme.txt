=== WP-Optimize ===

Contributors: ruhanirabin

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KYFUKEK7NXAZ8

Tags: comments, spam, optimize, database, revisions, users, posts, trash, schedule, automatic, clean, phpmyadmin

Requires at least: 3.5

Tested up to: 3.7.1

Stable tag: 1.1.2



This simple but effective plugin allows you to clean up your WordPress database and optimize it without phpMyAdmin.



== Description ==



WP-Optimize is a WordPress 3.xx database cleanup and optimization tool. It doesn't require PhpMyAdmin to optimize your database tables. 

It allows you to remove post revisions, comments in the spam queue, un-approved comments, items in trash within few clicks. 

**Weekly scheduler introduced (EXPERIMENTAL)**

Now Multi-Lingual. 


**Are you interested to be a contributed developer of this plugin, use the contact form link below to contact me.**

You will be credited inside the plugin screen and the plugin listing at WordPress.


**Requirements:**



*   WordPress 3++

*   PHP 5.1.xx

*   MySql 5.1.xx

*   PHP 4.xx and MySql 4.xx not supported



**Translators are welcome to send their Translated Files to be included.**

See options inside plugin or contact me via http://www.ruhanirabin.com/contact/



**WP-Optimize Translators:**



*   Русский язык - Виталий МакЛауд (Эликсир жизни) - http://www.visbiz.org/

*   正體中文語系檔案由香腸炒魷魚(香腸)翻譯。 - http://sofree.cc



**Features:**



*   [NEW] Retain/Keep last X number of weeks data - this option keeps the last selected number of weeks data when cleaning up.

*   [NEW] Remove all trackbacks and pingbacks (can significantly reduce db size)

*   [NEW] Clear Trash Comments and Posts (This will also follow the "Keep X number of weeks data" option if it was selected)

*   [NEW] Enable/Disable weekly schedules of optimization  (This will also follow the "Keep X number of weeks data" option if it was selected) Note: This is an EXPERIMENTAL feature. It may or may not work on all servers.

*   [NEW] Remove the WordPress post revisions (This will also follow the "Keep X number of weeks data" option if it was selected)

*   [NEW] Remove the WordPress auto draft posts (This will also follow the "Keep X number of weeks data" option if it was selected)

*   [NEW] Remove all the comments in the spam queue (This will also follow the "Keep X number of weeks data" option if it was selected)

*   [NEW] Remove all the un-approved comments (This will also follow the "Keep X number of weeks data" option if it was selected)

*   Apply MySql optimize commands on your database tables without phpMyAdmin.

*   Display Database table statistics. Shows how much space can be optimized and how much space has been cleared.

*   Visible only to the administrators.




**How this could help you?**



*   Every-time you save a new post or pages, WordPress creates a revision of that post or page. If you edit a post 6 times you might have 5 copy of that post as revisions. Imagine if your post or pages are long and big. It is a huge number of bytes that's on your MySQL overhead. Now WP-Optimize allows you to optimize and shrink your posts table by removing not necessary post revisions from the database. As example, if you have a post which is approximately 100KB data and you have 5 revisions of that post, the total space wasted is about 500KB. And if you have 100 posts similar to it, you have 50MB database space wasted.

*   Similar to the scenario described above, there might be thousands of spam and un-approved comments in your comments table, WP-Optimize can clean and remove those in a single click

*   WP-Optimize reports which database tables have overhead and wasted spaces also it allows you to shrink and get rid of those wasted spaces

*   Automatically cleans database every week and respects the "Keep X number of weeks data" option. 




== Installation ==



*   Unzip the archive into your hard drive. 

*   Upload the folder to your wp-content/plugins folder on your web host.

*   Login to your dashboard and activate the plugin via activate option.

*   The main level menu item is WP-Optimize. Scroll down to see it.



== Frequently Asked Questions ==



= Optimization does not have any effect on database / it is not optimizing the database =



Some of the shared web hosting company does not allow scripts to run OPTIMIZE command via SQL statements. If you are hosted with these web hosts, the optimize action will not be able to optimize your database. Please consult your web hosting company regarding this matter.



= I am having error - Warning: mysql_num_rows(): supplied argument is not a valid MySQL result resource ...  =



*   Upgrade/Update your WordPress to at least 3.5

*   Upgrade/Update your WP-Optimize plugin

*   Upgrade your PHP to at least 5.1.xx

*   Upgrade your MySql to at least MySql 5.1.xx

*   Remember: PHP 4.xx and MySql 4.xx not supported



= Is there any bug in this plugin =



This is a very primary version of the plugin. So I would recommend you to test it out on your local system or make a backup of your database (just to be extra careful).




== Screenshots ==



1. Main screen

2. Database tables report

3. Menu Item



== Changelog ==

= 1.1.2 =

* removed persistent admin bar menu item

* Language ru_RU and zh_TW updated.



= 1.1.1 =

* Fix Fatal Error.


= 1.1.0 =

* Added WP-Optimize to admin menu bar on top. Always accessible.

* Added wp-optimize.pot file for translators (inside ./languages/ folder).

* Last auto optimization timestamp / display

* Fix possible scheduler bug as requested at support forum

* Fix some other codes regarding SQL query parameters

* Ability to keep last X weeks of data, any junk data before that period will be deleted - this option affects both Auto and Manual process. Appreciate time and help from Mikel King (http://mikelking.com/) about this matter.


= 1.0.1 =

* Removed auto cleanup of trackbacks or pingbacks.. it's better for people to do it manually.


= 0.9.8-beta =

* added beta tag


= 0.9.8 =

* Remove all trackbacks and pingbacks (can significantly reduce db size)

* Remove all Trash Comments and Posts

* Enable/Disable weekly schedules of optimization. This is an EXPERIMENTAL feature. It may or may not work on all servers.


= 0.9.4 =

* Non Initialized variables fixes as of http://wordpress.org/support/topic/plugin-wp-optimize-errors-in-debug-mode?replies=2


= 0.9.3 =

* Removed security tools.
* Full database size displayed


= 0.9.2 =

* Now the plugin is visible to site administrators only. Authors, Contributors, Editors won't be able to see it.



= 0.9.1 =

* Fixed problem with database names containing "-" .

* NEW Main Level Menu Item added for WP-Optimize, You might need to scroll down to see it

* Compatibilty with WordPress 3.1

* Added few translations

* Added auto draft post removal feature



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

