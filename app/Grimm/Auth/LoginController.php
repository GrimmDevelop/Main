<?php

namespace Grimm\Auth;

use Controller;
use View;
use Auth;
use Input;
use Redirect;

class LoginController extends Controller
{
    public function loginForm()
    {
        return View::make('login.loginform');
    }

    public function authenticate()
    {
        $credentials = array(
            'username' => Input::get('username'),
            'password' => Input::get('password')
        );

        // Validate!!!!

        $remember = (Input::get('remember-me') === 'remember-me');

        if($user = Auth::attempt($credentials, $remember)) {
            return Redirect::intended('admin');
        }
    }

    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
        }

        return Redirect::to('login')->with('auth_msg', 'logout_success');
    }
}