<?php

Route::group(array('prefix' => 'admin', 'before' => 'grimm_auth'), function() {
    Route::get('/', function() {
        return View::make('grimm::test');
    });
});

Route::get('login', array('as' => 'login', 'uses' => 'Grimm\Auth\LoginController@loginForm'));
Route::post('login/auth', array('before' => 'crsf', 'uses' => 'Grimm\Auth\LoginController@authenticate'));
Route::get('logout', array('as' => 'logout', 'uses' => 'Grimm\Auth\LoginController@logout'));
