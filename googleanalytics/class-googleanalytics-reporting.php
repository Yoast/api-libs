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
	 * @param        string secret
	 *
	 * @return array|null
	 */
	public function do_request( $target_url, $scope, $access_token, $secret ) {
		$gdata     = $this->get_gdata( $scope, $access_token, $secret );
		$response  = $gdata->get( $target_url );
		$http_code = wp_remote_retrieve_response_code( $response );
		$response  = wp_remote_retrieve_body( $response );

		if ( $http_code == 200 ) {
			return array(
				'response' => array( 'code' => $http_code ),
				'body_raw' => $response,
				'body'     => $this->parse_response( json_decode( $response ) ),
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
	 *
	 * @return mixed
	 */
	public function parse_response( $raw_data ) {
		$data = array();

		if ( isset( $raw_data->rows ) ) {
			if ( is_array( $raw_data->rows ) ) {
				foreach ( $raw_data->rows as $key => $item ) {
					$data[] = array(
						'date'  => (int) $this->format_ga_date( $item[0] ),
						'value' => (int) $item[1],
					);
				}
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
		$year  = substr( $date, 0, 2 );
		$month = substr( $date, 4, 2 );
		$day   = substr( $date, 6, 2 );

		return strtotime( $year . '-' . $month . '-' . $day );
	}

}