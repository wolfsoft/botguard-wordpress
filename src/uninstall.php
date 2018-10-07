<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://botguard.net
 * @since      1.0.0
 * @package    BotGuard
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'botguard_enabled' );
delete_site_option( 'botguard_enabled' );
delete_option( 'botguard_server_primary' );
delete_site_option( 'botguard_server_primary' );
delete_option( 'botguard_server_secondary' );
delete_site_option( 'botguard_server_secondary' );
