<?php namespace Kumuwai\LocalStripe;

use Illuminate\Support\ServiceProvider;


class LocalStripeServiceProvider extends ServiceProvider 
{

    protected $defer = true;    // only load if/when needed

    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/src/database/migrations') => $this->app->databasePath().'/migrations',
        ]);
    }

    public function register()
    {
        $this->app['local-stripe'] = $this->app->share(function($app){

            $connector = new Connector;
            $connector->setApiKey(getenv('STRIPE_SECRET'));

            $parser = new ParameterParser;
            $pusher = new Pusher($connector, $parser);
            $fetcher = new Fetcher($connector);

            return new LocalStripe($connector, $pusher, $fetcher);
        });
    }

    public function provides()
    {
        return array('LocalStripe');
    }

}

