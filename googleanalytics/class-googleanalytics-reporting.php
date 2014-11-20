<?php

class Yoast_Googleanalytics_Reporting {

	/**
	 * Store this instance
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Get instance, construct itself
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public function get_instance( $name ) {
		if ( isset( self::$instances[$name] ) ) {
			return self::$instances[$name];
		}

		return false;
	}



}