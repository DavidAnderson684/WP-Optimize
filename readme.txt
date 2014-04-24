=== WP-Optimize ===

Contributors: ruhanirabin

Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=KYFUKEK7NXAZ8

Tags: comments, spam, optimize, database, revisions, users, posts, trash, schedule, automatic, clean, phpmyadmin, meta, postmeta

Requires at least: 3.7

Tested up to: 3.9.1.alpha

Stable tag: 1.7.4



Simple but effective plugin allows you to extensively clean up your WordPress database and optimize it without doing manual queries.



== Description ==



WP-Optimize is an extensive WordPress database cleanup and optimization tool. It doesn't require PhpMyAdmin to clean and optimize your database tables. 


**MAJOR FEATURES:**



*   Removal of stale post revisions

*   Removal of stale unapproved and spam comments

*   Removal of trshed comments

*   Clear out the Post Trash

*   Automatic Cleanup of all the integrated options (Also uses retention if enabled)

*   Ability to keep X number of weeks data when cleaning up (Retention feature)

*   Removal of all trackbacks and pingbacks

*   Cleaning up Auto Draft posts

*   Removal of Transient options

*   Option to add or remove link on wp admin bar.

*   Enable/Disable weekly schedules of optimization

*   Apply native WordPress MySql optimize commands on your database tables without phpMyAdmin or any manual query.

*   Display Database table statistics. Shows how much space can be optimized and how much space has been cleared.

*   Visible only to the Administrators.


**Simple scheduler introduced (Still in very EXPERIMENTAL stage)**

**All the potentially dangerous clean up options are MARKED RED.**

**When you use this plugin for the first time or just updated to major version, make a backup of your database. This is a must for everyone**

**Are you interested to be a contributed developer of this plugin, use the contact form link below to contact me.**

You will be credited inside the plugin screen and the plugin listing at WordPress.


**Requirements:**



*   WordPress 3.7+

*   PHP 5.1.xx

*   MySql 5.1.xx

*   PHP 4.xx and MySql 4.xx not supported



**Translators are welcome to send their Translated Files to be included.**

You can **Email translations to plugins(at)ruhanirabin.com**


**WP-Optimize Translators:**



*   Language ru_RU: Русский язык - Виталий МакЛауд (Эликсир жизни) - http://www.visbiz.org/

*   Language zh_TW: 正體中文語系檔案由香腸炒魷魚(香腸)翻譯。 -  http://sofree.cc

*   Language zh_CN: 简体中文语言包 由 SoumaHoshino 提供 - http://moesora.com/

*   Language fr_FR: Stéphane Benoit. - http://www.gnosticisme.com

*   Language de_DE: Rewolve44 - http://www.myfotohome.at/

*   Language lv: Tulkotāji - http://trendfor.lv

*   Language pt_BR: Leonardo Kfoury - http://www.kfoury.com.br/site/

*   Language fa_IR: Morteza Amiri - http://www.GameSiders.com/

*   Language es_ES: Navone Juan - http://navonejuan.com.ar/

*   Language tr_TR: Hakan Er tarafından Türkçe Dili - http://hakanertr.wordpress.com/

*   Language id_ID: Nasrulhaq Muiz - http://al-badar.net/

*   Language sl_SI: Tomi Sambrailo - http://www.refuzed.it/

*   Language nb_NO: Simen Eggen - http://www.simeneggen.com

*   Language nl_NL: Mischa ter Smitten - http://blog.tersmitten.nl/

*   Language sk_SK: Patrik Žec (PATWIST) - http://patwist.com

*   Language lt_LT: Su pagarba Bronislav - http://www.internetiniusvetainiukurimas.com/

*   Language it_IT: Diego Belli

*   Language pl_PL: Kornel




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



= Can you help me restore my database? =



No I can't. Please make a backup of your entire database before using this Plugin for the first time. Also backup your database when you upgrade to a MAJOR NEW version (for example - v0.9.8 to 1.5.5 ).



= Everytime I clear transient options, it comes back automatically =



WordPress automatically creates transient options. It is pretty difficult to completley get rid of it. This is why everytime you clean transient options, you will notice new transient options has been created. My best advice would be clear the transient options on a schedule. So, for example it may create 50 transient option in a week and you clear it weekly so the next 50 transient option comes in. Rather than having 100 transient option, you will have 50 per week.



= Optimization does not have any effect on database / it is not optimizing the database =



Some of the shared web hosting company does not allow scripts to run OPTIMIZE command via SQL statements. If you are hosted with these web hosts, the optimize action will not be able to optimize your database. Please consult your web hosting company regarding this matter.



= I am having error - Warning: mysql_num_rows(): supplied argument is not a valid MySQL result resource ...  =



*   Upgrade/Update your WordPress to at least 3.8

*   Upgrade/Update your WP-Optimize plugin

*   Upgrade your PHP to at least 5.5.xx

*   Upgrade your MySql to at least MySql 5.1.xx

*   Remember: PHP 4.xx and MySql 4.xx not supported



= Is there any bug in this plugin =



This is a very primary version of the plugin. So I would recommend you to test it out on your local system or make a backup of your database (just to be extra careful).




== Screenshots ==



1. Optimizer

2. Settings Screen

3. Table Report



== Changelog ==


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

