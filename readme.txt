=== PostLinks ===
Contributors: Khanh Cao
Tags: fields, post, links, custom, admin, meta, data
Requires at least: 3.0
Tested up to: 3.0
Stable tag: 0.5

(Beta) An extension of Fields, a custom field management plugin. PostLinks provides additional field types such as Series, PhotoLink and PostLink.

== Description ==

(Beta) An extension of Fields, a custom field management plugin. PostLinks provides additional field types such as Series, PhotoLink and PostLink.

Three new field types:

* PostSeries: link your posts into series and display them with the [series] shortcode, also it comes with 2 widgets
* PhotoLink: to be added
* PostLink: to be added

== Installation ==

To install Fields:

1. Upload the 'postlinks' folder to the '/wp-content/plugins/' directory
2. Activate the plugin.

== Frequently Asked Questions ==

= Get custom field data =

* Please refer to &#60;a href='http://www.wordpress.org/extend/plugins/fields'&#62;Fields&#60;/a&#62; documentation.

= Display custom fields in posts =

use shortcode *series* with parameters:

* key =&#62; '' (required)
* before' =&#62; '&#60;ul class="ls-series"&#62'
* after' =&#62; '&#60;/ul&#62'
* before_item' =&#62; '&#60;li&#62'
* after_item' =&#62; '&#60;/li&#62'
* show' =&#62; 'all', possible values are "previous", "prev", "next", "n" and "all"
* before_link_text' =&#62; ''
* after_link_text' =&#62; ''
* part_separator' =&#62; ' - '

example: [series key='s1'], [series key='s1' show='next' 'after_link_text' =&#62; ' →']

== Screenshots ==


== Changelog ==

= 0.2 =
* Widgets for Series added

= 0.1 =
* Beta release