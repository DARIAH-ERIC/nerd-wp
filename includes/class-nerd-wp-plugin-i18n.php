<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.dariah.eu
 * @since      0.1.0
 *
 * @package    Nerd_Wp_Plugin
 * @subpackage Nerd_Wp_Plugin/includes
 */
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.1.0
 * @package    Nerd_Wp_Plugin
 * @subpackage Nerd_Wp_Plugin/includes
 * @author     Yoann <yoann.moranville@dariah.eu>
 */
class Nerd_Wp_Plugin_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'nerd-wp-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}