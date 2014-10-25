<?php

Route::group(array('prefix' => 'admin', 'before' => 'grimm_auth'), function () {
    Route::get('/', function () {
        return View::make('admin.index');
    });

    Route::post('import/letters', 'Grimm\Controller\Api\ImportController@startLetterImport');
    Route::post('import/locations', 'Grimm\Controller\Api\ImportController@startLocationImport');
    Route::post('import/persons', 'Grimm\Controller\Api\ImportController@startPersonImport');

    Route::get('partials/{file}', 'Grimm\Controller\Admin\PartialsController@load');

    Route::post('assign/from', 'Grimm\Controller\Admin\AssignController@from');
    Route::post('assign/to', 'Grimm\Controller\Admin\AssignController@to');
    Route::post('assign/senders', 'Grimm\Controller\Admin\AssignController@senders');
    Route::post('assign/receivers', 'Grimm\Controller\Admin\AssignController@receivers');

    Route::post('assign/cache/location', 'Grimm\Controller\Admin\AssignController@cacheLocation');
    Route::post('assign/cache/person', 'Grimm\Controller\Admin\AssignController@cachePerson');

    Route::get('mailing/list', 'Grimm\Controller\Admin\MailingListController@mailList');

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
