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

Route::get('/', function () {
    return Redirect::to(URL::to('search'));
});

Route::get('/search', 'Grimm\Controller\SearchController@searchForm');
Route::post('/search', 'Grimm\Controller\SearchController@searchResult');
Route::get('/search/codes', 'Grimm\Controller\SearchController@codes');
Route::get('/search/filters', 'Grimm\Controller\SearchController@loadFilters');
Route::put('/search/filters', 'Grimm\Controller\SearchController@saveFilters');

Route::get('/api', 'Grimm\Controller\ApiController@overview');
Route::post('/api/mailinglist', ['before' => 'csrf', 'uses' => 'Grimm\Controller\ApiController@addToMaillingList']);

App::missing(function ($exception) {
    return Redirect::to('/')->withErrors(
        array(
            '404' => $exception->getMessage()
        )
    );
});
