<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://botguard.net
 * @since      1.0.0
 *
 * @package    BotGuard
 * @subpackage BotGuard/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and hooks.
 *
 * @package    BotGuard
 * @subpackage BotGuard/admin
 * @author     Dennis Prochko <support@botguard.net>
 */
class BotGuard_Admin {

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
	 * @param    string    $plugin_name    The name of this plugin.
	 * @param    string    $version        The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Add plugin options page into WordPress admin area.
	 *
	 * @since    1.0.0
	 */
	public function init_settings_page() {
		add_options_page( __( 'BotGuard Settings', 'botguard' ), 'BotGuard', 'manage_options', 'botguard', 'botguard_options_page' );
	}

	public function init_admin_page() {
		register_setting( 'botguard', 'botguard_enabled' );
		register_setting( 'botguard', 'botguard_server_primary' );
		register_setting( 'botguard', 'botguard_server_secondary' );
		add_settings_section( 'botguard', __( 'Main Settings', 'botguard' ), 'botguard_settings_callback', 'botguard' );
		add_settings_field( 'botguard_enabled', __( 'Blocking Mode', 'botguard' ), 'botguard_enabled_callback', 'botguard', 'botguard' );
		add_settings_field( 'botguard_server_primary', __( 'Primary Server', 'botguard' ), 'botguard_server_primary_callback', 'botguard', 'botguard' );
		add_settings_field( 'botguard_server_secondary', __( 'Secondary Server', 'botguard' ), 'botguard_server_secondary_callback', 'botguard', 'botguard' );
	}

}
