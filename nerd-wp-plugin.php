<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.dariah.eu/
 * @since             0.1.0
 * @package           Nerd
 *
 * @wordpress-plugin
 * Plugin Name:       NERD WP Plugin
 * Plugin URI:        https://github.com/dariah-eric/nerd-wp-plugin
 * Description:       NERD (Named Entity Recognition and Disambiguation: http://nerd.hum-num.fr) is an application that allows to recognize and disambiguate named entities. This plugin allows integration of this with Wordpress.
 * Version:           0.1.0
 * Author:            Yoann
 * Author URI:        https://www.dariah.eu
 * License:           Apache License - 2.0
 * License URI:       http://www.apache.org/licenses/LICENSE-2.0
 * Text Domain:       nerd-wp-plugin
 * Domain Path:       /languages
 */

require 'libraries/guzzle/autoloader.php';
require 'libraries/readability/autoloader.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'NERD_ROOT', dirname( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nerd-wp-plugin-activator.php
 */
function activate_nerd_wp_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nerd-wp-plugin-activator.php';
	Nerd_Wp_Plugin_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nerd-wp-plugin-deactivator.php
 */
function deactivate_nerd_wp_plugin() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nerd-wp-plugin-deactivator.php';
	Nerd_Wp_Plugin_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_nerd_wp_plugin' );
register_deactivation_hook( __FILE__, 'deactivate_nerd_wp_plugin' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nerd-wp-plugin.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_nerd_wp_plugin() {
	$plugin = new Nerd_Wp_Plugin();
	$plugin->run();
}
run_nerd_wp_plugin();