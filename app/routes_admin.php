<?php

Route::group(array('prefix' => 'admin', 'before' => 'grimm_auth'), function() {
    Route::get('/', function() {
        return View::make('admin.index');
    });

    // Route::resource('users', 'Grimm\Controller\Admin\UserController');

    Route::post('import/letters', 'Grimm\Controller\Api\ImportController@startLetterImport');
    Route::post('import/locations', 'Grimm\Controller\Api\ImportController@startLocationImport');

    Route::get('partials/{file}', 'Grimm\Controller\Admin\PartialsController@load');

    //Route::get('upload', 'Grimm\Controller\Admin\FileController@uploadGet');
    //Route::post('upload', 'Grimm\Controller\Admin\FileController@uploadPost');

    $filesController = 'Grimm\Controller\Admin\Files\Controller';

    Route::get('files/list', $filesController . '@getFolder');

    Route::get('files/upload', $filesController . '@uploadGet');
    Route::post('files/upload', $filesController . '@uploadPost');

    Route::get('files/mkdir', $filesController . '@mkdir');

    Route::get('files/move', $filesController . '@move');
    Route::get('files/rename', $filesController . '@rename');

    Route::get('files/delete', $filesController . '@deleteFile');
    Route::get('files/deleteFolder', $filesController . '@deleteFolder');
});

Route::get('login', array('as' => 'login', 'uses' => 'Grimm\Auth\LoginController@loginForm'));
Route::post('login/auth', array('before' => 'crsf', 'uses' => 'Grimm\Auth\LoginController@authenticate'));
Route::get('logout', array('as' => 'logout', 'uses' => 'Grimm\Auth\LoginController@logout'));

Route::get('files/{path}', 'Grimm\Controller\Admin\Files\Controller@resolveFile')->where('path', '(?:[^<>]*)');
