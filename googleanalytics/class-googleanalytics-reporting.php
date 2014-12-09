<?php

class Yoast_Googleanalytics_Reporting {

	/**
	 * Store this instance
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Getting the instance object
	 *
	 * This method will return the instance of itself, if instance not exists, becauses of it's called for the first
	 * time, the instance will be created.
	 *
	 * @return null|Yoast_Google_Analytics
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Doing request to Google Analytics
	 *
	 * This method will do a request to google and get the response code and body from content
	 *
	 * @param string $target_url
	 * @param string $scope
	 * @param string $access_token
	 * @param        string      secret
	 * @param        string      table,datelist
	 * @param int    $start_date Unix timestamp
	 * @param int    $end_date   Unix timestamp
	 *
	 * @return array|null
	 */
	public function do_api_request( $target_url, $scope, $access_token, $secret, $store_as, $start_date, $end_date ) {
		$gdata     = $this->get_gdata( $scope, $access_token, $secret );
		$response  = $gdata->get( $target_url );
		$http_code = wp_remote_retrieve_response_code( $response );
		$response  = wp_remote_retrieve_body( $response );

		if ( $http_code == 200 ) {
			return array(
				'response' => array( 'code' => $http_code ),
				'body_raw' => $response,
				'body'     => $this->parse_response( json_decode( $response ), $store_as, $start_date, $end_date ),
			);
		} else {
			return array(
				'body_raw'  => $response,
				'response'  => $response,
				'http_code' => $http_code,
				'gdata'     => $gdata,
			);
		}
	}

	/**
	 * Getting WP_GData object
	 *
	 * If not available include class file and create an instance of WP_GDAta
	 *
	 * @param string $scope
	 * @param null   $token
	 * @param null   $secret
	 *
	 * @return WP_GData
	 */
	protected function get_gdata( $scope, $token = null, $secret = null ) {
		$args = array(
			'scope'              => $scope,
			'xoauth_displayname' => 'Google Analytics by Yoast',
		);

		$gdata = new WP_GData( $args, $token, $secret );

		return $gdata;
	}

	/**
	 * Format a response
	 *
	 * @param $raw_data
	 * @param $store_as
	 * @param $start_date
	 * @param $end_date
	 *
	 * @return array
	 */
	private function parse_response( $raw_data, $store_as, $start_date, $end_date ) {
		$data = array();

		if ( $store_as == 'datelist' ) {
			$data_tmp = $this->date_range( strtotime( $start_date ), strtotime( $end_date ) );
			$data     = array_keys( $data_tmp );
		}

		if ( isset( $raw_data->rows ) && is_array( $raw_data->rows ) ) {
			foreach ( $raw_data->rows as $key => $item ) {
				if ( $store_as == 'datelist' ) {
					$data[(int) $this->format_ga_date( $item[0] )] = $this->parse_row( $item );
				} else {
					$data[] = $this->parse_data_row( $item );
				}
			}
		}

		if ( $store_as == 'datelist' ) {
			$data = $this->check_validity_data( $data );
		}

		return $data;
	}

	/**
	 * Check the key on valid unix timestamps and remove invalid keys
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function check_validity_data( $data = array() ) {
		foreach( $data as $key => $value ){
			if(strlen($key)<=5){
				unset($data[$key]);
			}
		}

		return $data;
	}

	/**
	 * Format the GA date value
	 *
	 * @param $date
	 *
	 * @return int
	 */
	private function format_ga_date( $date ) {
		$year  = substr( $date, 0, 4 );
		$month = substr( $date, 4, 2 );
		$day   = substr( $date, 6, 2 );

		return strtotime( $year . '-' . $month . '-' . $day );
	}

	/**
	 * Parse a row and return an array with the correct data rows
	 *
	 * @param $item
	 *
	 * @return array
	 */
	private function parse_row( $item ) {
		if ( isset( $item[2] ) ) {
			return array(
				'date'  => (int) $this->format_ga_date( $item[0] ),
				'value' => (string) $item[1],
				'total' => (int) $item[2],
			);
		} else {
			return (int) $item[1];
		}
	}

	/**
	 * Parse a row for the list storage type
	 *
	 * @param $item
	 *
	 * @return array
	 */
	private function parse_data_row( $item ) {
		return array(
			'name'  => (string) $item[0],
			'value' => (int) $item[1],
		);
	}

	/**
	 * Calculate the date range between 2 dates
	 *
	 * @param        $first
	 * @param        $last
	 * @param string $step
	 * @param string $format
	 *
	 * @return array
	 */
	private function date_range( $first, $last, $step = '+1 day', $format = 'Y-m-d' ) {
		$dates   = array();
		$current = $first;
		$last    = $last;

		while ( $current <= $last ) {
			$dates[] = date( $format, $current );
			$current = strtotime( $step, $current );
		}

		return $dates;
	}

}