<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://botguard.net
 * @since      1.0.0
 *
 * @package    BotGuard
 * @subpackage BotGuard/admin/partials
 */

function botguard_options_page() {
?>
<div class="wrap">
	<h1><?php _e( 'BotGuard Settings', 'botguard' ); ?></h1>
	<?php _e( '<p>To configure bot blocking options, please visit the <a href="https://botguard.net/en/website" target="_blank">Botguard dashboard</a>.</p>', 'botguard' ); ?>
	<form action="options.php" method="post">
		<?php settings_fields( 'botguard' ); ?>
		<?php do_settings_sections( 'botguard' ); ?>
		<?php submit_button(); ?>
	</form>
</div>
<?php
}

function botguard_settings_callback() {
	_e( '<p>The primary and secondary BotGuard server addresses assigned to your web site.</p>', 'botguard' );
}

function botguard_server_primary_callback() {
	$setting = esc_attr( get_option( 'botguard_server_primary' ) );
    echo "<input type='text' name='botguard_server_primary' value='$setting' />";
    _e( '<p class="description">The address of the primary BotGuard server.</p>', 'botguard' );
}

function botguard_server_secondary_callback() {
	$setting = esc_attr( get_option( 'botguard_server_secondary' ) );
    echo "<input type='text' name='botguard_server_secondary' value='$setting' />";
    _e( '<p class="description">The address of the secondary BotGuard server.</p>', 'botguard' );
}

?>
