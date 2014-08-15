=== WP-Optimize ===
Contributors: ruhanirabin
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LTCMF6JDX94QS
Tags: comments, spam, optimize, database, revisions, users, posts, trash, schedule, automatic, clean, phpmyadmin, meta, postmeta, responsive, mobile
Requires at least: 3.8
Tested up to: 4.0-beta3
Stable tag: 1.8.9
License: GPLv2+
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple but effective plugin allows you to extensively clean up your WordPress database and optimize it without doing manual queries.

== Description ==

WP-Optimize is an extensive WordPress database cleanup and optimization tool. It doesn't require PhpMyAdmin to clean and optimize your database tables.

Please show your support for this plugin by giving it [a rating](http://wordpress.org/support/view/plugin-reviews/wp-optimize?rate=5#postform) :)

Now hosted at [GitHub](https://github.com/ruhanirabin/WP-Optimize). I do not monitor wp forums, so use plugins+support(at)ruhanirabin.com for support questions. 

Please join GitHub and collaborate.

**MAJOR FEATURES:**

*   Removal of stale post revisions
*   Removal of stale unapproved and spam comments
*   Removal of trashed comments
*   Removal of akismet metadata from comments
*   Removal of other stale metadata from comments
*   Mobile device friendly, now you can optimize your site on the go
*   Removal of all trackbacks and pingbacks
*   Cleaning up auto draft posts
*   Removal of transient options
*   Clear out the post trash
*   Automatic cleanup of all the integrated options (also uses retention if enabled)
*   Ability to keep selected number of weeks data when cleaning up
*   Option to add or remove link on wp admin bar.
*   Enable/Disable weekly schedules of optimization
*   Apply native WordPress MySql optimize commands on your database tables without phpMyAdmin or any manual query.
*   Display Database table statistics. Shows how much space can be optimized and how much space has been cleared.
*   Enabled for Administrators only.

**All the potentially dangerous clean up options are marked RED.**

**When you use this plugin for the first time or just updated to major version, make a backup of your database. It is always the best practice to make a database backup before using this program first time.**

**How this could help you?**

*   Every-time you save a new post or pages, WordPress creates a revision of that post or page. If you edit a post 6 times you might have 5 copy of that post as revisions. Imagine if your post or pages are long and big. It is a huge number of bytes that's on your MySQL overhead. Now WP-Optimize allows you to optimize and shrink your posts table by removing not necessary post revisions from the database. As example, if you have a post which is approximately 100KB data and you have 5 revisions of that post, the total space wasted is about 500KB. And if you have 100 posts similar to it, you have 50MB database space wasted.
*   Similar to the scenario described above, there might be thousands of spam and un-approved comments in your comments table, WP-Optimize can clean and remove those in a single click
*   WP-Optimize reports which database tables have overhead and wasted spaces also it allows you to shrink and get rid of those wasted spaces
*   Automatically cleans database every week and respects the "Keeps selected number of weeks data" option. 

**Are you interested to be a contributed developer of this plugin, join with me at GitHub. Or email plugins(at)ruhanirabin.com**

You will be credited inside the plugin screen and the plugin listing at WordPress.

**Requirements:**

*   WordPress 3.8+
*   PHP 5.1.xx
*   MySql 5.1.xx


**Translators are welcome to send their Translated Files to be included.**
Existing translators should join [Translation Utility](http://wp-managed.com/projects/wp-optimize) to submit their translations. 

You must have an account in order to edit translation - [Get the free account here](http://wp-managed.com/wp-login.php?action=register). 

Once you get your account password in your email [Log in here to edit language](http://wp-managed.com/login?redirect_to=http%3A%2F%2Fwp-managed.com%2Fprojects%2Fwp-optimize)

**WP-Optimize Translators:**

*   Language de_DE: Rene Wolf - http://www.fluchtsportler.de
*   Language fr_FR: Stéphane Benoit. - http://www.gnosticisme.com
*   Language lv   : Juris Orlovs - http://trendfor.lv
*   Language tr_TR: Hakan Er tarafından Türkçe Dili - http://hakanertr.wordpress.com/
*   Language sl_SI: Tomi Sambrailo - http://www.refuzed.it/
*   Language ka_GE: Givi Tabatadze - http://tagiweb.com
*   Language zh_CN: Maie - http://maie.name
*   Language es_ES: Navone Juan -  http://navonej.com.ar/

** Some languages are removed from the above list, because translators did not update the languages for current version **
[See a list of currently editable available languages at](http://wp-managed.com/projects/wp-optimize)

== Installation ==

There are 3 different ways to install WP-Optimize.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'WP-Optimize'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `WP-Optimize.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `WP-Optimize.zip`
2. Extract the `WP-Optimize` directory to your computer
3. Upload the `WP-Optimize` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Frequently Asked Questions ==

= Does WP-Optimize Supports InnoDB Format =
InnoDB Table formats not supported. WP-Optimize will disable some features if it detects InnoDB table format. Optimization of the database will not work but other clean up features would work.

= Can you help me restore my database? =
No I can't. Please make a backup of your entire database before using this Plugin for the first time. Also backup your database when you upgrade to a MAJOR NEW version (for example - v0.9.8 to 1.5.5 ).

= Is there any bug in this plugin =
The plugin is a development on the go - that means there are unforeseen situations and bugs. So I would recommend you to test it out on your local system or make a backup of your database (just to be extra careful).

= Every time I clear transient options, it comes back automatically =
WordPress automatically creates transient options. It is pretty difficult to completely get rid of it. This is why everytime you clean transient options, you will notice new transient options has been created. My best advice would be clear the transient options on a schedule. So, for example it may create 50 transient option in a week and you clear it weekly so the next 50 transient option comes in. Rather than having 100 transient option, you will have 50 per week.

= Optimization does not have any effect on database / it is not optimizing the database =
Some of the shared web hosting company does not allow scripts to run OPTIMIZE command via SQL statements. If you are hosted with these web hosts, the optimize action will not be able to optimize your database. Please consult your web hosting company regarding this matter.

= I am having error - Warning: mysql_num_rows(): supplied argument is not a valid MySQL result resource ...  =
*   Upgrade/Update your WordPress to at least 3.8
*   Upgrade/Update your WP-Optimize plugin
*   Upgrade your PHP to at least 5.5.xx
*   Upgrade your MySql to at least MySql 5.1.xx

= Table size shows wrong / Not optimizing  =
Please check your database for corrupted tables. That can happen, usually your web hosting company can run the repair command on the db.

== Screenshots ==

1. Optimizer Screen
2. Settings Screen
3. Table Report
4. Mobile View Top (Actual screen from Galaxy Note 3)
5. Mobile View Bottom (Actual screen from Galaxy Note 3)

== Changelog ==

= 1.8.9 =
* ONE MILLION+ Downloads. THANK YOU!!
* Language updates platform - see readme file for details. 
* Mixed type tables optimization supported and in BETA 
* Removal of akismet metadata from comments
* Removal of other stale metadata from comments
* InnoDB tables won't be optimized. 
* Main screen user selection will be saved. Red items selection will not be saved
* Scheduled time display will be shown according to WordPress blog local time

= 1.8.6 =
* Language updates
* Fix issues with total gain number problem 
* InnoDB tables detected and features disabled automatically, tables view will not show Overhead. Main view will not show space saved, or total gain. 

= 1.8.5 =
* Version bump + modified translator names

= 1.8.4 =
* Problem with readme file changes

= 1.8.3 =
* Minor fixes

= 1.8.1 =
* A whole lot more code optimization
* Slick new interface
* Responsive mobile interface, supports running from iPhone/Android/Tablets
* Tables moved to independent tab
* Optimize faster
* GitHub updater support
* All translations updates will come in soon
* I do not monitor WP forums, support email at plugins+support(at)ruhanirabin.com

= 1.7.4 =
* More Translation compatibility.
* Added MYSQL and PHP versions beside the Optimizer tab.

= 1.7.3 =
* Fixed Problems with wpMail.
* Fixed Problems with wpAdmin menubar.
* Fixed Permission issues on some site.
* wp-optimize.pot file is added to language directory, for the translators. That file is also linked at the info tab of the plugin. 
* Russian and German translation updated.

= 1.7.2 =
* All MySQL statements re-factored into native WP database calls - necessary for future versions of MySQL and WordPress.
* Upgrade to match WordPress 3.9 changes.
* Additional 2 languages.
* Now postmeta cleanup is disabled from code - it will be updated soon with native WordPress postmeta cleaning options. 

= 1.6.2 =
* 3 Translation update.

= 1.6.1 =
* Fixed - trashed Comments was not clearing out.
* 1 Translation update.

= 1.5.7 =
* 2 new Translations updates and 2 new languages added.

= 1.5.6 =
* "Unused Tags cleanup" option made a problem on some WordPress sites that it deletes empty categories. Since I am unable to replicate this problem. I am making this option disabled.
* Translations updates and 3 new languages added.
* Minor maintenance and fixes.

= 1.5.5 =
* Safe clean up options are selected by default, defaults are not by user preference for now (Optimizer Page).
* All the potentially dangerous clean up options are MARKED RED.
* Translations update for language - lv, de_DE, zh_TW, pt_BR, fa_IR, es_ES.
* New features explained - http://j.mp/HBIoVT (read the blog post).

= 1.5.4 =
* More path related fixes for various warnings. Maintenance

= 1.5.2 =
* Fatar error fix, if it disabled your wp admin, please remove the wp-optimize directory and reinstall again.

= 1.5.1 =
* Option to add or remove link on wp admin bar (even enabled - it is visible to admin only).
* New admin interface.
* Settings to select items for automatic optimization.
* Removal of WordPress transient options.
* Removal of orphaned post meta tags.
* Removal of unused tags.
* 3 different schedule times added (weekly, bi-weekly and monthly).
* 3 language added - ru_RU, zh_CN, fr_FR (zh_TW and nl_NL coming soon).
* Code optimization and translation strings updated.
* Updated .PO file for translators.
* Integrated development log from TRAC

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