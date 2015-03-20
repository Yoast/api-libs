<?php

abstract class Oauth {

	abstract public function Authenticate();

	/**
	 * Method to set the config
	 *
	 * @param array $Config
	 */
	abstract protected function SetConfig( array $Config );

	/**
	 * Opens the service authentication screen
	 *
	 * @return mixed
	 */
	abstract protected function OpenAuthentication();

	/**
	 * Validates the current active access token
	 *
	 * @return mixed
	 */
	abstract protected function ValidateAccessToken();

	/**
	 * Requesting an access token from the service
	 *
	 * @param $Code
	 *
	 * @return mixed
	 */
	abstract protected function RequestAccessToken( $Code );

	/**
	 * Getting accesstoken from the options
	 *
	 * @return mixed
	 */
	abstract protected function GetAccessToken();

	/**
	 * Saving access token to the options
	 *
	 * @param $AccessToken
	 *
	 * @return mixed
	 */
	abstract protected function SaveAccessToken( $AccessToken );

	abstract protected function DoRequest( $RequestURL );
}