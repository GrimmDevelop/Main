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

Route::get('/api', 'Grimm\Controller\ApiController@overview');

App::missing(function ($exception) {
    return Redirect::to('/')->withErrors(
        array(
            '404' => $exception->getMessage()
        )
    );
});
