<?php

/**
 * Include this class to use the Yoast_Api_Libs, you can include this as a submodule in your project
 * and you just have to autoload this class
 *
 *
 * NAMING CONVENTIONS
 * - Register 'oauth' by using $this->register_api_library()
 * - Create folder 'oauth'
 * - Create file 'class-api-oauth.php'
 * - Class name should be 'Yoast_Api_Oauth'
 */

if ( ! class_exists( 'Yoast_Api_Libs' ) ) {

	class Yoast_Api_Libs {

		/**
		 * Store the available API libraries
		 *
		 * @var array
		 */
		private $api_libs = array();

		/**
		 * Construct the API Libs
		 */
		public function __construct() {
			$this->register_api_libs();

			$this->load_classes();
		}

		/**
		 * Get the registerd API libraries
		 *
		 * @return array
		 */
		public function get_api_libs() {
			return $this->api_libs;
		}

		/**
		 * Init function and register the API libraries
		 */
		private function register_api_libs() {
			$this->register_api_library( 'oauth' );
		}

		/**
		 * Register a new API library to this class
		 *
		 * @param $name
		 *
		 * @return bool
		 */
		private function register_api_library( $name ) {
			$name             = strtolower( $name );
			$this->api_libs[] = $name;
			$classname        = 'Yoast_Api_' . ucfirst( $name );
			$classpath        = 'class-api-' . $name . '.php';

			if ( file_exists( $name . '/' . $classpath ) ) {
				require_once( $name . '/' . $classpath );

				if ( class_exists( $classname ) ) {
					$this->$name = new $classname;

					return true;
				}

				return false;
			} else {
				return false;
			}
		}

	}

}