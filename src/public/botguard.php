<?php

@include 'botguard-settings.php';
require __DIR__ . '/../includes/vendor/autoload.php';

use BotGuard\BotGuard;

if ( defined( 'BOTGUARD_SERVER_PRIMARY' ) && defined( 'BOTGUARD_SERVER_SECONDARY' ) && !empty( BOTGUARD_SERVER_PRIMARY ) && !empty( BOTGUARD_SERVER_SECONDARY ) ) {

	if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/') !== 0 ) {

		$botguard = BotGuard::instance( array(
			'server' => BOTGUARD_SERVER_PRIMARY,
			'backup' => BOTGUARD_SERVER_SECONDARY,
		) );

		$profile = $botguard->check();

		if ( $profile->getScore() >= 5 ) {
			http_response_code(403);
			die('Forbidden');
		}

		if ( $profile->getScore() > 0 ) {
			$botguard->challenge();
			die();
		}

	}

}
