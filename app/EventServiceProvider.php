<?php

namespace Grimm;

class EventServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        \Event::listen('letter.created', function ()
        {

        });

        \Event::listen('letter.saved', function ()
        {

        });

        \Event::listen('letter.deleted', function ()
        {

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
