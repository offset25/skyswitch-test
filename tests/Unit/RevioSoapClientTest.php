<?php

namespace Tests\Unit;

use Tests\TestCase;

class RevioSoapClient extends TestCase {

	public function testTimeout() {
		config()->set('is_unit_test', true);
		config()->set('soap_client_class_name', '\Sky\Revio\UnitTests\Helpers\SoapClientTimeout');

		$customer_id = 1;

		$revio = new \Sky\Revio\RevioSoapClient();
		$is_exception_caught = false;
		try {
			$result = $revio->getCustomerInfoByID($customer_id);
		} catch(\Sky\Revio\Exceptions\Timeout $e) {
			//print "Timeout Caught\n";
			$is_exception_caught = true;
		}

		if ($is_exception_caught) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}

	public function testAuthorizationFailure() {
		config()->set('is_unit_test', true);
		config()->set('soap_client_class_name', '\Sky\Revio\UnitTests\Helpers\SoapClientAuthorizationFailure');

		$customer_id = 1;

		$revio = new \Sky\Revio\RevioSoapClient();
		$is_exception_caught = false;
		try {
			$result = $revio->getCustomerInfoByID($customer_id);
		} catch(\Sky\Revio\Exceptions\AuthorizationFailure $e) {
			$is_exception_caught = true;
			//print "Authorization Failure Caught\n";
		}
		if ($is_exception_caught) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}

	public function testSoapClientSoapRequestFailed() {
		config()->set('is_unit_test', true);
		config()->set('soap_client_class_name', '\Sky\Revio\UnitTests\Helpers\SoapClientSoapRequestFailed');

		$customer_id = 1;

		$revio = new \Sky\Revio\RevioSoapClient();
		$is_exception_caught = false;
		try {
			$result = $revio->getCustomerInfoByID($customer_id);
		} catch(\Sky\Revio\Exceptions\SoapRequestFailed $e) {
			$is_exception_caught = true;
			//print "Authorization Failure Caught\n";
		}
		if ($is_exception_caught) {
			$this->assertTrue(true);
		} else {
			$this->assertTrue(false);
		}
	}

}
