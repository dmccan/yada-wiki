=== Yada Wiki ===
Contributors: dmccan
Tags: wiki, shortcode, internal links, page links, faq, knowledge base
Requires at least: 4.1
Tested up to: 4.3
Stable tag: 2.6.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Yada Wiki is a simple wiki for your WordPress site.

== Description ==
Yada Wiki provides a wiki post type, custom tags and categories, and a table of contents option.  The plugin allows you to link your wiki pages together using the wiki page titles.  

When viewing wiki pages, if the wiki page exists it shows as a link.  If it does not exist, and the user has permissions to edit posts, then the link shows up in red to indicate that the page needs to be created.  You can click on the link to go to the page editor to create the new page.  If the user does not have permissions, then they see the title in red, but no link is available.

There are two easy to use shortcodes, available via buttons on the editor toolbar.  

**Links Shortcode:**
When you click the first button, the wiki link button, a pop-up opens where you enter the two parameters:  

* The "link" parameter takes the title of the wiki page you are linking to.  The "link" parameter is required.
* The "show" parameter takes the link description that you want to show.  The "show" parameter is optional.  If it is blank then the value of the "link" parameter is shown.

For convenience, the pop-up does an AJAX lookup of wiki page titles when you are typing in the "link" field.  

When you click "OK", a shortcode is inserted into your edit window at the cursor location.  

**TOC Shortcode:**
With Yada Wiki, you can create a special wiki post with the title of "TOC", for table of contents, and create a wiki post that will serve as the table of contents for your wiki.  You can use wiki links and style the table of contents as you like in the post editor.  The second button opens a pop-up for the TOC shortcode.  

The TOC shorcode takes two optional parameters:  "Category" and "Order".  If no optional parameter is chosen then the content of the TOC wiki article will show in the shortcode location when the page is viewed.  Note that the TOC only shows if it has been published.  If a "Category" is chosen then that will override the insertion of the TOC content and will instead insert a list of the wiki articles assigned to that category.  The category list can be ordered by article title or by article creation date.  Title is the default.  If no category is chosen the "Order" parameter is ignored.

**Sidebar Widget:**
There is also a widget for showing the TOC article or a category list in the sidebar.  Please see the FAQ section for information about using the sidebar widget.

== Installation ==
You can install the Yada Wiki plugin either via the WordPress.org plugin directory or by uploading the files to your server.  Once the plugin is installed, you can activate the plugin through the Plugins menu in WordPress.

== Screenshots ==
1. These are the editor buttons for the Yada Wiki shortcodes.  

== Frequently Asked Questions ==
* Can I include external links and links to non-wiki pages in my wiki?

Yes, but the wiki shortcode is only for linking to wiki pages.  If you want to link to external sites or regular posts or pages, then use the usual methods in the post editor to do so, but not the Yada Wiki shortcode.

* Can I use the wiki shortcode in non-wiki posts and pages?

Yes, there is a Yada Wiki sub-menu under the main settings menu where you can enable showing the Yada Wiki shortcode buttons when editing posts and pages.  Remember, the wiki shortcode is only for linking to wiki pages.

* How can I add a wiki table of contents menu in the sidebar?

There is now a sidebar widget so that you can include the TOC or a category list of wiki articles. It is a multi-instance widget so you can have more that one version of the widget active at a time.

* Can I use the sidebar widget with Page Builder plugins?

The sidebar widget works well in regular widget areas, but does not work when embedded using Page Builder plugins.  

* Can I nest shortcodes or include multiple TOC Category shortcodes on the same page?

You can include regular wiki link shortcodes in your TOC.  You can also add multiple TOC Category shortcodes to a regular wiki page.  Adding TOC shortcodes to the TOC page, or adding TOC Category shortcodes to the TOC page does not work.

* How can I display the custom tags and categories?  

You can use the tag cloud widget to display the wiki custom tags. There are also plugins in the WordPress repository that will allow you to display custom categories (or taxonomies).  

* How do I control comment options?

Yada Wiki does not support comments by default.  There is a Yada Wiki sub-menu under the main settings menu where you can enable comment options and set defaults for new wiki pages.

* After upgrading the plugin the wiki editor buttons are gone. How do I get them back?

The way the buttons are loaded was changed in version 2.  If you do not see the buttons you may need to clear your browser cache.  

* Is the plugin compatible with WordPress multisite?

The plugin is not compatible with multisite.  

* Can I use custom permalinks?

The wiki links are based on links to wiki page titles, so it is expected that you will use the "Post name" type of permalink structure.  

* How can I change the colors of the wiki links?

When rendered on the viewed page, wiki links are given a CSS class related to their post status (published, draft, etc). You can override the default colors in your style sheet. This is a convenience for those with edit permissions who might not realize that wiki articles have not been published. Note that WordPress permissions still control what posts can actually be viewed, this is just related to the display of wiki links. The classes are:

When viewing links for users **with** the "edit_posts" capacity:

  * .wikilink-new (post does not exist - a link to create it)
  * .wikilink-trash (post is in the trash - just text, no link)
  * .wikilink-pending (post has the status of "draft", "auto-draft", "pending", or "future" - a regular link)
  * .wikilink-private (post has a status of "private" - a regular link)
  * .wikilink-published (the post is published - a regular link)
  * .wikilink-other (default if not one of the above. Applied to posts with a custom post status or to those with a status of "inherit" - a regular link)
  
When viewing links for users **without** the "edit posts" capacity:  

  * .wikilink-no-edit (post does not yet exist or the post has the status of "trash", "draft", "auto-draft", "pending", or "future" - just text, no link)
  * .wikilink-published (the post is published - a regular link)
  * .wikilink-private (post has a status of "private" - a regular link)
  * .wikilink-other (default if not one of the above. Applied to posts with a custom post status or to those with a status of "inherit" - a regular link)

== Acknowledgements ==

The user JulianSMoore has been very helpful in suggesting improvements and reviewing changes. 

== Changelog ==

= 2.6.1 =
* Fixed post status filter so all posts show as they should.
* Added additional CSS classes.  

= 2.6 =
* Added different CSS classes for wiki links depending on post status.  Added style sheet for wiki link classes.
* Added option to show Yada Wiki editor buttons on the editing screen for all post types, in addition to the wiki post type.

= 2.5.1 =
* Update to get files in sync and fix tagging error.

= 2.4.0 =
* Added filter to override default display of categories in the post editor to maintain order and hierarchy.
* Fixed problem with shortcode popup display due to conflict with other plugins.

= 2.3.0 =
* Adjusted version number to keep updates in sync.

= 2.2.0 =
* Added optional support for comments.
* Added a settings page to control comment options for wiki pages.
* Removed old style PHP constructor in preparation for 4.3.
* Fixed deprecated function calls that gave a debug warning.

= 2.1.0 =
* Adjusted user permissions so that roles have the same edit abilities for wiki articles as they do for regular posts.

= 2.0.0 =
* Changed the editor dialogs to be in-line jQuery UI dialogs. 
* The links dialog has an AJAX look-up of existing wiki post titles.
* The TOC dialog has a drop-down list of categories.
* A sidebar TOC / category list widget was added.

= 1.2.0 =
* Added editor dialog to make it easier to enter the wiki shortcode parameters

= 1.1.0 =
* Change to make wiki categories hierarchical

= 1.0.1 =
* Fix for empty post type

= 1.0 =
* Initial release