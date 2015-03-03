<?php

namespace Grimm;

use Grimm\Converter\Letter;
use Grimm\Converter\Location;
use Grimm\Converter\Person;
use Grimm\Logging\UserActionLogger;
use Grimm\Search\EloquentFilterService;
use Grimm\Search\EloquentSearchService;
use Grimm\Search\FilterService;
use Grimm\Search\SearchService;
use Grimm\Transformer\LetterRecord;
use Grimm\Transformer\LocationRecord;
use Grimm\Transformer\PersonRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\ServiceProvider;

class BootServiceProvider extends ServiceProvider {

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
        /**
         * TODO: revert back to data object {"d": []}
         */
        $this->app->after(function($request, $response)
        {
            if($response instanceof JsonResponse) {
                $json = ")]}',\n" . $response->getContent();
                return $response->setContent($json);
            }
        });

        $this->app->bind('grimm.unauthorized', function ()
        {
            return \Response::json('Grimm Unauthorized', 401);
        });

        $this->app->bind(Letter::class, function ()
        {
            return new Letter($this->app->make(LetterRecord::class));
        });

        $this->app->bind(Location::class, function ()
        {
            return new Location($this->app->make(LocationRecord::class));
        });

        $this->app->bind(Person::class, function ()
        {
            return new Person($this->app->make(PersonRecord::class));
        });

        $this->app->bind(UserActionLogger::class, function ()
        {
            return new UserActionLogger();
        });

        $this->app->bind(SearchService::class, function() {
            return $this->app->make(EloquentSearchService::class);
        });

        $this->app->bind(FilterService::class, function() {
            return $this->app->make(EloquentFilterService::class);
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
