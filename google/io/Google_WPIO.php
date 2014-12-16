<?php
/*
 * Copyright 2010 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * WP based implementation of apiIO.
 *
 */

require_once 'Google_CacheParser.php';

class Google_WPIO extends Google_IO {
	private static $ENTITY_HTTP_METHODS = array("POST" => null, "PUT" => null);
	private static $HOP_BY_HOP = array(
		'connection', 'keep-alive', 'proxy-authenticate', 'proxy-authorization',
		'te', 'trailers', 'transfer-encoding', 'upgrade');

	/**
	 * Perform an authenticated / signed apiHttpRequest.
	 * This function takes the apiHttpRequest, calls apiAuth->sign on it
	 * (which can modify the request in what ever way fits the auth mechanism)
	 * and then calls apiWPIO::makeRequest on the signed request
	 *
	 * @param Google_HttpRequest $request
	 * @return Google_HttpRequest The resulting HTTP response including the
	 * responseHttpCode, responseHeaders and responseBody.
	 */
	public function authenticatedRequest(Google_HttpRequest $request) {
		$request = Google_Client::$auth->sign($request);
		return $this->makeRequest($request);
	}

	/**
	 * Execute a apiHttpRequest
	 *
	 * @param Google_HttpRequest $request the http request to be executed
	 * @return Google_HttpRequest http request with the response http code, response
	 * headers and response body filled in
	 */
	public function makeRequest(Google_HttpRequest $request) {

		// First, check to see if we have a valid cached version.
		$cached = $this->getCachedRequest($request);
		if ($cached !== false) {
			if (!$this->checkMustRevaliadateCachedRequest($cached, $request)) {
				return $cached;
			}
		}

		if (array_key_exists($request->getRequestMethod(),
			self::$ENTITY_HTTP_METHODS)) {
			$request = $this->processEntityRequest($request);
		}

		$params = array(
			'user-agent' => $request->getUserAgent()
		);

		if ($request->getPostBody()) {
			$params['body'] = $request->getPostBody();
		}

		$requestHeaders = $request->getRequestHeaders();
		if ($requestHeaders && is_array($requestHeaders)) {
			$params['headers'] = $requestHeaders;
		}


		switch( $request->getRequestMethod() ) {
			case 'POST' :
				$response = wp_remote_post( $request->getUrl(), $params );
				break;

			case 'GET' :
				$response = wp_remote_get( $request->getUrl(), $params );
				break;
		}

		$responseBody    = wp_remote_retrieve_body( $response );
		$respHttpCode    = wp_remote_retrieve_response_code( $response );
		$responseHeaders = wp_remote_retrieve_headers( $response );

		if ($respHttpCode == 304 && $cached) {
			// If the server responded NOT_MODIFIED, return the cached request.
			$this->updateCachedRequest($cached, $responseHeaders);
			return $cached;
		}

		// Fill in the apiHttpRequest with the response values
		$request->setResponseHttpCode($respHttpCode);
		$request->setResponseHeaders($responseHeaders);

		$request->setResponseBody($responseBody);
		// Store the request in cache (the function checks to see if the request
		// can actually be cached)
		$this->setCachedRequest($request);
		// And finally return it

		return $request;
	}

	/**
	 * Set options that update default behavior.
	 *
	 * @param array $optParams Multiple options used by a session.
	 */
	public function setOptions($optParams) {

	}

}
