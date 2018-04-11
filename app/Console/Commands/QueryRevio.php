<?php

namespace App\Console\Commands;


use Illuminate\Console\Command;

use Sky\Revio\RevioSoapClient;

class QueryRevio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'query:revio {customer_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'revio';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
	//print "handle call\n";
	//config()->set('is_unit_test', true);
	//config()->set('soap_client_class_name', '\Sky\Revio\UnitTests\Helpers\SoapClientTimeout');
	//config()->set('soap_client_class_name', '\Sky\Revio\UnitTests\Helpers\SoapClientAuthorizationFailure');

	$customer_id = $this->argument('customer_id');

	//print "customer id is $customer_id\n";

	$revio = new RevioSoapClient();
	try {
		$result = $revio->getCustomerInfoByID($customer_id);
		print_r($result);
	} catch(\Sky\Revio\Exceptions\AuthorizationFailure $e) {
		print "Authorization Failure Caught\n";
	} catch(\Sky\Revio\Exceptions\Timeout $e) {
		print "Timeout Caught\n";
		//print "Caught\n";
	}
        //
    }
}
