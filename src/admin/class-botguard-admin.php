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
	 * The configuration file path.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $config    The configuration file path.
	 */
	private $config;

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
		$this->config = plugin_dir_path( dirname( __FILE__ ) ) . 'public/botguard-settings.php';

	}

	/**
	 * Utility functions
	 *
	 * @since    1.0.4
	 */
	public function isPluginActive( $plugin ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || $this->isPluginActiveForNetwork( $plugin );
	}

	public function isPluginActiveForNetwork( $plugin ) {
		if ( !is_multisite() )
			return false;

		$plugins = get_site_option( 'active_sitewide_plugins');
		if ( isset($plugins[$plugin]) )
			return true;

		return false;
	}

	/**
	 * Add plugin options page into WordPress admin area.
	 *
	 * @since    1.0.0
	 */
	public function init_admin_menu() {

		add_options_page( __( 'BotGuard Settings', 'botguard' ), 'BotGuard', 'manage_options', 'botguard', 'botguard_options_page' );
		add_settings_section( 'botguard', __( 'Main Settings', 'botguard' ), 'botguard_settings_callback', 'botguard' );
		add_settings_field( 'botguard_server_primary', __( 'Primary Server', 'botguard' ), 'botguard_server_primary_callback', 'botguard', 'botguard' );
		add_settings_field( 'botguard_server_secondary', __( 'Secondary Server', 'botguard' ), 'botguard_server_secondary_callback', 'botguard', 'botguard' );

		if ($this->isPluginActive('wp-fastest-cache/wpFastestCache.php')) {
			set_transient( 'wp_incompatible_plugins', true, 0 );
			deactivate_plugins('wp-fastest-cache/wpFastestCache.php');
			return;
		}

		BotGuard_Activator::activate();
	}

	public function init_admin_page() {

		register_setting( 'botguard', 'botguard_server_primary', function( $input ) {
			if ( empty($input) ) {
				add_settings_error('validate_option', 'validate_option_empty', __( 'Primary Server name is not set.') );
				return null;
			}

			if ( preg_match( '/.+\.botguard\.net$/', $input ) === 0 ) {
				add_settings_error('validate_option', 'validate_option_invalid', __( 'Primary Server has incorrect value.') );
				return null;
			}

			if ( !is_writeable( $this->config ) ) {
				add_settings_error('validate_option', 'config_file_invalid', sprintf( __( 'BotGuard configuration file %s is not writeable. Check file permissions.'), $this->config ) );
				return null;
			}

			return $input;
		});

		register_setting( 'botguard', 'botguard_server_secondary', function( $input ) {
			if ( empty($input) ) {
				add_settings_error('validate_option', 'validate_option_empty', __( 'Secondary Server name is not set.') );
				return null;
			}

			if ( preg_match( '/.+\.botguard\.net$/', $input ) === 0 ) {
				add_settings_error('validate_option', 'validate_option_invalid', __( 'Secondary Server has incorrect value.') );
				return null;
			}

			return $input;
		});

	}

	public function init_plugin_links( $links, $file ) {

		if ( $file == 'botguard/botguard.php' ) {
			$settings_link = '<a href="' . admin_url( 'options-general.php?page=botguard' ) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link );
		}

		return $links;

	}

	public function update_option( $option, $value = '', $new_value = '' ) {

		if ( $option == 'botguard_server_primary' || $option == 'botguard_server_secondary' ) {
			self::update_config_file( $this->config );
		}

	}

	public static function update_config_file( $file ) {
		$primary = get_option('botguard_server_primary', '');
		$secondary = get_option('botguard_server_secondary', '');
		$content =
<<<EOT
<?php

define( 'BOTGUARD_SERVER_PRIMARY', '$primary' );
define( 'BOTGUARD_SERVER_SECONDARY', '$secondary' );
EOT;
		return file_put_contents( $file, $content ) !== false;
	}

	/**
	 * Shows the help text.
	 *
	 * Provides the information about WordPress config modifications and activation errors.
	 *
	 * @since    1.0.4
	 */
	public function show_admin_notice() {
		if ( get_transient( 'wp_settings_modified' ) ) {
			echo '<div class="notice notice-success is-dismissible">' . __( '<p>Your <strong>wp-settings.php</strong> file was modified. Backup copy was created as <strong>wp-settings.php.bak</strong> file.</p>', 'botguard' ) . '</div>';
			delete_transient( 'wp_settings_modified' );
		}
		if ( get_transient( 'wp_incompatible_plugins' ) ) {
			echo '<div class="notice notice-success is-dismissible"><p>' . __( '<strong>WP Fastest Cache</strong> plugin is incompatible with BotGuard Wordpress Plugin and <strong>was deactivated</strong>' ) . '</p></div>';
			delete_transient( 'wp_incompatible_plugins' );
		}
	}

}
