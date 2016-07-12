<?php

namespace Lokielse\LaravelMNS;

use Illuminate\Support\ServiceProvider;
use Lokielse\LaravelMNS\Connectors\MNSConnector;
use Lokielse\LaravelMNS\Console\MNSFlushCommand;

class LaravelMNSServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;


    public function boot()
    {
        $this->registerConnector($this->app['queue']);

        $this->commands('command.queue.mns.flush');
    }


    /**
     * Add the connector to the queue drivers.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }


    /**
     * Register the MNS queue connector.
     *
     * @param \Illuminate\Queue\QueueManager $manager
     *
     * @return void
     */
    protected function registerConnector($manager)
    {
        $manager->addConnector('mns', function () {
            return new MNSConnector();
        });
    }


    private function registerCommand()
    {
        $this->app->singleton('command.queue.mns.flush', function () {
            return new MNSFlushCommand();
        });
    }
}
