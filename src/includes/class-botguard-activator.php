<?php

/**
 * Fired during plugin activation
 *
 * @link       https://botguard.net
 * @since      1.0.0
 *
 * @package    BotGuard
 * @subpackage BotGuard/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    BotGuard
 * @subpackage BotGuard/includes
 * @author     Dennis Prochko <support@botguard.net>
 */
class BotGuard_Activator {

	/**
	 * Activates the BotGuard plugin.
	 *
	 * Modifies wp-settings.php to add hook to BotGuard API on early initialization phase
	 * (before caching stuff is loaded).
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$config = file_get_contents( ABSPATH . 'wp-settings.php' );
		if ( !$config ) {
			die( sprintf( __( 'Cannot read %s file.', 'botguard' ), ABSPATH . 'wp-settings.php' ) );
		}

		if ( strpos( $config, '// BotGuard initialization' ) !==  false ) {
			return;
		}

		if ( file_put_contents( ABSPATH . 'wp-settings.php.bak', $config ) === false ) {
			die( sprintf( __( 'Can\'t backup the wp-settings.php file. Please check %s directory permissions, it\'s not writeable.' ), ABSPATH ) );
		}

		$new_config = str_replace(
			'wp_debug_mode();',
<<<'EOT'
wp_debug_mode();

// BotGuard initialization
@include( WP_CONTENT_DIR . '/plugins/botguard/public/botguard.php' );
EOT
			, $config
		);

		if ( file_put_contents( ABSPATH . 'wp-settings.php', $new_config ) === false ) {
			die( __( 'Can\'t overwrite wp-settings.php file. Please check file permissions, it\'s not writeable.' ) );
		}

		$botguard_config_file = plugin_dir_path( dirname( __FILE__ ) ) . 'public/botguard-settings.php';
		if ( !BotGuard_Admin::update_config_file( $botguard_config_file ) ) {
			die( sprintf( __( 'BotGuard configuration file %s is not writeable. Check file permissions.'), $botguard_config_file ) );
		}

		set_transient( 'wp_settings_modified', true, 5 );

	}

	/**
	 * Deactivates the BotGuard plugin.
	 *
	 * Modifies wp-settings.php to remove hook to BotGuard API.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		$config = file_get_contents( ABSPATH . 'wp-settings.php' );
		if ( !$config ) {
			die( sprintf( __( 'Cannot read %s file.', 'botguard' ), ABSPATH . 'wp-settings.php' ) );
		}

		if ( strpos( $config, '// BotGuard initialization' ) ===  false ) {
			return;
		}

		if ( file_put_contents( ABSPATH . 'wp-settings.php.bak', $config ) === false ) {
			die( sprintf( __( 'Can\'t backup the wp-settings.php file. Please check %s directory permissions, it\'s not writeable.' ), ABSPATH ) );
		}

		$new_config = str_replace(
<<<'EOT'

// BotGuard initialization
@include( WP_CONTENT_DIR . '/plugins/botguard/public/botguard.php' );

EOT
		, '', $config);

		if ( file_put_contents( ABSPATH . 'wp-settings.php', $new_config ) === false ) {
			die( __( 'Can\'t overwrite wp-settings.php file. Please check file permissions, it\'s not writeable.' ) );
		}

	}

	/**
	 * Shows the help text.
	 *
	 * Provides the information about WordPress config modifications.
	 *
	 * @since    1.0.0
	 */
	public static function show_admin_notice() {
		if ( get_transient( 'wp_settings_modified' ) ) {
			echo '<div class="notice notice-success is-dismissible">' . __( '<p>Your <i>wp-settings.php</i> file was modified. Backup copy was created as <i>wp-settings.php.bak</i> file.</p>', 'botguard' ) . '</div>';
			delete_transient( 'wp_settings_modified' );
		}
	}

}
