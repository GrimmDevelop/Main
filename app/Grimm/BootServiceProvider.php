<?php

namespace Grimm;

use Grimm\Cluster\ClusterService;
use Grimm\Cluster\EloquentClusterService;
use Grimm\Cluster\EloquentSubscriber;
use Grimm\Cluster\Subscriber;
use Grimm\Converter\Letter;
use Grimm\Converter\Location;
use Grimm\Converter\Person;
use Grimm\Import\Persistence\LetterRecordPersistence;
use Grimm\Import\Persistence\PersonRecordPersistence;
use Grimm\Letter\EloquentLetterService;
use Grimm\Letter\LetterService;
use Grimm\Location\EloquentLocationService;
use Grimm\Location\LocationService;
use Grimm\Logging\UserActionLogger;
use Grimm\Person\EloquentPersonService;
use Grimm\Person\PersonService;
use Grimm\Queue\QueueJobManager;
use Grimm\Queue\Jobs\Letter as LetterJob;
use Grimm\Queue\Jobs\Person as PersonJob;
use Grimm\Search\Compiler\EloquentFilterCompiler;
use Grimm\Search\DateToCodeConverter;
use Grimm\Search\EloquentFilterService;
use Grimm\Search\EloquentSearchService;
use Grimm\Search\FilterQueryGenerator;
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

        $this->app->bind(LetterJob::class, function() {
            return new LetterJob(
                $this->app->make(Letter::class),
                $this->app->make(QueueJobManager::class),
                $this->app->make(LetterRecordPersistence::class)
            );
        });

        $this->app->bind(PersonJob::class, function() {
            return new PersonJob(
                $this->app->make(Person::class),
                $this->app->make(QueueJobManager::class),
                $this->app->make(PersonRecordPersistence::class)
            );
        });

        $this->app->bind(UserActionLogger::class, function ()
        {
            return new UserActionLogger();
        });

        $this->app->bind(FilterQueryGenerator::class, function() {
            return new FilterQueryGenerator(
                $this->app->make(DateToCodeConverter::class),
                new EloquentFilterCompiler(\Grimm\Models\Letter::query())
            );
        });

        $this->app->bind(SearchService::class, function() {
            return $this->app->make(EloquentSearchService::class);
        });

        $this->app->bind(FilterService::class, function() {
            return $this->app->make(EloquentFilterService::class);
        });

        $this->app->bind(LetterService::class, function() {
            return $this->app->make(EloquentLetterService::class);
        });

        $this->app->bind(PersonService::class, function() {
            return $this->app->make(EloquentPersonService::class);
        });

        $this->app->bind(LocationService::class, function() {
            return $this->app->make(EloquentLocationService::class);
        });

        $this->app->bind(ClusterService::class, function() {
            return $this->app->make(EloquentClusterService::class);
        });

        $this->app->bind(Subscriber::class, function() {
            return $this->app->make(EloquentSubscriber::class);
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
