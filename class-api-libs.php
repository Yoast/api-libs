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
		private static $api_libs = array();

		/**
		 * Store the instances of the API class
		 *
		 * @var array
		 */
		private static $instances = array();

		/**
		 * Call this method to init the libraries you need
		 *
		 * @param array $libraries
		 */
		public static function load_api_libraries( $libraries = array() ) {
			if ( is_array( $libraries ) && count( $libraries ) >= 1 ) {
				foreach ( $libraries as $lib ) {
					self::register_api_library( $lib );
				}
			}

			print_r( self::get_api_libs() );
		}

		/**
		 * Get the registerd API libraries
		 *
		 * @return array
		 */
		public static function get_api_libs() {
			return self::$api_libs;
		}

		/**
		 * Register a new API library to this class
		 *
		 * @param $name
		 *
		 * @return bool
		 */
		private static function register_api_library( $name ) {
			$name      = strtolower( $name );
			$classname = 'Yoast_Api_' . ucfirst( $name );
			$classpath = 'class-api-' . $name . '.php';

			self::$api_libs[$name] = array(
				'name'      => $name,
				'classname' => $classname,
				'classpath' => $classpath,
			);

			if ( file_exists( $name . '/' . $classpath ) ) {
				require_once( $name . '/' . $classpath );

				if ( class_exists( $classname ) ) {
					self::$instances->$name = new $classname;

					return true;
				}
			}

			return false;
		}

	}

}