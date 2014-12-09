<?php

if ( ! class_exists( 'Yoast_Api_Googleanalytics' ) ) {

	class Yoast_Api_Googleanalytics {

		public $options;

		/**
		 * This class will be loaded when someone calls the API library with the Google analytics module
		 */
		public function __construct() {
			$this->load_api_oauth_files();
		}

		/**
		 * Autoload the Oauth classes
		 */
		private function load_api_oauth_files() {
			$oauth_files = array(
				'yoast_api_googleanalytics_reporting'                  => 'class-googleanalytics-reporting',
			);

			foreach ( $oauth_files as $key => $name ) {
				if ( file_exists( dirname( __FILE__ ) . '/' . $name . '.php' ) ) {
					require_once( dirname( __FILE__ ) . '/' . $name . '.php' );
				}
			}
		}

	}

}