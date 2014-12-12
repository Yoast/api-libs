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
		 * Autoload the Oauth classes
		 */
		private function load_api_google_files() {
			require_once( dirname( __FILE__ ) . '/Google_Client.php' );
		}

	}

}