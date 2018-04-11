<?php

namespace Sky\Revio\UnitTests\Helpers;

use \stdClass;

/**
 * Class SoapClient
 * @package Sky\Revio
 */
class SoapClientSoapRequestFailed extends \SoapClient {

	public function Customers_Query($params) {

		throw new \Sky\Revio\Exceptions\SoapRequestFailed('Soap Request Failed');
		

		$response = new \Stdclass();
		$response->Customers_QueryResult = new \Stdclass();
		$response->Customers_QueryResult->Header = new \Stdclass();
		$response->Customers_QueryResult->Header->Message = "Success";
		$response->Customers_QueryResult->Header->Success = true;
		return $response;
	}

	public function Sessions_Create($params) {
		throw new \Sky\Revio\Exceptions\SoapRequestFailed('Soap Request Failed');

		$response = new \Stdclass();
		$response->Sessions_CreateResult = new \Stdclass();
		$response->Sessions_CreateResult->Header = new \Stdclass();
		$response->Sessions_CreateResult->Header->Success = true;
		$response->Sessions_CreateResult->Header->Message = "Success";
		$response->Sessions_CreateResult->SessionKey = "dummy";
		return $response;
	}

	public function Products_Query($params) {
		throw new \Sky\Revio\Exceptions\SoapRequestFailed('Soap Request Failed');

		$response = new \Stdclass();
		$response->Products_QueryResult = new \Stdclass();
		$response->Products_QueryResult->Header = new \Stdclass();
		$response->Products_QueryResult->Header->Success = true;
		$response->Products_QueryResult->Header->Message = "Success";
		return $response;
	}



	/*
	public function __doRequest($request, $location, $action, $version, $one_way = FALSE) {
		// Call via parent because we require no timeout
		$response = parent::__doRequest($request, $location, $action, $version, $one_way);
		return $response;
		//throw new Exception(curl_error($curl));
	}
	*/
}
