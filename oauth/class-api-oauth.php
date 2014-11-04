<?php

if ( class_exists( 'Yoast_Api_Oauth' ) ) {

	class Yoast_Api_Oauth {

		/**
		 * This class will be loaded when someone calls the API library with the Oauth module
		 */
		public function __construct() {
			$this->load_api_oauth_files();
		}

		/**
		 * Autoload the Oauth classes
		 */
		private function load_api_oauth_files(){
			$oauth_files = array(
				'Yoast_OAuthException'	=>	'class-oauth-exception',
			);

			foreach( $oauth_files as $key => $name ){
				if( file_exists( $name . '.php' ) ){
					require_once( $name . '.php' );
				}
			}
		}

	}

}