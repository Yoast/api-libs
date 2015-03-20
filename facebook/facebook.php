<?php

class Facebook extends Oauth {

	private $ApiURL      = 'https://graph.facebook.com/';

	private $AppSecret;

	private $AppID;

	private $RedirectURL;

	private $AccessToken;

	public function __construct(array $Config ) {
		$this->SetConfig( $Config );
	}

	/**
	 * Doing the authentication
	 *
	 * @return bool|void
	 */
	public function Authenticate() {
		if($this->ValidateAccessToken()) {
			return true;
		}

		// There is no code
		$this->OpenAuthentication();



		if($this->AccessToken === null ) {
			$this->RequestAccessToken( $_GET['code'] );
		}

		return false;
	}

	/**
	 * Runs a facebook graph request
	 *
	 * @param  string $QueryURL
	 *
	 * @return array
	 * @throws Exception
	 */
	public function Query($QueryURL) {
		return $this->DoRequest( $QueryURL . '?access_token=' . $this->AccessToken);
	}

	/**
	 * Setting the config
	 *
	 * @param array $Config
	 *
	 */
	protected function SetConfig( array $Config ) {
		foreach ( $Config AS $Property => $Value ) {
			if ( property_exists( $this, $Property ) ) {
				$this->$Property = $Value;
			}
		}
	}

	/**
	 * Redirects to the authentication
	 */
	protected function OpenAuthentication() {
		if ( empty($_GET['code'])) {
			wp_redirect( "https://www.facebook.com/dialog/oauth?client_id={$this->AppID}&redirect_uri=" . $this->RedirectURL . "&response_type=code" );
		}
	}

	/**
	 * Validates the current saved accesstoken
	 *
	 * @return bool
	 */
	protected function ValidateAccessToken() {
		$AccessToken = $this->GetAccessToken();
		if($AccessToken !== false) {
			// Listing current profile
			try {
				// Doing simple request to get user details
				$this->DoRequest("me?access_token=" . $AccessToken);

				// Saving the accesstoken in a property
				$this->AccessToken = $AccessToken;

				return true;

			} catch(Exception $e) {
				// Reset the access token
				$this->SaveAccessToken(false);

				// Opens the authentication flow
				$this->OpenAuthentication();
			}
		}

		return false;
	}

	/**
	 * Request a simple access token
	 *
	 * @param string $Code
	 *
	 * @return bool
	 */
	protected function RequestAccessToken( $Code ) {
		try {
			$Response = $this->DoRequest("oauth/access_token?client_id={$this->AppID}&client_secret={$this->AppSecret}&redirect_uri=https://www.facebook.com/connect/login_success.html&code={$Code}");

			if($AccessToken = strstr($Response['body'], 'access_token=', false)) {
				$AccessToken = substr($AccessToken, 13);
				$this->SaveAccessToken($AccessToken);

				if($this->ValidateAccessToken()) {
					wp_redirect( $this->RedirectURL );
				}
			}
		} catch(Exception $e) {
			echo "<pre>";
			print_r($e);
		}
	}

	/**
	 * Getting accesstoken from the options
	 *
	 * @return mixed
	 */
	protected function GetAccessToken() {
		return get_option('fb_accesstoken', false);
	}

	/**
	 * Saving the accesstoken
	 *
	 * @param string $AccessToken
	 *
	 * @return bool
	 */
	protected function SaveAccessToken( $AccessToken ) {
		update_option('fb_accesstoken', $AccessToken);

		return true;
	}

	/**
	 * Doing a request to the facebook service
	 *
	 * @param  string $RequestURL
	 * @throws Exception
	 * @return array
	 */
	protected function DoRequest( $RequestURL ) {
		$Content = wp_remote_get($this->ApiURL . $RequestURL);

		if ($JSON = json_decode($Content['body']) ) {
			$Content = (array) $JSON;

			// When there is an error, throw new Exception
			if ( ! empty( $Content['error'] ) ) {
				throw new Exception($Content['error']->message, $Content['error']->code);
			}
		}

		return $Content;
	}
}