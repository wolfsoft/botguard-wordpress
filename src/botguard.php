<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://botguard.net
 * @since             1.0.0
 * @package           BotGuard
 *
 * @wordpress-plugin
 * Plugin Name:       BotGuard
 * Plugin URI:        https://botguard.net
 * Description:       BotGuard provides the service to protect your website from malicious bots, crawlers, scrapers, and hacker attacks.
 * Version:           1.0.0
 * Author:            Dennis Prochko
 * Author URI:        mailto:support@botguard.net
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       botguard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'BotGuard_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-botguard.php';

// Begins execution of the plugin.
$plugin = new BotGuard();
$plugin->run();
