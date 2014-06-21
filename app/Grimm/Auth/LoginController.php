<?php

namespace Grimm\Auth;

use Controller;
use View;
use Sentry;
use Input;
use Redirect;

use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Throttling\UserBannedException;

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

        try {
            $user = Sentry::authenticate($credentials, $remember);

            return Redirect::intended('/');
        } catch (LoginRequiredException $e) {
            return Redirect::to('login')->with('auth_error', 'login_required');
        } catch (PasswordRequiredException $e) {
            return Redirect::to('login')->with('auth_error', 'password_required');
        } catch (WrongPasswordException $e) {
            return Redirect::to('login')->with('auth_error', 'wrong_password');
        } catch (UserNotFoundException $e) {
            return Redirect::to('login')->with('auth_error', 'user_not_found');
        } catch (UserNotActivatedException $e) {
            return Redirect::to('login')->with('auth_error', 'user_not_active');
        } catch (UserSuspendedException $e) {
            return Redirect::to('login')->with('auth_error', 'user_suspended');
        } catch (UserBannedException $e) {
            return Redirect::to('login')->with('auth_error', 'user_banned');
        }
    }

    public function logout()
    {
        if (Sentry::check()) {
            Sentry::logout();
        }

        return Redirect::to('login')->with('auth_msg', 'logout_success');
    }
}