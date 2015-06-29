<?php

class Yoast_Google_Analytics_Client extends Yoast_Api_Google_Client {

	/**
	 * @var string
	 */
	protected $option_refresh_token = 'yoast-ga-refresh_token';

	/**
	 * @var string
	 */
	protected $option_access_token  = 'yoast-ga-access_token';

	/**
	 * @var array
	 */
	protected $default_config = array(
		'redirect_uri' => 'urn:ietf:wg:oauth:2.0:oob',
		'scopes'       => array( 'https://www.googleapis.com/auth/analytics.readonly' ),
	);

}
