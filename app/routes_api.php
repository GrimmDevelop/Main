<?php

Route::group(array('prefix' => 'api'), function()
{
    Route::get('version', 'Grimm\Controller\Api\InfoController@version');

    Route::get('letters/stream', 'Grimm\Controller\Api\LetterController@stream');
    Route::resource('letters', 'Grimm\Controller\Api\LetterController');
    Route::get('letters/{year}/{month}/{day}', 'Grimm\Controller\Api\LetterController@lettersChangedAfter');
    Route::put('letters/assign/{mode}', 'Grimm\Controller\Api\LetterController@assign');

    Route::resource('locations', 'Grimm\Controller\Api\LocationController');
    Route::post('locations/search', 'Grimm\Controller\Api\LocationController@search');

    Route::resource('persons', 'Grimm\Controller\Api\PersonController');
    Route::get('persons/{year}/{month}/{day}', 'Grimm\Controller\Api\PersonController@personsChangedAfter');
    Route::post('persons/search', 'Grimm\Controller\Api\PersonController@search');

    Route::resource('users', 'Grimm\Controller\Api\UserController');
    Route::resource('groups', 'Grimm\Controller\Api\GroupController');

});
