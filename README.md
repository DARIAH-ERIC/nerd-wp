# NERD WordPress Plugin

_Developed by [DARIAH](https://www.dariah.eu/) for [OpenMethods](https://openmethods.dariah.eu/)_

[NERD](https://github.com/kermitt2/entity-fishing) is an application that allows to recognize and disambiguate named entities.
This plugin allows integration of this with Wordpress. Each post can be run through NERD and will automatically create tags for it.
Those tags, in return are used to propose extra information coming from Wikipedia and Wikidata.

---

# Install (via WordPress plugins)
1. Install via [WordPress plugins](https://www.wordpress.org/plugins/nerd-wp)

# Install (manually)
1. Upload directory `nerd-wp` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

# Add the plugin Widget
1. Once in the admin section, go to the Widget section and add the NERD WP Widget to your sidebar
1. You may also modify the title of the Widget

# How does it work?
This plugin will automatically create WordPress Tags that were found with NERD. The text of the post (or the source 
of the post if run with [PressForward](https://github.com/PressForward/pressforward/) plugin) is sent to the NERD 
service and then is retrieved Named Entities that are used to create those Tags. The threshold of the score of NERD 
can be adjusted in the admin options. 

## Three ways to create those WordPress tags
1. When creating a new post and at the moment of publishing it, the plugin will then go to work and contact the NERD 
instance in order to retrieve the Named Entities for your post. This only happen once.
1. In the admin/edition part of a post, you will find a meta box in the sidebar that will allow you to relaunch NERD
whenever you need. By clicking this button, the plugin will then contact the NERD instance and retrieve the 
Named Entities from your post. This can be launched whenever the admin/editor wishes, for edxample whenever the 
content of a post has been updated or modified.
1. When used with the [PressForward](https://github.com/PressForward/pressforward/) plugin, the source of the post 
(whose URL is in the post meta table with key `item_link`) will then be used an sent to the NERD instance

# The options available in the admin section
1. URL of NERD instance:
    1. The URL of where NERD is located, the main service URL that is today publicly available is located at 
the TGIR Huma-Num: `http://nerd.huma-num.fr/nerd/`
    1. We need the URL of the service itself, the one url that when appended a `service/isalive` would provide a 
correct answer to our plugin, for example: `http://nerd.huma-num.fr/nerd/` for
`http://nerd.huma-num.fr/nerd/service/isalive`
1. Weight of the global categories (if a category weight this or more, then it is used as a tag)
    1. NERD provides scores for each named entities found in the text provided. This score is comprised between 0 and
1, 0 being the worst score possible. So this option allows the admin to decide at which score it becomes 
necessary to keep the Wikipedia global categories that NERD found.
1. Weight of the entities (if an entity weight this or more, then it is used as a tag - we use nerd_selection_score)
    1. As above, this score is comprised between 0 and 1 and this option allows the admin to decide at which score it
becomes necessary to keep the Wikipedia and/or Wikidata Named Entities that NERD found.

# Development for WordPress 5
To prepare the plugin for WordPress 5.x, we added support for node.js 10.1.0 via [asdf](https://github.com/asdf-vm/asdf).
That is the reason we have the `.tool-versions` file that asdf uses. Of course, you can use `npm` without `asdf`, for
example if you installed it directly.
We installed all packages with `npm install @wordpress/plugins --save` for what is needed for WordPress 5.x.
Updates are done with `npm install @wordpress/plugins --update`.
