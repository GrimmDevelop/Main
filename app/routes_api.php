<?php

Route::group(array('prefix' => 'api'), function()
{

    Route::resource('letters', 'Grimm\Controller\Api\LetterController');
    Route::get('letters/{year}/{month}/{day}', 'Grimm\Controller\Api\LetterController@lettersChangedAfter');

    Route::resource('persons', 'Grimm\Controller\Api\PersonController');
    Route::get('persons/{year}/{month}/{day}', 'Grimm\Controller\Api\PersonController@personsChangedAfter');

    Route::resource('users', 'Grimm\Controller\Api\UserController');

});
