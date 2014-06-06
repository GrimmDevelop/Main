<?php

Route::group(array('prefix' => 'api'), function()
{

    Route::resource('letters', 'LetterController');
    Route::get('letters/{year}/{month}/{day}', "LetterController@lettersChangedAfter");

    Route::resource('persons', 'PersonController');
    Route::get('persons/{year}/{month}/{day}', "PersonController@personsChangedAfter");

});
