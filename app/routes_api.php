<?php

/*
GET         /resource   index   resource.index
POST        /resource   store   resource.store
GET         /resource/{resource}    show    resource.show
PUT/PATCH   /resource/{resource}    update  resource.update
DELETE      /resource/{resource}    destroy     resource.destroy
*/

Route::group(['prefix' => 'api'], function()
{
    Route::get('/',                     ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\ApiController@overview']);
    Route::post('mailinglist',          ['before' => 'csrf',                'uses' => 'Grimm\Controller\ApiController@addToMaillingList']);
    Route::get('version',               ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\InfoController@version']);

    // Letter api
    Route::get('letters/stream',        ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\LetterController@stream']);
    Route::get('letters',               ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\LetterController@index']);

    Route::get('letters/trashed',       ['before' => 'grimm_access:letters.destroy','uses' => 'Grimm\Controller\Api\LetterController@trashed']);
    Route::post('letters/restore',      ['before' => 'grimm_access:letters.destroy','uses' => 'Grimm\Controller\Api\LetterController@restore']);
    Route::get('letters/{id}',          ['before' => 'grimm_access:none',           'uses' => 'Grimm\Controller\Api\LetterController@show']);
    Route::post('letters',              ['before' => 'grimm_access:letters.create', 'uses' => 'Grimm\Controller\Api\LetterController@store']);
    Route::put('letters/{id}',          ['before' => 'grimm_access:letters.edit',   'uses' => 'Grimm\Controller\Api\LetterController@update']);
    Route::delete('letters/{id}',       ['before' => 'grimm_access:letters.destroy','uses' => 'Grimm\Controller\Api\LetterController@destroy']);

    Route::put('letters/assign/{mode}', ['before' => 'grimm_access:letters.edit',   'uses' => 'Grimm\Controller\Api\LetterController@assign']);
    Route::delete('letters/assign/{mode}',['before' => 'grimm_access:letters.edit', 'uses' => 'Grimm\Controller\Api\LetterController@unassign']);

    // Location api
    Route::get('locations',             ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\LocationController@index']);
    Route::get('locations/{id}',        ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\LocationController@show']);
    Route::post('locations/search',     ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\LocationController@search']);

    Route::delete('locations/{id}',     ['before' => 'grimm_access:locations.delete', 'uses' => 'Grimm\Controller\Api\LocationController@destroy']);

    // Person api
    Route::get('persons',               ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\PersonController@index']);
    Route::post('persons/search',       ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\PersonController@search']);
    Route::get('persons/codes',         ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\PersonController@codes']);
    Route::get('persons/{id}',          ['before' => 'grimm_access:none',   'uses' => 'Grimm\Controller\Api\PersonController@show']);

    Route::post('persons',              ['before' => 'grimm_access:persons.create', 'uses' => 'Grimm\Controller\Api\PersonController@store']);
    Route::put('persons/{id}',          ['before' => 'grimm_access:persons.edit',   'uses' => 'Grimm\Controller\Api\PersonController@update']);
    Route::delete('persons/{id}',       ['before' => 'grimm_access:persons.delete', 'uses' => 'Grimm\Controller\Api\PersonController@destroy']);

    // User api
    Route::get('users',                 ['before' => 'grimm_access:users.view',     'uses' => 'Grimm\Controller\Api\UserController@index']);
    Route::post('users',                ['before' => 'grimm_access:users.create',   'uses' => 'Grimm\Controller\Api\UserController@store']);
    Route::put('users/{id}',            ['before' => 'grimm_access:users.edit',     'uses' => 'Grimm\Controller\Api\UserController@update']);
    Route::get('users/{id}',            ['before' => 'grimm_access:users.view',     'uses' => 'Grimm\Controller\Api\UserController@show']);
    Route::delete('users/{id}',         ['before' => 'grimm_access:users.delete',   'uses' => 'Grimm\Controller\Api\UserController@destroy']);

    Route::get('groups',                ['before' => 'grimm_access:users.view',     'uses' => 'Grimm\Controller\Api\GroupController@index']);
    //Route::post('groups',             ['before' => 'grimm_access:users.create',   'uses' => 'Grimm\Controller\Api\GroupController@store']);
    //Route::put('groups/{id}',         ['before' => 'grimm_access:users.edit',     'uses' => 'Grimm\Controller\Api\GroupController@update']);
    Route::get('groups/{id}',           ['before' => 'grimm_access:users.view',     'uses' => 'Grimm\Controller\Api\GroupController@show']);
    Route::delete('groups/{id}',        ['before' => 'grimm_access:users.delete',   'uses' => 'Grimm\Controller\Api\GroupController@destroy']);

    // Cluster api
    Route::get('cluster/changes',       ['before' => 'grimm_access:none',           'uses' => 'Grimm\Controller\Api\ClusterController@changes']);
    Route::post('cluster/publish',      ['before' => 'grimm_access:cluster.publish','uses' => 'Grimm\Controller\Api\ClusterController@publish']);
    Route::post('cluster/subscribe',    ['before' => 'grimm_access:none',           'uses' => 'Grimm\Controller\Api\ClusterController@subscribe']);
});
