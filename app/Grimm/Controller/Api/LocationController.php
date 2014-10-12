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
        return Location::orderBy('name')->paginate(abs((int)Input::get('items_per_page', 25)));
    }

    public function search()
    {
        $builder = Location::query();

        if(Input::get('ahead', false)) {
            return $builder->where('name', 'like', \Input::get('name') . '%')->orderBy('population', 'desc')->take(5)->get();
        } else {
            $builder->where('name', \Input::get('name'));
            $builder->orWhere('asciiname', \Input::get('name'));

            if (Input::get('in_alternate_names', false)) {
                $builder->orWhere('alternatenames', 'like', '%,' . \Input::get('name') . ',%');
            }
        }

        $result = $builder->paginate(abs((int)Input::get('items_per_page', 25)));

        if ($result->count() > 0) {
            $return = new \stdClass();

            $return->total = $result->getTotal();
            $return->per_page = $result->getPerPage();
            $return->current_page = $result->getCurrentPage();
            $return->last_page = $result->getLastPage();
            $return->from = $result->getFrom();
            $return->to = $result->getTo();
            $return->data = $result->getCollection()->toArray();

            return json_encode($return);
        }

        return \Response::json(array('type' => 'danger', 'message' => 'Location not found'), 404);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('locations.create'))) {
            return \Response::json('Unauthorized action.', 403);
        }

        $data = Input::only(array(// 'username', 'password', 'email'
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
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('locations.create'))) {
            return \Response::json('Unauthorized action.', 403);
        }


    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Location::find($id)->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('locations.edit'))) {
            return \Response::json('Unauthorized action.', 403);
        }

        return null;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('locations.edit'))) {
            return \Response::json('Unauthorized action.', 403);
        }

        $location = Location::find($id);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('locations.delete'))) {
            return \Response::json('Unauthorized action.', 403);
        }

        Location::destroy($id);
    }


}
