<?php

namespace Grimm\Controller\Api;

use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Grimm\Auth\Models\User;
use Input;
use Sentry;

class UserController extends \Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.view'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        return User::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.create'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.create'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        $data = Input::only(array(
            'username', 'password', 'email'
        ));

        $user = new User($data);


        // $user->save();

        return \Response::json(array('message' => 'Not implemented.'), 404);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.view'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        return User::find($id)->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.edit'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        return null;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.edit'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        $data = Input::only(array(
            'first_name',
            'last_name',
            'username',
            'password',
            'password_confirmation',
            'email',
            'activated'
        ));

        $user = User::find($id);

        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];

        $user->username = $data['username'];
        if($data['password'] != '') {
            if($data['password'] != $data['password_confirmation']) {
                return \Response::json(array('message' => "Password and password confirmation didn\'t match."), 500);
            }

            $user->password = $data['password'];
        }

        if(!$user->activated && $data['activated']) {
            $this->activation_code = null;
            $user->activated = true;
            $user->activated_at = new \DateTime();
        } else if($user->activated && !$data['activated']) {
            $user->activated = false;
        }

        $user->email = $data['email'];

        if($user->save()) {
            return \Response::json(array('message' => "User successfully saved!"));
        } else {
            return \Response::json(array('message' => "Upps, something went wrong while saving!"), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('users.delete'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        User::destroy($id);
    }


}
