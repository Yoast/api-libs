<?php
/*
 * This class implements the caching mechanism for WordPress
 */
class Google_WPCache extends Google_Cache {

	/**
	 * Retrieves the data for the given key, or false if they
	 * key is unknown or expired
	 *
	 * @param String $key The key who's data to retrieve
	 * @param boolean|int $expiration Expiration time in seconds
	 *
	 */
	public function get($key, $expiration = false) {
		
	}

	/**
	 * Store the key => $value set. The $value is serialized
	 * by this function so can be of any type
	 *
	 * @param string $key Key of the data
	 * @param string $value data
	 */
	public function set($key, $value) {

	}

	/**
	 * Removes the key/data pair for the given $key
	 *
	 * @param String $key
	 */
	public function delete($key) {

	}


}