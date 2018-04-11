<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SoapClientServiceProvider extends ServiceProvider
{


	  protected $defer = true;
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
	// default soap class to use
	config()->set('soap_client_class_name', '\SoapClient');


	$this->app->bind('\Sky\Revio\SoapClient', function ($app) {
                //print "binded\n";
		//print_r($app);
                //return = new SoapClientTimeout($this->wsdl, array('trace' => 1));
		$is_unit_test = config()->get('is_unit_test');
		$class_name = config()->get('soap_client_class_name');
		//print "is unit test is $is_unit_test\n";
                //$wsdl = 'http://api.myh2o.com/v20/default.asmx?WSDL';
		$wsdl = \Config::get('revio.wsdl');

		//print "wsdl $wsdl\n";
		//print "class name is $class_name\n";
		//$class_name = "\SoapClient";
		$soap_client = new $class_name($wsdl, array('trace' => 1));
		//print_r($soap_client);



                return $soap_client;
        });
        //
    }
}
