<?php

namespace Grimm\Controller\Api;

use Grimm\Models\Location;
use Input;
use Sentry;

class LocationController extends \Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return Location::all();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('locations.create'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        $data = Input::only(array(
            // 'username', 'password', 'email'
        ));

        $location = new Location($data);

        // $user->save();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('locations.create'))) {
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
        return Location::find($id)->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('locations.edit'))) {
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
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('locations.edit'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        $location = Location::find($id);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if(!(Sentry::check() && Sentry::getUser()->hasPermission('locations.delete'))) {
            return \App::abort(403, 'Unauthorized action.');
        }

        Location::destroy($id);
    }


}
