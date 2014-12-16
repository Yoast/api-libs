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
		 * Register the Autoload the Oauth classes
		 */
		private function load_api_oauth_files() {
			spl_autoload_register( array( $this, 'autoload_api_oauth_files' ) );
		}

		/**
		 * Autoload the API Oauth classes
		 */
		private function autoload_api_oauth_files() {
			$path        = dirname( __FILE__ );
			$oauth_files = array(
				'yoast_api_googleanalytics_reporting' => 'class-googleanalytics-reporting',
				'yoast_google_analytics_client'       => 'class-google-analytics-client',
			);

			foreach ( $oauth_files as $key => $name ) {
				if ( file_exists( $path . '/' . $name . '.php' ) ) {
					require_once( $path . '/' . $name . '.php' );
				}
			}
		}

	}

}