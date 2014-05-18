---
layout: post
title: "What's new in release v.1.8.9"
modified: 2014-05-12 07:06:06 +0800
tags: [updates, releasenotes]
image:
  feature: 
  credit: 
  creditlink: 
comments: 
share: true
---
There are a lot of stuff that has been re-coded in this release. 

### Most notable changes are:

1. Persistent settings for main screen: Allows user to save the checkbox selection on the main screen so next time they do not need to select the items they use frequently. Red marked items could not be saved. ![Image]({{ site.url }}/images/2014-05-12-1.png)

2. Credits page has been re-organized with proper information and links.

3. Optimize is at per table basis now, skips optimization if it is innoDB tables

4. Time settings according to the blog local time, so schedules and time display will show time properly. This would only work if the blog time has been set up properly

5. I have enabled mixed type tables optimization. So basically what will it do? It will enable you to run optimization if you have mixed of innoDB and MyISAM tables. But, it will skip the optimization commands on innoDB tables

6. New table type column - this gives you an overlook of what types of tables you have on your database. 

7. InnoDB table types are set to 0 bytes because most of the time they report wrong overhead size. 