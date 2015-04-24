=== Yada Wiki ===
Contributors: dmccan
Tags: wiki, shortcode, internal links, page links, faq, knowledge base
Requires at least: 4.1
Tested up to: 4.2
Stable tag: 1.3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Yada Wiki is a simple wiki for your WordPress site.

== Description ==
Yada Wiki provides a wiki post type, custom tags and categories, and a table of contents option.  The plugin allows you to link your wiki pages together using the wiki page titles.  

There are two easy to use shortcodes, available via buttons on the editor toolbar.  When you click the first button, the wiki link button, a pop-up opens where you enter the two parameters:  

* The "link" parameter takes the title of the wiki page you are linking to.
* The "show" parameter takes the link description that you want to show.

When you click "OK", a shortcode is inserted into your edit window at the cursor location.  

When viewing wiki pages, if the wiki page exists it shows as a link.  If it does not exist, and the user has permissions to create posts and pages, then the link shows up in red to indicate that the page needs to be created.  You can click on the link to go to the page editor to create the new page.  If the user does not have permissions, then they see the title in red, but no link is available.

With Yada Wiki, you can create a special wiki post with the title of "TOC", for table of contents, and create a wiki post that will serve as the table of contents for your wiki.  You can use wiki links and style the table of contents as you like in the post editor.  The second button inserts a table of contents shortcode into the editor window at the cursor location.  This will insert the TOC wiki page you created at the shortcode location.  Note that the TOC only shows if it has been published.  

== Installation ==
You can install the Yada Wiki plugin either via the WordPress.org plugin directory or by uploading the files to your server.  Once the plugin is installed, you can activate the plugin through the Plugins menu in WordPress

== Frequently Asked Questions ==
=  Can I include external links and links to non-wiki pages in my wiki?

Yes, but the wiki shortcode is only for linking to wiki pages.  If you want to link to external sites or regular posts or pages, then use the usual methods in the post editor to do so, but not the Yada Wiki shortcode.

=Can I use the TOC shortcode to add a custom menu in the sidebar?

No, the shortcodes only work on wiki pages. If you want to use custom menus for your wiki pages, you can create them in the regular menu editor.  

=How can I display the custom tags and categories?  

You can use the tag cloud widget to display the wiki custom tags. There are also plugins in the WordPress repository that will allow you to display custom categories (or taxonomies).  

=Is the plugin compatible with WordPress multisite?

It has not been tested with multisite.  

=Can I use custom permalinks?

The wiki links are based on links to wiki page titles, so it is expected that you will use the "Post name" type of permalink structure.  


== Changelog ==

= 1.0 =
* Initial release

= 1.0.1 =
* Fix for empty post type

= 1.1.0 =
* Change to make wiki categories hierarchical

= 1.2.0 =
* Added editor dialog to make it easier to enter the wiki shortcode parameters
