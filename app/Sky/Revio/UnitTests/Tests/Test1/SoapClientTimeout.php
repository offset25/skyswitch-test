<?php

namespace Sky\Revio\UnitTests\Test1;

use \stdClass;

/**
 * Class SoapClient
 * @package Sky\Revio
 */
class SoapClientTimeout extends \SoapClient {

	public function __doRequest($request, $location, $action, $version, $one_way = FALSE) {
		throw new Exception("Timeout");
	}
}
