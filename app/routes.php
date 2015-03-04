<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

require_once('routes_admin.php');
require_once('routes_api.php');
require_once('routes_user.php');

Route::get('/', function () {
    return Redirect::route('search');
});

Route::get('partials/{file}', 'Grimm\Controller\PartialsController@load');

Route::group(['prefix' => 'search'], function () {
    Route::get('/', ['uses' => 'Grimm\Controller\SearchController@searchForm', 'as' => 'search']);
    Route::post('/', 'Grimm\Controller\SearchController@searchResult');

    Route::get('codes', 'Grimm\Controller\SearchController@codes');
    Route::get('displayed/codes', 'Grimm\Controller\SearchController@displayedCodes');
    Route::get('displayed/codes/short', 'Grimm\Controller\SearchController@displayedCodesShort');

    // filter api
    Route::get('filters', 'Grimm\Controller\SearchController@loadFilters');
    Route::get('filters/{get}', 'Grimm\Controller\SearchController@loadFilter');
    Route::post('filters', 'Grimm\Controller\SearchController@newFilter');
    Route::put('filters', 'Grimm\Controller\SearchController@saveFilter');
    Route::put('filters/public', 'Grimm\Controller\SearchController@publicFilter');
    Route::delete('filters/{id}', 'Grimm\Controller\SearchController@deleteFilter');

    Route::post('distanceMap', ['before' => 'grimm_auth', 'uses' => 'Grimm\Controller\DistanceMapController@computeDistanceMap']);

    Route::get('/dateRange', 'Grimm\Controller\SearchController@dateRange');

    Route::get('/find/{id}', 'Grimm\Controller\SearchController@findById');
    Route::get('/code/{code}', 'Grimm\Controller\SearchController@findByCode');

    // TODO: set flag to display search results on page load
    Route::get('/{filterKey}', 'Grimm\Controller\SearchController@searchForm');


});

App::missing(function ($exception) {
    return Redirect::route('search')->withErrors(
        array(
            '404' => $exception->getMessage()
        )
    );
});
