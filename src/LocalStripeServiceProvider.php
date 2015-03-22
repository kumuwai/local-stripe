<?php namespace Kumuwai\LocalStripe;

use Illuminate\Support\ServiceProvider;


class LocalStripeServiceProvider extends ServiceProvider 
{

	protected $defer = true;    // only load if/when needed

	public function register()
	{
		$this->app['local-stripe'] = $this->app->share(function($app){
			return new LocalStripe();
		});
	}

	public function provides()
	{
		return array('LocalStripe');
	}

}

