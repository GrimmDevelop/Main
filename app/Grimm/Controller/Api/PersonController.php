<?php

namespace Grimm\Controller\Api;

use Grimm\Models\Person;

class PersonController extends \Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->lettersChangedAfter(0, 0, 0);
    }


    /**
     * Display a listing of letters changed after given date
     * @param  int $year
     * @param  int $month
     * @param  int $day
     * @return Response
     */
    public function lettersChangedAfter($year, $month, $day)
    {
        return Person::where('updated_at', '>=', \Carbon\Carbon::createFromDate($year, $month, $day))->get()->toJson();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.create'))) {
            return \Response::json('Unauthorized action.', 403);
        }


    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.create'))) {
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
        return Person::find($id)->toJson();
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.edit'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.edit'))) {
            return \Response::json('Unauthorized action.', 403);
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
        if (!(Sentry::check() && Sentry::getUser()->hasAccess('persons.delete'))) {
            return \Response::json('Unauthorized action.', 403);
        }
    }


}
