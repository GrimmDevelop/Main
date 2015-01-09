<?php

namespace Grimm\Controller\Api;

use Grimm\Auth\Models\User;
use Input;
use Sentry;
use Validator;

class UserController extends \Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return User::with('groups')->get();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make(
            Input::only(['username', 'password', 'password_confirmation', 'email', 'activated']),
            [
                'username' => 'required|min:5',
                'email' => 'required|email|unique:users',
                'password' => 'required|confirmed',
                'activated' => 'required'
            ]
        );

        if ($validator->fails()) {
            $res = [];
            foreach ($validator->messages()->toArray() as $field) {
                $res = array_merge($res, $field);
            }

            return \Response::json(array('error' => array('message' => $res)), 200);
        }

        $user = Sentry::createUser(
            Input::only(['username', 'first_name', 'last_name', 'password', 'email', 'activated'])
        );

        return \Response::json(array('success' => array('message' => 'User created')), 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return User::find($id)->load('groups')->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
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
        if ($data['password'] != '') {
            if ($data['password'] != $data['password_confirmation']) {
                return \Response::json(array('error' => array('message' => "Password and password confirmation didn\'t match.")), 500);
            }

            $user->password = $data['password'];
        }

        if (!$user->activated && $data['activated']) {
            $this->activation_code = null;
            $user->activated = true;
            $user->activated_at = new \DateTime();
        } else if ($user->activated && !$data['activated']) {
            $user->activated = false;
        }

        $user->email = $data['email'];

        if ($user->save()) {
            return \Response::json(array('success' => array('message' => "User successfully saved!")));
        } else {
            return \Response::json(array('error' => array('message' => "Upps, something went wrong while saving!")), 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        User::destroy($id);
    }


}
