<?php

namespace Sky\Revio;

use \stdClass;

//use Sky\Revio\SoapClientTimeout;
//use Sky\Revio\SoapClient;

/**
 * Class SoapClient
 * @package Sky\Revio
 */
class RevioSoapClient {

	/**
	 * @var string
	 */
	protected $wsdl = '';
	/**
	 * @var string
	 */
	protected $authUsername;
	/**
	 * @var string
	 */
	protected $authPassword;
	/**
	 * @var string
	 */
	protected $authClient;
	/**
	 * @var \SoapClient
	 */
	protected $client;
	/**
	 * @var
	 */
	protected $sessionKey;

	/**
	 *
	 */
	public function __construct() {
		$this->wsdl = \Config::get('revio.wsdl');
		$this->client = app('\Sky\Revio\SoapClient');

		$credentials = Array();
		$credentials['username'] = \Config::get('revio.username');
                $credentials['authClient'] = \Config::get('revio.auth_client');
                $credentials['password'] = \Config::get('revio.password');

		$this->initializeApiCredentials($credentials);
	}

	/**
	 * @param $customerID
	 * @return stdClass
	 * @throws \Exception
	 */
	public function getCustomerInfoByID($customerID){
		$test = $this->makeCall('Customers_Query',
			[
				'CustomerID' => $customerID
			]);
		return $test;
	}

	public function queryProduct($productID){
		return $this->makeCall('Products_Query', ['ProductID' => $productID]);
	}


	public function initializeApiCredentials($credentials){
		if(!isset($credentials['username']) && !isset($credentials['authClient']) && !isset($credentials['password'])){
			throw new Exceptions\InvalidAPICredentials('Invalid API credentials');
		}

		$this->authUsername = $credentials['username'];
		$this->authClient = $credentials['authClient'];
		$this->authPassword = $credentials['password'];
	}



	/**
	 * Make API call to retrieve SessionKey. Since this only needs to be done once, store SessionKey locally for use
	 * in future API calls.
	 *
	 * @return void
	 */
	protected function createSession() {
		$params = [
			'Request' => [
				'Credentials' => [
					'Username' => $this->authUsername,
					'Password' => $this->authPassword,
					'Client' => $this->authClient,
				]
			]
		];
		$method = "Sessions_Create";

		$response = $this->send('Sessions_Create', $params);

		//print_r($response);
		if(!$response->{$method . 'Result'}->Header->Success){
			if ($response->{$method. 'Result'}->Header->Error_Code == "S2005") {
				throw new Exceptions\AuthorizationFailure('H2O SOAP request failed with reason(s): ' . $response->{$method . 'Result'}->Header->Error_Description);
				//print_r($response);
			}
		} else {
			$this->sessionKey = $response->Sessions_CreateResult->SessionKey;
		}
	}


	/**
	 * Make authenticated API call.
	 *
	 * @param $method
	 * @param array $methodParams
	 * @return stdClass
	 */
	protected function makeCall($method, array $methodParams = []) {
		if(!$this->haveSession()) {
			$this->createSession();
		}

		$params['Request'] = array_merge(['Session' => ['SessionKey' => $this->sessionKey]], $methodParams);

		$response = $this->send($method, $params);
		//print_r($response);

		if(!$response->{$method . 'Result'}->Header->Success){
			throw new Exceptions\SoapRequestFailed('H2O SOAP request failed with reason(s): ' . $response->{$method . 'Result'}->Header->Message);
		}

		return $response;
	}

	/**
	 * Make raw API call.
	 *
	 * @param $method
	 * @param array $params
	 * @return mixed
	 */
	protected function send($method, array $params) {
		return $this->client->$method((object) $params);
	}

	/**
	 * Return true if session has already been created, false if not.
	 *
	 * @return bool
	 */
	protected function haveSession() {
		return !is_null($this->sessionKey);
	}
}
