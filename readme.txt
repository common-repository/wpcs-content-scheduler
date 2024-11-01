=== Content Scheduler - Schedule Posts, Editorial Calendar and Notes ===
Contributors: ninetyninew
Tags: schedule posts, scheduled posts, post scheduler, editorial calendar
Requires at least: 5.0
Tested up to: 5.9.2
Requires PHP: 7.0
Stable tag: 1.3.0
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage and schedule posts, pages and more in a drag and drop editorial calendar.

== Description ==

### Manage and schedule posts, pages and more in a drag and drop editorial calendar.

Content Scheduler displays all your content (posts, pages and more) in one easy to use drag and drop calendar. Using the dashboard you can easily drag content to different dates, edit posts/pages in a popup without leaving the dashboard, add notes, filter content by post types, post statuses, taxonomies and users.

Content Scheduler also allows you to define your own post status colors for easy content management. The calendar has 4 different views: month, week, day and list. The dashboard can be filtered/saved without reloading the page for fast, easy content management.

Content Scheduler's editorial calendar is the ultimate tool to see all your content and publish/scheduled dates, combine this with allowing you to make edits without navigating back and forth throughout the WordPress dashboard this really is a great time saving tool for any content management tasks. Don't forget you can also add notes to posts/pages for future reference.

= Standard Features =

- **Editorial calendar** of your past/current/scheduled posts
- **Drag and drop posts** to change dates/times
- **4 calendar views** (month, week, day and list)
- **Add/edit core post types** in a popup window without leaving the dashboard
- **Add notes** on core post type posts
- **Filtering** of core post types, post statuses, taxonomies and users
- **Post status colors** to easily differentiate posts
- **Post status color key** shows a summary of post status colors
- **No reloading** to navigate/add/edit
- **Fast keyboard navigation**
- **Uses your WordPress admin color scheme**

= Premium Features =

- **Add/edit custom post type posts** in a popup window without leaving the dashboard
- **Add notes** on custom post type posts
- **Filtering** of custom post types, custom post statuses and custom taxonomies
- **Notes on post hover** displays notes when hovering posts in the dashboard calendar

= Settings =

- Post types: Posts of these post types will be displayed and are filterable in the dashboard and for selection when adding new content
- Post statuses: Posts with these post statuses where matched to the selected post types will be displayed and are filterable in the dashboard
- Taxonomies: The terms from the taxonomies selected will be available to filter the dashboard
- User roles: The users assigned to the selected roles will be available to filter the dashboard
- Post status colors: Set any color to the post statuses you have enabled, these are used to highlight posts and displayed in the color key
- Notes: Enable/disable notes on posts via the "Content Scheduler Notes" meta box (depending on post types selected).
- Popup width/height: Set the width and height of the popup used when adding/editing posts within the dashboard

= Filter Hooks =

- wpcs_content_scheduler_capability( $capability ) - If a user has $capability then allows access to the Content Scheduler, default is edit_posts

== Screenshots ==

1. Dashboard
2. Edit Posts
3. Add Posts
4. Add Notes
5. Settings

== Installation ==

= Minimum requirements =

* WordPress 5.0 or greater
* PHP 7.0 or greater

= Automatic installation =

To do an automatic install of this plugin log in to your WordPress dashboard, navigate to the Plugins menu, and click "Add New".
 
Search for "Content Scheduler", find this plugin in the list and click the install button, once done simply activate the plugin.

= Manual installation =

Manual installation method requires downloading the plugin and uploading it to your web server via an FTP application. See these [instructions on how to do this](https://wordpress.org/support/article/managing-plugins/#manual-plugin-installation).

= Getting started =

Once you have installed and activated Content Scheduler you can access it from the 'Content Scheduler' menu in your WordPress dashboard. You will be greeted by the dashboard, before you start using the calendar we recommend heading over to the 'Content Scheduler > Settings' page to ensure you have set up the post types, post statuses, colors, etc you require.

== Frequently Asked Questions ==

= How do I drag posts to a different calendar view? =

If you wish to drag a post to a different calendar view (e.g. drag an event from January to February) press left/right on your keyboard while dragging the post.

= Can I drag posts to a different time? =

Yes, this can be achieved through the week/day calendar view in 30 minute periods, or for full control simply click the post to edit it in a popup window, change the date, save the post and close the popup, the dashboard will automatically be updated without reloading the page.

= Can I edit a post from the dashboard? =

Yes, simply click the post and a popup will be displayed allowing you to edit the post, once done save the post and close the window. The dashboard will automatically be updated without reloading the page.

= Can I restrict access to certain users? =

Yes, by default any user with the core 'edit_posts' capability has access to Content Scheduler. If you want to amend the capability you can do so using the wpcs_content_scheduler_capability filter hook (see above for details).

= My calendar does not display any posts/pages/custom post types? =

Content Scheduler requires the permalinks of your WordPress website to be flushed so a new rewrite is included which the calendar attempts to access. Upon activation permalinks should be flushed automatically, but it is possible this process could fail during activation. If your calendar is not showing anything then we recommend manually flushing permalinks by re-saving the permalinks in 'Settings > Permalinks' from your WordPress dashboard.

== Changelog ==

= 1.3.0 - 2022-03-25 =

* Note: This version includes several changes to asset enqueues, it is recommended you clear all caches after upgrading to ensure all assets are reloaded
* Added: WPCS_Content_Scheduler_Translation class
* Added: Minified CSS/JS assets created and enqueued
* Changed: CSS assets now SCSS
* Changed: Inline JS/CSS reduced
* Changed: Upgrade conditions now use version_compare
* Changed: Enqueues plugins_url function calls so folder name not included that could effect installations where folder renamed
* Changed: WordPress tested up to 5.9.2
* Fixed: Incorrect text domains on some strings
* Fixed: Translations may not load due to load_plugin_textdomain not hooked on init

= 1.2.0 - 2022-02-27 =

* Added: Core post types can now be added via dashboard
* Changed: WordPress tested up to 5.9.1
* Fixed: Security fixes

= 1.1.1 - 2021-12-24 =

* Fixed: Posts that are already published do not get a scheduled post status when dragged to a future date

= 1.1.0 - 2021-12-04 =

* Added: Notes can be added to posts which are of the enabled post types
* Added: Notes in title tooltips when hovering over posts in dashboard calendar if premium version
* Added: Notes enable/disable setting
* Added: Post date/time now shown when hovering posts in dashboard calendar
* Added: WPCS_Content_Scheduler_Notes class
* Fixed: Settings field labels do not focus on fields

= 1.0.1 - 2021-11-25 =

* Fixed: Freemius SDK licensing conditions may cause features to be unavailable to some users

= 1.0.0 - 2021-11-25 =

* Initial release