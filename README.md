# NERD WordPress Plugin

_Developed by DARIAH for [OpenMethods](https://openmethods.dariah.eu/)_

[NERD](https://github.com/kermitt2/entity-fishing) is an application that allows to recognize and disambiguate named entities.
This plugin allows integration of this with Wordpress. Each post can be run through NERD and will automatically create tags for it.
Those tags, in return are used to propose extra information coming from Wikipedia and Wikidata.

---

# Install (via WordPress plugins)
1. Install via [WordPress plugins](https://www.wordpress.org/plugins/nerd-wp)

# Install (manually)
1. Upload directory `nerd-wp` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

# How does it work?

This plugin will automatically create WordPress Tags that were found with NERD. The text of the post (or the source 
of the post if run with [PressForward](https://github.com/PressForward/pressforward/) plugin) is sent to the NERD 
service and then is retrieved Named Entities that are used to create those Tags. The threshold of the score of NERD 
can be adjusted in the admin options. 
