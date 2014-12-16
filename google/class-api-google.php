<?php

if ( ! class_exists( 'Yoast_Api_Google' ) ) {

	class Yoast_Api_Google {

		public $options;

		/**
		 * This class will be loaded when someone calls the API library with the Google analytics module
		 */
		public function __construct() {
			$this->load_api_google_files();
		}

		/**
		 * Register the Autoload the Google class
		 */
		private function load_api_google_files() {
			spl_autoload_register( array( $this, 'autoload_api_google_files' ) );
		}

		/**
		 * Autoload the API Google class
		 */
		private function autoload_api_google_files() {
			$path        = dirname( __FILE__ );
			$oauth_files = array(
				// Main requires
				'yoast_google_model'           => 'service/Google_Model',
				'yoast_google_service'         => 'service/Google_Service',
				'yoast_google_serviceresource' => 'service/Google_ServiceResource',
				'yoast_google_assertion'       => 'auth/Google_AssertionCredentials',
				'yoast_google_signer'          => 'auth/Google_Signer',
				'yoast_google_p12signer'       => 'auth/Google_P12Signer',
				'yoast_google_batchrequest'    => 'service/Google_BatchRequest',
				'yoast_google_uritemplate'     => 'external/URITemplateParser',
				'yoast_google_auth'            => 'auth/Google_Auth',
				'yoast_google_cache'           => 'cache/Google_Cache',
				'yoast_google_io'              => 'io/Google_IO',
				'yoast_google_mediafileupload' => 'service/Google_MediaFileUpload',
				'yoast_google_client'          => 'Google_Client',

				// Requires in classes
				'yoast_google_authnone'        => 'auth/Google_AuthNone',
				'yoast_google_oauth2'          => 'auth/Google_OAuth2',
				'yoast_google_verifier'        => 'auth/Google_Verifier',
				'yoast_google_loginticket'     => 'auth/Google_LoginTicket',
				'yoast_goole_utils'            => 'service/Google_Utils',
				
			);


			foreach ( $oauth_files as $key => $name ) {
				if ( file_exists( $path . '/' . $name . '.php' ) ) {
					require_once( $path . '/' . $name . '.php' );
				}
			}
		}

	}

}