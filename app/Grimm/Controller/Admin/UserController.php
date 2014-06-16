<?php

namespace Grimm\Controller\Admin;

use Grimm\Auth\Models\User;
use Sentry;
use View;

class UserController extends \Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        if(!Sentry::getUser()->hasPermission('users.view')) {
            return \Redirect::to('admin')->with('error', 'auth.permission');
        }
		return View::make('admin.users.index', array(
            'users' => User::all()
        ));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
        if(!Sentry::getUser()->hasPermission('users.view')) {
            return \Redirect::to('admin')->with('error', 'auth.permission');
        }
        return View::make('admin.users.show', array(
            'user' => User::findOrFail($id)
        ));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        if(!Sentry::getUser()->hasPermission('users.edit')) {
            return \Redirect::to('admin')->with('error', 'auth.permission');
        }

        return View::make('admin.users.edit', array(
            'user' => User::findOrFail($id)
        ));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        if(!Sentry::getUser()->hasPermission('users.edit')) {
            return \Redirect::to('admin')->with('error', 'auth.permission');
        }

        $user = User::findOrFail($id);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        if(!Sentry::getUser()->hasPermission('users.delete')) {
            return \Redirect::to('admin')->with('error', 'auth.permission');
        }

        return User::destroy($id);
	}


}
