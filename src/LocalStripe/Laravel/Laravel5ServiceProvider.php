<?php namespace Kumuwai\LocalStripe\Laravel;

use Illuminate\Support\ServiceProvider;

use Kumuwai\LocalStripe\Connector;
use Kumuwai\LocalStripe\ParameterParser;
use Kumuwai\LocalStripe\Pusher;
use Kumuwai\LocalStripe\Fetcher;
use Kumuwai\LocalStripe\LocalStripe;


class Laravel5ServiceProvider extends ServiceProvider 
{
    protected $defer = true;    // only load if/when needed

    public function boot()
    {
        $this->publishes([
            realpath(__DIR__.'/../migrations') => $this->app->databasePath().'/migrations'
        ], 'migrations');

        $this->publishes([
            realpath(__DIR__.'/../config/config.php') => config_path('local-stripe.php')
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'local-stripe');

        $this->app['local-stripe'] = $this->app->share(function($app){

            $secret = $app['config']->get(
                'local-stripe.keys.secret',
                getenv('STRIPE_SECRET')
            );

            $connector = new Connector;
            $connector->setApiKey($secret);

            $parser = new ParameterParser;
            $pusher = new Pusher($connector, $parser);
            $fetcher = new Fetcher($connector);

            return new LocalStripe($connector, $pusher, $fetcher);
        });
    }

    public function provides()
    {
        return array('local-stripe');
    }

}

