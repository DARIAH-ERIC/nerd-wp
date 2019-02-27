=== NERD WP Plugin ===
Contributors: yoannspace
Donate link:
Tags: entity recognition, disambiguation, entity fishing
Requires at least: 4.9.1
Tested up to: 4.9.1
Requires PHP: 5.6.35
Stable tag: 1.2.0
License: Apache License - 2.0
License URI: http://www.apache.org/licenses/LICENSE-2.0

NERD (https://github.com/kermitt2/entity-fishing) is an application that allows to recognize and disambiguate named entities. This plugin allows integration of this with Wordpress.

== Description ==

[NERD](https://github.com/kermitt2/entity-fishing) is an application that allows to recognize and disambiguate named entities.
This plugin allows integration of the NERD service with Wordpress. Each post can be run through NERD and will automatically create tags for it.
Those tags, in return are used to propose extra information coming from Wikipedia and Wikidata.


== Installation (via WordPress plugins) ==

1. Install via [WordPress plugins](https://www.wordpress.org/plugins/nerd-wp)

== Installation (manually) ==

1. Upload directory `nerd-wp` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Add the plugin Widget ==
1. Once in the admin section, go to the Widget section and add the NERD WP Widget to your sidebar
1. You may also modify the title of the Widget

== Frequently Asked Questions ==

To come later

== Screenshots ==

1. Example of the widget that includes the Tags created with the Named Entities by NERD

== Changelog ==

= 1.2.0 =
* Title of Widget can be modified
* Loading wheel when retrieving the information from NERD in the Widget [See Issue](https://github.com/DARIAH-ERIC/nerd-wp/issues/4)
* Add more information in README about the Widget

= 1.1.4 =
* Add missing namespace for the HTML parser

= 1.1.3 =
* Add missing imports, was not important for most usage

= 1.1.2 =
* Fix for category links that were not correctly constructed [See Issue](https://github.com/DARIAH-ERIC/nerd-wp/issues/3)

= 1.1.1 =
* Fix for different languages within the Wikipedia links [See Issue](https://github.com/DARIAH-ERIC/nerd-wp/issues/2)

= 1.1.0 =
* Ajax queries to NERD are not done via JS anymore but via the PHP code [See Issue](https://github.com/DARIAH-ERIC/nerd-wp/issues/1)

= 1.0.0 =
* Very first version

== Upgrade Notice ==

No upgrade notice for now
