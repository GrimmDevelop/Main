<?php

namespace Grimm\Controller\Api;

use Grimm\Models\User;
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

        $data = Input::only(array(
            'username', 'password', 'email'
        ));

        $user = new User($data);

        $user->save();
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
            'username', 'password', 'email'
        ));

        $user = User::find($id);

        // ...

        $user->save();
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
