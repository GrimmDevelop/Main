<?php

Route::group(['prefix' => 'user'], function () {
    Route::get('display/views', 'Grimm\Controller\DisplayController@views');
    Route::get('display/views/{section}', 'Grimm\Controller\DisplayController@views');
    Route::get('display/defaultView/{section}', 'Grimm\Controller\DisplayController@defaultView');

    Route::put('display/changeView/{section}', 'Grimm\Controller\DisplayController@changeView');
});
