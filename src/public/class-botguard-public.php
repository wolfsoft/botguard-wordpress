<?php

use BotGuard\BotGuard;

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://botguard.net
 * @since      1.0.0
 * @package    BotGuard
 * @subpackage BotGuard/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    BotGuard
 * @subpackage BotGuard/public
 * @author     Dennis Prochko <support@botguard.net>
 */
class BotGuard_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name   The name of the plugin.
	 * @param      string    $version       The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Process the HTTP request with BotGuard API.
	 *
	 * @since    1.0.0
	 */
	public function process_request() {

		$enabled = get_option( 'botguard_enabled' );
		$server_primary = get_option( 'botguard_server_primary' );
		$server_secondary = get_option( 'botguard_server_secondary' );

		if ( $server_primary && $server_secondary ) {

			$botguard = BotGuard::instance( array(
				'server' => $server_primary,
				'backup' => $server_secondary,
			) );

			$current_user = wp_get_current_user();

			if ( $enabled && !user_can( $current_user, 'administrator' ) ) {

				$profile = $botguard->check();

				if ( $profile->getScore() >= 5 ) {
					http_response_code(403);
					die();
				}

				if ( $profile->getScore() > 0 ) {
					$botguard->challenge();
					die();
				}

			}

		}

	}

}
