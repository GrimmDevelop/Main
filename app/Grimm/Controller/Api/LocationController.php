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
        $result = Location::orderBy('name')->paginate(abs((int) Input::get('items_per_page', 25)));

        $return = new \stdClass();

        $return->total = $result->getTotal();
        $return->per_page = $result->getPerPage();
        $return->current_page = $result->getCurrentPage();
        $return->last_page = $result->getLastPage();
        $return->from = $result->getFrom();
        $return->to = $result->getTo();
        $return->countries = Location::selectRaw("DISTINCT country_code")->orderBy('country_code')->get()->lists('country_code');
        $return->data = $result->getCollection()->toArray();

        return json_encode($return);
    }

    public function search()
    {
        $builder = Location::query();

        if (Input::get('ahead', false))
        {
            return $builder->where('name', 'like', \Input::get('name') . '%')->orderBy('population', 'desc')->take(5)->get();
        } else
        {
            $builder->where('name', \Input::get('name'));
            $builder->orWhere('asciiname', \Input::get('name'));

            if (Input::get('in_alternate_names', false))
            {
                $builder->orWhere('alternatenames', 'like', '%,' . \Input::get('name') . ',%');
            }
        }

        $result = $builder->paginate(abs((int) Input::get('items_per_page', 25)));

        if ($result->count() > 0)
        {
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        if ($location = Location::find($id))
        {
            return $location->toJson();
        }

        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        Location::destroy($id);
    }


}
