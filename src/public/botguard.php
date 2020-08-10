<?php

@include 'botguard-settings.php';
require __DIR__ . '/../includes/vendor/autoload.php';

use BotGuard\BotGuard;
use BotGuard\Profile;

if ( defined( 'BOTGUARD_SERVER_PRIMARY' ) && defined( 'BOTGUARD_SERVER_SECONDARY' ) && !empty( BOTGUARD_SERVER_PRIMARY ) && !empty( BOTGUARD_SERVER_SECONDARY ) ) {

	if ( strpos( $_SERVER['REQUEST_URI'], '/wp-admin/') !== 0 ) {

		$botguard = BotGuard::instance( array(
			'primary_server' => BOTGUARD_SERVER_PRIMARY,
			'secondary_server' => BOTGUARD_SERVER_SECONDARY,
		) );

		$profile = $botguard->check();

		if ( $profile ) {
			switch ( $profile->getMitigation() ) {
				case Profile::MITIGATION_DENY:
				case Profile::MITIGATION_RETURN_FAKE:
					http_response_code( 403 );
					exit;
				case Profile::MITIGATION_CHALLENGE:
					http_response_code( 403 );
					$profile->challenge();
					exit;
				case Profile::MITIGATION_REDIRECT:
				case Profile::MITIGATION_CAPTCHA:
					header( 'Location: ' . $profile->getMitigationURL(), true, 302 );
					exit;
			}
		}

	}

}
