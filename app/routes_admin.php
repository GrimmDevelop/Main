<?php

Route::group(array('prefix' => 'admin', 'before' => 'grimm_auth'), function() {
    Route::get('/', function() {
        return View::make('admin.index');
    });

    // Route::resource('users', 'Grimm\Controller\Admin\UserController');

    Route::get('import', function() {
        Queue::push('Grimm\Controller\Queue\Letter@importLetters', array('source' => storage_path() . '/dbase.dbf'));
    });

    Route::get('partials/{file}', 'Grimm\Controller\Admin\PartialsController@load');
});

Route::get('login', array('as' => 'login', 'uses' => 'Grimm\Auth\LoginController@loginForm'));
Route::post('login/auth', array('before' => 'crsf', 'uses' => 'Grimm\Auth\LoginController@authenticate'));
Route::get('logout', array('as' => 'logout', 'uses' => 'Grimm\Auth\LoginController@logout'));
