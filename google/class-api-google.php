<?php

if ( ! class_exists( 'Yoast_Api_Google' ) ) {

	class Yoast_Api_Google{

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
				'yoast_google_client' => 'Google_Client',
			);

			foreach ( $oauth_files as $key => $name ) {
				if ( file_exists( $path . '/' . $name . '.php' ) ) {
					require_once( $path . '/' . $name . '.php' );
				}
			}
		}

	}

}