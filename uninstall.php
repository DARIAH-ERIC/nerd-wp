<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://www.dariah.eu
 * @since      0.1.0
 *
 * @package    Nerd_Wp
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete postmeta from option table
 *
 */
$keys = array(
	'nerd_test'
);

global $wpdb;
foreach ( $keys as $key ) {
	$wpdb->query(
		$wpdb->prepare(
			"
			 DELETE FROM $wpdb->postmeta
			 WHERE meta_key = %s
			",
			$key
		)
	);
}
