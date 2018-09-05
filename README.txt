=== Wordpress Google Map Plugin ===
Contributors: nafsinvk
Donate link: paypal.me/nafsin
Tags: map, google map, simple
Version: 2.2.1
Requires at least: 3.0.1
Tested up to: 4.9.7
Stable tag: 2.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple Google map plugin, works great without any expetise in programming

== Description ==

Simple google map plugin, which allows multiple locations on the same map, and multiple maps in single post/page.

1. Easy and simple settings
2. A new post type called map, which can be enabled/disabled fom the plugin settings
3. A new map item, which has title (for administration) lat, lng, marker image and bubble content
4. A map can directly be inserted into the editor
5. A sample map will look like this.
6. A custom taxonomy (non hirarchial), to group and display multiple marks on the same map.

https://youtu.be/rNQV8RmlEUA

Easy to setup

*   Install the plugin
*   Configure the API key and optional lat lng
*   Will create a post type map and a button on the editor, which enable direct insert in to post
*   Insert custom or prebuilt maps in to the content
*   Works with short tag too
stable.



== Installation ==


1. Upload plugin to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
== Frequently Asked Questions ==

How to install the plugin?
Easy, like any other plugin, upload and activate.

What are the features included?
 a. Insert a goole map directly on to the content
 b. Custom icons
 c. Map popover text
 d. Multiple maps in the same page
 
How will I enable a custom post type called "map"?
You could easily activate/deactivate it from the map plugin settings, using the provided checkbox.

Can I have multiple markers on the same map?
You can group the contents of type map, and then insert tag to display multiple loactions on the same map.

Why don't my category appear while insertion?
Only those categories with atleast one map will appear over there.

The widget will be activated, when you enable post type.
Widget will use all the location (under map type) to create a multi location map

== Screenshots ==

1. Easy and simple settings, where you can control the availablility of content type map
2. A new post type called map, which can be enabled/disabled fom the plugin settings
3. A new map item, which has title (for administration) lat, lng, marker image and bubble content
4. A map can directly be inserted into the editor
6. Either a category, where all the location will get ploted on a single map, or a pre-built single map, or a new custom map
5. A sample map will look like this.

== Changelog ==

= 2.2.1 =
* Fixed a bug that Shown widget before initialising the post type 
* Tested against 4.9.7

= 2.2.0 =
* Fixed a bug that prevent to load icon in single map
* Performance improvement and fixes
* Tested against 4.8
* Video Tutorial on Description.


= 2.1.1 =
* Look and fee improvement.
* Helptext for map insertion form.
* Prebuit, content not found amented.
* Category, content not fount amented.
* Fxed a bug that prevented different map on same page from having differnet width and height.
* Added min-width and min-height for map display.
* Default marker is now updated.

= 2.1.0 =
* New Custom taxonomy implemented
* Now Single map will display multiple markers
* Fixed a bug while selecting category
* Fixed a bug that prevented, to disappear control for map content type
* Fxed a bug that generated an unwanted text character in tha map output container

<?php code(); // goes in backticks ?>`